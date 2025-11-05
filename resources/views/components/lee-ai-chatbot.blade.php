@props(['type' => 'public', 'userName' => null])

<div class="lee-ai-chatbot-container">
    <div class="chatbot-card-lee">
        <div class="chatbot-header-lee">
            <div class="chatbot-avatar-lee">
                <i class="fas fa-robot"></i>
            </div>
            <div class="chatbot-header-content-lee">
                <h3>Lee AI Assistant</h3>
                <p class="chatbot-description-lee">Your 24/7 dental health companion powered by AI</p>
                <div class="chatbot-status-lee">
                    <span class="status-dot-lee"></span>
                    <span class="status-text-lee">Online - Ready to help!</span>
                </div>
            </div>
        </div>
        
        <div class="chatbot-messages-lee" id="{{ $type === 'public' ? 'publicChatMessages' : 'protectedChatMessages' }}">
            <div class="welcome-message-lee">
                <i class="fas fa-tooth"></i>
                <div>
                    <p>Hello{{ $userName ? ' ' . $userName : '' }}! I'm Lee AI, your dental health assistant. I can help you with:</p>
                    <ul>
                        <li>Dental care tips and advice</li>
                        <li>Information about our services</li>
                        <li>Booking guidance</li>
                        <li>General dental health questions</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <form id="{{ $type === 'public' ? 'publicChatForm' : 'protectedChatForm' }}" class="chatbot-input-form-lee">
            @csrf
            <div class="chatbot-input-wrapper-lee">
                <input 
                    type="text" 
                    id="{{ $type === 'public' ? 'publicChatInput' : 'protectedChatInput' }}" 
                    placeholder="Ask about dental services, tooth pain, etc..." 
                    maxlength="500"
                    required
                    autocomplete="off"
                />
                <button type="submit" id="{{ $type === 'public' ? 'publicSendBtn' : 'protectedSendBtn' }}">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
        
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/lee-ai-chatbot.css') }}">
<script src="{{ asset('js/lee-ai-chatbot.js') }}"></script>
<script>
    // Initialize Lee AI Chatbot
    document.addEventListener('DOMContentLoaded', function() {
        initLeeAIChatbot('{{ $type }}', '{{ $type === "public" ? "/ask-gemini-public" : "/ask-gemini" }}');
    });
</script>
