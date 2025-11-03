<x-guest-layout>
    @section('title', 'Verify Email')

    <div class="bg-white rounded-lg shadow-lg flex flex-row w-full max-w-4xl mx-auto mt-10 overflow-hidden">
        
        <!-- Left Side: Icon and Message -->
        <div class="w-2/5 bg-gradient-to-br from-blue-600 to-green-400 text-white p-10 flex flex-col justify-center items-center">
            <div class="mb-6">
                <i class="fas fa-envelope-circle-check text-8xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-center mb-3">Email Sent!</h2>
            <p class="text-center text-sm opacity-90">Check your inbox to verify your account</p>
        </div>

        <!-- Right Side: Instructions and Actions -->
        <div class="w-3/5 p-10">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Check Your Email</h3>
            
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-6">
                <p class="text-gray-800 mb-2">
                    📧 We've sent a verification link to:
                </p>
                <p class="text-blue-600 font-semibold">
                    {{ auth()->user()->email }}
                </p>
            </div>

            <p class="text-gray-600 mb-4 text-sm">
                Click the link in your email to verify your account. If you don't see the email, check your spam folder.
            </p>

            <!-- Open Gmail Button -->
            <a href="https://mail.google.com" target="_blank" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200 text-center mb-3">
                <i class="fas fa-external-link-alt mr-2"></i> Open Gmail
            </a>

            <!-- Resend Button -->
            <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
                @csrf
                <button type="submit" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 rounded-lg transition duration-200">
                    <i class="fas fa-redo mr-2"></i> Resend Email
                </button>
            </form>

            @if (session('status') == 'verification-link-sent')
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-3 text-sm">
                    ✓ Verification email sent successfully!
                </div>
            @endif

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-gray-500 hover:text-gray-700 font-medium py-2 text-sm">
                    <i class="fas fa-sign-out-alt mr-1"></i> Log Out
                </button>
            </form>
        </div>
    </div>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</x-guest-layout>
