<?php

namespace App\Services;

use App\Models\ChatHistory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiChatService
{
    private string $apiKey;

    private string $apiUrl;

    private string $model;

    private int $maxTokens;

    private float $temperature;

    private string $systemPrompt;

    public function __construct()
    {
        $this->apiKey = config('gemini.api_key');
        $this->apiUrl = config('gemini.api_url');
        $this->model = config('gemini.model');
        $this->maxTokens = config('gemini.max_tokens');
        $this->temperature = config('gemini.temperature');
        $this->systemPrompt = config('gemini.system_prompt');
    }

    public function chat(string $message, int $userId): string
    {
        $history = $this->getRecentHistory($userId);

        $messages = $this->buildMessages($history, $message);

        try {
            $response = Http::timeout(60)->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->apiKey}",
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
            ])->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => $messages,
                'max_tokens' => $this->maxTokens,
                'temperature' => $this->temperature,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiResponse = $data['choices'][0]['message']['content'] ?? 'Maaf, saya tidak dapat memproses pertanyaan Anda saat ini.';

                $this->saveHistory($userId, $message, $aiResponse);

                Log::info('Chat success with model: ' . $this->model);

                return $aiResponse;
            }

            Log::error('Chat API Error', [
                'model' => $this->model,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return 'Maaf, terjadi kesalahan saat menghubungi AI. Silakan coba lagi nanti.';
        } catch (\Exception $e) {
            Log::error('Chat API Exception', [
                'model' => $this->model,
                'message' => $e->getMessage(),
            ]);

            return 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.';
        }
    }

    private function buildMessages(array $history, string $currentMessage): array
    {
        $messages = [];

        $messages[] = [
            'role' => 'system',
            'content' => $this->systemPrompt,
        ];

        foreach ($history as $chat) {
            $messages[] = [
                'role' => 'user',
                'content' => $chat->message,
            ];
            $messages[] = [
                'role' => 'assistant',
                'content' => $chat->response,
            ];
        }

        $messages[] = [
            'role' => 'user',
            'content' => $currentMessage,
        ];

        return $messages;
    }

    private function getRecentHistory(int $userId, int $limit = 10): array
    {
        return ChatHistory::where('user_id', $userId)
            ->latest()
            ->limit($limit)
            ->get()
            ->reverse()
            ->values()
            ->all();
    }

    private function saveHistory(int $userId, string $message, string $response): void
    {
        ChatHistory::create([
            'user_id' => $userId,
            'message' => $message,
            'response' => $response,
        ]);

        $this->cleanupOldHistory($userId);
    }

    private function cleanupOldHistory(int $userId, int $keep = 50): void
    {
        $ids = ChatHistory::where('user_id', $userId)
            ->latest()
            ->skip($keep)
            ->take(100)
            ->pluck('id');

        if ($ids->isNotEmpty()) {
            ChatHistory::whereIn('id', $ids)->delete();
        }
    }

    public function getHistory(int $userId, int $limit = 20): array
    {
        return ChatHistory::where('user_id', $userId)
            ->latest()
            ->limit($limit)
            ->get()
            ->reverse()
            ->values()
            ->all();
    }

    public function clearHistory(int $userId): bool
    {
        return ChatHistory::where('user_id', $userId)->delete() > 0;
    }

    public function getQuickPrompts(): array
    {
        return config('gemini.quick_prompts', []);
    }
}
