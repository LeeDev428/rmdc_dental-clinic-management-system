<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\ServiceFeedback;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ServiceFeedbackController extends Controller
{
    // Check if user has completed appointments that need feedback
    public function checkPendingFeedback()
    {
        $userId = Auth::id();
        $now = Carbon::now();
        
        // Find appointments that just ended (within last 5 minutes) and don't have feedback yet
        $completedAppointment = Appointment::where('user_id', $userId)
            ->where('status', 'accepted')
            ->whereDoesntHave('feedback')
            ->where('end', '<=', $now)
            ->where('end', '>=', $now->copy()->subMinutes(5))
            ->first();
        
        if ($completedAppointment) {
            return response()->json([
                'show_feedback' => true,
                'appointment_id' => $completedAppointment->id,
                'appointment' => $completedAppointment
            ]);
        }
        
        return response()->json(['show_feedback' => false]);
    }
    
    // Store feedback
    public function store(Request $request)
    {
        $userId = Auth::id();
        
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);
        
        // Check if appointment belongs to user
        $appointment = Appointment::findOrFail($validated['appointment_id']);
        
        if ($appointment->user_id != $userId) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 403);
        }
        
        // Check if feedback already exists
        $existingFeedback = ServiceFeedback::where('appointment_id', $validated['appointment_id'])->first();
        
        if ($existingFeedback) {
            return response()->json([
                'error' => 'Feedback already submitted for this appointment.'
            ], 422);
        }
        
        // Create feedback
        $feedback = ServiceFeedback::create([
            'appointment_id' => $validated['appointment_id'],
            'user_id' => $userId,
            'rating' => $validated['rating'],
            'comment' => $validated['comment']
        ]);
        
        return response()->json([
            'success' => 'Thank you for your feedback!',
            'feedback' => $feedback
        ]);
    }
    
    // Admin view: Show all feedbacks
    public function index()
    {
        $feedbacks = ServiceFeedback::with(['user', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.service-feedbacks', compact('feedbacks'));
    }
}
