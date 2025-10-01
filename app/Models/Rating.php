<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings_review'; // Updated table name

    protected $fillable = ['rating', 'message']; // Removed user_id and appointment_id
}
