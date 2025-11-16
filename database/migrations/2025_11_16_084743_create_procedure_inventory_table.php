<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('procedure_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procedure_price_id')->constrained('procedure_prices')->onDelete('cascade');
            $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade');
            $table->decimal('quantity_used', 10, 2); // How many pieces/units needed per procedure
            $table->timestamps();
            
            // Ensure unique combination of procedure and inventory
            $table->unique(['procedure_price_id', 'inventory_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedure_inventory');
    }
};
