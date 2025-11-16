<?php
// filepath: /c:/Users/grafr/RMDC/app/Http/Controllers/AdminAppointment.php
namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Appointment;
use App\Models\DeclinedAppointment;
use App\Models\Message;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\AppointmentStatusChanged;
use App\Traits\LogsActivity;

class AdminAppointment extends Controller
{
    use LogsActivity;


    public function handleAction(Request $request, $id, $action)
{
    // Find the appointment by its ID
    $appointment = Appointment::findOrFail($id);

    if ($action == 'decline') {
    $request->validate([
        'message' => 'required|string|max:255' // Admin's reason
    ]);

    $dateTime = \Carbon\Carbon::parse($appointment->start)->format('F j, Y \a\t g:i A');
    $reason = $request->message;

$autoMessage = " for <strong regret to inform you that your appointment scheduledtrong>{$dateTime}</strong> has been declined due to <strong>{$reason}</strong>. Thank you for your understanding. You may reschedule your appointment at your convenience.";

// Save the auto-generated message
Message::create([
    'user_id' => $appointment->user_id,
    'message' => $autoMessage,
    'is_admin' => true,
    'status' => 'unread'
]);

// Save decline record
DeclinedAppointment::create([
    'appointment_id' => $appointment->id,
    'user_id' => $appointment->user_id,
    'decline_reason' => $reason,
]);

// Update appointment status
$appointment->status = 'declined';
$appointment->start = '2003-04-28 23:59';
$appointment->end = '2003-04-28 23:59';
$appointment->save();

// Log appointment decline
$this->logAppointmentActivity('declined', $appointment, [
    'declined_by' => Auth::user()->name ?? 'Admin',
    'reason' => $reason,
    'description' => 'Appointment declined by admin',
]);

// Optional notification
Notification::create([
    'user_id' => $appointment->user_id,
    'message' => "Your appointment has been declined. You may reschedule your appointment."
]);

broadcast(new AppointmentStatusChanged($appointment));

return redirect()->back()->with('success', 'Appointment declined successfully and message sent.');
}


    // Handle other actions (like accept)
    if ($action === 'accept') {
        $appointment->status = 'accepted';
        $message = "Your appointment has been accepted.";
    } else {
        return redirect()->back()->with('error', 'Invalid action.');
    }

    // Save the updated appointment status (if needed)
    $appointment->save();

    // Log appointment acceptance
    $this->logAppointmentActivity('accepted', $appointment, [
        'accepted_by' => Auth::user()->name ?? 'Admin',
        'description' => 'Appointment accepted by admin',
    ]);

    // Create a notification for the user
    Notification::create([
        'user_id' => $appointment->user_id,
        'message' => $message,
    ]);

    // Broadcast the status change (optional)
    broadcast(new AppointmentStatusChanged($appointment));

    // Return a success message
    return redirect()->back()->with('success', "Appointment has been {$action}ed.");
}





    public function markNotificationsAsRead(Request $request)
    {
        Notification::where('user_id', Auth::id())
                    ->where('status', 'unread')
                    ->update(['status' => 'read']);

        return response()->json(['success' => true]);
    }

    public function fetchNotifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->where('status', 'unread') // Ensure it only counts unread notifications
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('status', 'unread')
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount // Send unread count
        ]);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        return response()->json(['message' => 'All notifications marked as read']);
    }


public function unreadNotificationCount()
{
    $unreadCount = Notification::where('user_id', Auth::id())
        ->where('status', 'unread')
        ->count();

    return response()->json(['unreadCount' => $unreadCount]);
}

public function getUnreadCount()
{
    $unreadCount = Notification::where('user_id', Auth::id())
        ->where('status', 'unread') // Check status column instead of read_at
        ->count();

    return response()->json(['unreadCount' => $unreadCount]);
}

