<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DentalRecord extends Model
{
    protected $fillable = [
        'user_id',
        'dentist_id',
        'visit_date',
        'chief_complaint',
        'medical_history',
        'current_medications',
        'allergies',
        'blood_pressure',
        'oral_examination',
        'gum_condition',
        'tooth_condition',
        'xray_findings',
        'diagnosis',
        'treatment_plan',
        'treatment_performed',
        'teeth_numbers',
        'prescription',
        'recommendations',
        'next_visit',
        'notes',
        'attachments'
    ];

    protected $casts = [
        'visit_date' => 'date',
        'next_visit' => 'date',
        'attachments' => 'array'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dentist()
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }
}
