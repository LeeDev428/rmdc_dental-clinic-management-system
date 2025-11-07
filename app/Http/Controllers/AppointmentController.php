<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ProcedurePrice;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AppointmentController extends Controller

{
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
    
    // ✅ CHECK: Prevent booking if user already has a pending or unpaid appointment
    $hasActiveAppointment = Appointment::where('user_id', $user_id)
        ->whereIn('status', ['pending', 'unpaid'])
        ->where('created_at', '>', now()->subHours(2)) // Only check appointments within last 2 hours
        ->exists();
    
    if ($hasActiveAppointment) {
        return response()->json([
            'error' => 'You already have an active appointment. Please complete or cancel it before booking another one.'
        ], 422);
    }
    
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
$startOfWeek = $today->copy()->startOfWeek(); // Monday
$endOfWeek = $today->copy()->endOfWeek(); // Sunday

if ($today->isSunday()) {
    // If today is Sunday, allow only Monday
    $allowedStart = $today->copy()->addDay(); // Monday
    $allowedEnd = $allowedStart; // Only Monday
} else {
    // If today is Monday-Saturday, allow booking from tomorrow to Sunday
    $allowedStart = $today->copy()->addDay(); // Tomorrow
    $allowedEnd = $endOfWeek; // Sunday
}

// Ensure the selected start time is within the allowed range
if ($startTime < $allowedStart || $startTime > $allowedEnd) {
    return response()->json(['error' => 'Invalid booking date. Please follow the allowed schedule.'], 422);
}

// ✅ New Rule: Ensure booking is at least 4 hours ahead of the present time
$minimumBookingTime = $now->copy()->addHours(4);
if ($startTime < $minimumBookingTime) {
    return response()->json(['error' => 'Appointments must be scheduled at least 4 hours in advance.'], 422);
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

    // Create appointment with payment details
    // Status is 'unpaid' until payment is completed, then changes to 'pending' for admin approval
    $appointment = Appointment::create([
        'title' => $validated['title'],
        'procedure' => $validated['procedure'],
        'time' => $validated['time'],
        'start' => $startTime,
        'end' => $endTime,
        'duration' => $duration,
        'user_id' => $user_id,
        'image_path' => $image_path,
        'payment_method' => $validated['payment_method'] ?? null,
        'total_price' => $validated['total_price'] ?? 0,
        'down_payment' => $validated['down_payment'] ?? 0,
        'payment_status' => 'unpaid', // Set to unpaid initially
        'status' => 'unpaid', // Set appointment status to unpaid until payment confirmed
    ]);

    // Generate PayMongo payment URL
    $paymentUrl = null;
    if ($validated['payment_method']) {
        $paymentUrl = route('payment.create', ['appointment' => $appointment->id]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Appointment created successfully! Redirecting to payment...',
        'appointment' => [
            'id' => $appointment->id,
            'title' => $appointment->title,
            'start' => $appointment->start,
            'end' => $appointment->end,
            'procedure' => $appointment->procedure,
            'duration' => $appointment->duration,
            'user_id' => $appointment->user_id,
            'image_path' => $appointment->image_path ? Storage::url($appointment->image_path) : null,
            'payment_method' => $appointment->payment_method,
            'total_price' => $appointment->total_price,
            'down_payment' => $appointment->down_payment,
        ],
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

        // Delete image if exists
        if ($appointment->image_path) {
            Storage::delete($appointment->image_path);
        }

        $appointment->delete();

        return response()->json(['success' => 'Appointment deleted successfully.']);
    }
}
