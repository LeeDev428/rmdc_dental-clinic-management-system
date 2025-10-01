<?php

// app/Models/Appointment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    // use HasFactory;

    // protected $table = 'appointments'; // Use the actual table name

    // protected $fillable = [
    //     'user_id',
    //     'title',
    //     'procedure',
    //     'duration',
    //     'time',
    //     'start',
    //     'end',
    //     'status',
    //     'image_path',
    // ];

    protected $guarded = [];
}
