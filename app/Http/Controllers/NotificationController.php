<?php

namespace App\Http\Controllers;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function markAsRead()
    {
        Notification::where('user_id', Auth::id())
                    ->where('status', 'unread')
                    ->update(['status' => 'read']);
    
        return response()->json(['success' => true]);
    }
    
    // public function checkNewNotifications()
    // {
    //     $newAppointments = Appointment::where('status', 'upcoming')
    //         ->where('created_at', '>', session('last_check', now()))
    //         ->get();
    
    //     // Update the session timestamp for the last check
    //     session(['last_check' => now()]);
    
    //     return response()->json($newAppointments);
    // }

    // public function markAsRead($id)
    // {
    //     $notification = Notification::find($id);

    //     if ($notification) {
    //         $notification->markAsRead();
    //         return redirect()->route('notifications.index')->with('success', 'Notification marked as read');
    //     }

    //     return redirect()->route('notifications.index')->with('error', 'Notification not found');
    // }

    // public function markAllAsRead(Request $request)
    // {
    //     // Mark notifications as read
    //     $unreadNotifications = Auth::user()->unreadNotifications;
        
    //     foreach ($unreadNotifications as $notification) {
    //         $notification->update(['status' => 'read']);
    //     }

    //     return response()->json(['success' => true]);
    // }

    /**
     * Get unread notification count for authenticated user
     */
    public function getUnreadCount()
    {
        if (!Auth::check()) {
            return response()->json(['unreadCount' => 0]);
        }

        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('status', 'unread')
            ->count();

        return response()->json(['unreadCount' => $unreadCount]);
    }
}
