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

   // Search logic for user_id or user name
   $query = User::where('is_admin', 0);  // Get all patients

   if ($request->has('search')) {
       $search = $request->search;
       $query->where(function($q) use ($search) {
           $q->where('id', 'like', "%$search%")
             ->orWhere('name', 'like', "%$search%");
       });
   }

   // Retrieve users
   $users = $query->get();

   $messages = [];
   $selectedUser = null;

   if ($request->has('user_id')) {
       $selectedUser = User::find($request->user_id);
       
       // Fetch messages from MongoDB
       $messages = MongoMessage::conversation(auth()->id(), $selectedUser->id)
           ->with(['sender', 'recipient'])
           ->orderBy('created_at', 'asc')
           ->get();
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
