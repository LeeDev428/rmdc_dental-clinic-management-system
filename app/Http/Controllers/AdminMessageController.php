<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MongoMessage;
use App\Models\User;
use App\Models\Appointment;

class AdminMessageController extends Controller
{
    public function index(Request $request)
    {
        // Search logic for user_id or user name - get all non-admin users (patients)
        $query = User::where('usertype', '!=', 'admin');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhere('name', 'like', "%$search%");
            });
        }

        $users = $query->get();

        // Attach last message timestamp from MongoDB to each user for sorting
        $currentUserId = auth()->id();
        foreach ($users as $user) {
            $lastMessage = MongoMessage::where(function ($q) use ($user, $currentUserId) {
                $q->where(function ($inner) use ($user, $currentUserId) {
                    $inner->where('sender_id', $currentUserId)
                          ->where('recipient_id', $user->id);
                })->orWhere(function ($inner) use ($user, $currentUserId) {
                    $inner->where('sender_id', $user->id)
                          ->where('recipient_id', $currentUserId);
                });
            })
            ->orderBy('created_at', 'desc')
            ->first();

            $user->last_message      = $lastMessage?->message;
            $user->last_message_time = $lastMessage?->created_at;
        }

        $users = $users->sortByDesc(fn ($u) => $u->last_message_time ?? '1970-01-01')->values();

        $messages     = collect([]);
        $selectedUser = null;

        if ($request->has('user_id')) {
            $selectedUser = User::find($request->user_id);
            $currentUser  = auth()->user();

            $messages = MongoMessage::conversation($currentUserId, $selectedUser->id)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($msg) use ($selectedUser, $currentUser) {
                    $msg->sender    = $msg->sender_id    == $currentUser->id ? $currentUser : $selectedUser;
                    $msg->recipient = $msg->recipient_id == $currentUser->id ? $currentUser : $selectedUser;
                    return $msg;
                });
        }

        $pendingCount     = Appointment::where('status', 'pending')->count();
        $mongoUnavailable = false;

        return view('admin.patient_messages', compact('users', 'messages', 'selectedUser', 'pendingCount', 'mongoUnavailable'));
    }

}

