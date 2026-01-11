<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\MongoMessage;
use App\Models\User;
use App\Models\Appointment;

class AdminMessageController extends Controller
{
    /**
     * Check if MongoDB is available
     */
    private function isMongoAvailable()
    {
        return extension_loaded('mongodb') && !empty(config('database.connections.mongodb.dsn'));
    }

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
   
   // Attach last message from MongoDB to each user (if MongoDB is available)
   if ($this->isMongoAvailable()) {
       $currentUserId = auth()->id();
       foreach ($users as $user) {
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
           
           // Attach MongoDB message data as properties
           if ($lastMessage) {
               $user->last_message = $lastMessage->message;
               $user->last_message_time = $lastMessage->created_at;
           } else {
               $user->last_message = null;
               $user->last_message_time = null;
           }
       }
       
       // Sort users by last message time
       $users = $users->sortByDesc(function($user) {
           return $user->last_message_time ?? '1970-01-01';
       })->values();
   } else {
       // MongoDB not available, set defaults for all users
       foreach ($users as $user) {
           $user->last_message = null;
           $user->last_message_time = null;
       }
   }

   $messages = [];
   $selectedUser = null;

   if ($request->has('user_id')) {
       $selectedUser = User::find($request->user_id);
       
       if ($this->isMongoAvailable()) {
           // Debug: Log the conversation participants
           \Log::info('Admin conversation initialized', [
               'admin_id' => auth()->id(),
               'patient_id' => $selectedUser->id
           ]);
           
           // Fetch messages from MongoDB
           $messages = MongoMessage::conversation(auth()->id(), $selectedUser->id)
               ->orderBy('created_at', 'asc')
               ->get();
           
           // Debug: Log the count
           \Log::info('Admin messages loaded', [
               'admin_id' => auth()->id(),
               'patient_id' => $selectedUser->id,
               'message_count' => $messages->count(),
               'sample_messages' => $messages->take(2)->map(fn($m) => [
                   'sender' => $m->sender_id,
                   'recipient' => $m->recipient_id,
                   'message' => substr($m->message, 0, 20)
               ])
           ]);
           
           // Manually attach user data
           $currentUser = auth()->user();
           $messages = $messages->map(function($msg) use ($selectedUser, $currentUser) {
               // Don't convert to array, work with the model instance
               $msg->sender = $msg->sender_id == $currentUser->id ? $currentUser : $selectedUser;
               $msg->recipient = $msg->recipient_id == $currentUser->id ? $currentUser : $selectedUser;
               return $msg;
           });
       } else {
           // MongoDB not available
           $messages = collect([]);
       }
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
