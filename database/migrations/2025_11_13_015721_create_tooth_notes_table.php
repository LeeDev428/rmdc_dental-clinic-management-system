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
        Schema::create('tooth_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tooth_record_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Doctor/admin who created note
            $table->string('note_type', 50)->default('general'); // general, treatment, observation, diagnosis
            $table->text('content');
            $table->date('note_date')->default(now());
            $table->timestamps();
            
            $table->index(['tooth_record_id', 'note_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tooth_notes');
    }
};
