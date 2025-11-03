<x-guest-layout>
    @section('title', 'Register')
    @vite('resources/css/alreadyregistered.css')

    <!-- Font Awesome CDN for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <div class="bg-white rounded-lg shadow-lg flex flex-col md:flex-row w-full max-w-4xl mx-auto mt-6 overflow-hidden">

        <!-- Register Form -->
        <div class="w-full md:w-3/5 p-6 md:p-10 order-1 md:order-2">
            <h2 class="text-2xl font-bold text-gray-900 text-center">Create an Account</h2>
            <p class="text-center text-gray-600 mt-2">Sign up to get started</p>

            <!-- Social Media Login Options -->
            <div class="flex justify-center gap-3 mt-4">
                <a href="{{ route('facebook.login') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white text-lg">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <a href="{{ route('google.login') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-red-500 text-white text-lg">
                    <i class="fa-brands fa-google"></i>
                </a>
            </div>

            <form method="POST" action="{{ route('register') }}" class="mt-3">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <x-input-label for="name" :value="__('Name')" class="text-md" />
                    <x-text-input id="name" class="block mt-1 w-full p-2.5 text-md border border-gray-300 rounded-md" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-500" />
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" class="text-md" />
                    <x-text-input id="email" class="block mt-1 w-full p-2.5 text-md border border-gray-300 rounded-md" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500" />
                </div>

                <!-- Password -->
                <div class="mb-4 relative">
                    <x-input-label for="password" :value="__('Password')" class="text-md" />
                    <x-text-input id="password" class="block mt-1 w-full p-2.5 text-md border border-gray-300 rounded-md" type="password" name="password" required autocomplete="new-password" oninput="checkPasswordStrength()" />
                    <i class="fa fa-eye absolute top-10 right-3 cursor-pointer" onclick="togglePasswordVisibility('password')"></i>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-4 relative">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-md" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full p-2.5 text-md border border-gray-300 rounded-md" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <i class="fa fa-eye absolute top-10 right-3 cursor-pointer" onclick="togglePasswordVisibility('password_confirmation')"></i>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500" />
                </div>

                <!-- Password Strength Indicator -->
                <div class="mb-4">
                    <label class="text-md font-semibold">Password Strength:</label>
                    <div class="flex items-center gap-2 mt-2">
                        <div class="flex-1 h-2 rounded-full bg-gray-300" id="strength-bar-1"></div>
                        <div class="flex-1 h-2 rounded-full bg-gray-300" id="strength-bar-2"></div>
                        <div class="flex-1 h-2 rounded-full bg-gray-300" id="strength-bar-3"></div>
                        <div class="flex-1 h-2 rounded-full bg-gray-300" id="strength-bar-4"></div>
                    </div>
                    <p id="strength-label" class="mt-2 text-sm font-semibold text-gray-600">Weak</p>
                </div>

                <!-- Captcha -->
                <div class="mb-4">
                    <x-input-label for="captcha" :value="__('Verify you are human:')" class="text-md" />
                    <div class="flex items-center gap-3 mt-2">
                        <img src="{{ captcha_src('flat') }}" alt="Captcha" class="border rounded-md cursor-pointer" onclick="this.src='{{ captcha_src('flat') }}?'+Math.random()" title="Click to refresh">
                        <button type="button" onclick="document.querySelector('img[alt=Captcha]').src='{{ captcha_src('flat') }}?'+Math.random()" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                    <x-text-input id="captcha" class="block w-full p-2.5 text-md border border-gray-300 rounded-md mt-2" type="text" name="captcha" placeholder="Enter the characters above" required />
                    <x-input-error :messages="$errors->get('captcha')" class="mt-1 text-red-500" />
                </div>

                <!-- Register Button -->
                <div>
                    <x-primary-button class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-md">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
<br>
<br>
        <!-- Welcome Section (Now Below the Form on Mobile) -->
        <div class="flex flex-col items-center justify-center text-center p-6 md:w-2/5 bg-gradient-to-r from-blue-600 to-green-400 text-white md:rounded-l-lg order-2 md:order-1">
            <h3 class="text-xl font-bold">Welcome Back!</h3>
            <p class="mt-3 text-md">Already have an account? Log in and keep your smile on track!</p>
            <a href="{{ route('login') }}" class="mt-4 bg-white text-blue-600 hover:bg-gray-100 px-5 py-2 rounded-md text-md font-bold">
                Log In
            </a>
        </div>

    </div>
    <br>
    <br>

    <script>
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const bars = [
                document.getElementById('strength-bar-1'),
                document.getElementById('strength-bar-2'),
                document.getElementById('strength-bar-3'),
                document.getElementById('strength-bar-4'),
            ];
            const label = document.getElementById('strength-label');

            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;

            bars.forEach((bar, index) => {
                bar.style.backgroundColor = index < strength ? (strength === 4 ? 'green' : strength === 3 ? 'orange' : 'red') : 'gray';
            });

            label.textContent = strength === 4 ? 'Strong' : strength === 3 ? 'Medium' : 'Weak';
            label.style.color = strength === 4 ? 'green' : strength === 3 ? 'orange' : 'red';
        }

        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', type);
        }
    </script>
</x-guest-layout>
