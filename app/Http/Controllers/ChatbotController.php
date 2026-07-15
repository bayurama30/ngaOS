<?php

namespace App\Http\Controllers;

use App\Services\GeminiChatService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function __construct(
        private GeminiChatService $chatService
    ) {}

    public function index()
    {
        return view('chatbot.index');
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $response = $this->chatService->chat(
            $request->input('message'),
            $request->user()->id
        );

        return response()->json([
            'response' => $response,
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
