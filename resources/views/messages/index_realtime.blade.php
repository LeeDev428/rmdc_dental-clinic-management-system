<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <style>
        .messages-page {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .chat-box {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
        }
        
        .chat-header-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }
        
        .chat-header-info h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }
        
        .chat-header-info p {
            margin: 0;
            font-size: 13px;
            opacity: 0.9;
        }
        
        .online-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #4ade80;
            margin-left: 8px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .typing-indicator {
            padding: 12px 20px;
            font-size: 14px;
            color: #6b7280;
            font-style: italic;
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            display: none;
        }
        
        .typing-indicator.active {
            display: block;
        }
        
        .typing-dots {
            display: inline-flex;
            gap: 4px;
        }
        
        .typing-dots span {
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #9ca3af;
            animation: typing 1.4s infinite;
        }
        
        .typing-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .typing-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }
        
        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-10px); }
        }
        
        .messages-container {
            height: 500px;
            overflow-y: auto;
            padding: 20px;
            background-color: #f9fafb;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .message-item {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            animation: slideIn 0.3s ease-out;
        }
        
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
        
        .message-item.sent {
            flex-direction: row-reverse;
            align-self: flex-end;
        }
        
        .message-item.received {
            flex-direction: row;
            align-self: flex-start;
        }
        
        .message-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .message-bubble {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 18px;
            word-wrap: break-word;
            position: relative;
        }
        
        .message-item.sent .message-bubble {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom-right-radius: 4px;
        }
        
        .message-item.received .message-bubble {
            background-color: #e5e7eb;
            color: #111827;
            border-bottom-left-radius: 4px;
        }
        
        .message-text {
            margin: 0;
            font-size: 15px;
            line-height: 1.5;
        }
        
        .message-time {
            display: block;
            font-size: 11px;
            margin-top: 4px;
            opacity: 0.7;
        }
        
        .message-status {
            display: inline-block;
            margin-left: 4px;
        }
        
        .message-input-area {
            padding: 20px;
            background-color: white;
            border-top: 1px solid #e5e7eb;
        }
        
        .message-form {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .message-textarea {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 24px;
            font-size: 15px;
            resize: none;
            outline: none;
            transition: border-color 0.2s;
            font-family: inherit;
        }
        
        .message-textarea:focus {
            border-color: #667eea;
        }
        
        .send-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .send-btn:hover:not(:disabled) {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .send-btn svg {
            width: 20px;
            height: 20px;
            fill: white;
        }
        
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #9ca3af;
        }
        
        .empty-state i {
            font-size: 64px;
            margin-bottom: 16px;
            opacity: 0.3;
        }
        
        .empty-state p {
            font-size: 16px;
            margin: 0;
        }
    </style>

    <div class="py-12">
        <div class="messages-page">
            <div class="chat-box">
                <!-- Chat Header -->
                <div class="chat-header">
                    <img src="{{ asset('img/default-dp.jpg') }}" 
                         alt="Dentist" 
                         class="chat-header-avatar">
                    <div class="chat-header-info">
                        <h3>
                            RMDC Dental Clinic
                            <span class="online-dot" id="onlineStatus"></span>
                        </h3>
                        <p>We're here to help you!</p>
                    </div>
                </div>
                
                <!-- Typing Indicator -->
                <div class="typing-indicator" id="typingIndicator">
                    <div class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    Dentist is typing...
                </div>
                
                <!-- Messages Container -->
                <div class="messages-container" id="messagesContainer">
                    <!-- Messages will be loaded here via JavaScript -->
                </div>
                
                <!-- Message Input -->
                <div class="message-input-area">
                    <form id="messageForm" class="message-form" onsubmit="sendMessage(event)">
                        @csrf
                        <textarea 
                            id="messageInput"
                            name="message" 
                            class="message-textarea" 
                            placeholder="Type your message..." 
                            rows="1"
                            required
                            autocomplete="off"
                            onkeydown="handleKeyPress(event)"
                            oninput="autoResize(this)"></textarea>
                        <button type="submit" class="send-btn" id="sendButton">
                            <svg viewBox="0 0 24 24">
                                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const currentUserId = {{ Auth::id() }};
        const adminUserId = 1; // Default admin user ID - adjust if needed
        let messages = [];
        let typingTimeout = null;
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadMessages();
            setupRealtimeListeners();
            focusInput();
        });
        
        // Setup Pusher real-time listeners
        function setupRealtimeListeners() {
            if (!window.Echo) {
                console.error('Laravel Echo not initialized');
                return;
            }
            
            // Listen for new messages
            window.Echo.channel(`messages.${currentUserId}`)
                .listen('.new.message', (data) => {
                    console.log('📨 New message received:', data);
                    
                    // Add message to UI if from admin
                    if (data.sender_id !== currentUserId) {
                        addMessageToUI(data, false);
                        scrollToBottom();
                        
                        // Mark as read immediately
                        markMessagesAsRead([data.id]);
                        
                        // Hide typing indicator
                        hideTypingIndicator();
                    }
                });
            
            // Listen for read receipts
            window.Echo.channel(`messages.${currentUserId}`)
                .listen('.message.read', (data) => {
                    console.log('✓✓ Message read:', data);
                    updateMessageReadStatus(data.message_id);
                });
            
            console.log('✅ Pusher listeners setup complete');
        }
        
        // Load messages from MongoDB
        function loadMessages() {
            fetch(`/mongo-messages/conversation?user_id=${adminUserId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messages = data.messages;
                    renderMessages();
                    markUnreadMessages();
                    scrollToBottom();
                } else {
                    console.error('Failed to load messages:', data);
                }
            })
            .catch(error => {
                console.error('Error loading messages:', error);
                showEmptyState();
            });
        }
        
        // Render all messages
        function renderMessages() {
            const container = document.getElementById('messagesContainer');
            container.innerHTML = '';
            
            if (messages.length === 0) {
                showEmptyState();
                return;
            }
            
            messages.forEach(msg => {
                addMessageToUI(msg, true);
            });
        }
        
        // Add single message to UI
        function addMessageToUI(msg, skipAnimation = false) {
            const container = document.getElementById('messagesContainer');
            const isSent = msg.sender_id == currentUserId;
            
            const messageDiv = document.createElement('div');
            messageDiv.className = `message-item ${isSent ? 'sent' : 'received'}`;
            messageDiv.dataset.messageId = msg.id;
            if (!skipAnimation) {
                messageDiv.style.animation = 'slideIn 0.3s ease-out';
            }
            
            const avatar = isSent 
                ? '{{ Auth::user()->avatar_url ?? asset("img/default-dp.jpg") }}'
                : '{{ asset("img/default-dp.jpg") }}';
            
            const time = formatTime(msg.created_at);
            const readStatus = msg.is_read && isSent ? '<span class="message-status">✓✓</span>' : '';
            
            messageDiv.innerHTML = `
                ${!isSent ? `<img src="${avatar}" alt="Avatar" class="message-avatar" onerror="this.src='{{ asset('img/default-dp.jpg') }}'">` : ''}
                <div class="message-bubble">
                    <p class="message-text">${escapeHtml(msg.message)}</p>
                    <span class="message-time">${time} ${readStatus}</span>
                </div>
                ${isSent ? `<img src="${avatar}" alt="Avatar" class="message-avatar" onerror="this.src='{{ asset('img/default-dp.jpg') }}'">` : ''}
            `;
            
            container.appendChild(messageDiv);
        }
        
        // Send message via AJAX
        function sendMessage(event) {
            event.preventDefault();
            
            const input = document.getElementById('messageInput');
            const button = document.getElementById('sendButton');
            const message = input.value.trim();
            
            if (!message) return;
            
            button.disabled = true;
            
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add message to UI
                    addMessageToUI(data.message, false);
                    
                    // Clear input
                    input.value = '';
                    input.style.height = 'auto';
                    
                    // Scroll to bottom
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
                focusInput();
            });
        }
        
        // Mark messages as read
        function markUnreadMessages() {
            const unreadIds = messages
                .filter(msg => msg.recipient_id == currentUserId && !msg.is_read)
                .map(msg => msg.id);
            
            if (unreadIds.length > 0) {
                markMessagesAsRead(unreadIds);
            }
        }
        
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
                if (data.success) {
                    console.log('Messages marked as read');
                }
            })
            .catch(error => console.error('Error marking messages as read:', error));
        }
        
        function updateMessageReadStatus(messageId) {
            const msgElement = document.querySelector(`[data-message-id="${messageId}"]`);
            if (msgElement) {
                const statusSpan = msgElement.querySelector('.message-status');
                if (statusSpan) {
                    statusSpan.textContent = '✓✓';
                }
            }
        }
        
        // Show/hide typing indicator
        function showTypingIndicator() {
            const indicator = document.getElementById('typingIndicator');
            if (indicator) {
                indicator.classList.add('active');
            }
        }
        
        function hideTypingIndicator() {
            const indicator = document.getElementById('typingIndicator');
            if (indicator) {
                indicator.classList.remove('active');
            }
        }
        
        // Handle Enter key
        function handleKeyPress(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendMessage(event);
            }
        }
        
        // Auto-resize textarea
        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
        }
        
        // Scroll to bottom
        function scrollToBottom() {
            const container = document.getElementById('messagesContainer');
            if (container) {
                setTimeout(() => {
                    container.scrollTop = container.scrollHeight;
                }, 100);
            }
        }
        
        // Focus input
        function focusInput() {
            const input = document.getElementById('messageInput');
            if (input) {
                input.focus();
            }
        }
        
        // Show empty state
        function showEmptyState() {
            const container = document.getElementById('messagesContainer');
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-comments"></i>
                    <p>No messages yet. Start a conversation!</p>
                </div>
            `;
        }
        
        // Format time
        function formatTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            
            // Less than 1 minute
            if (diff < 60000) {
                return 'Just now';
            }
            
            // Less than 1 hour
            if (diff < 3600000) {
                const minutes = Math.floor(diff / 60000);
                return `${minutes}m ago`;
            }
            
            // Less than 24 hours
            if (diff < 86400000) {
                return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
            }
            
            // Older
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        }
        
        // Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</x-app-layout>
