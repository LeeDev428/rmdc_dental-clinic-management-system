<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Exempt public AI chatbot endpoint from CSRF verification
        $middleware->validateCsrfTokens(except: [
            '/ask-gemini-public',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
