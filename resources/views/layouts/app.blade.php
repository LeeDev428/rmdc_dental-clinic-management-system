<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
     
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('img/dcms_loading_logo.png') }}" type="image/png">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Pre-render Script to Set Theme -->
        <script>
            (function() {
                const savedTheme = localStorage.getItem('theme') || '{{ session('theme', 'light') }}';
                if (savedTheme === 'dark') {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Alef&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

<!-- Only Bootstrap Modal JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <!-- Additional CSS -->
        @yield('css') <!-- Add this line -->
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased {{ session('theme', 'light') }}">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Check if the theme is stored in localStorage
                const storedTheme = localStorage.getItem('theme');
                if (storedTheme) {
                    // Apply the theme from localStorage
                    document.body.classList.add(storedTheme);
                } else {
                    // If no theme is stored, fallback to the session-based theme (default: light)
                    const sessionTheme = '{{ session('theme', 'light') }}';
                    document.body.classList.add(sessionTheme);
                }
    
                // Theme toggle functionality
                document.getElementById('theme-toggle')?.addEventListener('click', function() {
                    const body = document.body;
                    body.classList.toggle('dark');
                    body.classList.toggle('light');
                    const currentTheme = body.classList.contains('dark') ? 'dark' : 'light';
    
                    // Save the theme to localStorage
                    localStorage.setItem('theme', currentTheme);
    
                    // Send the theme to the server for session storage
                    fetch('{{ route('set-theme') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ theme: currentTheme })
                    });
                });
            });
        </script>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Additional Scripts -->
        @yield('scripts') <!-- Add this line -->
        
        <!-- Include Alpine.js -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

        <script>
            // Store the theme in session and update the body class
            document.getElementById('theme-toggle')?.addEventListener('click', function() {
                const body = document.body;
                body.classList.toggle('dark');
                body.classList.toggle('light');
                const currentTheme = body.classList.contains('dark') ? 'dark' : 'light';
        
                // Save the theme to localStorage
                localStorage.setItem('theme', currentTheme);
        
                // Send the theme preference to the server
                fetch('{{ route('set-theme') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ theme: currentTheme })
                });
            });
        </script>
        
        
    </body>
</html>
