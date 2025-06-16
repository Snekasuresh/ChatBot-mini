<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat'); 
    }
    public function chat(Request $request)
    {
        $userMessage = $request->message;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openrouter.key'),
                'Content-Type' => 'application/json',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'openai/gpt-3.5-turbo', // or another supported model
                'messages' => [
                    ['role' => 'user', 'content' => $userMessage],
                ],
            ]);

            // Optional: Log full response to check errors
            Log::info('OpenRouter Response:', $response->json());

            if ($response->successful()) {
                $reply = $response['choices'][0]['message']['content'] ?? 'No reply found.';
                return response()->json(['reply' => $reply]);
            } else {
                return response()->json(['reply' => 'Failed to fetch reply from API.'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Chat error: ' . $e->getMessage());
            return response()->json(['reply' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
}
