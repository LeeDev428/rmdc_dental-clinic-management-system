<x-app-layout>
    @section('title', 'Settings')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 p-6">
                    <!-- Profile Settings Card -->
                    <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-xl transform hover:scale-105 transition duration-300 ease-in-out">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Profile Settings</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Update your profile information and avatar.</p>
                        <a a href="{{ route('profile.edit') }}" class="text-blue-600 hover:text-blue-400">Edit Profile</a>
                    </div>

                    <!-- Language Settings Card -->
                     {{--<div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-xl transform hover:scale-105 transition duration-300 ease-in-out">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Language Settings</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Select your preferred language for the system.</p>
                        <a href="#" class="text-blue-600 hover:text-blue-400">Change Language</a>
                    </div>

                    <!-- Notification Settings Card -->
                    <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-xl transform hover:scale-105 transition duration-300 ease-in-out">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Notification Preferences</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Control your email and SMS notifications.</p>
                        <a href="#" class="text-blue-600 hover:text-blue-400">Manage Notifications</a>
                    </div>

                    <!-- Privacy Settings Card -->
                     <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-xl transform hover:scale-105 transition duration-300 ease-in-out">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Privacy Settings</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Set your privacy preferences and visibility settings.</p>
                        <a href="#" class="text-blue-600 hover:text-blue-400">Manage Privacy</a>
                    </div>

                    <!-- Password Settings Card -->
                    {{-- <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-xl transform hover:scale-105 transition duration-300 ease-in-out">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Change Password</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Update your account password.</p>
                        <a href="{{ route('profile.edit') }}#update-password-section" class="text-blue-600 hover:text-blue-400">Change Password</a>
                    </div> --}}

                    <!-- Account Settings Card
                        <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-xl transform hover:scale-105 transition duration-300 ease-in-out">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Account Settings</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Manage your account preferences.</p>
                        <a href="#" class="text-blue-600 hover:text-blue-400">Account Settings</a>
                    </div> -->

                    <!-- Theme Settings Card -->
                    <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-xl transform hover:scale-105 transition duration-300 ease-in-out">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Theme Settings</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Switch between light and dark mode.</p>
                        <a href="{{ route('theme.settings') }}" class="text-blue-600 hover:text-blue-400">Change Theme</a>

                    </div>

                    <!-- History Settings Card -->
                     <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-xl transform hover:scale-105 transition duration-300 ease-in-out">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">History Settings</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Notification, Appointment, Billing History.</p>
                        <a href="{{ route('history.settings') }}" class="text-blue-600 hover:text-blue-400">History Settings</a>
                    </div>

                    <!-- Advanced Settings Card
                    <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-xl transform hover:scale-105 transition duration-300 ease-in-out">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Advanced Settings</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Access advanced configurations and developer settings.</p>
                        <a href="#" class="text-blue-600 hover:text-blue-400">Advanced Settings</a>
                    </div>-->   
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
