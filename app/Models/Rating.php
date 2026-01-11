<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings_review'; // Updated table name

    protected $fillable = ['rating', 'message', 'user_id', 'appointment_id', 'featured'];

    protected $casts = [
        'featured' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function appointment()
    {
        return $this->belongsTo(\App\Models\Appointment::class);
    }
}
