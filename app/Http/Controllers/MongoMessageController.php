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
        $users = User::where('usertype', '!=', 'admin')->get();
        
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

        // Create message in MongoDB with proper timezone
        $message = MongoMessage::create([
            'sender_id' => $user->id,
            'recipient_id' => $request->recipient_id,
            'message' => $request->message,
            'sender_type' => $user->usertype === 'admin' ? 'admin' : 'user',
            'is_read' => false,
            'attachments' => $request->attachments ?? [],
            'created_at' => new \MongoDB\BSON\UTCDateTime(now()->timestamp * 1000),
        ]);

        // Manually attach user data (can't use Eloquent relationships across databases)
        $recipient = User::find($request->recipient_id);
        $messageData = $message->toArray();
        $messageData['sender'] = [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'avatar_url' => $user->avatar_url,
        ];
        $messageData['recipient'] = [
            'id' => $recipient->id,
            'name' => $recipient->name,
            'avatar' => $recipient->avatar,
            'avatar_url' => $recipient->avatar_url,
        ];
        
        // Format created_at for JavaScript
        if (isset($messageData['created_at'])) {
            if ($messageData['created_at'] instanceof \MongoDB\BSON\UTCDateTime) {
                $messageData['created_at'] = $messageData['created_at']->toDateTime()->format('Y-m-d H:i:s');
            }
        }

        // Broadcast the message via Pusher
        broadcast(new NewMessage($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $messageData,
        ]);
    }

    /**
     * Broadcast typing status
     */
    public function typing(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'typing' => 'required|boolean',
        ]);

        $user = Auth::user();

        // Broadcast typing status to the recipient
        broadcast(new \App\Events\UserTyping([
            'sender_id' => $user->id,
            'recipient_id' => $request->recipient_id,
            'typing' => $request->typing,
        ]))->toOthers();

        return response()->json(['success' => true]);
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

        // Get updated unread count
        $unreadCount = MongoMessage::where('recipient_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount
        ]);
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
            ->orderBy('created_at', 'asc')
            ->get();

        // Manually attach user data for each message
        $userIds = $messages->pluck('sender_id')->merge($messages->pluck('recipient_id'))->unique();
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');
        
        $messages = $messages->map(function($message) use ($users) {
            $messageArray = $message->toArray();
            
            $sender = $users->get($message->sender_id);
            $recipient = $users->get($message->recipient_id);
            
            $messageArray['sender'] = $sender ? [
                'id' => $sender->id,
                'name' => $sender->name,
                'avatar' => $sender->avatar,
                'avatar_url' => $sender->avatar_url
            ] : null;
            
            $messageArray['recipient'] = $recipient ? [
                'id' => $recipient->id,
                'name' => $recipient->name,
                'avatar' => $recipient->avatar,
                'avatar_url' => $recipient->avatar_url
            ] : null;
            
            return $messageArray;
        });

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
        if (Auth::user()->usertype !== 'admin') {
            abort(403);
        }

        $users = User::where('usertype', '!=', 'admin')->get();
        
        // Manually add unread count from MongoDB
        $users = $users->map(function($user) {
            $unreadCount = MongoMessage::where('sender_id', $user->id)
                ->where('recipient_id', Auth::id())
                ->where('is_read', false)
                ->count();
            
            $userData = $user->toArray();
            $userData['unread_count'] = $unreadCount;
            return $userData;
        });
        
        // Sort by unread count
        $users = collect($users)->sortByDesc('unread_count')->values();

        return response()->json([
            'success' => true,
            'users' => $users,
        ]);
    }
}
