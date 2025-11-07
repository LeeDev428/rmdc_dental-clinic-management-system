@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-purple-100 py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <div id="loading-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-8 text-center">
                <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-purple-600 mx-auto"></div>
                <p class="mt-4 text-gray-700 font-medium">Processing payment...</p>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white rounded-lg p-3">
                            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <div class="text-white">
                            <h1 class="text-2xl font-bold">Credit/Debit Card Payment</h1>
                            <p class="text-purple-100 text-sm">Secure SSL encrypted</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointment Details -->
            <div class="px-8 py-6 bg-gray-50 border-b">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Appointment Summary</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Procedure:</span>
                        <span class="font-semibold text-gray-900">{{ $appointment->procedure }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date & Time:</span>
                        <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($appointment->start)->format('F d, Y h:i A') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Price:</span>
                        <span class="font-semibold text-gray-900">₱{{ number_format($appointment->total_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg border-t pt-2 mt-2">
                        <span class="text-gray-800 font-semibold">Down Payment (20%):</span>
                        <span class="font-bold text-purple-600">₱{{ number_format($appointment->down_payment, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <form id="payment-form" class="px-8 py-6">
                @csrf
                <div class="space-y-6">
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 flex items-center space-x-3">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <p class="text-sm text-purple-900">Your payment information is encrypted and secure with PayMongo</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Card Number</label>
                        <div id="card-number" class="w-full px-4 py-3 border border-gray-300 rounded-lg"></div>
                        <div id="card-number-error" class="text-red-500 text-sm mt-1"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                            <div id="card-expiry" class="w-full px-4 py-3 border border-gray-300 rounded-lg"></div>
                            <div id="card-expiry-error" class="text-red-500 text-sm mt-1"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CVC</label>
                            <div id="card-cvc" class="w-full px-4 py-3 border border-gray-300 rounded-lg"></div>
                            <div id="card-cvc-error" class="text-red-500 text-sm mt-1"></div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Billing Details</label>
                        <input type="text" id="billing-name" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent mb-3"
                               placeholder="Full Name" value="{{ Auth::user()->name }}">
                        <input type="email" id="billing-email" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent mb-3"
                               placeholder="Email" value="{{ Auth::user()->email }}">
                        <input type="tel" id="billing-phone" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="Phone Number" value="{{ Auth::user()->contact ?? '' }}">
                    </div>

                    <div id="error-message" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg"></div>

                    <div class="flex space-x-4">
                        <a href="{{ route('appointments') }}" 
                           class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-center font-medium text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" id="pay-button"
                                class="flex-1 px-6 py-3 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition disabled:opacity-50">
                            Pay ₱{{ number_format($appointment->down_payment, 2) }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-6 flex justify-center space-x-4">
            <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" alt="Visa" class="h-8">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="h-8">
        </div>
    </div>
</div>

<script src="https://unpkg.com/@paymongo/paymongo-js@1.0.0/dist/paymongo.js"></script>
<script>
    const pmPublicKey = '{{ env('PAYMONGO_PUBLIC_KEY') }}';
    const clientKey = '{{ $clientKey ?? '' }}';
    const paymentIntentId = '{{ $paymentIntentId ?? '' }}';
    const appointmentId = '{{ $appointment->id }}';
    
    console.log('PayMongo Public Key:', pmPublicKey);
    console.log('Client Key:', clientKey);
    console.log('Payment Intent ID:', paymentIntentId);
    
    // Initialize PayMongo
    const paymongoClient = new Paymongo(pmPublicKey);
    
    // Create card elements
    const cardNumberElement = paymongoClient.createCardNumber('#card-number', {
        style: {
            base: {
                fontSize: '16px',
                color: '#374151',
                fontFamily: 'system-ui, -apple-system, sans-serif',
            },
            invalid: {
                color: '#EF4444',
            }
        },
        hideIcon: false,
    });
    
    const cardExpiryElement = paymongoClient.createCardExpiry('#card-expiry', {
        style: {
            base: {
                fontSize: '16px',
                color: '#374151',
                fontFamily: 'system-ui, -apple-system, sans-serif',
            }
        }
    });
    
    const cardCvcElement = paymongoClient.createCardCvc('#card-cvc', {
        style: {
            base: {
                fontSize: '16px',
                color: '#374151',
                fontFamily: 'system-ui, -apple-system, sans-serif',
            }
        }
    });
    
    // Handle form submission
    document.getElementById('payment-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const payButton = document.getElementById('pay-button');
        const errorMessage = document.getElementById('error-message');
        const loadingOverlay = document.getElementById('loading-overlay');
        
        payButton.disabled = true;
        errorMessage.classList.add('hidden');
        loadingOverlay.classList.remove('hidden');
        
        try {
            console.log('Creating payment method...');
            
            // Create payment method using PayMongo
            const paymentMethodResult = await paymongoClient.createPaymentMethod('card', {
                card_number: cardNumberElement,
                exp_month: cardExpiryElement,
                exp_year: cardExpiryElement,
                cvc: cardCvcElement,
            }, {
                billing: {
                    name: document.getElementById('billing-name').value,
                    email: document.getElementById('billing-email').value,
                    phone: document.getElementById('billing-phone').value,
                }
            });
            
            console.log('Payment Method Result:', paymentMethodResult);
            
            if (paymentMethodResult.error) {
                throw new Error(paymentMethodResult.error.message || 'Failed to create payment method');
            }
            
            // Send to server
            console.log('Sending to server...');
            const response = await fetch(`/payment/card/${appointmentId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    payment_method_id: paymentMethodResult.paymentMethod.id,
                    client_key: clientKey
                })
            });
            
            const data = await response.json();
            console.log('Server Response:', data);
            
            if (data.redirect) {
                console.log('Redirecting to:', data.redirect_url);
                window.location.href = data.redirect_url;
            } else if (data.success) {
                window.location.href = '{{ route("dashboard") }}';
            } else {
                throw new Error(data.message || 'Payment failed');
            }
            
        } catch (error) {
            console.error('Payment Error:', error);
            errorMessage.textContent = error.message || 'An error occurred during payment';
            errorMessage.classList.remove('hidden');
            payButton.disabled = false;
            loadingOverlay.classList.add('hidden');
        }
    });
</script>
@endsection
