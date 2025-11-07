@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-green-100 py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('payment-logo/maya.png') }}" alt="Maya" class="h-12 w-auto bg-white rounded-lg p-2">
                        <div class="text-white">
                            <h1 class="text-2xl font-bold">Maya Payment</h1>
                            <p class="text-green-100 text-sm">Secure payment gateway</p>
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
                        <span class="font-bold text-green-600">₱{{ number_format($appointment->down_payment, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <form action="{{ route('payment.process', $appointment->id) }}" method="POST" class="px-8 py-6">
                @csrf
                <input type="hidden" name="payment_method" value="paymaya">

                <div class="space-y-6">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h3 class="font-semibold text-green-900 mb-2">📱 How to Pay with Maya</h3>
                        <ol class="text-sm text-green-800 space-y-1 list-decimal list-inside">
                            <li>Open your Maya app</li>
                            <li>Send ₱{{ number_format($appointment->down_payment, 2) }} to: <strong>09123456789</strong></li>
                            <li>Enter the reference number below</li>
                        </ol>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maya Mobile Number</label>
                        <input type="text" name="paymaya_number" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="09XX XXX XXXX">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maya Reference Number</label>
                        <input type="text" name="paymaya_reference" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="Enter reference number">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="confirm" required class="h-4 w-4 text-green-600 rounded border-gray-300 focus:ring-green-500">
                        <label for="confirm" class="ml-2 text-sm text-gray-600">
                            I confirm that I have sent the payment via Maya
                        </label>
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('appointments') }}" 
                           class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-center font-medium text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition">
                            Confirm Payment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
