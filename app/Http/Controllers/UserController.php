<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcedurePrice;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Notifications\AppointmentStatusUpdatedNotification;

class UserController extends Controller
{
    public function index(Request $request, $id = null, $action = null)
{
    $start = Appointment::select('start')->get();
    $time = Appointment::select('time')->get();
    $end = Appointment::select('end')->get();
    $procedurePrices = ProcedurePrice::all(); // Fetch all procedure prices
    $userId = Auth::id();
    
    // Get unread notifications (limit to latest 1)
    $unreadNotifications = Notification::where('user_id', $userId)
        ->where('status', 'unread')
        ->latest()
        ->first();  // Get the most recent unread notification
    
    // Get all notifications
    $allNotifications = Notification::where('user_id', $userId)
        ->latest()
        ->get();

    // Check if an appointment ID and action are passed
    if ($id && $action) {
        // Find the appointment
        $appointment = Appointment::findOrFail($id);
        
        // Handle appointment action (accept/decline)
        if ($action === 'accept') {
            $appointment->status = 'accepted';
            $message = "Appointment Title named {$appointment->procedure} has been accepted.";
            $actionType = 'accepted';
        } elseif ($action === 'decline') {
            $appointment->status = 'declined';
            $message = "Appointment Title named {$appointment->procedure} has been declined.";
            $actionType = 'declined';
        } else {
            return redirect()->back()->with('error', 'Invalid action.');
        }
        
        // Save updated appointment status
        $appointment->save();
        
        // Create a notification for the user
        Notification::create([
            'user_id' => $appointment->user_id,
            'message' => $message,
        ]);
        
        // Send email notification to the patient
        try {
            $appointment->user->notify(new AppointmentStatusUpdatedNotification($appointment, $actionType));
        } catch (\Exception $e) {
            Log::error('Failed to send appointment status email: ' . $e->getMessage());
        }
        
        // Redirect back with the success message
        return redirect()->back()->with('success', $message);
    }

    // Return the dashboard view and pass data
    return view('dashboard', [
        'unreadNotifications' => $unreadNotifications,
        'allNotifications' => $allNotifications,
        'appointments' => Appointment::where('user_id', $userId)->latest()->first(), // Get the most recent appointment
        'procedurePrices' => $procedurePrices,
        'start' => $start,
        'time' => $time,
        'end' => $end
    ]);
}

public function getProcedureDetails(Request $request)
{
    $procedureName = $request->procedure;

    // Fetch the procedure details
    $procedure = ProcedurePrice::whereRaw('LOWER(procedure_name) = ?', [strtolower($procedureName)])->first();


    // If procedure is not found, return default values instead of "N/A"
    return response()->json([
        'duration' => $procedure ? $procedure->duration . ' minutes' : '0 minutes',
        'price' => $procedure ? '₱' . number_format($procedure->price, 2) : '₱0.00'
    ]);
}

public function showAppointments()
{
    $user = auth::user(); // Get authenticated user

    return view('dashboard', compact('user'));
}

public function getAdminDetails()
{
    $admin = User::where('usertype', 'admin')->first();

    if (!$admin) {
        return response()->json(['error' => 'Admin not found'], 404);
    }

    return response()->json([
        'name' => $admin->name,
        'email' => $admin->email
    ]);
}
}