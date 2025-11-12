<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToothRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tooth_number',
        'quadrant',
        'tooth_type',
        'condition',
        'color_code',
        'x_position',
        'y_position',
        'notes',
        'last_treatment_date',
        'next_appointment_date',
        'is_missing',
    ];

    protected $casts = [
        'is_missing' => 'boolean',
        'last_treatment_date' => 'date',
        'next_appointment_date' => 'date',
        'x_position' => 'decimal:2',
        'y_position' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notes()
    {
        return $this->hasMany(ToothNote::class);
    }

    public function images()
    {
        return $this->hasMany(ToothImage::class);
    }
}
