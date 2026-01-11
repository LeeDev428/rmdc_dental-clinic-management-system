@extends('layouts.admin')

@section('title', 'Messages')

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
        white-space: nowrap;
    }
    
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background-color: #fff;
    }
    
    .chat-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e4e6eb;
        background-color: #fff;
        display: flex;
        align-items: center;
    }
    
    .chat-header-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 12px;
        object-fit: cover;
    }
    
    .chat-header-name {
        font-size: 17px;
        font-weight: 600;
        color: #050505;
        margin: 0;
    }
    
    .messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: linear-gradient(to bottom, #e6f2ff 0%, #f0f2f5 100%);
    }
    
    .message-wrapper {
        display: flex;
        margin-bottom: 8px;
        align-items: flex-end;
    }
    
    .message-wrapper.sent {
        justify-content: flex-end;
    }
    
    .message-wrapper.received {
        justify-content: flex-start;
    }
    
    .message-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 8px;
    }
    
    .message-bubble {
        max-width: 60%;
        padding: 8px 12px;
        border-radius: 18px;
        position: relative;
    }
    
    .message-wrapper.sent .message-bubble {
        background-color: #0084ff;
        color: #fff;
        border-bottom-right-radius: 4px;
    }
    
    .message-wrapper.received .message-bubble {
        background-color: #e4e6eb;
        color: #050505;
        border-bottom-left-radius: 4px;
    }
    
    .message-content {
        font-size: 15px;
        line-height: 1.4;
        word-wrap: break-word;
        margin: 0;
    }
    
    .message-time {
        font-size: 11px;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
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
            <form method="GET" action="{{ route('admin.messages') }}">
                <input type="text" name="search" placeholder="Search by ID or Name" value="{{ request('search') }}">
            </form>
        </div>
        
        <div class="users-list">
            @foreach ($users as $user)
                <a href="{{ route('admin.messages', ['user_id' => $user->id]) }}" 
                   class="user-item {{ $selectedUser && $selectedUser->id == $user->id ? 'active' : '' }}">
                    <img src="{{ $user->avatar_url }}" 
                         alt="{{ $user->name }}"
                         onerror="this.src='{{ asset('img/default-dp.jpg') }}'"
                         class="user-avatar">
                    <div class="user-info">
                        <p class="user-name">{{ $user->name }}</p>
                        <p class="user-preview">
                            @if(isset($user->last_message))
                                {{ Str::limit($user->last_message, 30, '...') }}
                            @else
                                No messages yet
                            @endif
                        </p>
                    </div>
                    @if(isset($user->last_message_time))
                        <span class="user-time">
                            {{ $user->last_message_time instanceof \MongoDB\BSON\UTCDateTime ? $user->last_message_time->toDateTime()->diffForHumans(null, true) : \Carbon\Carbon::parse($user->last_message_time)->diffForHumans(null, true) }}
                        </span>
                    @endif
                </a>
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
                <h3 class="chat-header-name">{{ $selectedUser->name }}</h3>
            </div>
            
            <div class="messages-container" id="messagesContainer">
                @foreach ($messages as $message)
                    <div class="message-wrapper {{ $message->sender_id == Auth::id() ? 'sent' : 'received' }}">
                        @if($message->sender_id != Auth::id())
                            <img src="{{ $selectedUser->avatar_url ?? asset('img/default-dp.jpg') }}" 
                                 alt="{{ $selectedUser->name }}"
                                 onerror="this.src='{{ asset('img/default-dp.jpg') }}'"
                                 class="message-avatar">
                        @endif
                        
                        <div class="message-bubble">
                            <p class="message-content">{{ $message->message }}</p>
                            <div class="message-time">
                                <span>• {{ $message->created_at instanceof \MongoDB\BSON\UTCDateTime ? $message->created_at->toDateTime()->setTimezone(new \DateTimeZone(config('app.timezone')))->format('g:i A') : \Carbon\Carbon::parse($message->created_at)->format('g:i A') }}</span>
                            </div>
                        </div>
                        
                        @if($message->sender_id == Auth::id())
                            <img src="{{ Auth::user()->avatar_url ?? asset('img/default-dp.jpg') }}" 
                                 alt="{{ Auth::user()->name }}"
                                 onerror="this.src='{{ asset('img/default-dp.jpg') }}'"
                                 class="message-avatar">
                        @endif
                    </div>
                @endforeach
            </div>
            
            <div class="message-input-container">
                <form id="messageForm" onsubmit="sendMessage(event)">
                    @csrf
                    <input type="hidden" name="user_id" id="recipientId" value="{{ $selectedUser->id }}">
                    <div class="message-input-wrapper">
                        <input type="text" 
                               name="message" 
                               id="messageInput"
                               class="message-input" 
                               placeholder="Type your message..." 
                               required 
                               autocomplete="off">
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
                <p>Select a user to start messaging</p>
            </div>
        @endif
    </div>
</div>

@if ($selectedUser)
<script>
    const currentUserId = {{ Auth::id() }};
    const selectedUserId = {{ $selectedUser->id }};
    
    document.addEventListener("DOMContentLoaded", function() {
        const messagesContainer = document.getElementById("messagesContainer");
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        
        // Setup Pusher real-time listening
        if (window.Echo) {
            console.log('✅ Setting up Pusher listeners...');
            
            // Listen for new messages
            window.Echo.channel(`messages.${currentUserId}`)
                .listen('.new.message', (data) => {
                    console.log('📨 New message received:', data);
                    if (data.sender_id == selectedUserId) {
                        addMessageToUI(data);
                        scrollToBottom();
                        markMessagesAsRead([data.id]);
                    }
                });
            
            // Listen for message read receipts
            window.Echo.channel(`messages.${currentUserId}`)
                .listen('.message.read', (data) => {
                    console.log('✓✓ Message read:', data);
                });
        }
    });
    
    // Add message to UI
    function addMessageToUI(msg) {
        const container = document.getElementById('messagesContainer');
        const isSent = msg.sender_id == currentUserId;
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `message-wrapper ${isSent ? 'sent' : 'received'}`;
        messageDiv.style.animation = 'fadeIn 0.3s ease-in';
        
        // Get avatar from message data or fallback
        const avatar = isSent 
            ? (msg.sender && msg.sender.avatar_url ? msg.sender.avatar_url : '{{ Auth::user()->avatar_url ?? asset("img/default-dp.jpg") }}')
            : (msg.sender && msg.sender.avatar_url ? msg.sender.avatar_url : '{{ $selectedUser->avatar_url ?? asset("img/default-dp.jpg") }}');
        
        const time = new Date().toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
        
        messageDiv.innerHTML = `
            ${!isSent ? `<img src="${avatar}" alt="Avatar" class="message-avatar" onerror="this.src='{{ asset('img/default-dp.jpg') }}'">` : ''}
            <div class="message-bubble">
                <p class="message-content">${escapeHtml(msg.message)}</p>
                <div class="message-time">
                    <span>• ${time}</span>
                </div>
            </div>
            ${isSent ? `<img src="${avatar}" alt="Avatar" class="message-avatar" onerror="this.src='{{ asset('img/default-dp.jpg') }}'">` : ''}
        `;
        
        container.appendChild(messageDiv);
    }
    
    // Send message via AJAX to MongoDB
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
                recipient_id: selectedUserId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                addMessageToUI(data.message);
                input.value = '';
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
</script>
@endif
@endsection
