<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Client\ConnectionException;
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
            $provider = $this->resolveProvider(Setting::get('ai_provider', 'groq'), $apiKey);
            $model = $this->resolveModel($provider, Setting::get('ai_model'));
            $systemPrompt = Setting::get('ai_system_prompt', $this->getDefaultSystemPrompt());
            $enabled = Setting::get('ai_enabled', 'true');

            if ($enabled === 'false') {
                return response()->json([
                    'message' => 'Maaf, layanan chat AI sedang tidak tersedia. Silakan hubungi kami via WhatsApp.'
                ], 503);
            }

            if (empty($apiKey)) {
                \Log::warning('AI chat missing API key', ['provider' => $provider]);
                return response()->json([
                    'message' => 'Maaf, API key belum diset. Silakan set API key di admin panel AI Chat settings.'
                ]);
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
                    'has_api_key' => !empty($apiKey),
                    'error' => $response['error'] ?? null,
                ]);

                return response()->json([
                    'message' => 'Maaf, terjadi kesalahan: ' . ($response['error'] ?? 'Unknown error'),
                    'debug' => config('app.env') === 'local' ? $response['error'] : null,
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
        } catch (\Exception $e) {
            \Log::error('AI chat unexpected error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Maaf, terjadi kesalahan sistem: ' . $e->getMessage(),
            ], 500);
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
            'provider' => Setting::get('ai_provider', 'groq'),
            'model' => Setting::get('ai_model', config('services.groq.model', 'llama-3.1-8b-instant')),
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
            'provider' => ['required', 'string', 'in:groq,openai,anthropic,google'],
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
     * Test current AI provider settings without saving a chat message.
     */
    public function testProvider(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'provider' => ['nullable', 'string', 'in:groq,openai,anthropic,google'],
            'model' => ['nullable', 'string'],
            'api_key' => ['nullable', 'string'],
            'system_prompt' => ['nullable', 'string', 'max:5000'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $apiKey = trim((string) ($validated['api_key'] ?? Setting::get('ai_api_key')));
        $storedProvider = $validated['provider'] ?? Setting::get('ai_provider', 'groq');
        $provider = $this->resolveProvider($storedProvider, $apiKey);
        $model = $this->resolveModel($provider, $validated['model'] ?? Setting::get('ai_model'));
        $systemPrompt = $validated['system_prompt'] ?? Setting::get('ai_system_prompt', $this->getDefaultSystemPrompt());
        $testMessage = $validated['message'] ?? 'Balas singkat: tulis satu kalimat tentang layanan lanyard custom yang kamu pahami.';

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $testMessage],
        ];

        $response = $this->callAI($provider, $apiKey, $model, $messages);

        if (! $response['success']) {
            return response()->json([
                'success' => false,
                'provider' => $provider,
                'model' => $model,
                'error' => $response['error'] ?? 'Unknown provider error',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'provider' => $provider,
            'model' => $response['model'] ?? $model,
            'message' => $response['content'],
        ]);
    }

    /**
     * Call AI provider API
     */
    private function callAI(string $provider, string $apiKey, string $model, array $messages): array
    {
        try {
            switch ($provider) {
                case 'openai':
                    return $this->callOpenAI($apiKey, $model, $messages);
                case 'anthropic':
                    return $this->callAnthropic($apiKey, $model, $messages);
                case 'google':
                    return $this->callGoogle($apiKey, $model, $messages);
                case 'groq':
                default:
                    return $this->callGroq($apiKey, $model, $messages);
            }
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function callGroq(string $apiKey, string $model, array $messages): array
    {
        $baseUrl = rtrim((string) config('services.groq.base_url', 'https://api.groq.com/openai/v1'), '/');
        $model = $model !== '' ? $model : (string) config('services.groq.model', 'llama-3.1-8b-instant');

        try {
            $response = Http::connectTimeout(10)
                ->timeout(60)
                ->withToken($apiKey)
                ->acceptJson()
                ->post($baseUrl . '/chat/completions', [
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => 0.7,
                    'max_tokens' => 1000,
                ]);
        } catch (ConnectionException $e) {
            return [
                'success' => false,
                'error' => 'Tidak bisa terhubung ke Groq. Cek koneksi internet atau base URL. Detail: ' . $e->getMessage(),
            ];
        }

        if ($response->successful()) {
            $data = $response->json();
            $text = data_get($data, 'choices.0.message.content');

            if (filled($text)) {
                return [
                    'success' => true,
                    'content' => $text,
                    'tokens' => data_get($data, 'usage.total_tokens'),
                    'model' => $model,
                ];
            }

            return [
                'success' => false,
                'error' => 'Groq response missing text payload for model ' . $model,
            ];
        }

        return [
            'success' => false,
            'error' => $this->formatGroqError($response->status(), $response->body(), $model),
        ];
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
        $candidateModels = array_values(array_unique(array_filter([
            $this->normalizeGoogleModel($model),
            'gemini-2.0-flash',
        ])));

        $lastError = null;

        foreach ($candidateModels as $candidateModel) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/openai/chat/completions', [
                'model' => $candidateModel,
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 1000,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                $text = data_get($data, 'choices.0.message.content')
                    ?? data_get($data, 'choices.0.text')
                    ?? data_get($data, 'choices.0.message');
                if (filled($text)) {
                    return [
                        'success' => true,
                        'content' => $text,
                        'tokens' => null,
                        'model' => $candidateModel,
                    ];
                }

                $lastError = 'Gemini OpenAI-compatible response missing text payload for model ' . $candidateModel;
            } else {
                $lastError = 'OpenAI-compatible Gemini model ' . $candidateModel . ' failed: ' . $response->status() . ' ' . $response->body();
            }

            $nativeResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$candidateModel}:generateContent?key={$apiKey}", [
                'systemInstruction' => [
                    'parts' => [
                        ['text' => $this->extractSystemPrompt($messages)],
                    ],
                ],
                'contents' => $this->convertMessagesToGoogleContents($messages),
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 1000,
                ],
            ]);

            if ($nativeResponse->successful()) {
                $nativeData = $nativeResponse->json();
                $nativeText = data_get($nativeData, 'candidates.0.content.parts.0.text');

                if (filled($nativeText)) {
                    return [
                        'success' => true,
                        'content' => $nativeText,
                        'tokens' => null,
                        'model' => $candidateModel,
                    ];
                }

                $lastError = 'Gemini native response missing text payload for model ' . $candidateModel;
                continue;
            }

            $lastError = trim($lastError . ' | Native REST fallback: ' . $nativeResponse->status() . ' ' . $nativeResponse->body());
        }

        return ['success' => false, 'error' => $lastError ?: 'Unknown Gemini error'];
    }

    private function formatGroqError(int $status, string $body, string $model): string
    {
        if ($status === 401) {
            return 'API key Groq tidak valid atau belum diisi.';
        }

        if ($status === 404) {
            return 'Endpoint Groq tidak ditemukan. Cek konfigurasi base URL.';
        }

        if ($status === 400 && str_contains($body, 'model')) {
            return 'Model ' . $model . ' tidak tersedia di Groq. Pilih model lain di dashboard.';
        }

        if ($status === 429) {
            return 'Groq membatasi request Anda sementara. Coba lagi sebentar atau ganti model.';
        }

        return 'Groq request failed: ' . $status . ' ' . $body;
    }

    private function extractSystemPrompt(array $messages): string
    {
        foreach ($messages as $message) {
            if (($message['role'] ?? null) === 'system') {
                return (string) ($message['content'] ?? '');
            }
        }

        return '';
    }

    private function convertMessagesToGoogleContents(array $messages): array
    {
        $contents = [];

        foreach ($messages as $message) {
            if (($message['role'] ?? null) === 'system') {
                continue;
            }

            $role = ($message['role'] ?? 'user') === 'assistant' ? 'model' : 'user';
            $contents[] = [
                'role' => $role,
                'parts' => [
                    ['text' => (string) ($message['content'] ?? '')],
                ],
            ];
        }

        return $contents;
    }

    private function resolveProvider(string $storedProvider, ?string $apiKey): string
    {
        $allowed = ['groq', 'openai', 'anthropic', 'google'];
        $provider = in_array($storedProvider, $allowed, true) ? $storedProvider : 'groq';

        return $provider;
    }

    private function resolveModel(string $provider, ?string $storedModel): string
    {
        $storedModel = trim((string) $storedModel);

        if ($storedModel === '') {
            return match ($provider) {
                'groq' => (string) config('services.groq.model', 'llama-3.1-8b-instant'),
                'openai' => 'gpt-4o-mini',
                'anthropic' => 'claude-3-haiku-20240307',
                'google' => 'gemini-1.5-flash',
                default => (string) config('services.groq.model', 'llama-3.1-8b-instant'),
            };
        }

        return $provider === 'google' ? $this->normalizeGoogleModel($storedModel) : $storedModel;
    }

    private function fallbackReply(string $message): string
    {
        return 'Maaf, saya belum bisa menjawab saat ini. Silakan coba lagi sebentar atau lanjutkan via WhatsApp.';
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
