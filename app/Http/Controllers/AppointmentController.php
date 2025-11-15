<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ProcedurePrice;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Traits\LogsActivity;

class AppointmentController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has any pending appointments
        $hasPendingAppointment = Appointment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();
        
        $appointments = Appointment::all();
        $procedurePrices = ProcedurePrice::all(); // Fetch procedure prices from the database
        $selectedDate = $request->input('date', now()->toDateString());
    
        $workingHours = [
            '08:00', '08:15', '08:30', '08:45',
            '09:00', '09:15', '09:30', '09:45', 
            '10:00', '10:15', '10:30', '10:45',
            '11:00', '11:15', '11:30', '11:45',
            '12:00', '12:15', '12:30', '12:45',
            '13:00', '13:15', '13:30', '13:45',
            '14:00', '14:15', '14:30', '14:45',
            '15:00', '15:15', '15:30', '15:45',
            '16:00', '16:15', '16:30', '16:45',
        ];
    
        // Remove booked times (including all slots within duration)
        $availableTimes = array_filter($workingHours, function ($time) use ($appointments, $selectedDate) {
            $currentTime = Carbon::parse("$selectedDate $time");
            foreach ($appointments as $appointment) {
                $start = Carbon::parse($appointment->start);
                $end = Carbon::parse($appointment->end);
                if ($currentTime >= $start && $currentTime < $end) {
                    return false; // Remove this time slot
                }
            }
            return true; // Keep this time slot
        });
    
        $availableTimes = array_values($availableTimes); // Reindex array
    
        // Retrieve the selected procedure (if any)
        $selectedProcedure = $request->input('procedure');
        
        // If a procedure is selected, fetch its price
        $procedurePrice = null;
        if ($selectedProcedure) {
            // Find the procedure price based on the selected procedure
            $procedurePrice = ProcedurePrice::where('procedure_name', $selectedProcedure)->first();
        }
    
        // Return JSON if it's an AJAX request
        if ($request->ajax()) {
            return response()->json(['availableTimes' => $availableTimes]);
        }
    
        // Return the HTML view for normal page load
        return view('appointments', compact('availableTimes', 'selectedDate', 'appointments', 'procedurePrices', 'procedurePrice', 'selectedProcedure', 'hasPendingAppointment'));
    }
    
    
    public function getProcedurePrice(Request $request)
{
    $procedureName = $request->input('procedure');

    // Find the price for the selected procedure
    $procedurePrice = ProcedurePrice::where('procedure_name', $procedureName)->first();

    // Return the price in JSON format
    if ($procedurePrice) {
        return response()->json(['price' => $procedurePrice->price]);
    }

    return response()->json(['price' => null]); // In case no price is found
}
    

