<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

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
     * Send question to Groq AI and get response
     */
    public function ask(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:1000'
        ]);

        $question = $request->input('question');
        $apiKey   = config('groq.api_key');
        $model    = config('groq.model', 'llama-3.3-70b-versatile');

        if (!$apiKey) {
            return response()->json([
                'error' => 'AI service is not configured. Please contact the administrator.'
            ], 500);
        }

        // Cache frequently asked questions for 1 hour
        $cacheKey       = 'groq_response_' . md5(strtolower(trim($question)));
        $cachedResponse = Cache::get($cacheKey);

        if ($cachedResponse) {
            return response()->json([
                'success'  => true,
                'response' => $cachedResponse,
                'cached'   => true,
            ]);
        }

        $systemPrompt = "You are Lee AI, a professional dental assistant chatbot for Dr. Cristina Moncayo's RMDC Dental Clinic.

STRICT RULES:
1. ONLY answer questions related to dental health, oral care, dentistry, teeth, gums, orthodontics, our clinic services, or information about Dr. Cristina Moncayo and Lee Torres (the creator).
2. If asked about non-dental topics (politics, sports, cooking, general programming, etc.), politely redirect: 'I''m Lee AI, specialized in dental health only. I can help you with questions about teeth, oral care, or our clinic services. How can I assist you with your dental health today?'
3. Be professional, empathetic, and provide accurate dental information.
4. For medical emergencies, advise to visit the clinic or seek immediate medical attention.

FORMATTING GUIDELINES:
- Use **bold** for important terms and headings
- Use * for bullet points to create organized lists
- Add blank lines between sections for better readability
- Keep paragraphs concise (2-3 sentences max)

CLINIC INFORMATION:
- Dentist: Dr. Cristina Moncayo
  Facebook: https://www.facebook.com/iten10
- Clinic 1: Unit F Medina Bldg, in front gate of Niog Elementary School, Bacoor, Philippines
  Hours: 7:00 AM to 2:00 PM (Monday to Saturday)
- Clinic 2: Marigold corner Hyacinth Sts, F E De Castro Village, Bacoor, Philippines
  Hours: 3:00 PM to 8:00 PM (Monday to Saturday), 1:00 PM to 8:00 PM (Sunday)

CREATOR INFORMATION:
- Creator: Lee Rafael Torres
- Title: Software Engineer
- Age: 21 | Location: Calauan, Laguna, Philippines
- Education: PUP Calauan Campus, Laguna
- Social: Facebook: https://www.facebook.com/lee.torres.5496683/ | GitHub: https://github.com/LeeDev428 | LinkedIn: https://www.linkedin.com/in/lee-torres-361168333/ | Website: https://leedev.vercel.app/";

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model'       => $model,
                    'messages'    => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user',   'content' => $question],
                    ],
                    'temperature' => 0.7,
                    'max_tokens'  => 500,
                    'top_p'       => 0.8,
                ]);

            if ($response->successful()) {
                $data       = $response->json();
                $aiResponse = $data['choices'][0]['message']['content'] ?? 'Sorry, I could not generate a response.';

                $formattedResponse = $this->formatAIResponse($aiResponse);
                Cache::put($cacheKey, $formattedResponse, 3600);

                return response()->json([
                    'success'  => true,
                    'response' => $formattedResponse,
                    'cached'   => false,
                ]);
            }

            if ($response->status() === 429) {
                return response()->json([
                    'error'      => 'Too many requests. Please wait a moment before asking another question.',
                    'rate_limit' => true,
                ], 429);
            }

            Log::error('Groq API Error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            $errorBody    = $response->json();
            $errorMessage = $errorBody['error']['message'] ?? 'Failed to get response from AI. Please try again later.';

            return response()->json([
                'success' => false,
                'error'   => $errorMessage,
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Groq API Exception', ['message' => $e->getMessage()]);

            return response()->json([
                'error'   => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Format AI response - converts markdown-style text to HTML
     */
    private function formatAIResponse(string $text): string
    {
        // Convert **bold** to <strong>
        $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text);

        $lines          = explode("\n", $text);
        $inList         = false;
        $formattedLines = [];

        foreach ($lines as $line) {
            $trimmedLine = trim($line);

            if (preg_match('/^[\*\-\x{2022}]\s+(.+)$/u', $trimmedLine, $matches)) {
                if (!$inList) {
                    $formattedLines[] = '<ul class="ai-list">';
                    $inList = true;
                }
                $formattedLines[] = '<li>' . $matches[1] . '</li>';
            } else {
                if ($inList) {
                    $formattedLines[] = '</ul>';
                    $inList = false;
                }
                if (!empty($trimmedLine)) {
                    $formattedLines[] = '<p>' . $trimmedLine . '</p>';
                } else {
                    $formattedLines[] = '<br>';
                }
            }
        }

        if ($inList) {
            $formattedLines[] = '</ul>';
        }

        $formatted = implode("\n", $formattedLines);

        // Convert URLs to clickable links (safe regex - no user input in pattern)
        $formatted = preg_replace(
            '/(https?:\/\/[^\s<"]+)/',
            '<a href="$1" target="_blank" rel="noopener noreferrer" class="ai-link">$1</a>',
            $formatted
        );

        return $formatted;
    }
}
