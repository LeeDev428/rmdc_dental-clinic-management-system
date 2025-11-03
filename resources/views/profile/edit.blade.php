<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash message -->
            @if (session('status'))
                <div class="p-4 mb-4 text-sm text-green-800 bg-green-100 rounded-lg dark:text-green-400 dark:bg-green-900" role="alert">
                    <span class="font-medium">{{ session('status') }}</span>
                </div>
            @endif

            <div class="flex flex-col md:flex-row justify-start gap-6 md:gap-12"> <!-- Changed justify-center to justify-start -->

                <!-- Profile Overview Section -->
                <div class="flex-1 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg max-w-xl text-left"> <!-- Added text-left -->
                    <h3 class="text-3xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Profile Overview</h3>

                    <!-- Avatar Display in Overview -->
                    <div class="mb-6">
                        @if ($profile->avatar)
                              <img src="{{ $profile->avatar_url }}" alt="Avatar" class="rounded-full w-60 h-60 mb-6 mx-auto">
                        @else
                        
                            <div class="rounded-full w-40 h-40 bg-gray-300 mb-6 mx-auto"></div> <!-- Placeholder if no avatar -->
                        @endif

                        <p class="text-lg text-gray-700 dark:text-gray-400 mb-4 flex items-center justify-start"> <!-- Changed justify-center to justify-start -->
                            <img src="{{ asset('img/editusername.png') }}" alt="User Icon" class="w-11 h-10 mr-3"> <!-- Adjusted size -->
                            {{ $profile->name }}
                        </p>
                        
                        <p class="text-lg text-gray-700 dark:text-gray-400 mb-4 flex items-center justify-start"> <!-- Changed justify-center to justify-start -->
                            <img src="{{ asset('img/editbio.png') }}" alt="Info Icon" class="w-12 h-12 mr-3"> <!-- Adjusted size -->
                            {{ $profile->bio ?? 'No bio available' }}
                        </p>
                        
                        <p class="text-lg text-gray-700 dark:text-gray-400 flex items-center justify-start"> <!-- Changed justify-center to justify-start -->
                            <img src="{{ asset('img/editemail.png') }}" alt="Email Icon" class="w-11 h-10 mr-3"> <!-- Adjusted size -->
                            {{ $profile->email }}
                        </p>
                    </div>
                </div>

                <!-- Edit Profile Section -->
                <div class="flex-1 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h3 class="text-xl font-medium text-gray-800 dark:text-gray-200 mb-4">Edit Profile</h3>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $profile->name ?? '') }}" class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 dark:bg-gray-800 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Bio Field -->
                        <div class="mb-4">
                            <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Bio</label>
                            <textarea name="bio" id="bio" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('bio', $profile->bio ?? '') }}</textarea>
                        </div>

                        <!-- Avatar Field -->
                        <div class="mb-4">
                            <label for="avatar" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Avatar</label>
                            
                            @if ($profile->avatar)
                                <div class="mb-2">
                                    <img id="avatar-preview" src="{{ $profile->avatar_url }}" alt="Avatar" class="rounded-full w-20 h-20">
                                </div>
                            @else
                                <div class="mb-2">
                                    <img id="avatar-preview" src="" alt="Avatar" class="rounded-full w-20 h-20" style="display: none;">
                                </div>
                            @endif

                            <input type="file" name="avatar" id="avatar" class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 dark:bg-gray-800 dark:border-gray-600 rounded-md focus:ring-indigo-500 focus:border-indigo-500" onchange="previewAvatar(event)">
                        </div>

                        <!-- Email Field -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $profile->email ?? '') }}" class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 dark:bg-gray-800 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                {{ __('Update Profile') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Update Section -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account Section -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewAvatar(event) {
            const preview = document.getElementById('avatar-preview');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block'; // Show the preview
                };
                
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>
