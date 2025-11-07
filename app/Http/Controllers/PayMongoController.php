<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PayMongoController extends Controller
{
    private $secretKey;
    private $publicKey;
    private $baseUrl = 'https://api.paymongo.com/v1';

    public function __construct()
    {
        $this->secretKey = env('PAYMONGO_SECRET_KEY');
        $this->publicKey = env('PAYMONGO_PUBLIC_KEY');
    }

    /**
     * Create payment source (GCash, PayMaya, Card)
     */
    public function createPaymentSource(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'payment_method' => 'required|in:gcash,paymaya,card',
            'amount' => 'required|numeric|min:0'
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);
        
        // Check if user owns this appointment
        if ($appointment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $amount = (float) $request->amount * 100; // Convert to centavos

            // Create source based on payment method
            $sourceType = $this->getSourceType($request->payment_method);
            
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->baseUrl}/sources", [
                    'data' => [
                        'attributes' => [
                            'amount' => $amount,
                            'redirect' => [
                                'success' => route('payment.success', ['appointment_id' => $appointment->id]),
                                'failed' => route('payment.failed', ['appointment_id' => $appointment->id])
                            ],
                            'type' => $sourceType,
                            'currency' => 'PHP',
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $source = $response->json()['data'];
                
                // Create payment record
                $payment = Payment::create([
                    'appointment_id' => $appointment->id,
                    'user_id' => Auth::id(),
                    'amount' => $request->amount,
                    'total_price' => $appointment->total_price,
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'pending',
                    'paymongo_source_id' => $source['id'],
                    'payment_details' => $source
                ]);

                return response()->json([
                    'success' => true,
                    'checkout_url' => $source['attributes']['redirect']['checkout_url'],
                    'payment_id' => $payment->id
                ]);
            }

            return response()->json([
                'error' => 'Failed to create payment source',
                'details' => $response->json()
            ], 400);

        } catch (\Exception $e) {
            Log::error('PayMongo Error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment processing failed'], 500);
        }
    }

    /**
     * Create payment intent for card payments
     */
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'amount' => 'required|numeric|min:0'
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);
        
        if ($appointment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $amount = (float) $request->amount * 100; // Convert to centavos

            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->baseUrl}/payment_intents", [
                    'data' => [
                        'attributes' => [
                            'amount' => $amount,
                            'payment_method_allowed' => ['card'],
                            'payment_method_options' => [
                                'card' => ['request_three_d_secure' => 'any']
                            ],
                            'currency' => 'PHP',
                            'description' => "Down payment for appointment #{$appointment->id}",
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $paymentIntent = $response->json()['data'];
                
                // Create payment record
                $payment = Payment::create([
                    'appointment_id' => $appointment->id,
                    'user_id' => Auth::id(),
                    'amount' => $request->amount,
                    'total_price' => $appointment->total_price,
                    'payment_method' => 'card',
                    'payment_status' => 'pending',
                    'paymongo_payment_id' => $paymentIntent['id'],
                    'payment_details' => $paymentIntent
                ]);

                return response()->json([
                    'success' => true,
                    'client_key' => $paymentIntent['attributes']['client_key'],
                    'payment_id' => $payment->id,
                    'payment_intent_id' => $paymentIntent['id']
                ]);
            }

            return response()->json([
                'error' => 'Failed to create payment intent',
                'details' => $response->json()
            ], 400);

        } catch (\Exception $e) {
            Log::error('PayMongo Payment Intent Error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment processing failed'], 500);
        }
    }

    /**
     * Attach payment method to payment intent
     */
    public function attachPaymentMethod(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required',
            'payment_method_id' => 'required'
        ]);

        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->baseUrl}/payment_intents/{$request->payment_intent_id}/attach", [
                    'data' => [
                        'attributes' => [
                            'payment_method' => $request->payment_method_id
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $paymentIntent = $response->json()['data'];
                
                return response()->json([
                    'success' => true,
                    'status' => $paymentIntent['attributes']['status'],
                    'next_action' => $paymentIntent['attributes']['next_action'] ?? null
                ]);
            }

            return response()->json([
                'error' => 'Failed to attach payment method',
                'details' => $response->json()
            ], 400);

        } catch (\Exception $e) {
            Log::error('PayMongo Attach Payment Error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment processing failed'], 500);
        }
    }

    /**
     * Payment success callback
     */
    public function paymentSuccess(Request $request)
    {
        $appointmentId = $request->query('appointment_id');
        $appointment = Appointment::findOrFail($appointmentId);
        
        // Update payment status
        $payment = Payment::where('appointment_id', $appointmentId)
            ->where('payment_status', 'pending')
            ->latest()
            ->first();
        
        if ($payment) {
            $payment->update([
                'payment_status' => 'paid',
                'paid_at' => now()
            ]);
            
            // Update appointment payment status
            $appointment->update([
                'payment_status' => 'partially_paid'
            ]);
        }
        
        return redirect()->route('appointments')
            ->with('success', '💰 Payment successful! Your 20% down payment has been processed.');
    }

    /**
     * Payment failed callback
     */
    public function paymentFailed(Request $request)
    {
        $appointmentId = $request->query('appointment_id');
        
        // Update payment status
        $payment = Payment::where('appointment_id', $appointmentId)
            ->where('payment_status', 'pending')
            ->latest()
            ->first();
        
        if ($payment) {
            $payment->update([
                'payment_status' => 'failed'
            ]);
        }
        
        return redirect()->route('appointments')
            ->with('error', '❌ Payment failed. Please try again or contact support.');
    }

    /**
     * Webhook handler for PayMongo events
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();
        Log::info('PayMongo Webhook:', $payload);

        $event = $payload['data'];
        $eventType = $event['attributes']['type'];

        switch ($eventType) {
            case 'source.chargeable':
                $this->handleSourceChargeable($event);
                break;
            case 'payment.paid':
                $this->handlePaymentPaid($event);
                break;
            case 'payment.failed':
                $this->handlePaymentFailed($event);
                break;
        }

        return response()->json(['success' => true]);
    }

    /**
     * Handle source chargeable event
     */
    private function handleSourceChargeable($event)
    {
        $sourceId = $event['attributes']['data']['id'];
        $payment = Payment::where('paymongo_source_id', $sourceId)->first();

        if ($payment) {
            // Create a payment/charge
            $amount = $event['attributes']['data']['attributes']['amount'];
            
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->baseUrl}/payments", [
                    'data' => [
                        'attributes' => [
                            'amount' => $amount,
                            'source' => [
                                'id' => $sourceId,
                                'type' => 'source'
                            ],
                            'currency' => 'PHP',
                            'description' => "Appointment #{$payment->appointment_id} down payment"
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $paymentData = $response->json()['data'];
                $payment->update([
                    'paymongo_payment_id' => $paymentData['id'],
                    'payment_details' => $paymentData
                ]);
            }
        }
    }

    /**
     * Handle payment paid event
     */
    private function handlePaymentPaid($event)
    {
        $paymentId = $event['attributes']['data']['id'];
        $payment = Payment::where('paymongo_payment_id', $paymentId)->first();

        if ($payment) {
            $payment->update([
                'payment_status' => 'paid',
                'paid_at' => now()
            ]);

            $payment->appointment->update([
                'payment_status' => 'partially_paid'
            ]);
        }
    }

    /**
     * Handle payment failed event
     */
    private function handlePaymentFailed($event)
    {
        $paymentId = $event['attributes']['data']['id'];
        $payment = Payment::where('paymongo_payment_id', $paymentId)->first();

        if ($payment) {
            $payment->update([
                'payment_status' => 'failed'
            ]);
        }
    }

    /**
     * Get source type for payment method
     */
    private function getSourceType($paymentMethod)
    {
        $types = [
            'gcash' => 'gcash',
            'paymaya' => 'paymaya',
            'card' => 'card'
        ];

        return $types[$paymentMethod] ?? 'gcash';
    }
}
