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
            // Gemini API endpoint - Using Gemini 2.0 Flash (full version)
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-exp:generateContent?key={$apiKey}";

            // Prepare the request payload with comprehensive dental-focused instructions
            $systemPrompt = "You are Lee AI, a professional dental assistant chatbot for Dr. Cristina Moncayo's RMDC Dental Clinic.

STRICT RULES:
1. ONLY answer questions related to dental health, oral care, dentistry, teeth, gums, orthodontics, our clinic services, or information about Dr. Cristina Moncayo and Lee Torres (the creator).
2. If asked about non-dental topics (politics, sports, cooking, general programming, etc.), politely redirect: 'I'm Lee AI, specialized in dental health only. I can help you with questions about teeth, oral care, or our clinic services. How can I assist you with your dental health today?'
3. Be professional, empathetic, and provide accurate dental information.
4. For medical emergencies, advise to visit the clinic or seek immediate medical attention.

FORMATTING GUIDELINES:
- Use **bold** for important terms and headings
- Use * for bullet points to create organized lists
- Add blank lines between sections for better readability
- Keep paragraphs concise (2-3 sentences max)
- Use proper spacing and structure

CLINIC INFORMATION:
- Dentist: Dr. Cristina Moncayo
  Facebook: https://www.facebook.com/iten10
- Clinic 1: Unit F Medina Bldg, in front gate of Niog Elementary School, Bacoor, Philippines
  Hours: 7:00 AM to 2:00 PM (Monday to Saturday)
- Clinic 2: Marigold corner Hyacinth Sts, F E De Castro Village, Bacoor, Philippines
  Hours: 3:00 PM to 8:00 PM (Monday to Saturday), 1:00 PM to 8:00 PM (Sunday)

WEBSITE & AI CREATOR INFORMATION:
- Creator: Lee Rafael Torres
- Title: Software Engineer 
- Description: An Experienced Software Engineer from Laguna, Philippines. I am passionate about programming. I enjoy coding and continuously strive to improve my skills in developing applications, websites, mobile apps, and systems. I do software engineering principles such as clean code, design patterns, and best practices to deliver high-quality software solutions.
- Age: 21
- Phone: +63 977 334 8124
- Email: grafrafraftorres28@gmail.com
- Location: Calauan, Laguna, Philippines
- Education: PUP Calauan Campus, Laguna
- Social Media Links:
  * Facebook: https://www.facebook.com/lee.torres.5496683/
  * GitHub: https://github.com/LeeDev428
  * LinkedIn: https://www.linkedin.com/in/lee-torres-361168333/
  * Personal Website: https://leedev.vercel.app/

Now, answer this dental question professionally and empathetically: {$question}";

            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $systemPrompt
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
                
                // Format the response for better display
                $formattedResponse = $this->formatAIResponse($aiResponse);
                
                return response()->json([
                    'success' => true,
                    'response' => $formattedResponse
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

    /**
     * Format AI response for better display
     * Converts markdown-style formatting to HTML
     * 
     * @param string $text
     * @return string
     */
    private function formatAIResponse($text)
    {
        // Preserve line breaks and format for HTML display
        
        // Convert **bold** to <strong>
        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
        
        // Convert *italic* to <em>
        $text = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $text);
        
        // Convert bullet points (*, -, •) to proper list items
        // First, detect list blocks
        $lines = explode("\n", $text);
        $inList = false;
        $formattedLines = [];
        
        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            
            // Check if line starts with bullet point
            if (preg_match('/^[\*\-\•]\s+(.+)$/', $trimmedLine, $matches)) {
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
                
                // Add paragraph tags for non-empty lines
                if (!empty($trimmedLine)) {
                    $formattedLines[] = '<p>' . $trimmedLine . '</p>';
                } else {
                    $formattedLines[] = '<br>';
                }
            }
        }
        
        // Close list if still open
        if ($inList) {
            $formattedLines[] = '</ul>';
        }
        
        $formatted = implode("\n", $formattedLines);
        
        // Convert URLs to clickable links
        $formatted = preg_replace(
            '/(https?:\/\/[^\s<]+)/',
            '<a href="$1" target="_blank" class="ai-link">$1</a>',
            $formatted
        );
        
        return $formatted;
    }
}
