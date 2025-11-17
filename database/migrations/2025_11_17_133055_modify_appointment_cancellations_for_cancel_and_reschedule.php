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
        Schema::table('appointment_cancellations', function (Blueprint $table) {
            // Add type column to distinguish between 'cancel' and 'reschedule'
            $table->enum('type', ['cancel', 'reschedule'])->default('cancel')->after('reason');
            
            // Rename cancelled_at to processed_at for better clarity
            $table->renameColumn('cancelled_at', 'processed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointment_cancellations', function (Blueprint $table) {
            // Remove type column
            $table->dropColumn('type');
            
            // Rename back to cancelled_at
            $table->renameColumn('processed_at', 'cancelled_at');
        });
    }
};
