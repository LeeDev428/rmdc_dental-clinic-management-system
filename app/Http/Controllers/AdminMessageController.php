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
       
       // Debug: Log the count
       \Log::info('Admin messages loaded', [
           'admin_id' => auth()->id(),
           'patient_id' => $selectedUser->id,
           'message_count' => $messages->count()
       ]);
       
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

    // OLD MYSQL METHODS - DEPRECATED - Use MongoDB methods above
    /*
    public function index1(Request $request)
    {
        // DEPRECATED: This method uses MySQL messages table
        // Use index() method instead which uses MongoDB
    }

    public function showMessages($userId)
    {
        // DEPRECATED: This method uses MySQL messages table
        // Use index() method with user_id parameter instead
    }

    public function store(Request $request)
    {
        // DEPRECATED: This method uses MySQL messages table
        // Use MongoMessageController@store instead
    }
    */
    
}
