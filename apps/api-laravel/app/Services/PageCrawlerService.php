<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\ChatMessage;

class PageCrawlerService
{
    /**
     * Crawl a page and extract content for training data
     */
    public function crawlPage(string $url): array
    {
        try {
            $response = Http::timeout(30)->get($url);

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'error' => "Failed to fetch page: HTTP {$response->status()}",
                ];
            }

            $html = $response->body();
            
            // Extract content
            $content = $this->extractContent($html, $url);

            // Store as training data
            $this->storeTrainingData($content, $url);

            return [
                'success' => true,
                'content' => $content,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Crawl multiple pages
     */
    public function crawlMultiple(array $urls): array
    {
        $results = [];
        foreach ($urls as $url) {
            $results[$url] = $this->crawlPage($url);
        }
        return $results;
    }

    /**
     * Extract content from HTML
     */
    private function extractContent(string $html, string $url): array
    {
        // Remove scripts and styles
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);

        // Extract title
        $title = '';
        if (preg_match('/<title>(.*?)<\/title>/is', $html, $matches)) {
            $title = strip_tags($matches[1]);
        }

        // Extract meta description
        $description = '';
        if (preg_match('/<meta[^>]*name=["\']description["\'][^>]*content=["\']([^"\']*)["\'][^>]*>/is', $html, $matches)) {
            $description = $matches[1];
        }

        // Extract headings (h1, h2, h3)
        $headings = [];
        if (preg_match_all('/<h[1-6][^>]*>(.*?)<\/h[1-6]>/is', $html, $matches)) {
            foreach ($matches[1] as $heading) {
                $headings[] = strip_tags($heading);
            }
        }

        // Extract body text (paragraphs)
        $bodyText = '';
        if (preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $html, $matches)) {
            foreach ($matches[1] as $paragraph) {
                $text = strip_tags($paragraph);
                if (strlen($text) > 20) { // Filter out very short paragraphs
                    $bodyText .= $text . "\n\n";
                }
            }
        }

        // Extract list items (for product features, etc.)
        $listItems = '';
        if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $html, $matches)) {
            foreach ($matches[1] as $item) {
                $text = strip_tags($item);
                if (strlen($text) > 10) {
                    $listItems .= $text . "\n";
                }
            }
        }

        // Combine all content
        $fullContent = trim($title . "\n\n" . $description . "\n\n" . implode("\n", $headings) . "\n\n" . $bodyText . "\n" . $listItems);
        $fullContent = preg_replace('/\s+/', ' ', $fullContent); // Normalize whitespace

        return [
            'url' => $url,
            'title' => $title,
            'description' => $description,
            'headings' => $headings,
            'body_text' => $bodyText,
            'list_items' => $listItems,
            'full_content' => $fullContent,
            'content_length' => strlen($fullContent),
        ];
    }

    /**
     * Store extracted content as training data
     */
    private function storeTrainingData(array $content, string $url): void
    {
        // Check if training data already exists for this URL
        $existing = ChatMessage::where('is_training_data', true)
            ->where('metadata->url', $url)
            ->first();

        if ($existing) {
            // Update existing
            $existing->update([
                'message' => $content['full_content'],
                'metadata' => array_merge($existing->metadata ?? [], $content),
            ]);
        } else {
            // Create new training data entry
            ChatMessage::create([
                'session_id' => 'training_' . md5($url),
                'message' => $content['full_content'],
                'role' => 'system',
                'response' => null,
                'is_training_data' => true,
                'metadata' => $content,
            ]);
        }
    }

    /**
     * Get relevant training data for AI context based on keywords
     */
    public function getRelevantTrainingData(string $query): array
    {
        // Extract keywords from query
        $keywords = $this->extractKeywords($query);
        
        $trainingData = ChatMessage::where('is_training_data', true)
            ->orderBy('created_at', 'desc')
            ->get(['message', 'metadata'])
            ->toArray();

        $relevantEntries = [];
        $maxChars = 2500;

        foreach ($trainingData as $data) {
            $content = strtolower($data['message'] ?? '');
            $title = strtolower($data['metadata']['title'] ?? '');
            
            // Calculate relevance score based on keyword matches
            $score = 0;
            foreach ($keywords as $keyword) {
                if (strpos($content, $keyword) !== false) $score += 2;
                if (strpos($title, $keyword) !== false) $score += 3;
            }

            if ($score > 0) {
                $relevantEntries[] = [
                    'data' => $data,
                    'score' => $score,
                ];
            }
        }

        // Sort by relevance score
        usort($relevantEntries, function($a, $b) {
            return $b['score'] - $a['score'];
        });

        // Build context from most relevant entries
        $context = '';
        $totalChars = 0;
        $usedCount = 0;

        foreach ($relevantEntries as $entry) {
            $data = $entry['data'];
            $url = $data['metadata']['url'] ?? 'Unknown';
            $title = $data['metadata']['title'] ?? '';
            $content = $data['message'] ?? '';
            
            // Limit content to 400 chars per entry
            if (strlen($content) > 400) {
                $content = substr($content, 0, 400) . '...';
            }
            
            $entryText = "Source: {$url}\nTitle: {$title}\nContent: {$content}\n\n";
            
            if ($totalChars + strlen($entryText) <= $maxChars) {
                $context .= $entryText;
                $totalChars += strlen($entryText);
                $usedCount++;
            }
        }

        return [
            'context' => $context,
            'count' => $usedCount,
            'chars' => $totalChars,
            'has_relevant' => !empty($context),
        ];
    }

    /**
     * Extract keywords from a query
     */
    private function extractKeywords(string $query): array
    {
        // Remove common Indonesian stop words
        $stopWords = ['apa', 'bagaimana', 'berapa', 'di', 'ke', 'dari', 'untuk', 'dengan', 'dan', 'atau', 'yang', 'adalah', 'ini', 'itu', 'saya', 'kamu', 'mau', 'ingin', 'bisa', 'tolong', 'harga', 'beli', 'pesan'];
        
        $query = strtolower($query);
        foreach ($stopWords as $word) {
            $query = str_replace(' ' . $word . ' ', ' ', $query);
            $query = str_replace($word . ' ', '', $query);
            $query = str_replace(' ' . $word, '', $query);
        }
        
        // Extract words (min 3 chars)
        $words = preg_split('/\s+/', $query);
        $keywords = array_filter($words, function($word) {
            return strlen($word) >= 3;
        });
        
        return array_values($keywords);
    }

    /**
     * Delete training data for a specific URL
     */
    public function deleteTrainingData(string $url): bool
    {
        return ChatMessage::where('is_training_data', true)
            ->where('metadata->url', $url)
            ->delete() > 0;
    }

    /**
     * Get all training data entries
     */
    public function getAllTrainingData(): array
    {
        return ChatMessage::where('is_training_data', true)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'session_id', 'metadata', 'created_at'])
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'url' => $item->metadata['url'] ?? 'Unknown',
                    'title' => $item->metadata['title'] ?? '',
                    'content_length' => $item->metadata['content_length'] ?? 0,
                    'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                ];
            })
            ->toArray();
    }
}
