<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcedureInventory extends Model
{
    use HasFactory;

    protected $table = 'procedure_inventory';

    protected $fillable = [
        'procedure_price_id',
        'inventory_id',
        'quantity_used'
    ];

    protected $casts = [
        'quantity_used' => 'decimal:2'
    ];

    /**
     * Get the procedure that owns this inventory link
     */
    public function procedurePrice()
    {
        return $this->belongsTo(ProcedurePrice::class);
    }

    /**
     * Get the inventory item
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
