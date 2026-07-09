<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\AppointmentCancellation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentCancellationController extends Controller
{
    // Show cancellation page
    public function index()
    {
        $userId = Auth::id();
        
        // Get user's ACCEPTED and PENDING appointments
        $pendingAppointments = Appointment::where('user_id', $userId)
            ->whereIn('status', ['accepted', 'pending'])
            ->orderBy('start', 'asc')
            ->get();
        
        // Get cancellation history (last 7 days)
        $cancellationHistory = AppointmentCancellation::where('user_id', $userId)
            ->with('appointment')
            ->orderBy('processed_at', 'desc')
            ->limit(10)
            ->get();
        
        // Check remaining cancellations
        $remainingCancellations = AppointmentCancellation::getRemainingCancellations($userId);
        $canCancel = AppointmentCancellation::canUserCancel($userId);
        
        return view('appointment-cancellation', compact(
            'pendingAppointments',
            'cancellationHistory',
            'remainingCancellations',
            'canCancel'
        ));
    }
    
    // Process cancellation
    public function cancel(Request $request, $appointmentId)
    {
        $userId = Auth::id();
        
        // Check if user can perform action (2 per week limit)
        if (!AppointmentCancellation::canUserCancel($userId)) {
            return response()->json([
                'error' => 'You have reached your limit (2 actions per week). Please try again later.'
            ], 422);
        }
        
        // Find the appointment
        $appointment = Appointment::findOrFail($appointmentId);
        
        // Verify ownership
        if ($appointment->user_id != $userId) {
            return response()->json([
                'error' => 'You can only cancel your own appointments.'
            ], 403);
        }
        
        // Validate reason
        $request->validate([
            'reason' => 'required|string|min:10|max:500'
        ]);
        
        // Create cancellation record
        AppointmentCancellation::create([
            'user_id' => $userId,
            'appointment_id' => $appointmentId,
            'reason' => $request->reason,
            'type' => 'cancel',
            'processed_at' => Carbon::now()
        ]);
        
        // Update appointment status to cancelled
        $appointment->update(['status' => 'cancelled']);
        
        $successMessage = 'Appointment cancelled successfully. Payment will be settled physically for future bookings.';
        
        return response()->json([
            'success' => $successMessage,
            'remaining' => AppointmentCancellation::getRemainingCancellations($userId),
        ]);
    }
    
    // Process reschedule (redirects to appointments page with pre-filled data)
    public function reschedule(Request $request, $appointmentId)
    {
        try {
            $userId = Auth::id();
            
            // Check if user can perform action (2 per week limit)
            if (!AppointmentCancellation::canUserCancel($userId)) {
                return response()->json([
                    'error' => 'You have reached your limit (2 actions per week). Please try again later.'
                ], 422);
            }
            
            // Find the appointment
            $appointment = Appointment::findOrFail($appointmentId);
            
            // Verify ownership
            if ($appointment->user_id != $userId) {
                return response()->json([
                    'error' => 'You can only reschedule your own appointments.'
                ], 403);
            }
            
            // Validate reason
            $validated = $request->validate([
                'reason' => 'required|string|min:10|max:500'
            ]);
            
            // Create reschedule record (type = 'reschedule')
            AppointmentCancellation::create([
                'user_id' => $userId,
                'appointment_id' => $appointmentId,
                'reason' => $validated['reason'],
                'type' => 'reschedule',
                'processed_at' => Carbon::now()
            ]);
            
            // Mark appointment as 'rescheduled' temporarily
            $appointment->update(['status' => 'rescheduled']);
            
            return response()->json([
                'success' => 'Please select a new date and time for your appointment.',
                'remaining' => AppointmentCancellation::getRemainingCancellations($userId),
                'redirect' => route('appointments', ['reschedule' => $appointmentId])
            ], 200);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Reschedule error: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while processing your request: ' . $e->getMessage()
            ], 500);
        }
    }
}