public function store(Request $request)
{
    $user_id = Auth::id();
    
    // ✅ CHECK 1: Prevent booking if user already has a pending or accepted appointment in database
    // User must wait for admin to accept/decline before booking another appointment
    $pendingAppointment = Appointment::where('user_id', $user_id)
        ->whereIn('status', ['pending', 'accepted'])
        ->first();
    
    if ($pendingAppointment) {
        Log::info('Blocked booking - user has pending appointment', [
            'user_id' => $user_id,
            'existing_appointment_id' => $pendingAppointment->id,
            'status' => $pendingAppointment->status,
            'procedure' => $pendingAppointment->procedure
        ]);
        
        $statusText = $pendingAppointment->status === 'accepted' 
            ? 'an accepted appointment' 
            : 'a pending appointment waiting for approval';
        
        return response()->json([
            'error' => "You already have {$statusText}. Please complete or cancel it before booking another appointment.",
            'existing_appointment' => [
                'id' => $pendingAppointment->id,
                'procedure' => $pendingAppointment->procedure,
                'start' => $pendingAppointment->start,
                'status' => $pendingAppointment->status
            ]
        ], 422);
    }
    
    // ✅ CHECK 2: REMOVED - Session check was causing issues with persistent database sessions
    // User can book multiple times and go to payment - database check above prevents duplicate confirmed appointments
    
    // Log the incoming request for debugging
    Log::info('Appointment Request Data:', [
        'has_payment_method' => $request->has('payment_method'),
        'payment_method' => $request->input('payment_method'),
        'has_total_price' => $request->has('total_price'),
        'total_price' => $request->input('total_price'),
        'has_down_payment' => $request->has('down_payment'),
        'down_payment' => $request->input('down_payment'),
        'all_data' => $request->except(['image_path'])
    ]);
    
    try {
        $validated = $request->validate([
            'title' => 'required|string',
            'procedure' => 'required|string',
            'time' => 'required|string',
            'start' => 'required|date',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            'payment_method' => 'required|string|in:gcash,paymaya,card',
            'total_price' => 'required|numeric|min:0.01',
            'down_payment' => 'required|numeric|min:0.01',
        ], [
            'payment_method.required' => 'Please select a payment method (GCash, PayMaya, or Card)',
            'total_price.required' => 'Total price is missing. Please refresh and select a procedure again.',
            'down_payment.required' => 'Down payment is missing. Please refresh and select a procedure again.',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Appointment Validation Failed:', [
            'errors' => $e->errors(),
            'request_data' => $request->except(['image_path'])
        ]);
        
        // Return more detailed error message
        $errors = $e->errors();
        $firstError = reset($errors)[0] ?? 'Validation failed';
        $totalErrors = count($errors);
        $errorMessage = $firstError;
        if ($totalErrors > 1) {
            $errorMessage .= " (and " . ($totalErrors - 1) . " more error" . ($totalErrors > 2 ? "s" : "") . ")";
        }
        
        return response()->json([
            'error' => $errorMessage,
            'errors' => $errors
        ], 422);
    }

    $startTime = Carbon::parse($validated['start']);
    $today = Carbon::today();
    $now = Carbon::now();

// ✅ Next-Day Booking System (T+1 to T+7)
// CANNOT book today - can ONLY book from tomorrow onwards up to 7 days
// Example: Today is Sunday Nov 16 → Can book Monday Nov 17 to Sunday Nov 23
$tomorrow = $today->copy()->addDay()->startOfDay();
$lastBookingDay = $today->copy()->addDays(7)->endOfDay();

// Ensure booking is NOT today (must be tomorrow or later)
if ($startTime->isSameDay($today)) {
    return response()->json([
        'error' => 'Cannot book appointments for today. Please select tomorrow (' . $tomorrow->format('M d, Y') . ') or later.'
    ], 422);
}

// Ensure booking starts from tomorrow onwards
if ($startTime < $tomorrow) {
    return response()->json([
        'error' => 'Appointments can only be booked from tomorrow onwards. Please select ' . $tomorrow->format('M d, Y') . ' or later.'
    ], 422);
}

// Ensure booking is within 7 days from today
if ($startTime > $lastBookingDay) {
    return response()->json([
        'error' => 'Appointments can only be booked up to 7 days in advance. Last available date: ' . $lastBookingDay->format('M d, Y') . '.'
    ], 422);
}

 // ✅ Fetch procedure duration from `procedure_prices` table
$procedure = ProcedurePrice::where('procedure_name', $validated['procedure'])->first();
$duration = $procedure ? (int) $procedure->duration : 30; // Convert to integer

// Calculate end time 
$endTime = $startTime->copy()->addMinutes($duration);


    // 🔥 FIX: Allow booking exactly at the end time
    $conflictingAppointments = Appointment::whereDate('start', $startTime->toDateString())
        ->where(function ($query) use ($startTime, $endTime) {
            $query->whereBetween('start', [$startTime, $endTime->copy()->subMinute()])
                  ->orWhere(function ($query) use ($startTime, $endTime) {
                      $query->where('start', '<', $startTime)
                            ->where('end', '>', $startTime);
                  });
        })
        ->exists();

    if ($conflictingAppointments) {
        return response()->json(['error' => 'Selected time slot is already booked!'], 422);
    }

    // Store image
    $image_path = null;
    if ($request->hasFile('image_path')) {
        $image = $request->file('image_path');
        $filename = time() . '_' . $image->getClientOriginalName();
        $path = $image->storeAs('valid_ids', $filename, 'public');
        $image_path = $path;
    }

    // Store appointment data in session instead of creating appointment immediately
    // This ensures appointment is only created AFTER successful payment
    $appointmentData = [
        'title' => $validated['title'],
        'procedure' => $validated['procedure'],
        'time' => $validated['time'],
        'start' => $startTime->toDateTimeString(),
        'end' => $endTime->toDateTimeString(),
        'duration' => $duration,
        'user_id' => $user_id,
        'image_path' => $image_path,
        'payment_method' => $validated['payment_method'],
        'total_price' => $validated['total_price'],
        'down_payment' => $validated['down_payment'],
    ];
    
    // Store in session with unique key
    $sessionKey = 'pending_appointment_' . $user_id . '_' . time();
    session([$sessionKey => $appointmentData]);
    session()->save(); // Force save to database immediately
    
    Log::info('Appointment data stored in session:', [
        'session_key' => $sessionKey,
        'data' => $appointmentData
    ]);

    // Log appointment initiation
    $this->logAppointmentActivity('initiated', null, [
        'procedure' => $validated['procedure'],
        'date' => $startTime->format('Y-m-d'),
        'time' => $validated['time'],
        'payment_method' => $validated['payment_method'],
        'total_price' => $validated['total_price'],
        'description' => 'Appointment booking initiated, pending payment',
    ]);

    // Generate PayMongo payment URL with session key
    $paymentUrl = route('payment.create') . '?session_key=' . $sessionKey;

    return response()->json([
        'success' => true,
        'message' => 'Redirecting to payment gateway...',
        'payment_url' => $paymentUrl,
        'redirect' => true,
    ]);
}

    

    public function update(Request $request, $id)
    {
        // Find the appointment by ID
        $appointment = Appointment::findOrFail($id);

        // Check if the authenticated user is the owner of the appointment
        if ($appointment->user_id != Auth::id()) {
            return response()->json(['message' => 'You can only edit your own appointments'], 403);
        }

        // Validate the updated data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'procedure' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date',
            'time' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Validate image upload
        ]);

        // Handle image upload if it exists
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('images', 'public'); // Store image in 'public/images'
            $validatedData['image_path'] = $image_path;
        }

        // Update the appointment
        $appointment->update($validatedData);

        // Log appointment update
        $this->logAppointmentActivity('updated', $appointment, [
            'updated_fields' => array_keys($validatedData),
            'description' => 'Appointment details updated',
        ]);

        // Return the updated appointment details to the front-end for calendar update
        return response()->json([
            'id' => $appointment->id,
            'title' => $appointment->title,
            'start' => $appointment->start,
            'end' => $appointment->end,
            'procedure' => $appointment->procedure,
            'user_id' => $appointment->user_id,
            'image_path' => $appointment->image_path // Include the updated image path
        ]);
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Ensure the authenticated user is the owner of the appointment
        if ($appointment->user_id != Auth::id()) {
            return response()->json(['message' => 'You can only delete your own appointments'], 403);
        }

        // Log appointment deletion before deleting
        $this->logAppointmentActivity('deleted', $appointment, [
            'description' => 'Appointment cancelled by user',
        ]);

        // Delete image if exists
        if ($appointment->image_path) {
            Storage::delete($appointment->image_path);
        }

        $appointment->delete();

        return response()->json(['success' => 'Appointment deleted successfully.']);
    }
    
    /**
     * Clear pending payment session for the authenticated user
     */
    public function clearPendingSession(Request $request)
    {
        $user_id = Auth::id();
        
        // Find and clear all pending sessions for this user
        $allSessions = session()->all();
        $clearedCount = 0;
        $clearedKeys = [];
        
        foreach ($allSessions as $key => $value) {
            // Only clear keys that match the exact pattern and are valid appointment data
            if (strpos($key, 'pending_appointment_' . $user_id . '_') === 0) {
                // Validate it's actually appointment data
                if (is_array($value) && isset($value['procedure'])) {
                    session()->forget($key);
                    $clearedCount++;
                    $clearedKeys[] = $key;
                    Log::info('Manually cleared pending session', [
                        'session_key' => $key, 
                        'user_id' => $user_id,
                        'procedure' => $value['procedure'] ?? 'unknown'
                    ]);
                }
            }
        }
        
        // Force save session to database
        session()->save();
        
        Log::info('Session clear summary', [
            'user_id' => $user_id,
            'cleared_count' => $clearedCount,
            'cleared_keys' => $clearedKeys
        ]);
        
        return response()->json([
            'success' => true,
            'message' => $clearedCount > 0 
                ? "Cleared $clearedCount pending payment session(s). You can now book a new appointment." 
                : 'No pending sessions found.',
            'cleared_count' => $clearedCount
        ]);
    }
}
