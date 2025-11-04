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
        'cancelled_at'
    ];

    protected $casts = [
        'cancelled_at' => 'datetime',
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

    // Check if user has reached cancellation limit (3 per week)
    public static function canUserCancel($userId)
    {
        $oneWeekAgo = Carbon::now()->subWeek();
        
        $cancellationsThisWeek = self::where('user_id', $userId)
            ->where('cancelled_at', '>=', $oneWeekAgo)
            ->count();
        
        return $cancellationsThisWeek < 3;
    }

    // Get remaining cancellations for this week
    public static function getRemainingCancellations($userId)
    {
        $oneWeekAgo = Carbon::now()->subWeek();
        
        $cancellationsThisWeek = self::where('user_id', $userId)
            ->where('cancelled_at', '>=', $oneWeekAgo)
            ->count();
        
        return max(0, 3 - $cancellationsThisWeek);
    }
}
