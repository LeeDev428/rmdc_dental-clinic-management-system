<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('img/dcms_loading_logo.png') }}" type="image/png">
    <title>{{ config('app.name', 'Laravel') }} | @yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Fonts and Styles -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Background for login and registration pages */
        body {
            background: url("{{ asset('img/dcms_bg.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            height: 100vh;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.55);
            z-index: -1;
        }

        /* Loading Screen */
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased" onload="hideLoadingScreen()">
    <!-- Loading Screen -->
    <div id="loading-screen">
        <img src="{{ asset('img/dcms_loading_logo.png') }}" alt="Loading..." style="width: 100px;">
    </div>

    <div id="main-content" style="display: none;">
        <div class="flex justify-center items-center min-h-screen">
            <!-- Login Form Container -->
            <div class="form-container">
                {{ $slot }}
            </div>
        </div>

        <footer class="text-center p-6 bg-gray-800 text-white">
            <p>&copy; 2025 RMDC. All Rights Reserved.</p>
            <p><a href="#" class="text-white">Privacy Policy &nbsp;&nbsp;|&nbsp;&nbsp; Terms of Service</a></p>
        </footer>
    </div>

    <script>
        // Hide Loading Screen After Page Loads
        function hideLoadingScreen() {
            document.getElementById("loading-screen").style.display = "none"; 
            document.getElementById("main-content").style.display = "block"; 
        }
    </script>
</body>
</html>
