<x-guest-layout>
    @section('title', 'Verify Email')
    <x-toast-notification />

    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md mx-auto mt-10">
        <!-- Icon -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-4">
                <i class="fas fa-envelope-open-text text-4xl text-blue-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Verify Your Email</h2>
        </div>

        <!-- Message -->
        <div class="text-center text-gray-600 mb-6">
            <p class="mb-4 text-lg font-semibold text-gray-800">Check Your Email for Verification Link!</p>
            <p class="mb-3">We've sent a verification link to <strong>{{ auth()->user()->email }}</strong>. Please check your email and click the link to verify your account.</p>
            <p class="text-sm text-gray-500">Didn't receive the email? Check your spam or junk folder, or click the button below to resend the verification link.</p>
        </div>

        <!-- Resend Button -->
        <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
            @csrf
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                <i class="fas fa-redo"></i>
                Resend Verification Email
            </button>
        </form>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-gray-600 hover:text-gray-900 font-medium py-2 rounded-lg transition duration-200">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </button>
        </form>
    </div>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <script>
        // Show toast for session messages
        @if (session('success'))
            window.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('success') }}', 'success');
            });
        @endif
        
        @if (session('status') == 'verification-link-sent')
            window.addEventListener('DOMContentLoaded', function() {
                showToast('A new verification link has been sent to your email.', 'info');
            });
        @endif
    </script>
</x-guest-layout>
