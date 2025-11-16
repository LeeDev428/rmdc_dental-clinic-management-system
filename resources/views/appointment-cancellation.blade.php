<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cancel Appointment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Cancellation Policy Notice -->
            <div class="bg-gradient-to-r from-red-50 to-orange-50 border-l-4 border-red-500 shadow-lg sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-bold text-red-900 mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Important Cancellation & Reschedule Policy
                            </h3>
                            <div class="text-sm text-red-800 space-y-2">
                                <p class="font-semibold">Before you proceed, please read carefully:</p>
                                <ul class="list-disc ml-5 space-y-1">
                                    <li><strong>Minimum Notice:</strong> Cancellations or reschedules must be made at least <strong class="text-red-900">2 days (48 hours)</strong> before your appointment.</li>
                                    <li><strong>Same-Day Policy:</strong> Same-day cancellations or reschedules are <strong class="text-red-900">NOT permitted</strong>.</li>
                                    <li><strong>Down Payment:</strong> Your down payment is <strong class="text-red-900">non-refundable</strong> for late cancellations or no-shows.</li>
                                    <li><strong>Eligible Actions:</strong> You may only cancel or reschedule appointments that are <strong>NOT in the current appointment period</strong>.</li>
                                    <li><strong>Weekly Limit:</strong> Maximum 3 cancellations allowed per week.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Cancellation Limit Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Cancellation Limit</h3>
                            <p class="text-sm text-gray-600 mt-1">You can cancel up to 3 appointments per week</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold {{ $remainingCancellations > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $remainingCancellations }}/3
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Remaining this week</p>
                        </div>
                    </div>
                    
                    @if(!$canCancel)
                        <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Cancellation Limit Reached</h3>
                                    <p class="text-sm text-red-700 mt-1">You have reached your cancellation limit for this week. You'll be able to cancel appointments again next week.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pending Appointments -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Pending Appointments</h3>
                    
                    @if($pendingAppointments->count() > 0)
                        <div class="space-y-4">
                            @foreach($pendingAppointments as $appointment)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">{{ $appointment->title }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <span class="font-medium">Procedure:</span> {{ $appointment->procedure }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Date & Time:</span> 
                                                {{ \Carbon\Carbon::parse($appointment->start)->format('M d, Y g:i A') }}
                                            </p>
                                            <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </div>
                                        
                                        <button 
                                            onclick="openCancelModal({{ $appointment->id }}, '{{ $appointment->title }}')"
                                            class="ml-4 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition {{ !$canCancel ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ !$canCancel ? 'disabled' : '' }}
                                        >
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">You don't have any pending appointments to cancel.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Cancellation History -->
            @if($cancellationHistory->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Cancellations</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Cancelled</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appointment</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($cancellationHistory as $cancellation)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $cancellation->cancelled_at->format('M d, Y g:i A') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <div>{{ $cancellation->appointment->title }}</div>
                                                <div class="text-xs text-gray-500">{{ $cancellation->appointment->procedure }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ Str::limit($cancellation->reason, 100) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Cancel Appointment</h3>
                    <button onclick="closeCancelModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-600">
                        Are you sure you want to cancel <span class="font-semibold" id="appointmentTitle"></span>?
                    </p>
                    <p class="text-xs text-red-600 mt-2">
                        You have {{ $remainingCancellations }} cancellation(s) remaining this week.
                    </p>
                </div>
                
                <form id="cancelForm" onsubmit="submitCancellation(event)">
                    <input type="hidden" id="appointmentId" name="appointment_id">
                    
                    <div class="mb-4">
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Cancellation <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="reason" 
                            name="reason" 
                            rows="4" 
                            required
                            minlength="10"
                            maxlength="500"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            placeholder="Please provide a detailed reason for cancelling your appointment (minimum 10 characters)"
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimum 10 characters, maximum 500 characters</p>
                    </div>
                    
                    <div id="errorMessage" class="hidden mb-4 bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-800"></div>
                    
                    <div class="flex justify-end gap-3">
                        <button 
                            type="button" 
                            onclick="closeCancelModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition"
                        >
                            Keep Appointment
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                            id="submitBtn"
                        >
                            Confirm Cancellation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCancelModal(appointmentId, appointmentTitle) {
            document.getElementById('appointmentId').value = appointmentId;
            document.getElementById('appointmentTitle').textContent = appointmentTitle;
            document.getElementById('cancelModal').classList.remove('hidden');
            document.getElementById('cancelModal').classList.add('flex');
            document.getElementById('reason').value = '';
            document.getElementById('errorMessage').classList.add('hidden');
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
            document.getElementById('cancelModal').classList.remove('flex');
        }

        async function submitCancellation(event) {
            event.preventDefault();
            
            const appointmentId = document.getElementById('appointmentId').value;
            const reason = document.getElementById('reason').value;
            const submitBtn = document.getElementById('submitBtn');
            const errorDiv = document.getElementById('errorMessage');
            
            // Disable button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Cancelling...';
            
            try {
                const response = await fetch(`/appointments/${appointmentId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ reason })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    alert(data.success);
                    window.location.reload();
                } else {
                    errorDiv.textContent = data.error || 'An error occurred';
                    errorDiv.classList.remove('hidden');
                }
            } catch (error) {
                errorDiv.textContent = 'Network error. Please try again.';
                errorDiv.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Confirm Cancellation';
            }
        }

        // Close modal when clicking outside
        document.getElementById('cancelModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCancelModal();
            }
        });
    </script>
</x-app-layout>
