<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Groq AI API Key
    |--------------------------------------------------------------------------
    |
    | API key for Groq (https://console.groq.com).
    | Set GROQ_API_KEY in your .env file.
    |
    */
    'api_key' => env('GROQ_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Groq Model
    |--------------------------------------------------------------------------
    |
    | The Groq model to use for AI completions.
    | Recommended: llama-3.3-70b-versatile (best quality, free tier).
    |
    */
    'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
];
