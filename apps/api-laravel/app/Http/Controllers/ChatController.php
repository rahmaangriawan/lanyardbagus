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
        $sessionId = $request->header('X-Session-ID') ?: $request->input('session_id');
        
        if (!$sessionId) {
            return response()->json(['messages' => []]);
        }

        $messages = ChatMessage::bySession($sessionId)
            ->orderBy('created_at', 'asc')
            ->limit(50)
            ->get(['id', 'message', 'response', 'role', 'created_at']);

        return response()->json(['messages' => $messages]);
    }

    /**
     * Send message to AI and get response
     */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'session_id' => ['required', 'string', 'max:64'],
            'user_name' => ['nullable', 'string', 'max:100'],
            'user_email' => ['nullable', 'email', 'max:100'],
        ]);

        // Get AI settings
        $apiKey = Setting::get('ai_api_key');
        $model = Setting::get('ai_model', 'gpt-4o-mini');
        $systemPrompt = Setting::get('ai_system_prompt', $this->getDefaultSystemPrompt());
        $enabled = Setting::get('ai_enabled', 'true');

        if ($enabled === 'false' || !$apiKey) {
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
        $provider = Setting::get('ai_provider', 'openai');
        $response = $this->callAI($provider, $apiKey, $model, $messages);

        if (!$response['success']) {
            return response()->json([
                'message' => 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.'
            ], 500);
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
            'api_key' => Setting::get('ai_api_key') ? '***' : '',
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
        // Gemini API
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
            'contents' => array_map(function ($msg) {
                return [
                    'role' => $msg['role'] === 'assistant' ? 'model' : 'user',
                    'parts' => [['text' => $msg['content']]],
                ];
            }, array_filter($messages, fn($m) => $m['role'] !== 'system')),
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 1000,
            ],
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'content' => $data['candidates'][0]['content']['parts'][0]['text'],
                'tokens' => null, // Gemini doesn't always return token counts
            ];
        }

        return ['success' => false, 'error' => $response->body()];
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
