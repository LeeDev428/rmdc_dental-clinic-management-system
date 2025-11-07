<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'appointment_id',
        'user_id',
        'amount',
        'total_price',
        'payment_method',
        'payment_status',
        'paymongo_payment_id',
        'paymongo_source_id',
        'payment_details',
        'paid_at'
    ];

    protected $casts = [
        'payment_details' => 'array',
        'paid_at' => 'datetime'
    ];

    // Relationship to Appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
