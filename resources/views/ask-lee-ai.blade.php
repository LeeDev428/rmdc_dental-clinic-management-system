<x-app-layout>
    @section('title', 'Ask Lee AI')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ask Lee AI - Your Dental Assistant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- AI Chat Interface Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white rounded-full p-3">
                            <i class="fas fa-robot text-blue-600 text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">Lee AI</h3>
                            <p class="text-blue-100 text-sm">Your 24/7 Dental Assistant</p>
                        </div>
                    </div>
                </div>

                <!-- Chat Container -->
                <div class="p-6">
                    <!-- Introduction -->
                    <div class="mb-6 bg-blue-50 dark:bg-gray-700 border-l-4 border-blue-500 p-4 rounded">
                        <p class="text-gray-700 dark:text-gray-300">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            Welcome! I'm Lee AI, your virtual dental assistant. Ask me anything about dental health, 
                            symptoms, procedures, or general oral care. I'm here to help! 🦷
                        </p>
                    </div>

                    <!-- Chat Messages Container -->
                    <div id="chatMessages" class="space-y-4 mb-6 max-h-96 overflow-y-auto">
                        <!-- Messages will appear here -->
                    </div>

                    <!-- Input Form -->
                    <form id="askForm" class="space-y-4">
                        @csrf
                        <div>
                            <label for="question" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-comment-dots mr-2"></i>Your Question
                            </label>
                            <textarea 
                                id="question" 
                                name="question" 
                                rows="4" 
                                maxlength="1000"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white resize-none"
                                placeholder="Example: My tooth hurts when I eat cold food. What should I do?"
                                required
                            ></textarea>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <span id="charCount">0</span>/1000 characters
                            </p>
                        </div>

                        <!-- Error Message -->
                        <div id="errorMessage" class="hidden bg-red-50 dark:bg-red-900 border-l-4 border-red-500 p-4 rounded">
                            <p class="text-red-700 dark:text-red-200 text-sm">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span id="errorText"></span>
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            id="submitBtn"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center space-x-2"
                        >
                            <i class="fas fa-paper-plane"></i>
                            <span id="btnText">Ask Lee AI</span>
                        </button>
                    </form>

                    <!-- Sample Questions -->
                    <div class="mt-8">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>Sample Questions:
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <button onclick="fillQuestion('What causes tooth sensitivity?')" 
                                    class="text-left text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 bg-blue-50 dark:bg-gray-700 p-3 rounded-lg transition">
                                <i class="fas fa-chevron-right mr-2"></i>What causes tooth sensitivity?
                            </button>
                            <button onclick="fillQuestion('How often should I visit the dentist?')" 
                                    class="text-left text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 bg-blue-50 dark:bg-gray-700 p-3 rounded-lg transition">
                                <i class="fas fa-chevron-right mr-2"></i>How often should I visit the dentist?
                            </button>
                            <button onclick="fillQuestion('What are the signs of gum disease?')" 
                                    class="text-left text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 bg-blue-50 dark:bg-gray-700 p-3 rounded-lg transition">
                                <i class="fas fa-chevron-right mr-2"></i>What are the signs of gum disease?
                            </button>
                            <button onclick="fillQuestion('How can I whiten my teeth naturally?')" 
                                    class="text-left text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 bg-blue-50 dark:bg-gray-700 p-3 rounded-lg transition">
                                <i class="fas fa-chevron-right mr-2"></i>How can I whiten my teeth naturally?
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disclaimer -->
            <div class="mt-6 bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 p-4 rounded">
                <p class="text-yellow-800 dark:text-yellow-200 text-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <strong>Disclaimer:</strong> Lee AI provides general dental information only. 
                    For emergencies or specific medical advice, please contact our clinic or visit a dentist immediately.
                </p>
            </div>
        </div>
    </div>

    <script>
        const questionInput = document.getElementById('question');
        const charCount = document.getElementById('charCount');
        const askForm = document.getElementById('askForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const errorMessage = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');
        const chatMessages = document.getElementById('chatMessages');

        // Character counter
        questionInput.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });

        // Fill question from sample buttons
        function fillQuestion(text) {
            questionInput.value = text;
            charCount.textContent = text.length;
            questionInput.focus();
        }

        // Add message to chat
        function addMessage(message, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${isUser ? 'justify-end' : 'justify-start'}`;
            
            const messageContent = `
                <div class="${isUser ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200'} rounded-lg px-4 py-3 max-w-[80%] shadow">
                    <div class="flex items-start space-x-2">
                        ${!isUser ? '<i class="fas fa-robot mt-1"></i>' : '<i class="fas fa-user mt-1"></i>'}
                        <div class="flex-1">
                            <p class="text-sm font-semibold mb-1">${isUser ? 'You' : 'Lee AI'}</p>
                            <p class="text-sm whitespace-pre-wrap">${message}</p>
                            <p class="text-xs mt-2 opacity-75">${new Date().toLocaleTimeString()}</p>
                        </div>
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
            
            if (!question) {
                showError('Please enter a question.');
                return;
            }

            // Hide error message
            errorMessage.classList.add('hidden');
            
            // Add user message to chat
            addMessage(question, true);
            
            // Disable form
            submitBtn.disabled = true;
            questionInput.disabled = true;
            btnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Thinking...';
            
            // Clear input
            questionInput.value = '';
            charCount.textContent = '0';

            try {
                // Send request to backend
                const response = await fetch('/ask-gemini', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ question: question })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Add AI response to chat
                    addMessage(data.response, false);
                } else {
                    showError(data.error || 'Failed to get response from AI.');
                    addMessage('Sorry, I encountered an error. Please try again later.', false);
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Network error. Please check your connection and try again.');
                addMessage('Sorry, I could not connect to the server. Please try again.', false);
            } finally {
                // Re-enable form
                submitBtn.disabled = false;
                questionInput.disabled = false;
                btnText.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Ask Lee AI';
            }
        });

        // Show error message
        function showError(message) {
            errorText.textContent = message;
            errorMessage.classList.remove('hidden');
            setTimeout(() => {
                errorMessage.classList.add('hidden');
            }, 5000);
        }

        // Make fillQuestion available globally
        window.fillQuestion = fillQuestion;
    </script>
</x-app-layout>
