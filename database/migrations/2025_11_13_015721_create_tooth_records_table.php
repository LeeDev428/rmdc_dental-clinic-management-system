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
        Schema::create('tooth_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('tooth_number'); // 1-32 for permanent teeth
            $table->string('quadrant', 20); // upper_right, upper_left, lower_left, lower_right
            $table->string('tooth_type', 50)->default('permanent'); // permanent, primary, implant
            $table->string('condition', 50)->default('healthy'); // healthy, watch, treatment_needed, missing, treated, crown, implant
            $table->string('color_code', 20)->default('#10b981'); // Color for visual indication
            $table->decimal('x_position', 8, 2)->nullable(); // For drag & drop positioning
            $table->decimal('y_position', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->date('last_treatment_date')->nullable();
            $table->date('next_appointment_date')->nullable();
            $table->boolean('is_missing')->default(false);
            $table->timestamps();
            
            // Unique constraint: one record per tooth per user
            $table->unique(['user_id', 'tooth_number']);
            $table->index(['user_id', 'condition']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tooth_records');
    }
};