public function declinedAppointments(Request $request)
{
    $query = Appointment::join('messages', 'appointments.user_id', '=', 'messages.user_id') // Join with messages table
        ->join('users', 'appointments.user_id', '=', 'users.id') // Join with users table to get patient name
        ->where('appointments.status', 'declined') // Explicitly reference appointments.status
        ->where('messages.is_admin', true) // Ensure the message is from the admin
        ->select(
            'appointments.user_id',
            'users.name as patient_name', // Fetch patient name from users table
            'appointments.title',
            'appointments.procedure',
            'messages.message as decline_reason', // Fetch the decline reason from messages
            'appointments.start',
            'appointments.end',
            'appointments.created_at',
            'appointments.updated_at'
        );

    // Apply search filter if provided
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('users.name', 'like', "%$search%")
              ->orWhere('appointments.title', 'like', "%$search%")
              ->orWhere('appointments.procedure', 'like', "%$search%");
        });
    }

    // Apply time-based filter (today, this week, this month)
    if ($request->has('filter')) {
        switch ($request->filter) {
            case 'today':
                $query->whereDate('appointments.updated_at', today());
                break;
            case 'week':
                $query->whereBetween('appointments.updated_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
                break;
            case 'month':
                $query->whereMonth('appointments.updated_at', now()->month)
                      ->whereYear('appointments.updated_at', now()->year);
                break;
        }
    }

    // Apply specific date filter if provided
    if ($request->has('date') && $request->date != '') {
        $query->whereDate('appointments.updated_at', $request->date);
    }

    // Sort by most recent declined first
    $query->orderBy('appointments.updated_at', 'desc');

    // Paginate the results (20 items per page)
    $declinedAppointments = $query->paginate(20);

    return view('admin.declined_appointments', compact('declinedAppointments'));
}

public function deleteAllDeclined() {
    Appointment::where('status', 'declined')->delete();
    DeclinedAppointment::truncate(); // Delete all from the table
    return redirect()->back()->with('success', 'All declined appointments deleted.');
}


