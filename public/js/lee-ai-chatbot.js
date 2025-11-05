/**
 * Lee AI Chatbot - Reusable JavaScript Component
 * Handles chat functionality for both public and protected routes
 */

function initLeeAIChatbot(type, apiEndpoint) {
    const chatForm = document.getElementById(`${type}ChatForm`);
    const chatInput = document.getElementById(`${type}ChatInput`);
    const chatMessages = document.getElementById(`${type}ChatMessages`);
    const sendBtn = document.getElementById(`${type}SendBtn`);

    if (!chatForm || !chatInput || !chatMessages || !sendBtn) {
        console.error('Lee AI Chatbot: Required elements not found');
        return;
    }

    /**
     * Add message to chat
     */
    function addMessage(message, isUser = false) {
        // Remove typing indicator if exists
        const existingTyping = chatMessages.querySelector('.typing-indicator-lee');
        if (existingTyping) {
            existingTyping.remove();
        }

        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message-lee ${isUser ? 'user-message-lee' : 'bot-message-lee'}`;
        
        const icon = isUser ? '<i class="fas fa-user"></i>' : '<i class="fas fa-robot"></i>';
        const time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        
        messageDiv.innerHTML = `
            <div class="message-content-lee">
                <div class="message-icon-lee">${icon}</div>
                <div class="message-text-lee">
                    <p>${escapeHtml(message)}</p>
                    <span class="message-time-lee">${time}</span>
                </div>
            </div>
        `;
        
        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }

    /**
     * Show typing indicator
     */
    function showTypingIndicator() {
        // Remove existing typing indicator
        const existingTyping = chatMessages.querySelector('.typing-indicator-lee');
        if (existingTyping) {
            existingTyping.remove();
        }

        const typingDiv = document.createElement('div');
        typingDiv.className = 'typing-indicator-lee';
        typingDiv.innerHTML = `
            <div class="typing-dots-lee">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <span class="typing-text-lee">Lee AI is typing...</span>
        `;
        
        chatMessages.appendChild(typingDiv);
        scrollToBottom();
    }

    /**
     * Hide typing indicator
     */
    function hideTypingIndicator() {
        const typingIndicator = chatMessages.querySelector('.typing-indicator-lee');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    /**
     * Scroll chat to bottom
     */
    function scrollToBottom() {
        setTimeout(() => {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 100);
    }

    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Handle form submission
     */
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const question = chatInput.value.trim();
        if (!question) return;
        
        // Add user message
        addMessage(question, true);
        
        // Clear input and disable form
        chatInput.value = '';
        chatInput.disabled = true;
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        // Show typing indicator
        showTypingIndicator();
        
        try {
            const response = await fetch(apiEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ question: question })
            });
            
            const data = await response.json();
            
            // Hide typing indicator
            hideTypingIndicator();
            
            if (response.ok && data.success) {
                addMessage(data.response, false);
            } else {
                addMessage('Sorry, I encountered an error. Please try again!', false);
            }
        } catch (error) {
            console.error('Lee AI Error:', error);
            hideTypingIndicator();
            addMessage('Connection error. Please check your internet and try again.', false);
        } finally {
            // Re-enable form
            chatInput.disabled = false;
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            chatInput.focus();
        }
    });

    // Character counter (optional)
    chatInput.addEventListener('input', function() {
        const maxLength = this.getAttribute('maxlength') || 500;
        const currentLength = this.value.length;
        
        // You can add a character counter display here if needed
        if (currentLength >= maxLength - 50) {
            this.style.borderColor = '#f59e0b';
        } else {
            this.style.borderColor = '';
        }
    });

    // Auto-focus on input when page loads
    setTimeout(() => {
        chatInput.focus();
    }, 500);

    // Expose fillQuestion function for sample questions
    window.fillQuestion = function(question) {
        chatInput.value = question;
        chatInput.focus();
    };
}
