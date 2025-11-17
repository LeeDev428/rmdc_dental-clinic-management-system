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
        Schema::table('inventories', function (Blueprint $table) {
            // Store the original/constant value (e.g., "100 pieces per box")
            $table->integer('original_items_per_unit')->default(1)->after('items_per_unit');
            
            // Track pieces remaining in the currently open box
            $table->integer('current_box_pieces')->default(0)->after('original_items_per_unit');
        });
        
        // Initialize values: copy items_per_unit to original_items_per_unit and current_box_pieces
        DB::statement('UPDATE inventories SET original_items_per_unit = items_per_unit, current_box_pieces = items_per_unit');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn(['original_items_per_unit', 'current_box_pieces']);
        });
    }
};
