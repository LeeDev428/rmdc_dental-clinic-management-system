<x-app-layout>
    @section('title', 'Chat with the Dentist')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chat with the Dentist') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <!-- Chat Section -->
                <div class="flex flex-col h-[calc(100vh-200px)]"> <!-- Ensuring full height with input form at the bottom -->
                    <!-- Chat Messages Container -->
                    <div id="message-container" class="flex-1 overflow-y-auto p-6 space-y-3 bg-gray-100 dark:bg-gray-800 rounded-t-lg border border-gray-300 dark:border-gray-700">
@foreach ($messages as $message)
    <div class="flex mb-3">
        <!-- If message is from the patient (right side) -->
        @if($message->is_admin == 0)
            <div class="ml-auto p-3 bg-blue-600 text-white rounded-lg max-w-full">
                <div class="flex items-center space-x-2 mb-2">
                    <!-- Patient's Profile Image -->
                    <img src="{{ $message->user->avatar ? Storage::url($message->user->avatar) : asset('img/defaultprofile.png') }}"
                         alt="Patient Avatar" style="width: 30px; height: 30px; border-radius: 50%;">
                    <!-- Message Time -->
                    <div class="text-xs text-gray-400 hover:text-gray-600 cursor-pointer">
                        • {{ $message->created_at->diffForHumans() }}
                    </div>
                </div>
                <p class="whitespace-pre-line break-words">{{ $message->message }}</p>
            </div>
        <!-- If message is from the admin (left side) -->
        @else
            <div class="mr-auto p-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg max-w-full">
                <div class="flex items-center space-x-2 mb-2">
                    <!-- Admin's Profile Image -->
                    <img src="{{ $message->user->avatar && Storage::exists('avatars/' . $message->user->avatar)
                     ? Storage::url('avatars/' . $message->user->avatar)
                     : asset('img/defaultprofile.png') }}"
                         alt="Admin Avatar" style="width: 30px; height: 30px; border-radius: 50%;">
                    <!-- Message Time -->
                    <div class="text-xs text-gray-400 hover:text-gray-600 cursor-pointer">
                        • {{ $message->created_at->diffForHumans() }}
                    </div>
                </div>
                <p class="whitespace-pre-line break-words">{{ $message->message }}</p>
            </div>
        @endif
    </div>
@endforeach

                    </div>

                    <!-- Input Form (fixed at the bottom) -->
                    <div class="flex items-center p-4 bg-white dark:bg-gray-800 border-t border-gray-300 dark:border-gray-700 mt-auto">
                        <form action="{{ route('messages.store') }}" method="POST" class="w-full flex items-center space-x-4">
                            @csrf
                            <textarea name="message" class="w-full p-4 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 resize-none" placeholder="Type your message..." required></textarea>
                            <button type="submit" class="text-white bg-blue-600 rounded-full p-3 hover:bg-blue-700 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const messageContainer = document.getElementById('message-container');
            // Scroll to the bottom of the message container
            messageContainer.scrollTop = messageContainer.scrollHeight;
        });

        // Automatically scroll the message container to the bottom on new messages
        window.addEventListener('message', function () {
            const messageContainer = document.getElementById('message-container');
            messageContainer.scrollTop = messageContainer.scrollHeight;
        });
    </script>
</x-app-layout>
