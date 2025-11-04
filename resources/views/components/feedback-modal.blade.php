<!-- Service Feedback Modal - Auto-popup when appointment ends -->
<div id="feedbackModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Appointment Completed!</h3>
                <p class="text-sm text-gray-600 mt-2">How was your experience?</p>
            </div>
            
            <form id="feedbackForm" onsubmit="submitFeedback(event)">
                <input type="hidden" id="feedbackAppointmentId" name="appointment_id">
                
                <!-- Star Rating -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3 text-center">
                        Rate Your Experience
                    </label>
                    <div class="flex justify-center gap-2" id="starRating">
                        <button type="button" class="star-btn text-4xl text-gray-300 hover:text-yellow-400 transition" data-rating="1">★</button>
                        <button type="button" class="star-btn text-4xl text-gray-300 hover:text-yellow-400 transition" data-rating="2">★</button>
                        <button type="button" class="star-btn text-4xl text-gray-300 hover:text-yellow-400 transition" data-rating="3">★</button>
                        <button type="button" class="star-btn text-4xl text-gray-300 hover:text-yellow-400 transition" data-rating="4">★</button>
                        <button type="button" class="star-btn text-4xl text-gray-300 hover:text-yellow-400 transition" data-rating="5">★</button>
                    </div>
                    <input type="hidden" id="rating" name="rating" required>
                    <p id="ratingError" class="text-xs text-red-600 text-center mt-2 hidden">Please select a rating</p>
                </div>
                
                <!-- Comment -->
                <div class="mb-6">
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                        Additional Comments (Optional)
                    </label>
                    <textarea 
                        id="comment" 
                        name="comment" 
                        rows="4" 
                        maxlength="1000"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        placeholder="Tell us about your experience..."
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">Maximum 1000 characters</p>
                </div>
                
                <div id="feedbackError" class="hidden mb-4 bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-800"></div>
                <div id="feedbackSuccess" class="hidden mb-4 bg-green-50 border border-green-200 rounded-lg p-3 text-sm text-green-800"></div>
                
                <!-- Buttons -->
                <div class="flex gap-3">
                    <button 
                        type="button" 
                        onclick="skipFeedback()"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium"
                    >
                        Skip
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium"
                        id="submitFeedbackBtn"
                    >
                        Submit Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let selectedRating = 0;
    let feedbackCheckInterval = null;

    // Star rating functionality
    document.querySelectorAll('.star-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            selectedRating = parseInt(this.dataset.rating);
            document.getElementById('rating').value = selectedRating;
            document.getElementById('ratingError').classList.add('hidden');
            
            // Update star colors
            document.querySelectorAll('.star-btn').forEach((star, index) => {
                if (index < selectedRating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.add('text-gray-300');
                    star.classList.remove('text-yellow-400');
                }
            });
        });
    });

    // Check for pending feedback every 30 seconds
    function checkForPendingFeedback() {
        fetch('/check-pending-feedback', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.show_feedback) {
                document.getElementById('feedbackAppointmentId').value = data.appointment_id;
                showFeedbackModal();
                // Stop checking once modal is shown
                if (feedbackCheckInterval) {
                    clearInterval(feedbackCheckInterval);
                }
            }
        })
        .catch(error => console.error('Error checking feedback:', error));
    }

    // Start checking when page loads (only for authenticated users)
    @auth
        feedbackCheckInterval = setInterval(checkForPendingFeedback, 30000); // Check every 30 seconds
        checkForPendingFeedback(); // Check immediately on load
    @endauth

    function showFeedbackModal() {
        document.getElementById('feedbackModal').style.display = 'flex';
        document.getElementById('feedbackModal').classList.remove('hidden');
    }

    function hideFeedbackModal() {
        document.getElementById('feedbackModal').style.display = 'none';
        document.getElementById('feedbackModal').classList.add('hidden');
        resetFeedbackForm();
    }

    function skipFeedback() {
        if (confirm('Are you sure you want to skip providing feedback?')) {
            hideFeedbackModal();
        }
    }

    function resetFeedbackForm() {
        selectedRating = 0;
        document.getElementById('rating').value = '';
        document.getElementById('comment').value = '';
        document.querySelectorAll('.star-btn').forEach(star => {
            star.classList.add('text-gray-300');
            star.classList.remove('text-yellow-400');
        });
        document.getElementById('feedbackError').classList.add('hidden');
        document.getElementById('feedbackSuccess').classList.add('hidden');
    }

    async function submitFeedback(event) {
        event.preventDefault();
        
        const appointmentId = document.getElementById('feedbackAppointmentId').value;
        const rating = document.getElementById('rating').value;
        const comment = document.getElementById('comment').value;
        const submitBtn = document.getElementById('submitFeedbackBtn');
        const errorDiv = document.getElementById('feedbackError');
        const successDiv = document.getElementById('feedbackSuccess');
        
        // Validate rating
        if (!rating) {
            document.getElementById('ratingError').classList.remove('hidden');
            return;
        }
        
        // Disable button
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';
        errorDiv.classList.add('hidden');
        successDiv.classList.add('hidden');
        
        try {
            const response = await fetch('/service-feedback', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    appointment_id: appointmentId,
                    rating: rating,
                    comment: comment
                })
            });
            
            const data = await response.json();
            
            if (response.ok) {
                successDiv.textContent = data.success;
                successDiv.classList.remove('hidden');
                
                // Close modal after 2 seconds
                setTimeout(() => {
                    hideFeedbackModal();
                }, 2000);
            } else {
                errorDiv.textContent = data.error || 'An error occurred';
                errorDiv.classList.remove('hidden');
            }
        } catch (error) {
            errorDiv.textContent = 'Network error. Please try again.';
            errorDiv.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit Feedback';
        }
    }
</script>
