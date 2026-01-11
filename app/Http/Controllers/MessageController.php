<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\MongoMessage;
use App\Models\User;  // Import the User model
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Check if MongoDB is available
     */
    private function isMongoAvailable()
    {
        return extension_loaded('mongodb') && !empty(config('database.connections.mongodb.dsn'));
    }

    public function index()
    {
        if (!$this->isMongoAvailable()) {
            return view('messages.index', [
                'messages' => collect([]),
                'adminUser' => User::where('usertype', 'admin')->first() ?? User::find(1),
                'mongoUnavailable' => true
            ]);
        }

        if (Auth::check()) {
            $currentUserId = Auth::id();
            
            // Find the admin this patient has been talking to (last message sender/recipient who is admin)
            $lastAdminMessage = MongoMessage::where(function($query) use ($currentUserId) {
                $query->where('sender_id', $currentUserId)
                      ->orWhere('recipient_id', $currentUserId);
            })
            ->whereIn('sender_type', ['admin'])
            ->orWhere(function($query) use ($currentUserId) {
                $query->where('sender_id', $currentUserId)->where('sender_type', 'user');
            })
            ->orderBy('created_at', 'desc')
            ->first();
            
            // Determine which admin to talk to
            if ($lastAdminMessage) {
                // Find the admin from the last message
                $adminId = $lastAdminMessage->sender_type === 'admin' 
                    ? $lastAdminMessage->sender_id 
                    : $lastAdminMessage->recipient_id;
                $adminUser = User::find($adminId);
            }
            
            // Fallback: find any admin user
            if (!isset($adminUser) || !$adminUser) {
                $adminUser = User::where('usertype', 'admin')->first();
            }
            
            if (!$adminUser) {
                // Last fallback to user with ID 1
                $adminUser = User::find(1);
            }
            
            // Debug: Log the conversation participants
            \Log::info('Patient conversation initialized', [
                'patient_id' => Auth::id(),
                'admin_id' => $adminUser->id
            ]);
            
            // Fetch messages from MongoDB - conversation between patient and admin
            $messages = MongoMessage::conversation(Auth::id(), $adminUser->id)
                ->orderBy('created_at', 'asc')
                ->get();
            
            // Debug: Log the count
            \Log::info('Patient messages loaded', [
                'patient_id' => Auth::id(),
                'admin_id' => $adminUser->id,
                'message_count' => $messages->count(),
                'sample_messages' => $messages->take(2)->map(fn($m) => [
                    'sender' => $m->sender_id,
                    'recipient' => $m->recipient_id,
                    'message' => substr($m->message, 0, 20)
                ])
            ]);
            
            // Manually attach user data
            $currentUser = Auth::user();
            $messages = $messages->map(function($msg) use ($adminUser, $currentUser) {
                // Don't convert to array, work with model instance
                $msg->sender = $msg->sender_id == $currentUser->id ? $currentUser : $adminUser;
                $msg->recipient = $msg->recipient_id == $currentUser->id ? $currentUser : $adminUser;
                return $msg;
            });
            
            // Retrieve the logged-in user's details
            $selectedUser = Auth::user();  // Fetch the currently authenticated user (patient)
            
            // Pass messages, selected user, and admin user to the view
            return view('messages.index', compact('messages', 'selectedUser', 'adminUser'));
        } else {
            return redirect()->route('login')->with('error', 'Please log in to view your messages.');
        }
    }

    public function store(Request $request)
    {
        // Validate message input
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        // Store the message in the database
        Message::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'status' => 'unread'  // Ensure status is set when the message is created
        ]);

        return back()->with('success', 'Message sent successfully.');
    }

    // Send a reply
    public function reply(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        // Save the admin's reply
        Message::create([
            'user_id' => $request->user_id,
            'message' => $request->message,
            'is_admin' => true,
            'status' => 'unread',
        ]);

        return redirect()->back()->with('success', 'Reply sent successfully!');
    }

 

public function unreadMessagesCount()
{
    $userId = auth::id(); // Get the logged-in user's ID

    $count = DB::table('messages')
        ->where('status', 'unread')  // Count only unread messages
        ->where('is_admin', 1)       // Only messages sent by the admin
        ->where('user_id', $userId) // Include only messages received by the logged-in user
        ->count();

    return response()->json(['count' => $count]);
}


public function markMessagesAsRead()
{
    Message::where('user_id', Auth::id())
        ->where('status', 'unread')
        ->update(['status' => 'read']);

    return response()->json(['success' => true]);
}

public function getUnreadMessagesCount()
{
    $unreadMessagesCount = DB::table('messages')
        ->where('is_admin', 0) // Messages from patients
        ->where('status', 'unread') // Only count unread messages
        ->count();

    return response()->json(['count' => $unreadMessagesCount]);
}

public function markMessagesAsReadAdmin()
{
    DB::table('messages')
        ->where('is_admin', 0) // Messages from patients
        ->where('status', 'unread')
        ->update(['status' => 'read']);

    return response()->json(['success' => true]);
}


}
