<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Create PayMongo Checkout Session and redirect to hosted checkout page
     */
    public function createPayment($appointmentId)
    {
        try {
            $appointment = Appointment::where('id', $appointmentId)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            $paymentMethod = $appointment->payment_method;
            $amount = (int)($appointment->down_payment * 100); // Convert to centavos (integer)
            
            // Determine payment method types for checkout session
            $paymentMethodTypes = [];
            if ($paymentMethod === 'gcash') {
                $paymentMethodTypes = ['gcash'];
            } elseif ($paymentMethod === 'paymaya') {
                $paymentMethodTypes = ['paymaya'];
            } elseif ($paymentMethod === 'card') {
                $paymentMethodTypes = ['card'];
            } else {
                return back()->with('error', 'Invalid payment method');
            }
            
            // Create Checkout Session using PayMongo API
            $response = Http::withBasicAuth(config('paymongo.secret_key'), '')
                ->post('https://api.paymongo.com/v1/checkout_sessions', [
                    'data' => [
                        'attributes' => [
                            'send_email_receipt' => true,
                            'show_description' => true,
                            'show_line_items' => true,
                            'description' => "RMDC Dental Clinic - Appointment #{$appointmentId}",
                            'line_items' => [
                                [
                                    'currency' => 'PHP',
                                    'amount' => $amount,
                                    'description' => $appointment->procedure,
                                    'name' => 'Down Payment (20%)',
                                    'quantity' => 1,
                                ]
                            ],
                            'payment_method_types' => $paymentMethodTypes,
                            'success_url' => route('payment.success', ['appointment' => $appointmentId]),
                            'cancel_url' => route('payment.failed', ['appointment' => $appointmentId]),
                            'billing' => [
                                'name' => Auth::user()->name,
                                'email' => Auth::user()->email,
                                'phone' => '09123456789', // Default phone for sandbox testing
                            ],
                            'metadata' => [
                                'appointment_id' => (string)$appointmentId,
                                'user_id' => (string)Auth::id(),
                            ],
                        ]
                    ]
                ]);
            
            if ($response->failed()) {
                Log::error('PayMongo Checkout Session Error:', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);
                return back()->with('error', 'Failed to create checkout session. Please try again.');
            }
            
            $checkoutSession = $response->json()['data'];
            
            // Store checkout session ID for verification
            $appointment->update([
                'payment_reference' => $checkoutSession['id'],
            ]);
            
            Log::info('PayMongo Checkout Session Created:', [
                'appointment_id' => $appointmentId,
                'checkout_session_id' => $checkoutSession['id'],
                'checkout_url' => $checkoutSession['attributes']['checkout_url']
            ]);
            
            // Redirect to PayMongo hosted checkout page
            return redirect($checkoutSession['attributes']['checkout_url']);
            
        } catch (\Exception $e) {
            Log::error('PayMongo Payment Creation Error: ' . $e->getMessage(), [
                'appointment_id' => $appointmentId,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to initialize payment. Please try again.');
        }
    }
    
    /**
     * Handle successful payment callback from PayMongo
     */
    public function paymentSuccess(Request $request, $appointmentId)
    {
        try {
            $appointment = Appointment::where('id', $appointmentId)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            // Retrieve checkout session from PayMongo to verify payment
            $response = Http::withBasicAuth(config('paymongo.secret_key'), '')
                ->get("https://api.paymongo.com/v1/checkout_sessions/{$appointment->payment_reference}");
            
            if ($response->successful()) {
                $checkoutSession = $response->json()['data'];
                $paymentStatus = $checkoutSession['attributes']['payment_status'] ?? 'unpaid';
                
                Log::info('PayMongo Checkout Session Retrieved:', [
                    'appointment_id' => $appointmentId,
                    'payment_status' => $paymentStatus,
                    'session' => $checkoutSession
                ]);
                
                if ($paymentStatus === 'paid') {
                    // Get payment details from checkout session
                    $payments = $checkoutSession['attributes']['payments'] ?? [];
                    $paymentId = !empty($payments) ? $payments[0]['id'] : $appointment->payment_reference;
                    
                    // Update appointment status to 'pending' (awaiting admin approval) after successful payment
                    $appointment->update([
                        'payment_status' => 'paid',
                        'payment_reference' => $paymentId,
                        'status' => 'pending', // Now ready for admin review
                    ]);
                    
                    return redirect()->route('dashboard')
                        ->with('success', 'Payment successful! Your appointment is now pending approval from the clinic.');
                }
            }
            
            // If payment not confirmed, redirect with warning
            return redirect()->route('dashboard')
                ->with('warning', 'Payment verification pending. We will confirm your appointment once payment is verified.');
                
        } catch (\Exception $e) {
            Log::error('Payment Success Callback Error: ' . $e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'Payment verification failed. Please contact support.');
        }
    }
    
    /**
     * Handle failed/cancelled payment callback from PayMongo
     */
    public function paymentFailed(Request $request, $appointmentId)
    {
        try {
            $appointment = Appointment::where('id', $appointmentId)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            $appointment->update([
                'payment_status' => 'failed',
            ]);
            
            Log::info('Payment Failed/Cancelled:', [
                'appointment_id' => $appointmentId
            ]);
            
            return redirect()->route('appointments')
                ->with('error', 'Payment was cancelled or failed. Please try again or choose a different payment method.');
                
        } catch (\Exception $e) {
            Log::error('Payment Failed Callback Error: ' . $e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'An error occurred. Please contact support.');
        }
    }
}
