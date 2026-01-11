<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardBanner extends Model
{
    use HasFactory;

    protected $fillable = ['image_path'];

    /**
     * Return the currently active banner (latest)
     */
    public static function current()
    {
        return static::latest()->first();
    }
}
