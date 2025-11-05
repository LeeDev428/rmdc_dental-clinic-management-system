<x-app-layout>
    @section('title', 'Ask Lee AI')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ask Lee AI - Your Dental Assistant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- AI Chatbot Component -->
            <div class="lee-ai-container">
                <x-lee-ai-chatbot 
                    type="protected" 
                    :userName="Auth::user()->name" 
                />

                <!-- Sample Questions -->
                <div class="sample-questions-section">
                    <h4><i class="fas fa-lightbulb"></i> Sample Questions</h4>
                    <div class="sample-questions-grid">
                        <button onclick="fillQuestion('What causes tooth sensitivity?')" class="sample-question-btn">
                            <i class="fas fa-chevron-right"></i>
                            <span>What causes tooth sensitivity?</span>
                        </button>
                        <button onclick="fillQuestion('How often should I visit the dentist?')" class="sample-question-btn">
                            <i class="fas fa-chevron-right"></i>
                            <span>How often should I visit the dentist?</span>
                        </button>
                        <button onclick="fillQuestion('What are the signs of gum disease?')" class="sample-question-btn">
                            <i class="fas fa-chevron-right"></i>
                            <span>What are the signs of gum disease?</span>
                        </button>
                        <button onclick="fillQuestion('How can I whiten my teeth naturally?')" class="sample-question-btn">
                            <i class="fas fa-chevron-right"></i>
                            <span>How can I whiten my teeth naturally?</span>
                        </button>
                        <button onclick="fillQuestion('What should I do if I have a toothache?')" class="sample-question-btn">
                            <i class="fas fa-chevron-right"></i>
                            <span>What should I do if I have a toothache?</span>
                        </button>
                        <button onclick="fillQuestion('How do I properly brush my teeth?')" class="sample-question-btn">
                            <i class="fas fa-chevron-right"></i>
                            <span>How do I properly brush my teeth?</span>
                        </button>
                    </div>
                </div>

                <!-- Disclaimer -->
                <div class="disclaimer-box">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Disclaimer:</strong> Lee AI provides general dental information only. 
                        For emergencies or specific medical advice, please contact our clinic or visit a dentist immediately.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .lee-ai-container {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            border: 2px solid #f0f0f0;
        }
        
        .chatbot-header-protected {
            background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%);
            padding: 25px;
            color: white;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .chatbot-avatar-protected {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #0077b6;
            flex-shrink: 0;
        }
        
        .chatbot-header-content-protected {
            flex: 1;
        }
        
        .chatbot-header-content-protected h3 {
            margin: 0 0 5px 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .chatbot-description-protected {
            margin: 0 0 10px 0;
            font-size: 14px;
            opacity: 0.9;
        }
        
        .chatbot-status-protected {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .status-dot-protected {
            width: 10px;
            height: 10px;
            background: #4ade80;
            border-radius: 50%;
            animation: pulse-protected 2s infinite;
        }
        
        @keyframes pulse-protected {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .chatbot-messages-protected {
            height: 500px;
            overflow-y: auto;
            padding: 25px;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .welcome-message-protected {
            background: white;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #00b4d8;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .welcome-message-protected i {
            font-size: 28px;
            color: #00b4d8;
            margin-bottom: 12px;
            display: block;
        }
        
        .welcome-message-protected p {
            margin: 10px 0;
            color: #333;
            line-height: 1.6;
            font-size: 15px;
        }
        
        .welcome-message-protected ul {
            margin: 12px 0;
            padding-left: 25px;
        }
        
        .welcome-message-protected li {
            margin: 6px 0;
            color: #555;
            font-size: 15px;
        }
        
        /* Typing Indicator */
        .typing-indicator-protected {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            max-width: 220px;
        }
        
        .typing-dots-protected {
            display: flex;
            gap: 5px;
        }
        
        .typing-dots-protected span {
            width: 10px;
            height: 10px;
            background: #00b4d8;
            border-radius: 50%;
            animation: typingBounce-protected 1.4s infinite;
        }
        
        .typing-dots-protected span:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .typing-dots-protected span:nth-child(3) {
            animation-delay: 0.4s;
        }
        
        @keyframes typingBounce-protected {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-10px); }
        }
        
        .typing-text-protected {
            font-size: 14px;
            color: #666;
            font-style: italic;
        }
        
        /* Chat Messages */
        .chat-message-protected {
            display: flex;
            animation: fadeIn-protected 0.3s ease-in;
        }
        
        @keyframes fadeIn-protected {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .message-content-protected {
            display: flex;
            gap: 12px;
            max-width: 85%;
        }
        
        .user-message-protected {
            justify-content: flex-end;
        }
        
        .user-message-protected .message-content-protected {
            flex-direction: row-reverse;
        }
        
        .message-icon-protected {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 18px;
        }
        
        .bot-message-protected .message-icon-protected {
            background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%);
            color: white;
        }
        
        .user-message-protected .message-icon-protected {
            background: #e9ecef;
            color: #495057;
        }
        
        .message-text-protected p {
            background: white;
            padding: 14px 18px;
            border-radius: 12px;
            margin: 0 0 5px 0;
            color: #333;
            line-height: 1.6;
            font-size: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .user-message-protected .message-text-protected p {
            background: #00b4d8;
            color: white;
        }
        
        .message-time-protected {
            font-size: 12px;
            color: #999;
            padding-left: 5px;
        }
        
        /* Input Form */
        .chatbot-input-form-protected {
            padding: 20px;
            background: white;
            border-top: 1px solid #e9ecef;
        }
        
        .chatbot-input-wrapper-protected {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .chatbot-input-wrapper-protected input {
            flex: 1;
            padding: 14px 18px;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            font-size: 15px;
            outline: none;
            transition: all 0.3s;
        }
        
        .chatbot-input-wrapper-protected input:focus {
            border-color: #00b4d8;
            box-shadow: 0 0 0 3px rgba(0, 180, 216, 0.1);
        }
        
        .chatbot-input-wrapper-protected button {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%);
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s;
        }
        
        .chatbot-input-wrapper-protected button:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 119, 182, 0.3);
        }
        
        .chatbot-input-wrapper-protected button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: scale(1);
        }
        
        .chatbot-char-count-protected {
            font-size: 13px;
            color: #999;
            text-align: right;
            margin: 5px 0;
        }
        
        .chatbot-footer-text-protected {
            text-align: center;
            font-size: 13px;
            color: #999;
            margin: 10px 0 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        
        /* Sample Questions */
        .sample-questions-section {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .sample-questions-section h4 {
            margin: 0 0 15px 0;
            font-size: 18px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sample-questions-section h4 i {
            color: #fbbf24;
            font-size: 20px;
        }
        
        .sample-questions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 12px;
        }
        
        .sample-question-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 18px;
            background: #f0f9ff;
            border: 2px solid #e0f2fe;
            border-radius: 12px;
            color: #0077b6;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: left;
        }
        
        .sample-question-btn:hover {
            background: #e0f2fe;
            border-color: #00b4d8;
            transform: translateX(5px);
        }
        
        .sample-question-btn i {
            font-size: 12px;
            flex-shrink: 0;
        }
        
        /* Disclaimer */
        .disclaimer-box {
            background: #fef3c7;
            border: 2px solid #fbbf24;
            border-radius: 12px;
            padding: 18px;
            display: flex;
            gap: 15px;
            align-items: start;
        }
        
        .disclaimer-box i {
            color: #f59e0b;
            font-size: 22px;
            flex-shrink: 0;
        }
        
        .disclaimer-box div {
            color: #78350f;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .disclaimer-box strong {
            font-weight: 600;
        }
        
        /* Scrollbar */
        .chatbot-messages-protected::-webkit-scrollbar {
            width: 8px;
        }
        
        .chatbot-messages-protected::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .chatbot-messages-protected::-webkit-scrollbar-thumb {
            background: #00b4d8;
            border-radius: 4px;
        }
        
        .chatbot-messages-protected::-webkit-scrollbar-thumb:hover {
            background: #0077b6;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sample-questions-grid {
                grid-template-columns: 1fr;
            }
            
            .chatbot-messages-protected {
                height: 400px;
            }
        }
    </style>

    <script>
        const questionInput = document.getElementById('question');
        const charCount = document.getElementById('charCount');
        const askForm = document.getElementById('askForm');
        const submitBtn = document.getElementById('submitBtn');
        const chatMessages = document.getElementById('chatMessages');
        const typingIndicator = document.getElementById('typingIndicator');

        // Character counter
        questionInput.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });

        // Fill question from sample buttons
        function fillQuestion(text) {
            // Get the protected chatbot input
            const protectedInput = document.getElementById('protectedChatInput');
            if (protectedInput) {
                protectedInput.value = text;
                protectedInput.focus();
            }
        }

        // Add message to chat
        function addMessage(message, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `chat-message-protected ${isUser ? 'user-message-protected' : 'bot-message-protected'}`;
            
            const messageContent = `
                <div class="message-content-protected">
                    <div class="message-icon-protected">
                        <i class="fas fa-${isUser ? 'user' : 'robot'}"></i>
                    </div>
                    <div class="message-text-protected">
                        <p>${message}</p>
                        <span class="message-time-protected">${new Date().toLocaleTimeString()}</span>
                    </div>
                </div>
            `;
            
            messageDiv.innerHTML = messageContent;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Handle form submission
        askForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const question = questionInput.value.trim();
            if (!question) return;
            
            // Add user message
            addMessage(question, true);
            
            // Clear and disable input
            questionInput.value = '';
            charCount.textContent = '0';
            questionInput.disabled = true;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            // Show typing indicator
            typingIndicator.style.display = 'flex';
            chatMessages.scrollTop = chatMessages.scrollHeight;

            try {
                const response = await fetch('/ask-gemini', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ question: question })
                });

                const data = await response.json();
                
                // Hide typing indicator
                typingIndicator.style.display = 'none';

                if (response.ok && data.success) {
                    addMessage(data.response, false);
                } else {
                    addMessage('Sorry, I encountered an error. Please try again later.', false);
                }
            } catch (error) {
                console.error('Error:', error);
                typingIndicator.style.display = 'none';
                addMessage('Sorry, I could not connect to the server. Please try again.', false);
            } finally {
                questionInput.disabled = false;
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
                questionInput.focus();
            }
        });

        // Make fillQuestion available globally
        window.fillQuestion = fillQuestion;
    </script>
</x-app-layout>
