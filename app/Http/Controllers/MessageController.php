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
    public function index()
    {
        if (Auth::check()) {
            // Find first admin user (usertype = 'admin')
            $adminUser = User::where('usertype', 'admin')->first();
            
            if (!$adminUser) {
                // Fallback to user with ID 1 if no admin found
                $adminUser = User::find(1);
            }
            
            // Fetch messages from MongoDB
            $messages = MongoMessage::conversation(Auth::id(), $adminUser->id)
                ->with(['sender', 'recipient'])
                ->orderBy('created_at', 'asc')
                ->get();
            
            // Retrieve the logged-in user's details
            $selectedUser = Auth::user();  // Fetch the currently authenticated user (patient)
            
            // Pass both messages and the selected user to the view
            return view('messages.index', compact('messages', 'selectedUser'));
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
