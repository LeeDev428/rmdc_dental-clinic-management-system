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
        
        // Get user's ACCEPTED appointments (not pending - those can't be cancelled yet)
        $pendingAppointments = Appointment::where('user_id', $userId)
            ->where('status', 'accepted')
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
        
        // Check if appointment is accepted (only accepted appointments can be cancelled/rescheduled)
        if ($appointment->status !== 'accepted') {
            return response()->json([
                'error' => 'You can only cancel accepted appointments. Pending appointments must be approved first.'
            ], 422);
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
        
        return response()->json([
            'success' => 'Appointment cancelled successfully.',
            'remaining' => AppointmentCancellation::getRemainingCancellations($userId)
        ]);
    }
    
    // Process reschedule (redirects to appointments page with pre-filled data)
    public function reschedule(Request $request, $appointmentId)
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
                'error' => 'You can only reschedule your own appointments.'
            ], 403);
        }
        
        // Check if appointment is accepted
        if ($appointment->status !== 'accepted') {
            return response()->json([
                'error' => 'You can only reschedule accepted appointments. Pending appointments must be approved first.'
            ], 422);
        }
        
        // Validate reason
        $request->validate([
            'reason' => 'required|string|min:10|max:500'
        ]);
        
        // Create reschedule record (type = 'reschedule')
        AppointmentCancellation::create([
            'user_id' => $userId,
            'appointment_id' => $appointmentId,
            'reason' => $request->reason,
            'type' => 'reschedule',
            'processed_at' => Carbon::now()
        ]);
        
        // Update appointment status to 'rescheduled'
        $appointment->update(['status' => 'rescheduled']);
        
        return response()->json([
            'success' => 'Your appointment has been marked for rescheduling. An admin will contact you to arrange a new date and time.',
            'remaining' => AppointmentCancellation::getRemainingCancellations($userId)
        ]);
    }
}
