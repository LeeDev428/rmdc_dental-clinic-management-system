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
            \Log::info('Rating submission started', [
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'message' => 'nullable|string|max:255',
                'appointment_id' => 'nullable|exists:appointments,id',
            ]);

            \Log::info('Validation passed', ['validated' => $validated]);

            $rating = Rating::create([
                'rating' => $validated['rating'],
                'message' => $validated['message'] ?? null,
                'user_id' => Auth::id(),
                'appointment_id' => $validated['appointment_id'] ?? null,
            ]);
            
            \Log::info('Rating created successfully', ['rating_id' => $rating->id]);
            
            // Mark appointment as reviewed if appointment_id is provided
            if (isset($validated['appointment_id'])) {
                Appointment::where('id', $validated['appointment_id'])
                    ->update(['reviewed_at' => now()]);
                \Log::info('Appointment marked as reviewed', ['appointment_id' => $validated['appointment_id']]);
            }

            return response()->json(['success' => true, 'message' => 'Rating saved successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Rating validation failed', [
                'errors' => $e->errors(),
                'user_id' => Auth::id()
            ]);
            return response()->json([
                'success' => false, 
                'message' => 'Validation failed: ' . implode(', ', array_map(fn($err) => implode(', ', $err), $e->errors()))
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Rating submission error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Failed to save rating. Please contact support if this persists.'
            ], 500);
        }
    }
}
