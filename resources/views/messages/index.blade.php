<x-app-layout>
    @section('title', 'Chat with the Dentist')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chat with the Dentist') }}
        </h2>
    </x-slot>
    
    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <!-- Chat Section -->
                <div class="flex flex-col h-[calc(100vh-200px)]"> <!-- Ensuring full height with input form at the bottom -->
                    <!-- Chat Messages Container -->
                    <div id="message-container" class="flex-1 overflow-y-auto p-6 space-y-3 bg-gray-100 dark:bg-gray-800 rounded-t-lg border border-gray-300 dark:border-gray-700">
                        
                        <!-- Typing indicator -->
                        <div id="typing-indicator" class="hidden flex items-center space-x-2 text-gray-600 dark:text-gray-400 text-sm mb-2">
                            <div class="flex space-x-1">
                                <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                                <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                                <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                            </div>
                            <span>Admin is typing...</span>
                        </div>
                        
@if($messages->isEmpty())
    <!-- Empty State -->
    <div class="flex flex-col items-center justify-center h-full text-center py-12">
        <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-6 mb-4">
            <i class="fas fa-comments text-5xl text-blue-600 dark:text-blue-400"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">No Messages Yet</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-6">Start a conversation with your dentist below!</p>
        <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
            <i class="fas fa-info-circle"></i>
            <span>Type your message in the box below to begin</span>
        </div>
    </div>
