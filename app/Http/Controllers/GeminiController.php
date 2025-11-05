<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiController extends Controller
{
    /**
     * Show the Ask Lee AI chat interface
     */
    public function index()
    {
        return view('ask-lee-ai');
    }

    /**
     * Send question to Gemini AI and get response
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ask(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'question' => 'required|string|max:1000'
        ]);

        $question = $request->input('question');
        $apiKey = env('GEMINI_API_KEY');

        // Check if API key is configured
        if (!$apiKey) {
            return response()->json([
                'error' => 'Gemini API key is not configured. Please add GEMINI_API_KEY to your .env file.'
            ], 500);
        }

        try {
            // Gemini API endpoint
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-lite:generateContent?key={$apiKey}";

            // Prepare the request payload
            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => "You are Lee AI, a helpful dental assistant chatbot for RMDC Dental Clinic. Answer the following dental question professionally and empathetically: {$question}"
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 500,
                    'topP' => 0.8,
                    'topK' => 40
                ]
            ];

            // Send POST request to Gemini API
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->post($url, $payload);

            // Check if request was successful
            if ($response->successful()) {
                $data = $response->json();
                
                // Extract the AI response text
                $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Sorry, I could not generate a response.';
                
                return response()->json([
                    'success' => true,
                    'response' => $aiResponse
                ]);
            } else {
                // Log the error for debugging
                Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return response()->json([
                    'error' => 'Failed to get response from AI. Please try again later.',
                    'details' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Gemini API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
