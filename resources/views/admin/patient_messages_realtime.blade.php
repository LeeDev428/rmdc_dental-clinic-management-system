@extends('layouts.admin')

@section('title', 'Real-time Messages')

@section('content')
<style>
    .chat-container {
        display: flex;
        height: calc(100vh - 120px);
        background-color: #f0f2f5;
    }
    
    .users-sidebar {
        width: 320px;
        background-color: #fff;
        border-right: 1px solid #e4e6eb;
        display: flex;
        flex-direction: column;
    }
    
    .users-header {
        padding: 16px;
        border-bottom: 1px solid #e4e6eb;
    }
    
    .users-header h3 {
        font-size: 24px;
        font-weight: 700;
        margin: 0;
        color: #050505;
    }
    
    .search-bar {
        padding: 8px 16px;
        border-bottom: 1px solid #e4e6eb;
    }
    
    .search-bar input {
        width: 100%;
        padding: 8px 12px;
        border: none;
        background-color: #f0f2f5;
        border-radius: 20px;
        font-size: 15px;
    }
    
    .users-list {
        flex: 1;
        overflow-y: auto;
    }
    
    .user-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        cursor: pointer;
        transition: background-color 0.2s;
        border: none;
        text-decoration: none;
        color: inherit;
        position: relative;
    }
    
    .user-item:hover {
        background-color: #f0f2f5;
    }
    
    .user-item.active {
        background-color: #e7f3ff;
    }
    
    .user-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        margin-right: 12px;
        object-fit: cover;
    }
    
    .user-info {
        flex: 1;
        min-width: 0;
    }
    
    .user-name {
        font-weight: 600;
        font-size: 15px;
        color: #050505;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .user-preview {
        font-size: 13px;
        color: #65676b;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .user-time {
        font-size: 12px;
        color: #65676b;
        margin-left: 8px;
    }
    
    .unread-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #ff4444;
        color: white;
        border-radius: 10px;
        padding: 2px 8px;
        font-size: 11px;
        font-weight: bold;
    }
    
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background-color: #fff;
    }
    
    .chat-header {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid #e4e6eb;
        background-color: #fff;
    }
    
    .chat-header-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 12px;
        object-fit: cover;
    }
    
    .chat-header-name {
        font-size: 16px;
        font-weight: 600;
        color: #050505;
        margin: 0;
    }
    
    .online-status {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #31a24c;
        margin-left: 8px;
    }
    
    .typing-indicator {
        font-size: 13px;
        color: #65676b;
        font-style: italic;
        padding: 8px 16px;
        display: none;
    }
    
    .typing-indicator.active {
        display: block;
    }
    
    .messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        background-color: #f0f2f5;
    }
    
    .message-wrapper {
        display: flex;
        align-items: flex-end;
        margin-bottom: 8px;
    }
    
    .message-wrapper.sent {
        flex-direction: row-reverse;
    }
    
    .message-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        margin: 0 8px;
        object-fit: cover;
    }
    
    .message-bubble {
        max-width: 60%;
        padding: 8px 12px;
        border-radius: 18px;
        word-wrap: break-word;
    }
    
    .message-wrapper.sent .message-bubble {
        background-color: #0084ff;
        color: #fff;
    }
    
    .message-wrapper.received .message-bubble {
        background-color: #e4e6eb;
        color: #050505;
    }
    
    .message-content {
        margin: 0;
        font-size: 15px;
        line-height: 1.4;
    }
    
    .message-time {
        display: flex;
        align-items: center;
        font-size: 11px;
        margin-top: 2px;
    }
    
    .message-wrapper.sent .message-time {
        color: rgba(255, 255, 255, 0.8);
        justify-content: flex-end;
    }
    
    .message-wrapper.received .message-time {
        color: #65676b;
    }
    
    .message-input-container {
        padding: 12px 20px;
        background-color: #fff;
        border-top: 1px solid #e4e6eb;
    }
    
    .message-input-wrapper {
        display: flex;
        align-items: center;
        background-color: #f0f2f5;
        border-radius: 20px;
        padding: 8px 16px;
    }
    
    .message-input {
        flex: 1;
        border: none;
        background: transparent;
        font-size: 15px;
        outline: none;
        padding: 0 8px;
        resize: none;
        min-height: 20px;
        max-height: 100px;
        font-family: inherit;
    }
    
    .send-button {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #0084ff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .send-button:hover {
        background-color: #0073e6;
    }
    
    .send-button:disabled {
        background-color: #cccccc;
        cursor: not-allowed;
    }
    
    .send-button svg {
        width: 20px;
        height: 20px;
        fill: #fff;
    }
    
    .no-chat-selected {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        font-size: 18px;
        color: #65676b;
        flex-direction: column;
    }
    
    .no-chat-selected i {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.3;
    }
    
    .message-fade-in {
        animation: fadeIn 0.3s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="chat-container">
    <!-- Users Sidebar -->
    <div class="users-sidebar">
        <div class="users-header">
            <h3>Messages</h3>
        </div>
        
        <div class="search-bar">
            <input type="text" id="userSearch" placeholder="Search patients..." autocomplete="off">
        </div>
        
        <div class="users-list" id="usersList">
            @foreach ($users as $user)
                <div class="user-item {{ $selectedUser && $selectedUser->id == $user->id ? 'active' : '' }}" 
                     data-user-id="{{ $user->id }}"
                     onclick="selectUser({{ $user->id }})">
                    <img src="{{ $user->avatar_url }}" 
                         alt="{{ $user->name }}"
                         onerror="this.src='{{ asset('img/default-dp.jpg') }}'"
                         class="user-avatar">
                    <div class="user-info">
                        <p class="user-name">{{ $user->name }}</p>
                        <p class="user-preview">Click to view messages</p>
                    </div>
                    <span class="unread-badge" style="display: none;">0</span>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Chat Main Area -->
    <div class="chat-main">
        @if ($selectedUser)
            <div class="chat-header">
                <img src="{{ $selectedUser->avatar_url }}" 
                     alt="{{ $selectedUser->name }}"
                     onerror="this.src='{{ asset('img/default-dp.jpg') }}'"
                     class="chat-header-avatar">
                <div>
                    <h3 class="chat-header-name">
                        {{ $selectedUser->name }}
                        <span class="online-status" id="onlineStatus" style="display: none;"></span>
                    </h3>
                </div>
            </div>
            
            <div class="typing-indicator" id="typingIndicator">
                <i class="fas fa-circle"></i>
                <i class="fas fa-circle"></i>
                <i class="fas fa-circle"></i>
                {{ $selectedUser->name }} is typing...
            </div>
            
            <div class="messages-container" id="messagesContainer">
                <!-- Messages will be loaded here -->
            </div>
            
            <div class="message-input-container">
                <form id="messageForm" onsubmit="sendMessage(event)">
                    @csrf
                    <input type="hidden" name="recipient_id" id="recipientId" value="{{ $selectedUser->id }}">
                    <div class="message-input-wrapper">
                        <textarea 
                            name="message" 
                            id="messageInput"
                            class="message-input" 
                            placeholder="Type your message..." 
                            required 
                            autocomplete="off"
                            rows="1"
                            onkeydown="handleKeyPress(event)"
                            oninput="autoResize(this)"></textarea>
                        <button type="submit" class="send-button" id="sendButton">
                            <svg viewBox="0 0 24 24">
                                <path d="M16.6915026,12.4744748 L3.50612381,13.2599618 C3.19218622,13.2599618 3.03521743,13.4170592 3.03521743,13.5741566 L1.15159189,20.0151496 C0.8376543,20.8006365 0.99,21.89 1.77946707,22.52 C2.41,23.01 3.50612381,23.01 4.13399899,22.52 L22.3541541,12.8338182 C22.8,12.5178016 23.03521743,12.0490137 23.03521743,11.5802258 C23.03521743,11.1114379 22.8,10.6426499 22.3541541,10.3266333 L4.13399899,0.951697 C3.50612381,0.45471905 2.41,0.45471905 1.77946707,0.951697 C0.99,1.43997686 0.8376543,2.52933424 1.15159189,3.31482114 L3.03521743,9.75581416 C3.03521743,9.91291159 3.19218622,10.0700089 3.50612381,10.0700089 L16.6915026,10.8554959 C16.6915026,10.8554959 17.1573181,10.8554959 17.1573181,11.6649767 C17.1573181,12.4744748 16.6915026,12.4744748 16.6915026,12.4744748 Z"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="no-chat-selected">
                <i class="fas fa-comments"></i>
                <p>Select a patient to start messaging</p>
            </div>
        @endif
    </div>
</div>

@if ($selectedUser)
<script>
    const currentUserId = {{ Auth::id() }};
    const selectedUserId = {{ $selectedUser->id }};
    let messages = [];
    
    // Load initial messages
    document.addEventListener('DOMContentLoaded', function() {
        loadMessages();
        scrollToBottom();
        
        // Setup Pusher real-time listening
        if (window.Echo) {
            // Listen for new messages
            window.Echo.channel(`messages.${currentUserId}`)
                .listen('.new.message', (data) => {
                    console.log('New message received:', data);
                    if (data.sender_id == selectedUserId) {
                        addMessageToUI(data, false);
                        scrollToBottom();
                        markMessagesAsRead([data.id]);
                    }
                });
            
            // Listen for message read receipts
            window.Echo.channel(`messages.${currentUserId}`)
                .listen('.message.read', (data) => {
                    console.log('Message read:', data);
                    updateMessageReadStatus(data.message_id);
                });
        }
    });
    
    // Load messages from MongoDB
    function loadMessages() {
        fetch(`/mongo-messages/conversation?user_id=${selectedUserId}`, {
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
            }
        })
        .catch(error => console.error('Error loading messages:', error));
    }
    
    // Render all messages
    function renderMessages() {
        const container = document.getElementById('messagesContainer');
        container.innerHTML = '';
        
        messages.forEach(msg => {
            addMessageToUI(msg, true);
        });
        
        scrollToBottom();
    }
    
    // Add single message to UI
    function addMessageToUI(msg, skipAnimation = false) {
        const container = document.getElementById('messagesContainer');
        const isSent = msg.sender_id == currentUserId;
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `message-wrapper ${isSent ? 'sent' : 'received'}${skipAnimation ? '' : ' message-fade-in'}`;
        messageDiv.dataset.messageId = msg.id;
        
        const avatar = msg.sender?.avatar_url || '{{ asset("img/default-dp.jpg") }}';
        const time = new Date(msg.created_at).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
        
        messageDiv.innerHTML = `
            ${!isSent ? `<img src="${avatar}" alt="Avatar" class="message-avatar" onerror="this.src='{{ asset('img/default-dp.jpg') }}'">` : ''}
            <div class="message-bubble">
                <p class="message-content">${escapeHtml(msg.message)}</p>
                <div class="message-time">
                    <span>• ${time}</span>
                </div>
            </div>
            ${isSent ? `<img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="message-avatar" onerror="this.src='{{ asset('img/default-dp.jpg') }}'">` : ''}
        `;
        
        container.appendChild(messageDiv);
    }
    
    // Send message via AJAX
    function sendMessage(event) {
        event.preventDefault();
        
        const form = event.target;
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
                recipient_id: selectedUserId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                addMessageToUI(data.message, false);
                input.value = '';
                input.style.height = 'auto';
                scrollToBottom();
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
            alert('Failed to send message. Please try again.');
        })
        .finally(() => {
            button.disabled = false;
            input.focus();
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
        .catch(error => console.error('Error marking messages as read:', error));
    }
    
    function updateMessageReadStatus(messageId) {
        const msgElement = document.querySelector(`[data-message-id="${messageId}"]`);
        if (msgElement) {
            // Add checkmark or read indicator if desired
        }
    }
    
    // Handle Enter key press
    function handleKeyPress(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendMessage(event);
        }
    }
    
    // Auto-resize textarea
    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, 100) + 'px';
    }
    
    // Scroll to bottom
    function scrollToBottom() {
        const container = document.getElementById('messagesContainer');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }
    
    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Select user
    function selectUser(userId) {
        window.location.href = `{{ route('admin.patient_messages') }}?user_id=${userId}`;
    }
    
    // Search users
    document.getElementById('userSearch')?.addEventListener('input', function(e) {
        const search = e.target.value.toLowerCase();
        document.querySelectorAll('.user-item').forEach(item => {
            const name = item.querySelector('.user-name').textContent.toLowerCase();
            item.style.display = name.includes(search) ? 'flex' : 'none';
        });
    });
</script>
@endif
@endsection