@else
@foreach ($messages as $message)
    <div class="flex mb-3">
        <!-- If message is from the patient (right side) -->
        @if($message->sender_id == Auth::id())
            <div class="ml-auto p-3 bg-blue-600 text-white rounded-lg max-w-full">
                <div class="flex items-center space-x-2 mb-2">
                    <!-- Patient's Profile Image -->
                    <img src="{{ $message->sender->avatar_url ?? Auth::user()->avatar_url ?? asset('img/default-dp.jpg') }}"
                         alt="Patient Avatar" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                    <!-- Message Time -->
                    <div class="text-xs text-gray-200 hover:text-gray-100 cursor-pointer">
                        • {{ $message->created_at instanceof \MongoDB\BSON\UTCDateTime ? $message->created_at->toDateTime()->setTimezone(new \DateTimeZone(config('app.timezone')))->diffForHumans() : \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}
                    </div>
                </div>
                <p class="whitespace-pre-line break-words">{{ $message->message }}</p>
            </div>
        <!-- If message is from the admin (left side) -->
        @else
            <div class="mr-auto p-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg max-w-full">
                <div class="flex items-center space-x-2 mb-2">
                    <!-- Admin's Profile Image -->
                    <img src="{{ $message->sender->avatar_url ?? asset('img/default-dp.jpg') }}"
                         alt="Admin Avatar" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                    <!-- Message Time -->
                    <div class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-600 cursor-pointer">
                        • {{ $message->created_at instanceof \MongoDB\BSON\UTCDateTime ? $message->created_at->toDateTime()->setTimezone(new \DateTimeZone(config('app.timezone')))->diffForHumans() : \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}
                    </div>
                </div>
                <p class="whitespace-pre-line break-words">{{ $message->message }}</p>
            </div>
        @endif
    </div>
@endforeach
@endif

                    </div>

                    <!-- Input Form (fixed at the bottom) -->
                    <div class="flex items-center p-4 bg-white dark:bg-gray-800 border-t border-gray-300 dark:border-gray-700 mt-auto">
                        <form id="messageForm" onsubmit="sendMessage(event)" class="w-full flex items-center space-x-4">
                            @csrf
                            <textarea id="messageInput" name="message" class="w-full p-4 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 resize-none" placeholder="Type your message..." required></textarea>
                            <button type="submit" id="sendButton" class="text-white bg-blue-600 rounded-full p-3 hover:bg-blue-700 focus:outline-none">
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
        const currentUserId = {{ Auth::id() }};
        const adminUserId = {{ isset($adminUser) && $adminUser ? $adminUser->id : 'null' }};
        
        console.log('Patient messaging page loaded', {
            patientId: currentUserId,
            talkingToAdminId: adminUserId
        });
        
        if (!adminUserId || adminUserId === null) {
            console.error('No admin user found! Cannot send messages.');
            alert('Error: No admin available to chat with. Please contact support.');
        }
        
        let typingTimer;
        const typingTimeout = 3000; // 3 seconds
        let isTyping = false;
        
        document.addEventListener('DOMContentLoaded', function () {
            const messageContainer = document.getElementById('message-container');
            const messageInput = document.getElementById('messageInput');
            const typingIndicator = document.getElementById('typing-indicator');
            
            // Scroll to the bottom of the message container
            messageContainer.scrollTop = messageContainer.scrollHeight;
            
            // Mark all unread messages as read when page loads
            const unreadMessages = [];
            @foreach ($messages as $message)
                @if($message->recipient_id == Auth::id() && !$message->is_read)
                    unreadMessages.push('{{ $message->_id }}');
                @endif
            @endforeach
            
            if (unreadMessages.length > 0) {
                console.log('Marking', unreadMessages.length, 'messages as read');
                markMessagesAsRead(unreadMessages);
            }
            
            // Typing indicator - send typing status
            messageInput.addEventListener('input', function() {
                if (!isTyping) {
                    isTyping = true;
                    sendTypingStatus(true);
                }
                
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    isTyping = false;
                    sendTypingStatus(false);
                }, typingTimeout);
            });
            
            // Send typing status to server
            function sendTypingStatus(typing) {
                fetch('/mongo-messages/typing', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                    body: JSON.stringify({
                        recipient_id: adminUserId,
                        typing: typing
                    })
                }).catch(err => console.error('Error sending typing status:', err));
            }
            
            // Setup Pusher real-time listeners
            if (window.Echo) {
                console.log('✅ Setting up Pusher listeners for patient...');
                
                // Listen for typing events
                window.Echo.channel(`messages.${currentUserId}`)
                    .listen('.user.typing', (data) => {
                        console.log('Typing event received:', data);
                        if (data.sender_id == adminUserId) {
                            if (data.typing) {
                                typingIndicator.classList.remove('hidden');
                                messageContainer.scrollTop = messageContainer.scrollHeight;
                            } else {
                                typingIndicator.classList.add('hidden');
                            }
                        }
                    });
                
                // Listen for new messages from admin
                window.Echo.channel(`messages.${currentUserId}`)
                    .listen('.new.message', (data) => {
                        console.log('📨 New message received:', data);
                        if (data.sender_id !== currentUserId) {
                            addMessageToUI(data, false);
                            scrollToBottom();
                            markMessagesAsRead([data.id]);
                        }
                    });
                
                // Listen for read receipts
                window.Echo.channel(`messages.${currentUserId}`)
                    .listen('.message.read', (data) => {
                        console.log('✓✓ Message read:', data);
                    });
            }
        });
        
        // Add message to UI
        function addMessageToUI(msg, isSent) {
            const container = document.getElementById('message-container');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex mb-3';
            messageDiv.style.animation = 'slideIn 0.3s ease-out';
            
            // Get avatar from message data or fallback
            let avatar;
            if (isSent) {
                avatar = msg.sender && msg.sender.avatar_url ? msg.sender.avatar_url : '{{ Auth::user()->avatar_url ?? asset("img/default-dp.jpg") }}';
            } else {
                avatar = msg.sender && msg.sender.avatar_url ? msg.sender.avatar_url : '{{ asset("img/default-dp.jpg") }}';
            }
            
            if (isSent) {
                messageDiv.innerHTML = `
                    <div class="ml-auto p-3 bg-blue-600 text-white rounded-lg max-w-full">
                        <div class="flex items-center space-x-2 mb-2">
                            <img src="${avatar}" alt="Avatar" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;" onerror="this.src='{{ asset('img/default-dp.jpg') }}'">
                            <div class="text-xs text-gray-200 hover:text-gray-100 cursor-pointer">
                                • Just now
                            </div>
                        </div>
                        <p class="whitespace-pre-line break-words">${escapeHtml(msg.message)}</p>
                    </div>
                `;
            } else {
                messageDiv.innerHTML = `
                    <div class="mr-auto p-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg max-w-full">
                        <div class="flex items-center space-x-2 mb-2">
                            <img src="${avatar}" alt="Admin Avatar" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;" onerror="this.src='{{ asset('img/default-dp.jpg') }}'">
                            <div class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-600 cursor-pointer">
                                • Just now
                            </div>
                        </div>
                        <p class="whitespace-pre-line break-words">${escapeHtml(msg.message)}</p>
                    </div>
                `;
            }
            
            container.appendChild(messageDiv);
        }
        
        // Send message via AJAX to MongoDB
        function sendMessage(event) {
            event.preventDefault();
            
            const input = document.getElementById('messageInput');
            const button = document.getElementById('sendButton');
            const message = input.value.trim();
            
            if (!message) return;
            
            if (!adminUserId || adminUserId === null) {
                alert('Cannot send message: No admin user available');
                return;
            }
            
            button.disabled = true;
            
            console.log('Sending message:', { message, recipient_id: adminUserId });
            
            fetch('/mongo-messages/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    recipient_id: adminUserId
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        console.error('Server error:', err);
                        throw new Error(err.message || 'Failed to send message');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    addMessageToUI(data.message, true);
                    input.value = '';
                    scrollToBottom();
                } else {
                    alert('Failed to send message. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
                alert('Network error. Please check your connection.');
            })
            .finally(() => {
                button.disabled = false;
                input.focus();
            });
        }
        
        // Mark messages as read
        function markMessagesAsRead(messageIds) {
            fetch('/mongo-messages/mark-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message_ids: messageIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.unread_count !== undefined) {
                    console.log('Messages marked as read. Unread count:', data.unread_count);
                }
            })
            .catch(error => console.error('Error marking messages as read:', error));
        }
        
        // Scroll to bottom
        function scrollToBottom() {
            const container = document.getElementById('message-container');
            if (container) {
                setTimeout(() => {
                    container.scrollTop = container.scrollHeight;
                }, 100);
            }
        }
        
        // Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Automatically scroll the message container to the bottom on new messages
        window.addEventListener('message', function () {
            const messageContainer = document.getElementById('message-container');
            messageContainer.scrollTop = messageContainer.scrollHeight;
        });
    </script>
</x-app-layout>
