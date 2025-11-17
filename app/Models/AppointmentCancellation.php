<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AppointmentCancellation extends Model
{
    protected $fillable = [
        'user_id',
        'appointment_id',
        'reason',
        'type', // 'cancel' or 'reschedule'
        'processed_at' // renamed from cancelled_at
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to Appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // Check if user has reached limit (2 total per week: any combination of cancel/reschedule)
    public static function canUserPerformAction($userId)
    {
        $oneWeekAgo = Carbon::now()->subWeek();
        
        $actionsThisWeek = self::where('user_id', $userId)
            ->where('processed_at', '>=', $oneWeekAgo)
            ->count();
        
        return $actionsThisWeek < 2; // Changed from 3 to 2
    }

    // Get remaining actions for this week (out of 2)
    public static function getRemainingActions($userId)
    {
        $oneWeekAgo = Carbon::now()->subWeek();
        
        $actionsThisWeek = self::where('user_id', $userId)
            ->where('processed_at', '>=', $oneWeekAgo)
            ->count();
        
        return max(0, 2 - $actionsThisWeek); // Changed from 3 to 2
    }

    // Legacy method for backwards compatibility
    public static function canUserCancel($userId)
    {
        return self::canUserPerformAction($userId);
    }

    // Legacy method for backwards compatibility
    public static function getRemainingCancellations($userId)
    {
        return self::getRemainingActions($userId);
    }
}