public function messageFromAdmin(Request $request, $id, $action)
{
    $appointment = Appointment::findOrFail($id); // Find the appointment by ID

    if ($action == 'decline') {
        $request->validate([
            'message' => 'required|string|max:255' // Validate the reason for declining
        ]);

        // Format the date and time
$dateTime = \Carbon\Carbon::parse($appointment->start)->format('F j, Y \a\t g:i A');

// Auto-generate the full message
$autoMessage = "We regret to inform you that your appointment scheduled for {$dateTime} has been declined due to {$request->message}. You may reschedule your appointment at your convenience. Thank you for your understanding.";

// Save the full message to messages table
Message::create([
    'user_id' => $appointment->user_id,
    'message' => $autoMessage,
    'is_admin' => true,
    'status' => 'unread'
]);


        // Create a record in the declined_appointments table
        DeclinedAppointment::create([
            'appointment_id' => $appointment->id,
            'user_id' => $appointment->user_id,
            'decline_reason' => $request->message, // Using message as decline reason
        ]);

        // Update the appointment status to "declined" and adjust the times
        $appointment->status = 'declined';
        $appointment->start = '2003-04-28 23:59'; // Set a default end time (if necessary)
        $appointment->end = '2003-04-28 23:59';
        $appointment->save(); // Save changes

        // Log appointment decline
        $this->logAppointmentActivity('declined', $appointment, [
            'declined_by' => Auth::user()->name ?? 'Admin',
            'reason' => $request->message,
            'description' => 'Appointment declined by admin with message',
        ]);

        // Create a notification for the user (optional)
        Notification::create([
            'user_id' => $appointment->user_id,
            'message' => "Your appointment has been declined."
        ]);

        // Broadcast the status change (optional)
        broadcast(new AppointmentStatusChanged($appointment));

        // Change from JSON response to redirect
        return redirect()->back()->with('success', 'Appointment declined successfully and message sent.');
    }

    if ($action === 'accept') {
        $appointment->status = 'accepted';
        $appointment->save();

        // Log appointment acceptance
        $this->logAppointmentActivity('accepted', $appointment, [
            'accepted_by' => Auth::user()->name ?? 'Admin',
            'description' => 'Appointment accepted by admin',
        ]);

        // Create a notification for the user
        Notification::create([
            'user_id' => $appointment->user_id,
            'message' => "Your appointment has been accepted."
        ]);

        // Broadcast the status change (optional)
        broadcast(new AppointmentStatusChanged($appointment));

        // Redirect back after success
        return redirect()->back()->with('success', 'Appointment accepted successfully.');
    }

    return redirect()->back()->with('error', 'Invalid action.');
}

    /**
     * Get appointment details for modal display
     */
    public function getAppointmentDetails($id)
    {
        try {
            $appointment = Appointment::with('user')->findOrFail($id);
            
            return response()->json($appointment);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Appointment not found'
            ], 404);
        }
    }

    /**
     * Mark appointment as completed and automatically deduct inventory
     */
    public function completeAppointment($id)
    {
        try {
            DB::beginTransaction();
            
            Log::info("=== Starting completeAppointment for ID: {$id} ===");
            
            $appointment = Appointment::findOrFail($id);
            Log::info("Appointment found - Procedure: {$appointment->procedure}, Status: {$appointment->status}");
            
            // Check if already completed
            if ($appointment->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'This appointment is already marked as completed.'
                ]);
            }
            
            // Find the procedure
            $procedure = \App\Models\ProcedurePrice::where('procedure_name', $appointment->procedure)->first();
            Log::info("Searching for procedure with name: " . $appointment->procedure);
            
            if (!$procedure) {
                DB::rollBack();
                Log::error("Procedure not found in database");
                return response()->json([
                    'success' => false,
                    'message' => 'Procedure not found. Cannot deduct inventory.'
                ], 404);
            }
            
            Log::info("Procedure found - ID: {$procedure->id}, Name: {$procedure->procedure_name}");
            
            // Get linked inventory items
            $supplies = $procedure->procedureInventories()->with('inventory')->get();
            Log::info("Found {$supplies->count()} supply items linked to procedure");
            
            if ($supplies->isEmpty()) {
                // No supplies linked, just mark as completed
                $appointment->status = 'completed';
                $appointment->save();
                
                // Log activity
                $this->logAppointmentActivity('completed', $appointment, [
                    'completed_by' => Auth::user()->name ?? 'Admin',
                    'description' => 'Appointment completed (no supplies linked)',
                ]);
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Appointment marked as completed. No inventory items were linked to this procedure.'
                ]);
            }
            
            $deductedItems = [];
            $insufficientStock = [];
            
            // Process each supply item
            foreach ($supplies as $supply) {
                $inventory = $supply->inventory;
                $quantityNeeded = $supply->quantity_used; // In pieces
                
                Log::info("Processing: {$inventory->name}, Unit: {$inventory->unit}, Current Qty: {$inventory->quantity}, Needed: {$quantityNeeded}");
                
                // Calculate how many units to deduct
                if ($inventory->unit === 'Pieces') {
                    // Direct deduction
                    if ($inventory->quantity < $quantityNeeded) {
                        $insufficientStock[] = "{$inventory->name} (Need: {$quantityNeeded} pieces, Available: {$inventory->quantity})";
                        Log::warning("Insufficient stock for {$inventory->name}");
                        continue;
                    }
                    $inventory->quantity -= $quantityNeeded;
                    Log::info("Deducted {$quantityNeeded} pieces. New quantity: {$inventory->quantity}");
                } else {
                    // Convert pieces to units (Box, Bottle, etc.)
                    $itemsPerUnit = $inventory->items_per_unit ?? 1;
                    $unitsNeeded = $quantityNeeded / $itemsPerUnit;
                    
                    Log::info("Converting: {$quantityNeeded} pieces / {$itemsPerUnit} items_per_unit = {$unitsNeeded} {$inventory->unit}");
                    
                    if ($inventory->quantity < $unitsNeeded) {
                        $insufficientStock[] = "{$inventory->name} (Need: " . number_format($unitsNeeded, 2) . " {$inventory->unit}, Available: {$inventory->quantity})";
                        Log::warning("Insufficient stock for {$inventory->name}");
                        continue;
                    }
                    $inventory->quantity -= $unitsNeeded;
                    Log::info("Deducted {$unitsNeeded} {$inventory->unit}. New quantity: {$inventory->quantity}");
                }
                
                $inventory->save();
                Log::info("Saved inventory ID: {$inventory->id}");
                $deductedItems[] = "{$inventory->name}: {$quantityNeeded} pieces";
            }
            
            // Check if there were insufficient stock items
            if (!empty($insufficientStock)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock for:\n" . implode("\n", $insufficientStock)
                ]);
            }
            
            // Update appointment status
            $appointment->status = 'completed';
            $appointment->save();
            
            // Log activity
            $this->logAppointmentActivity('completed', $appointment, [
                'completed_by' => Auth::user()->name ?? 'Admin',
                'description' => 'Appointment completed with inventory deduction',
                'deducted_items' => $deductedItems,
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Appointment completed successfully!\n\nInventory deducted:\n" . implode("\n", $deductedItems)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error completing appointment: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while completing the appointment: ' . $e->getMessage()
            ], 500);
        }
    }

}
