<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToothNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'tooth_record_id',
        'created_by',
        'note_type',
        'content',
        'note_date',
    ];

    protected $casts = [
        'note_date' => 'date',
    ];

    public function toothRecord()
    {
        return $this->belongsTo(ToothRecord::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
