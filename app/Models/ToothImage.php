<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToothImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'tooth_record_id',
        'image_type',
        'file_path',
        'file_name',
        'description',
        'image_date',
        'uploaded_by',
    ];

    protected $casts = [
        'image_date' => 'date',
    ];

    public function toothRecord()
    {
        return $this->belongsTo(ToothRecord::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
