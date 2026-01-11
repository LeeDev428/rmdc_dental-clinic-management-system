<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'message' => 'nullable|string|max:255',
                'appointment_id' => 'nullable|exists:appointments,id',
            ]);

            Rating::create([
                'rating' => $validated['rating'],
                'message' => $validated['message'] ?? null,
                'user_id' => Auth::id(),
                'appointment_id' => $validated['appointment_id'] ?? null,
            ]);
            
            // Mark appointment as reviewed if appointment_id is provided
            if (isset($validated['appointment_id'])) {
                Appointment::where('id', $validated['appointment_id'])
                    ->update(['reviewed_at' => now()]);
            }

            return response()->json(['success' => true, 'message' => 'Rating saved successfully.']);
        } catch (\Exception $e) {
            \Log::error('Rating submission error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Failed to save rating. Please try again.'
            ], 500);
        }
    }
}
