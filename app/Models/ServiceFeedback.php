<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceFeedback extends Model
{
    // Specify the table name explicitly
    protected $table = 'service_feedbacks';
    
    protected $fillable = [
        'appointment_id',
        'user_id',
        'rating',
        'comment'
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
}
