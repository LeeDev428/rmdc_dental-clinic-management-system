<x-guest-layout>
    @section('title', 'Recover')
    
    <div class="bg-white rounded-lg shadow-lg flex flex-col md:flex-row w-full max-w-4xl mx-auto mt-6 overflow-hidden">

        <!-- Left Side: Forgot Password Form -->
        <div class="w-full md:w-3/5 p-6 md:p-10">
            <h2 class="text-2xl font-bold text-gray-900 text-center">Forgot Your Password?</h2>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Forgot Password Form -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" class="text-md" />
                    <x-text-input id="email" class="block mt-1 w-full p-2.5 text-md border border-gray-300 rounded-md" type="email" name="email" :value="old('email')" required autofocus autocomplete="email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500" />
                </div>

                <!-- Email Password Reset Link Button -->
                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-md">
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

        <!-- Right Side: Instruction Section -->
        <div class="w-full md:w-2/5 bg-gradient-to-r from-blue-600 to-green-400 text-white p-6 md:p-10 flex flex-col justify-center items-center rounded-b-lg md:rounded-b-none md:rounded-r-lg">
            
            <p class="text-center mt-3 text-md">No problem! Just enter your email address below, and we'll send you a password reset link.</p>
        </div>

    </div>
</x-guest-layout>
