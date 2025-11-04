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
        
        // Get user's pending appointments
        $pendingAppointments = Appointment::where('user_id', $userId)
            ->where('status', 'pending')
            ->orderBy('start', 'asc')
            ->get();
        
        // Get cancellation history (last 7 days)
        $cancellationHistory = AppointmentCancellation::where('user_id', $userId)
            ->with('appointment')
            ->orderBy('cancelled_at', 'desc')
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
        
        // Check if user can cancel (3 per week limit)
        if (!AppointmentCancellation::canUserCancel($userId)) {
            return response()->json([
                'error' => 'You have reached your cancellation limit (3 per week). Please try again later.'
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
        
        // Check if appointment is pending
        if ($appointment->status !== 'pending') {
            return response()->json([
                'error' => 'You can only cancel pending appointments.'
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
            'cancelled_at' => Carbon::now()
        ]);
        
        // Update appointment status to cancelled
        $appointment->update(['status' => 'cancelled']);
        
        return response()->json([
            'success' => 'Appointment cancelled successfully.',
            'remaining' => AppointmentCancellation::getRemainingCancellations($userId)
        ]);
    }
}
