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
    public function createPayment(Request $request)
    {
        try {
            // Get appointment data from session
            $sessionKey = $request->query('session_key');
            if (!$sessionKey) {
                return redirect()->route('appointments')->with('error', 'Invalid payment session');
            }
            
            $appointmentData = session($sessionKey);
            if (!$appointmentData) {
                return redirect()->route('appointments')->with('error', 'Payment session expired. Please try again.');
            }
            
            Log::info('Retrieved appointment data from session:', [
                'session_key' => $sessionKey,
                'data' => $appointmentData
            ]);
            
            $paymentMethod = $appointmentData['payment_method'];
            $amount = (int)($appointmentData['down_payment'] * 100); // Convert to centavos (integer)
            
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
                            'description' => "RMDC Dental Clinic - {$appointmentData['procedure']}",
                            'line_items' => [
                                [
                                    'currency' => 'PHP',
                                    'amount' => $amount,
                                    'description' => $appointmentData['procedure'],
                                    'name' => 'Down Payment (20%)',
                                    'quantity' => 1,
                                ]
                            ],
                            'payment_method_types' => $paymentMethodTypes,
                            'success_url' => route('payment.success') . '?session_key=' . $sessionKey,
                            'cancel_url' => route('payment.failed') . '?session_key=' . $sessionKey,
                            'billing' => [
                                'name' => Auth::user()->name,
                                'email' => Auth::user()->email,
                                'phone' => '09123456789', // Default phone for sandbox testing
                            ],
                            'metadata' => [
                                'session_key' => $sessionKey,
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
                // Clear session data
                session()->forget($sessionKey);
                return redirect()->route('appointments')->with('error', 'Failed to create checkout session. Please try again.');
            }
            
            $checkoutSession = $response->json()['data'];
            
            // Store checkout session ID in appointment data
            $appointmentData['checkout_session_id'] = $checkoutSession['id'];
            session([$sessionKey => $appointmentData]);
            
            Log::info('PayMongo Checkout Session Created:', [
                'session_key' => $sessionKey,
                'checkout_session_id' => $checkoutSession['id'],
                'checkout_url' => $checkoutSession['attributes']['checkout_url']
            ]);
            
            // Redirect to PayMongo hosted checkout page
            return redirect($checkoutSession['attributes']['checkout_url']);
            
        } catch (\Exception $e) {
            Log::error('PayMongo Payment Creation Error: ' . $e->getMessage(), [
                'session_key' => $sessionKey ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            // Clear session data
            if (isset($sessionKey)) {
                session()->forget($sessionKey);
            }
            return redirect()->route('appointments')->with('error', 'Failed to initialize payment. Please try again.');
        }
    }
    
    /**
     * Handle successful payment callback from PayMongo
     */
    public function paymentSuccess(Request $request)
    {
        try {
            // Get session key from URL
            $sessionKey = $request->query('session_key');
            if (!$sessionKey) {
                return redirect()->route('dashboard')->with('error', 'Invalid payment session');
            }
            
            $appointmentData = session($sessionKey);
            if (!$appointmentData) {
                return redirect()->route('dashboard')->with('error', 'Payment session expired');
            }
            
            $checkoutSessionId = $appointmentData['checkout_session_id'] ?? null;
            if (!$checkoutSessionId) {
                return redirect()->route('dashboard')->with('error', 'Invalid checkout session');
            }
            
            // Retrieve checkout session from PayMongo to verify payment
            $response = Http::withBasicAuth(config('paymongo.secret_key'), '')
                ->get("https://api.paymongo.com/v1/checkout_sessions/{$checkoutSessionId}");
            
            if ($response->successful()) {
                $checkoutSession = $response->json()['data'];
                $payments = $checkoutSession['attributes']['payments'] ?? [];
                
                // CHECK PAYMENT STATUS FROM THE ACTUAL PAYMENT, NOT THE CHECKOUT SESSION
                $isPaid = false;
                $paymentId = null;
                
                if (!empty($payments)) {
                    $latestPayment = $payments[0];
                    $paymentStatus = $latestPayment['attributes']['status'] ?? 'unpaid';
                    $paymentId = $latestPayment['id'] ?? null;
                    $isPaid = ($paymentStatus === 'paid');
                    
                    Log::info('PayMongo Payment Retrieved:', [
                        'session_key' => $sessionKey,
                        'payment_id' => $paymentId,
                        'payment_status' => $paymentStatus,
                        'is_paid' => $isPaid,
                        'amount' => $latestPayment['attributes']['amount'] ?? 0
                    ]);
                } else {
                    Log::warning('No payments found in checkout session', [
                        'session_key' => $sessionKey,
                        'checkout_session_id' => $checkoutSessionId
                    ]);
                }
                
                if ($isPaid && $paymentId) {
                    // NOW CREATE THE APPOINTMENT after payment is confirmed
                    $appointment = Appointment::create([
                        'title' => $appointmentData['title'],
                        'procedure' => $appointmentData['procedure'],
                        'time' => $appointmentData['time'],
                        'start' => $appointmentData['start'],
                        'end' => $appointmentData['end'],
                        'duration' => $appointmentData['duration'],
                        'user_id' => $appointmentData['user_id'],
                        'image_path' => $appointmentData['image_path'],
                        'payment_method' => $appointmentData['payment_method'],
                        'total_price' => $appointmentData['total_price'],
                        'down_payment' => $appointmentData['down_payment'],
                        'payment_status' => 'paid',
                        'payment_reference' => $paymentId,
                        'status' => 'pending', // Ready for admin approval
                    ]);
                    
                    // Clear session data and save to database
                    session()->forget($sessionKey);
                    session()->save(); // Force save to database immediately
                    
                    Log::info('✅ APPOINTMENT CREATED SUCCESSFULLY:', [
                        'appointment_id' => $appointment->id,
                        'user_id' => $appointment->user_id,
                        'procedure' => $appointment->procedure,
                        'payment_reference' => $paymentId,
                        'payment_status' => 'paid',
                        'status' => 'pending'
                    ]);
                    
                    return redirect()->route('dashboard')
                        ->with('success', 'Payment successful! Your appointment (ID: ' . $appointment->id . ') is now pending approval from the clinic.');
                } else {
                    // Payment not completed yet
                    Log::warning('Payment not completed or not found', [
                        'session_key' => $sessionKey,
                        'checkout_session_id' => $checkoutSessionId,
                        'payments_count' => count($payments),
                        'has_payment_id' => !empty($paymentId)
                    ]);
                    
                    // Don't forget the session - user might complete payment later
                    return redirect()->route('dashboard')
                        ->with('warning', 'Payment verification failed. Please check your payment status or try booking again.');
                }
            } else {
                Log::error('Failed to retrieve checkout session from PayMongo', [
                    'status_code' => $response->status(),
                    'response' => $response->body()
                ]);
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
    public function paymentFailed(Request $request)
    {
        try {
            // Get session key from URL
            $sessionKey = $request->query('session_key');
            
            // Clear session data (no appointment was created)
            if ($sessionKey) {
                session()->forget($sessionKey);
                session()->save(); // Force save to database immediately
                Log::info('Payment Failed/Cancelled - Session cleared:', [
                    'session_key' => $sessionKey
                ]);
            }
            
            return redirect()->route('appointments')
                ->with('error', 'Payment was cancelled or failed. Please try again or choose a different payment method.');
                
        } catch (\Exception $e) {
            Log::error('Payment Failed Callback Error: ' . $e->getMessage());
            return redirect()->route('appointments')
                ->with('error', 'An error occurred. Please try booking again.');
        }
    }
}
