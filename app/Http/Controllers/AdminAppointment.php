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
use Illuminate\Support\Facades\Mail;
use App\Events\AppointmentStatusChanged;
use App\Traits\LogsActivity;
use App\Mail\AppointmentStatusUpdated;

class AdminAppointment extends Controller
{
    use LogsActivity;


    public function handleAction(Request $request, $id, $action)
{
    try {
        // Find the appointment by its ID
        $appointment = Appointment::findOrFail($id);

        if ($action == 'decline') {
            $request->validate([
                'message' => 'required|string|max:255' // Admin's reason
            ]);

            $dateTime = \Carbon\Carbon::parse($appointment->start)->format('F j, Y \a\t g:i A');
            $reason = $request->message;

            $autoMessage = "We regret to inform you that your appointment scheduled for <strong>{$dateTime}</strong> has been declined due to <strong>{$reason}</strong>. Thank you for your understanding. You may reschedule your appointment at your convenience.";

            // Try to save auto-generated message (might fail if using MongoDB for messages)
            try {
                Message::create([
                    'user_id' => $appointment->user_id,
                    'message' => $autoMessage,
                    'is_admin' => true,
                    'status' => 'unread'
                ]);
            } catch (\Exception $e) {
                Log::warning('Could not save message to messages table: ' . $e->getMessage());
            }

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
            try {
                Notification::create([
                    'user_id' => $appointment->user_id,
                    'message' => "Your appointment has been declined. You may reschedule your appointment."
                ]);
            } catch (\Exception $e) {
                Log::warning('Could not create notification: ' . $e->getMessage());
            }

            // Load user relationship for email
            $appointment = $appointment->fresh('user');

            // Send email notification
            Log::info('About to send decline email', [
                'appointment_id' => $appointment->id,
                'user_id' => $appointment->user_id,
                'user_email' => $appointment->user ? $appointment->user->email : 'USER IS NULL'
            ]);

            try {
                if (!$appointment->user) {
                    throw new \Exception('User relationship is null');
                }
                Mail::to($appointment->user->email)->send(new AppointmentStatusUpdated($appointment, 'declined'));
                Log::info('Decline email sent successfully', ['appointment_id' => $appointment->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send decline email: ' . $e->getMessage(), [
                    'appointment_id' => $appointment->id,
                    'trace' => $e->getTraceAsString()
                ]);
            }

            try {
                broadcast(new AppointmentStatusChanged($appointment));
            } catch (\Exception $e) {
                Log::warning('Failed to broadcast appointment status change: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Appointment declined successfully and message sent.'
            ]);
        }

        // Handle accept action
        if ($action === 'accept') {
            $appointment->status = 'accepted';
            $appointment->save();

            // Log appointment acceptance
            $this->logAppointmentActivity('accepted', $appointment, [
                'accepted_by' => Auth::user()->name ?? 'Admin',
                'description' => 'Appointment accepted by admin',
            ]);

            // Create a notification for the user
            try {
                Notification::create([
                    'user_id' => $appointment->user_id,
                    'message' => "Your appointment has been accepted.",
                ]);
            } catch (\Exception $e) {
                Log::warning('Could not create notification: ' . $e->getMessage());
            }

            // Load user relationship for email
            $appointment = $appointment->fresh('user');

            // Send email notification
            Log::info('About to send acceptance email', [
                'appointment_id' => $appointment->id,
                'user_id' => $appointment->user_id,
                'user_email' => $appointment->user ? $appointment->user->email : 'USER IS NULL'
            ]);
            
            try {
                if (!$appointment->user) {
                    throw new \Exception('User relationship is null');
                }
                Mail::to($appointment->user->email)->send(new AppointmentStatusUpdated($appointment, 'accepted'));
                Log::info('Acceptance email sent successfully', ['appointment_id' => $appointment->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send acceptance email: ' . $e->getMessage(), [
                    'appointment_id' => $appointment->id,
                    'trace' => $e->getTraceAsString()
                ]);
            }

            // Broadcast the status change (optional)
            try {
                broadcast(new AppointmentStatusChanged($appointment));
            } catch (\Exception $e) {
                Log::warning('Failed to broadcast appointment status change: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Appointment has been accepted.'
            ]);
        }

        // Invalid action
        return response()->json([
            'success' => false,
            'message' => 'Invalid action.'
        ], 400);

    } catch (\Exception $e) {
        Log::error('Error in handleAction: ' . $e->getMessage(), [
            'appointment_id' => $id,
            'action' => $action,
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while processing the appointment.'
        ], 500);
    }
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

        // Load user relationship for email
        $appointment = $appointment->fresh('user');

        // Send email notification
        Log::info('About to send decline email from messageFromAdmin', [
            'appointment_id' => $appointment->id,
            'user_id' => $appointment->user_id,
            'user_email' => $appointment->user ? $appointment->user->email : 'USER IS NULL'
        ]);

        try {
            if (!$appointment->user) {
                throw new \Exception('User relationship is null');
            }
            Mail::to($appointment->user->email)->send(new AppointmentStatusUpdated($appointment, 'declined'));
            Log::info('Decline email sent successfully from messageFromAdmin', ['appointment_id' => $appointment->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send decline email from messageFromAdmin: ' . $e->getMessage(), [
                'appointment_id' => $appointment->id,
                'trace' => $e->getTraceAsString()
            ]);
        }

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

        // Load user relationship for email
        $appointment = $appointment->fresh('user');

        // Send email notification
        Log::info('About to send acceptance email from messageFromAdmin', [
            'appointment_id' => $appointment->id,
            'user_id' => $appointment->user_id,
            'user_email' => $appointment->user ? $appointment->user->email : 'USER IS NULL'
        ]);

        try {
            if (!$appointment->user) {
                throw new \Exception('User relationship is null');
            }
            Mail::to($appointment->user->email)->send(new AppointmentStatusUpdated($appointment, 'accepted'));
            Log::info('Acceptance email sent successfully from messageFromAdmin', ['appointment_id' => $appointment->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send acceptance email from messageFromAdmin: ' . $e->getMessage(), [
                'appointment_id' => $appointment->id,
                'trace' => $e->getTraceAsString()
            ]);
        }

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
                $piecesNeeded = floatval($supply->quantity_used); // Always in pieces
                
                Log::info("Processing: {$inventory->name}, Unit: {$inventory->unit}, Needed: {$piecesNeeded} pieces");
                
                // NEW INVENTORY TRACKING SYSTEM:
                // - quantity = number of FULL/UNOPENED boxes
                // - current_box_pieces = pieces remaining in the CURRENTLY OPEN box
                // - original_items_per_unit = pieces in a FRESH box (constant, never changes)
                
                $fullBoxes = floatval($inventory->quantity);
                $openBoxPieces = floatval($inventory->current_box_pieces);
                $piecesPerFreshBox = floatval($inventory->original_items_per_unit ?? 1);
                
                // Calculate total available pieces
                $totalAvailable = ($fullBoxes * $piecesPerFreshBox) + $openBoxPieces;
                
                Log::info("Available: {$fullBoxes} full boxes + {$openBoxPieces} pieces in open box = {$totalAvailable} total pieces");
                
                // Check if we have enough
                if ($piecesNeeded > $totalAvailable) {
                    $insufficientStock[] = "{$inventory->name} (Need: {$piecesNeeded} pieces, Available: {$totalAvailable} pieces)";
                    Log::warning("Insufficient stock for {$inventory->name}");
                    continue;
                }
                
                // DEDUCTION LOGIC
                $remaining = $piecesNeeded;
                
                // Step 1: Try to fulfill from current open box first
                if ($openBoxPieces >= $remaining) {
                    // Simple case: deduct from open box only
                    $inventory->current_box_pieces = $openBoxPieces - $remaining;
                    Log::info("Deducted {$remaining} pieces from open box. Remaining in open box: {$inventory->current_box_pieces}");
                } else {
                    // Need more than what's in open box
                    Log::info("Using all {$openBoxPieces} pieces from open box");
                    $remaining -= $openBoxPieces;
                    $inventory->current_box_pieces = 0;
                    
                    // Step 2: Calculate how many full boxes we need
                    $fullBoxesNeeded = floor($remaining / $piecesPerFreshBox);
                    $piecesFromLastBox = $remaining % $piecesPerFreshBox;
                    
                    Log::info("Need {$fullBoxesNeeded} full boxes + {$piecesFromLastBox} pieces from another box");
                    
                    // Deduct full boxes
                    $inventory->quantity -= $fullBoxesNeeded;
                    
                    // Handle the last partial box
                    if ($piecesFromLastBox > 0) {
                        // Open one more box and take some pieces
                        $inventory->quantity -= 1;
                        $inventory->current_box_pieces = $piecesPerFreshBox - $piecesFromLastBox;
                        Log::info("Opened 1 more box, used {$piecesFromLastBox} pieces, {$inventory->current_box_pieces} pieces left in this box");
                    } else {
                        // Used exact boxes, reset open box to full
                        $inventory->current_box_pieces = $piecesPerFreshBox;
                        Log::info("Used exact boxes, new open box has {$inventory->current_box_pieces} pieces");
                    }
                }
                
                Log::info("Final: {$inventory->quantity} full boxes + {$inventory->current_box_pieces} pieces in open box");
                
                $inventory->save();
                $deductedItems[] = "{$inventory->name}: {$piecesNeeded} pieces (Now: {$inventory->quantity} {$inventory->unit} + {$inventory->current_box_pieces} pieces open)";
            }
            
            // Check if there were insufficient stock items
            if (!empty($insufficientStock)) {
                DB::rollBack();
                Log::warning("Transaction rolled back due to insufficient stock");
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock for:\n" . implode("\n", $insufficientStock)
                ]);
            }
            
            // Update appointment status
            $appointment->status = 'completed';
            $appointment->save();
            Log::info("Appointment status updated to completed");
            
            // Log activity
            $this->logAppointmentActivity('completed', $appointment, [
                'completed_by' => Auth::user()->name ?? 'Admin',
                'description' => 'Appointment completed with inventory deduction',
                'deducted_items' => $deductedItems,
            ]);
            
            DB::commit();
            Log::info("Transaction committed successfully. Deducted items: " . implode(", ", $deductedItems));
            
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
