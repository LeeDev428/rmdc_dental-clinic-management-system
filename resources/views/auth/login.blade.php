<x-guest-layout>
    @section('title', 'Log in')
    @vite('resources/css/forgotpassword.css')

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <div class="bg-white rounded-lg shadow-lg flex flex-col md:flex-row w-full max-w-4xl mx-auto mt-6 overflow-hidden">

        <!-- Left Side: Login Form -->
        <div class="w-full md:w-3/5 p-6 md:p-10">
            <h2 class="text-2xl font-bold text-gray-900 text-center">Login to Your Account</h2>
            <p class="text-center text-gray-600 mt-2">Login using social networks</p>

            <!-- Social Media Buttons -->
            <div class="flex justify-center gap-3 mt-4">
                <a href="{{ route('facebook.login') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white text-lg">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <a href="{{ route('google.login') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-red-500 text-white text-lg">
                    <i class="fa-brands fa-google"></i>
                </a>
                
            </div>

            <!-- OR Divider -->
            <div class="text-center my-5 text-gray-500">or</div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="mt-3">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" class="text-md" />
                    <x-text-input id="email" class="block mt-1 w-full p-2.5 text-md border border-gray-300 rounded-md" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500" />
                </div>

                <!-- Password -->
                <div class="mb-4 relative">
                    <x-input-label for="password" :value="__('Password')" class="text-md" />
                    <x-text-input id="password" class="block mt-1 w-full p-2.5 text-md border border-gray-300 rounded-md pr-10" type="password" name="password" required autocomplete="current-password" />
                    <i class="fa fa-eye absolute top-10 right-3 cursor-pointer text-gray-500 hover:text-gray-700" onclick="togglePasswordVisibility('password')"></i>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500" />
                </div>

                <!-- Remember Me -->
                <div class="mb-4 flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4" />
                    <label for="remember_me" class="ml-2 text-md text-gray-600">{{ __('Remember me') }}</label>
                </div>

                <!-- Links -->
                <div class="flex justify-between text-md mb-4">

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-gray-600 hover:underline">{{ __('Forgot your password?') }}</a>
                    @endif
                </div>

                <!-- Login Button -->
                <div>
                    <x-primary-button class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-md">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

        <!-- Right Side: Welcome Section -->
        <div class="w-full md:w-2/5 bg-gradient-to-r from-blue-600 to-green-400 text-white p-6 md:p-10 flex flex-col justify-center items-center rounded-b-lg md:rounded-b-none md:rounded-r-lg">
            <h3 class="text-xl font-bold text-center">New Here?</h3>
            <p class="text-center mt-3 text-md">Sign up for a smile-worthy experience!</p>
            <a href="{{ route('register') }}" class="mt-4 bg-white text-blue-600 hover:bg-gray-100 px-5 py-2 rounded-md text-md font-bold">
                Sign Up
            </a>
        </div>

    </div>

    <script>
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling;
            
            if (field.getAttribute('type') === 'password') {
                field.setAttribute('type', 'text');
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.setAttribute('type', 'password');
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</x-guest-layout>
