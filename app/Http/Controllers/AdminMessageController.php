<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\MongoMessage;
use App\Models\User;
use App\Models\Appointment;

class AdminMessageController extends Controller
{

    public function index(Request $request)  {

   // Search logic for user_id or user name - get all non-admin users (patients)
   $query = User::where('usertype', '!=', 'admin');  // Get all patients

   if ($request->has('search')) {
       $search = $request->search;
       $query->where(function($q) use ($search) {
           $q->where('id', 'like', "%$search%")
             ->orWhere('name', 'like', "%$search%");
       });
   }

   // Retrieve users
   $users = $query->get();
   
   // Attach last message from MongoDB to each user
   $currentUserId = auth()->id();
   $users = $users->map(function($user) use ($currentUserId) {
       $userData = $user->toArray();
       
       // Get last message between admin and this user from MongoDB
       $lastMessage = MongoMessage::where(function($query) use ($user, $currentUserId) {
           $query->where(function($q) use ($user, $currentUserId) {
               $q->where('sender_id', $currentUserId)
                 ->where('recipient_id', $user->id);
           })->orWhere(function($q) use ($user, $currentUserId) {
               $q->where('sender_id', $user->id)
                 ->where('recipient_id', $currentUserId);
           });
       })
       ->orderBy('created_at', 'desc')
       ->first();
       
       // Attach MongoDB message data
       if ($lastMessage) {
           $userData['last_message'] = $lastMessage->message;
           $userData['last_message_time'] = $lastMessage->created_at;
       } else {
           $userData['last_message'] = null;
           $userData['last_message_time'] = null;
       }
       
       return (object) $userData;
   });
   
   // Sort users by last message time
   $users = collect($users)->sortByDesc(function($user) {
       return $user->last_message_time ?? '1970-01-01';
   })->values();

   $messages = [];
   $selectedUser = null;

   if ($request->has('user_id')) {
       $selectedUser = User::find($request->user_id);
       
       // Fetch messages from MongoDB
       $messages = MongoMessage::conversation(auth()->id(), $selectedUser->id)
           ->orderBy('created_at', 'asc')
           ->get();
       
       // Manually attach user data
       $currentUser = auth()->user();
       $messages = $messages->map(function($msg) use ($selectedUser, $currentUser) {
           $msgData = is_array($msg) ? $msg : $msg->toArray();
           
           // Attach sender info
           if ($msgData['sender_id'] == $currentUser->id) {
               $msgData['sender'] = [
                   'id' => $currentUser->id,
                   'name' => $currentUser->name,
                   'avatar' => $currentUser->avatar,
                   'avatar_url' => $currentUser->avatar_url
               ];
           } else {
               $msgData['sender'] = [
                   'id' => $selectedUser->id,
                   'name' => $selectedUser->name,
                   'avatar' => $selectedUser->avatar,
                   'avatar_url' => $selectedUser->avatar_url
               ];
           }
           
           // Attach recipient info
           if ($msgData['recipient_id'] == $currentUser->id) {
               $msgData['recipient'] = [
                   'id' => $currentUser->id,
                   'name' => $currentUser->name,
                   'avatar' => $currentUser->avatar,
                   'avatar_url' => $currentUser->avatar_url
               ];
           } else {
               $msgData['recipient'] = [
                   'id' => $selectedUser->id,
                   'name' => $selectedUser->name,
                   'avatar' => $selectedUser->avatar,
                   'avatar_url' => $selectedUser->avatar_url
               ];
           }
           
           return (object) $msgData;
       });
   }
   $pendingCount = Appointment::where('status', 'pending')->count();
   
   return view('admin.patient_messages', compact('users', 'messages', 'selectedUser', 'pendingCount'));

}


    
    public function index1(Request $request)
    {
        // Search logic for user_id or user name
        $query = User::has('messages');  // Get users who have messages

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhere('name', 'like', "%$search%");
            });
        }

        // Retrieve users with their latest message
        $users = $query->with(['messages' => function ($query) {
            $query->orderByDesc('created_at'); // Sort by message date (latest first)
        }])->get();

        // Sort users by the most recent message timestamp
        $users = $users->sortByDesc(function ($user) {
            return $user->messages->first()->created_at ?? now(); // If no messages, put them at the bottom
        });

        $messages = [];
        $selectedUser = null;

        if ($request->has('user_id')) {
            $selectedUser = User::find($request->user_id);
            $messages = Message::where('user_id', $selectedUser->id)
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('admin.patient_messages', compact('users', 'messages', 'selectedUser'));
    
    }


    public function showMessages($userId)
    {
        // Get all users
        $users = User::whereHas('messages')->get();

        // Fetch selected user's messages
        $selectedUser = User::findOrFail($userId);
        $messages = Message::where('user_id', $userId)->orderBy('created_at')->get();

        return view('admin.patient_messages', compact('users', 'selectedUser', 'messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:5000',
        ]);

        Message::create([
            'user_id' => $request->user_id,
            'message' => $request->message,
            'is_admin' => true,
            'status' => 'unread',
        ]);

        return redirect()->route('admin.messages', ['user_id' => $request->user_id])->with('success', 'Response sent.');
    }

    
}
