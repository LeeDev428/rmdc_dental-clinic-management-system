<x-guest-layout>
    @section('title', 'Verify Email')

    <!-- Toast Notification -->
    @if (session('success'))
        <div id="toast" class="fixed top-5 right-5 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 z-50 animate-slide-in">
            <i class="fas fa-check-circle text-2xl"></i>
            <div>
                <p class="font-semibold">Success!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
            <button onclick="document.getElementById('toast').remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if (session('status') == 'verification-link-sent')
        <div id="toast" class="fixed top-5 right-5 bg-blue-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 z-50 animate-slide-in">
            <i class="fas fa-paper-plane text-2xl"></i>
            <div>
                <p class="font-semibold">Email Sent!</p>
                <p class="text-sm">A new verification link has been sent to your email.</p>
            </div>
            <button onclick="document.getElementById('toast').remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

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

    <!-- Toast Animation -->
    <style>
        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
    </style>

    <!-- Auto-hide toast after 5 seconds -->
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast');
            if (toast) {
                toast.style.animation = 'slide-in 0.3s ease-out reverse';
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    </script>
</x-guest-layout>
