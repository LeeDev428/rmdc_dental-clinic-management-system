<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MongoMessage;
use App\Models\User;

class MessageController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to view your messages.');
        }

        $currentUserId = Auth::id();

        // Find the admin this patient has been talking to (last message where sender_type is admin)
        $lastAdminMessage = MongoMessage::where(function ($query) use ($currentUserId) {
            $query->where('sender_id', $currentUserId)
                  ->orWhere('recipient_id', $currentUserId);
        })
        ->where('sender_type', 'admin')
        ->orderBy('created_at', 'desc')
        ->first();

        if ($lastAdminMessage) {
            $adminId = $lastAdminMessage->sender_id == $currentUserId
                ? $lastAdminMessage->recipient_id
                : $lastAdminMessage->sender_id;
            $adminUser = User::find($adminId);
        }

        if (empty($adminUser)) {
            $adminUser = User::where('usertype', 'admin')->first() ?? User::find(1);
        }

        $messages = MongoMessage::conversation($currentUserId, $adminUser->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $currentUser = Auth::user();
        $messages = $messages->map(function ($msg) use ($adminUser, $currentUser) {
            $msg->sender    = $msg->sender_id == $currentUser->id ? $currentUser : $adminUser;
            $msg->recipient = $msg->recipient_id == $currentUser->id ? $currentUser : $adminUser;
            return $msg;
        });

        $selectedUser = $currentUser;

        return view('messages.index', compact('messages', 'selectedUser', 'adminUser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message'      => 'required|string|max:1000',
            'recipient_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();

        MongoMessage::create([
            'sender_id'    => $user->id,
            'recipient_id' => (int) $request->recipient_id,
            'message'      => $request->message,
            'sender_type'  => $user->usertype === 'admin' ? 'admin' : 'user',
            'is_read'      => false,
            'attachments'  => [],
            'created_at'   => new \MongoDB\BSON\UTCDateTime(now()->timestamp * 1000),
        ]);

        return back()->with('success', 'Message sent successfully.');
    }

    /**
     * Unread count for the logged-in patient (messages from admins that are unread).
     */
    public function unreadMessagesCount()
    {
        $userId = Auth::id();

        $count = MongoMessage::where('recipient_id', $userId)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Mark all admin messages to the logged-in patient as read.
     */
    public function markMessagesAsRead()
    {
        $userId = Auth::id();

        MongoMessage::where('recipient_id', $userId)
            ->where('is_read', false)
            ->get()
            ->each(fn ($msg) => $msg->markAsRead());

        return response()->json(['success' => true]);
    }

    /**
     * Unread count for admin (messages from patients that are unread).
     */
    public function getUnreadMessagesCount()
    {
        $count = MongoMessage::where('sender_type', 'user')
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Mark all patient messages as read (admin action).
     */
    public function markMessagesAsReadAdmin()
    {
        MongoMessage::where('sender_type', 'user')
            ->where('is_read', false)
            ->get()
            ->each(fn ($msg) => $msg->markAsRead());

        return response()->json(['success' => true]);
    }
}
