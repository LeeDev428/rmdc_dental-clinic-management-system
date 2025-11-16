<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcedurePrice extends Model
{
    use HasFactory;

    protected $table = 'procedure_prices';

    protected $fillable = ['procedure_name', 'price', 'duration', 'image_path', 'description'];

    /**
     * Get the inventory links for this procedure
     */
    public function procedureInventories()
    {
        return $this->hasMany(ProcedureInventory::class);
    }

    /**
     * Get the inventory items used in this procedure (many-to-many)
     */
    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'procedure_inventory')
                    ->withPivot('quantity_used')
                    ->withTimestamps();
    }
}
 