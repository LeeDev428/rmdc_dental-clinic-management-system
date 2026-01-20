<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class RefundService
{
    /**
     * Process refund for cancelled/declined appointment
     * Refunds 20% of total price (clinic keeps 5% as cancellation fee)
     * 
     * @param Appointment $appointment
     * @param string $reason
     * @return array
     */
    public function processRefund(Appointment $appointment, string $reason)
    {
        try {
            // Get the payment record
            $payment = Payment::where('appointment_id', $appointment->id)
                ->where('payment_status', 'paid')
                ->first();
            
            if (!$payment) {
                Log::warning('No payment found for appointment refund', [
                    'appointment_id' => $appointment->id
                ]);
                return [
                    'success' => false,
                    'message' => 'No payment record found for this appointment'
                ];
            }
            
            // Calculate refund amount
            // Down payment: 25% of total price
            // Refund: 20% of total price (80% of down payment)
            // Clinic keeps: 5% of total price (20% of down payment as cancellation fee)
            $totalPrice = $payment->total_price;
            $downPayment = $payment->amount; // 25% already paid
            $refundAmount = $totalPrice * 0.20; // Refund 20% of total (80% of down payment)
            $cancellationFee = $downPayment - $refundAmount; // Clinic keeps 5%
            
            Log::info('Refund calculation', [
                'appointment_id' => $appointment->id,
                'total_price' => $totalPrice,
                'down_payment' => $downPayment,
                'refund_amount' => $refundAmount,
                'cancellation_fee' => $cancellationFee
            ]);
            
            // Process PayMongo refund
            $refundResult = $this->createPayMongoRefund($payment, $refundAmount, $reason);
            
            if ($refundResult['success']) {
                // Update payment record
                $payment->update([
                    'refund_amount' => $refundAmount,
                    'refund_status' => 'processed',
                    'paymongo_refund_id' => $refundResult['refund_id'] ?? null,
                    'refund_reason' => $reason,
                    'refunded_at' => now(),
                    'payment_status' => 'refunded'
                ]);
                
                Log::info('Refund processed successfully', [
                    'appointment_id' => $appointment->id,
                    'payment_id' => $payment->id,
                    'refund_amount' => $refundAmount,
                    'refund_id' => $refundResult['refund_id'] ?? null
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Refund processed successfully',
                    'refund_amount' => $refundAmount,
                    'cancellation_fee' => $cancellationFee,
                    'refund_id' => $refundResult['refund_id'] ?? null
                ];
            } else {
                // Mark refund as pending/failed
                $payment->update([
                    'refund_amount' => $refundAmount,
                    'refund_status' => 'pending',
                    'refund_reason' => $reason
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Refund initiated but pending processing: ' . ($refundResult['message'] ?? 'Unknown error'),
                    'refund_amount' => $refundAmount
                ];
            }
            
        } catch (Exception $e) {
            Log::error('Refund processing error', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to process refund: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Create refund via PayMongo API
     * 
     * @param Payment $payment
     * @param float $amount
     * @param string $reason
     * @return array
     */
    private function createPayMongoRefund(Payment $payment, float $amount, string $reason)
    {
        try {
            $secretKey = config('services.paymongo.secret_key');
            
            if (!$secretKey) {
                throw new Exception('PayMongo secret key not configured');
            }
            
            if (!$payment->paymongo_payment_id) {
                throw new Exception('No PayMongo payment ID found');
            }
            
            // Convert amount to cents (PayMongo uses centavos)
            $amountInCents = (int)($amount * 100);
            
            // PayMongo Refund API endpoint
            $url = "https://api.paymongo.com/v1/refunds";
            
            $response = Http::withBasicAuth($secretKey, '')
                ->timeout(30)
                ->post($url, [
                    'data' => [
                        'attributes' => [
                            'amount' => $amountInCents,
                            'payment_id' => $payment->paymongo_payment_id,
                            'reason' => $reason,
                            'notes' => 'Cancellation/Decline Refund - 20% of total price'
                        ]
                    ]
                ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $refundId = $data['data']['id'] ?? null;
                
                Log::info('PayMongo refund created', [
                    'refund_id' => $refundId,
                    'payment_id' => $payment->paymongo_payment_id,
                    'amount' => $amount
                ]);
                
                return [
                    'success' => true,
                    'refund_id' => $refundId,
                    'message' => 'Refund created successfully'
                ];
            } else {
                $error = $response->json();
                Log::error('PayMongo refund failed', [
                    'status' => $response->status(),
                    'error' => $error
                ]);
                
                return [
                    'success' => false,
                    'message' => $error['errors'][0]['detail'] ?? 'Refund creation failed'
                ];
            }
            
        } catch (Exception $e) {
            Log::error('PayMongo refund exception', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
