<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MongoMessage;
use App\Models\User;
use App\Events\NewMessage;
use App\Events\MessageRead;

class MongoMessageController extends Controller
{
    /**
     * Show admin real-time messages view
     */
    public function adminIndex(Request $request)
    {
        $selectedUserId = $request->get('user_id');
        $selectedUser = null;
        
        if ($selectedUserId) {
            $selectedUser = User::find($selectedUserId);
        }
        
        // Get all patients (non-admin users)
        $users = User::where('is_admin', 0)->get();
        
        return view('admin.patient_messages_realtime', compact('selectedUser', 'users'));
    }
    
    /**
     * Show patient real-time messages view
     */
    public function patientIndex()
    {
        return view('messages.index_realtime');
    }

    /**
     * Send a new message
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'recipient_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();

        // Create message in MongoDB
        $message = MongoMessage::create([
            'sender_id' => $user->id,
            'recipient_id' => $request->recipient_id,
            'message' => $request->message,
            'sender_type' => $user->is_admin ? 'admin' : 'user',
            'is_read' => false,
            'attachments' => $request->attachments ?? [],
        ]);

        // Broadcast the message via Pusher
        broadcast(new NewMessage($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message->load(['sender', 'recipient']),
        ]);
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'required|string',
        ]);

        $user = Auth::user();

        foreach ($request->message_ids as $messageId) {
            $message = MongoMessage::find($messageId);
            
            if ($message && $message->recipient_id == $user->id) {
                $message->markAsRead();
                
                // Broadcast read receipt
                broadcast(new MessageRead($messageId, $message->sender_id))->toOthers();
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get unread message count
     */
    public function unreadCount()
    {
        $user = Auth::user();
        
        $count = MongoMessage::where('recipient_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get all messages for a conversation (AJAX)
     */
    public function getMessages(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        
        $messages = MongoMessage::conversation($user->id, $request->user_id)
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * Get list of users for admin
     */
    public function getUserList()
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }

        $users = User::where('is_admin', 0)
            ->withCount(['mongoMessages as unread_count' => function ($query) {
                $query->where('recipient_id', Auth::id())
                      ->where('is_read', false);
            }])
            ->orderByDesc('unread_count')
            ->get();

        return response()->json([
            'success' => true,
            'users' => $users,
        ]);
    }
}
