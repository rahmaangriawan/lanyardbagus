<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    /**
     * Get chat history for a session
     */
    public function history(Request $request): JsonResponse
    {
        try {
            $sessionId = $request->header('X-Session-ID') ?: $request->input('session_id');

            if (! $sessionId) {
                return response()->json(['messages' => []]);
            }

            $messages = ChatMessage::bySession($sessionId)
                ->orderBy('created_at', 'asc')
                ->limit(50)
                ->get(['id', 'message', 'response', 'role', 'created_at']);

            return response()->json(['messages' => $messages]);
        } catch (\Throwable $e) {
            \Log::warning('Chat history lookup failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json(['messages' => []]);
        }
    }

    /**
     * Send message to AI and get response
     */
    public function send(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'message' => ['required', 'string', 'max:2000'],
                'session_id' => ['required', 'string', 'max:64'],
                'user_name' => ['nullable', 'string', 'max:100'],
                'user_email' => ['nullable', 'email', 'max:100'],
            ]);

            // Get AI settings
            $apiKey = Setting::get('ai_api_key');
            $provider = $this->resolveProvider(Setting::get('ai_provider', 'openai'), $apiKey);
            $model = $this->resolveModel($provider, Setting::get('ai_model'));
            $systemPrompt = Setting::get('ai_system_prompt', $this->getDefaultSystemPrompt());
            $enabled = Setting::get('ai_enabled', 'true');

            if ($enabled === 'false' || ! $apiKey) {
                return response()->json([
                    'message' => 'Maaf, layanan chat AI sedang tidak tersedia. Silakan hubungi kami via WhatsApp.'
                ], 503);
            }

            // Get recent conversation history for context
            $history = ChatMessage::bySession($validated['session_id'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(['role', 'message', 'response'])
                ->reverse()
                ->values();

            // Build messages array for AI
            $messages = [
                ['role' => 'system', 'content' => $systemPrompt]
            ];

            foreach ($history as $chat) {
                $messages[] = ['role' => 'user', 'content' => $chat->message];
                if ($chat->response) {
                    $messages[] = ['role' => 'assistant', 'content' => $chat->response];
                }
            }

            $messages[] = ['role' => 'user', 'content' => $validated['message']];

            // Call AI API (OpenAI compatible)
            $response = $this->callAI($provider, $apiKey, $model, $messages);

            if (! $response['success']) {
                \Log::warning('AI chat provider request failed', [
                    'provider' => $provider,
                    'model' => $model,
                    'error' => $response['error'] ?? null,
                ]);

                return response()->json([
                    'message' => $this->fallbackReply($validated['message'])
                ]);
            }

            // Save to database
            $chatMessage = ChatMessage::create([
                'session_id' => $validated['session_id'],
                'user_name' => $validated['user_name'] ?? null,
                'user_email' => $validated['user_email'] ?? null,
                'message' => $validated['message'],
                'role' => 'user',
                'response' => $response['content'],
                'ai_model' => $model,
                'tokens_used' => $response['tokens'] ?? null,
            ]);

            return response()->json([
                'message' => $response['content'],
                'id' => $chatMessage->id,
            ]);
        } catch (\Throwable $e) {
            \Log::error('Chat send failed unexpectedly', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => $this->fallbackReply($request->input('message', ''))
            ]);
        }
    }

    /**
     * Get all chat sessions (admin)
     */
    public function sessions(): JsonResponse
    {
        $sessions = ChatMessage::select('session_id')
            ->selectRaw('MAX(created_at) as last_activity')
            ->selectRaw('COUNT(*) as message_count')
            ->selectRaw('MAX(user_name) as user_name')
            ->groupBy('session_id')
            ->orderBy('last_activity', 'desc')
            ->limit(100)
            ->get();

        return response()->json($sessions);
    }

    /**
     * Get messages for a specific session (admin)
     */
    public function sessionMessages(string $sessionId): JsonResponse
    {
        $messages = ChatMessage::bySession($sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Mark message as training data
     */
    public function markTraining(Request $request, ChatMessage $message): JsonResponse
    {
        $validated = $request->validate([
            'is_training' => ['required', 'boolean'],
        ]);

        $message->update(['is_training_data' => $validated['is_training']]);

        return response()->json(['message' => 'Training data updated']);
    }

    /**
     * Get training dataset
     */
    public function trainingData(): JsonResponse
    {
        $data = ChatMessage::trainingData()
            ->orderBy('created_at', 'desc')
            ->get(['id', 'message', 'response', 'user_name', 'created_at']);

        return response()->json($data);
    }

    /**
     * Delete message
     */
    public function destroy(ChatMessage $message): JsonResponse
    {
        $message->delete();
        return response()->json(['message' => 'Message deleted']);
    }

    /**
     * Get AI settings
     */
    public function getSettings(): JsonResponse
    {
        $settings = [
            'enabled' => Setting::get('ai_enabled', 'true') === 'true',
            'provider' => Setting::get('ai_provider', 'openai'),
            'model' => Setting::get('ai_model', 'gpt-4o-mini'),
            'api_key' => '',
            'has_api_key' => (bool) Setting::get('ai_api_key'),
            'system_prompt' => Setting::get('ai_system_prompt', $this->getDefaultSystemPrompt()),
            'welcome_message' => Setting::get('ai_welcome_message', $this->getDefaultWelcomeMessage()),
            'widget_title' => Setting::get('ai_widget_title', 'Asisten Lanyard Bagus'),
        ];

        return response()->json($settings);
    }

    /**
     * Update AI settings
     */
    public function updateSettings(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'enabled' => ['required', 'boolean'],
            'provider' => ['required', 'string', 'in:openai,anthropic,google'],
            'model' => ['required', 'string'],
            'api_key' => ['nullable', 'string'],
            'system_prompt' => ['required', 'string', 'max:5000'],
            'welcome_message' => ['required', 'string', 'max:500'],
            'widget_title' => ['required', 'string', 'max:100'],
        ]);

        Setting::set('ai_enabled', $validated['enabled'] ? 'true' : 'false', 'ai');
        Setting::set('ai_provider', $validated['provider'], 'ai');
        Setting::set('ai_model', $validated['model'], 'ai');
        if ($validated['api_key'] && $validated['api_key'] !== '***') {
            Setting::set('ai_api_key', $validated['api_key'], 'ai');
        }
        Setting::set('ai_system_prompt', $validated['system_prompt'], 'ai');
        Setting::set('ai_welcome_message', $validated['welcome_message'], 'ai');
        Setting::set('ai_widget_title', $validated['widget_title'], 'ai');

        return response()->json(['message' => 'AI settings updated']);
    }

    /**
     * Call AI provider API
     */
    private function callAI(string $provider, string $apiKey, string $model, array $messages): array
    {
        try {
            switch ($provider) {
                case 'anthropic':
                    return $this->callAnthropic($apiKey, $model, $messages);
                case 'google':
                    return $this->callGoogle($apiKey, $model, $messages);
                case 'openai':
                default:
                    return $this->callOpenAI($apiKey, $model, $messages);
            }
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function callOpenAI(string $apiKey, string $model, array $messages): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => $model,
            'messages' => $messages,
            'temperature' => 0.7,
            'max_tokens' => 1000,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'content' => $data['choices'][0]['message']['content'],
                'tokens' => $data['usage']['total_tokens'] ?? null,
            ];
        }

        return ['success' => false, 'error' => $response->body()];
    }

    private function callAnthropic(string $apiKey, string $model, array $messages): array
    {
        // Convert messages to Anthropic format
        $systemMessage = '';
        $anthropicMessages = [];
        
        foreach ($messages as $msg) {
            if ($msg['role'] === 'system') {
                $systemMessage = $msg['content'];
            } else {
                $anthropicMessages[] = [
                    'role' => $msg['role'],
                    'content' => $msg['content'],
                ];
            }
        }

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'Content-Type' => 'application/json',
            'anthropic-version' => '2023-06-01',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => $model,
            'max_tokens' => 1000,
            'system' => $systemMessage,
            'messages' => $anthropicMessages,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'content' => $data['content'][0]['text'],
                'tokens' => $data['usage']['input_tokens'] + $data['usage']['output_tokens'] ?? null,
            ];
        }

        return ['success' => false, 'error' => $response->body()];
    }

    private function callGoogle(string $apiKey, string $model, array $messages): array
    {
        $contents = array_map(function ($msg) {
            return [
                'role' => $msg['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $msg['content']]],
            ];
        }, array_filter($messages, fn ($m) => $m['role'] !== 'system'));

        $candidateModels = array_values(array_unique(array_filter([
            $this->normalizeGoogleModel($model),
            'gemini-2.0-flash',
            'gemini-2.0-flash-lite',
            'gemini-1.5-flash',
            'gemini-1.5-pro',
        ])));

        $lastError = null;

        foreach ($candidateModels as $candidateModel) {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$candidateModel}:generateContent?key={$apiKey}", [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 1000,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();

                $text = data_get($data, 'candidates.0.content.parts.0.text');
                if (filled($text)) {
                    return [
                        'success' => true,
                        'content' => $text,
                        'tokens' => null,
                        'model' => $candidateModel,
                    ];
                }

                $lastError = 'Gemini response missing text payload for model ' . $candidateModel;
                continue;
            }

            $lastError = 'Model ' . $candidateModel . ' failed: ' . $response->status() . ' ' . $response->body();
        }

        return ['success' => false, 'error' => $lastError ?: 'Unknown Gemini error'];
    }

    private function resolveProvider(string $storedProvider, ?string $apiKey): string
    {
        $apiKey = trim((string) $apiKey);

        if (str_starts_with($apiKey, 'AIza')) {
            return 'google';
        }

        if (str_starts_with($apiKey, 'sk-') || str_starts_with($apiKey, 'gsk-')) {
            return 'openai';
        }

        return in_array($storedProvider, ['openai', 'anthropic', 'google'], true)
            ? $storedProvider
            : 'openai';
    }

    private function resolveModel(string $provider, ?string $storedModel): string
    {
        $storedModel = trim((string) $storedModel);

        return match ($provider) {
            'google' => str_starts_with($storedModel, 'gemini-') ? $storedModel : 'gemini-1.5-flash',
            'anthropic' => str_starts_with($storedModel, 'claude-') ? $storedModel : 'claude-3-5-sonnet-latest',
            default => $storedModel !== '' ? $storedModel : 'gpt-4o-mini',
        };
    }

    private function normalizeGoogleModel(?string $model): ?string
    {
        $model = trim((string) $model);

        if ($model === '') {
            return null;
        }

        if (str_starts_with($model, 'gemini-')) {
            return $model;
        }

        if ($model === 'google') {
            return 'gemini-2.0-flash';
        }

        return null;
    }

    private function fallbackReply(string $message): string
    {
        $lowerMessage = mb_strtolower($message);

        if (str_contains($lowerMessage, 'harga') || str_contains($lowerMessage, 'biaya')) {
            return 'Untuk estimasi harga lanyard custom, silakan kirim jumlah pcs, ukuran, dan model yang diinginkan. Tim kami akan bantu hitungkan opsi terbaik.';
        }

        if (str_contains($lowerMessage, 'waktu') || str_contains($lowerMessage, 'lama')) {
            return 'Waktu produksi umumnya 3-7 hari kerja tergantung jumlah dan jenis produk. Kalau mendesak, kami bisa cek opsi prioritas.';
        }

        if (str_contains($lowerMessage, 'desain') || str_contains($lowerMessage, 'logo')) {
            return 'Bisa, kami bantu cek desain dan rekomendasi material yang paling cocok. Kirim detail kebutuhan Anda supaya kami arahkan lebih tepat.';
        }

        return 'Siap, ceritakan kebutuhan lanyard Anda: jumlah pcs, ukuran, dan model yang diinginkan. Saya bantu arahkan ke opsi yang paling cocok.';
    }

    private function getDefaultSystemPrompt(): string
    {
        return "Kamu adalah asisten AI untuk Lanyard Bagus, perusahaan pembuat lanyard custom berkualitas tinggi di Indonesia. \n\nTugas kamu:\n1. Membantu customer menemukan produk lanyard yang sesuai kebutuhan\n2. Menjelaskan jenis-jenis lanyard (polyester, nylon, satin, dll)\n3. Memberikan informasi tentang harga dan minimal order\n4. Menjawab pertanyaan tentang proses pemesanan dan pengiriman\n5. Mengarahkan customer ke WhatsApp untuk pemesanan\n\nPenting:\n- Selalu ramah dan profesional\n- Jawaban singkat dan to the point (maksimal 3-4 kalimat)\n- Jika tidak tahu jawaban, arahkan ke WhatsApp: 0812-3456-7890\n- Bahasa Indonesia yang sopan\n- Jangan membuat janji harga spesifik tanpa konfirmasi";
    }

    private function getDefaultWelcomeMessage(): string
    {
        return "Halo! 👋 Saya asisten Lanyard Bagus. Ada yang bisa saya bantu tentang lanyard custom?";
    }
}
