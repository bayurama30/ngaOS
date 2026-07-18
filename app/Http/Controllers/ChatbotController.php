<?php

namespace App\Http\Controllers;

use App\Models\ChatHistory;
use App\Services\GeminiChatService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function __construct(
        private GeminiChatService $chatService
    ) {}

    public function index()
    {
        $userId = auth()->id();
        $todayCount = ChatHistory::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $remaining = max(0, 10 - $todayCount);

        return view('chatbot.index', [
            'remaining' => $remaining,
            'limit' => 10,
        ]);
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $userId = $request->user()->id;

        // Check daily limit
        $todayCount = ChatHistory::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        if ($todayCount >= 10) {
            return response()->json([
                'error' => 'Anda telah mencapai batas 10 chat per hari. Silakan coba lagi besok.',
                'remaining' => 0,
            ], 429);
        }

        $response = $this->chatService->chat(
            $request->input('message'),
            $userId
        );

        $remaining = max(0, 10 - $todayCount - 1);

        return response()->json([
            'response' => $response,
            'remaining' => $remaining,
        ]);
    }

    public function history(Request $request)
    {
        $history = $this->chatService->getHistory($request->user()->id);

        return response()->json($history);
    }

    public function clearHistory(Request $request)
    {
        $this->chatService->clearHistory($request->user()->id);

        return response()->json(['message' => 'History cleared']);
    }

    public function quickPrompts()
    {
        return response()->json($this->chatService->getQuickPrompts());
    }
}
