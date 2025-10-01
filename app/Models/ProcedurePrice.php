<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcedurePrice extends Model
{
    use HasFactory;

    protected $table = 'procedure_prices';

    protected $fillable = ['procedure_name', 'price', 'duration', 'image_path', 'description'];
}
 