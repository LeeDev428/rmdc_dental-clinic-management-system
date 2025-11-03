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
        Schema::create('dental_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('dentist_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Visit Information
            $table->date('visit_date');
            $table->string('chief_complaint')->nullable();
            
            // Medical History
            $table->text('medical_history')->nullable();
            $table->text('current_medications')->nullable();
            $table->text('allergies')->nullable();
            $table->string('blood_pressure')->nullable();
            
            // Dental Examination
            $table->text('oral_examination')->nullable();
            $table->text('gum_condition')->nullable();
            $table->text('tooth_condition')->nullable();
            $table->text('xray_findings')->nullable();
            
            // Diagnosis & Treatment
            $table->text('diagnosis')->nullable();
            $table->text('treatment_plan')->nullable();
            $table->text('treatment_performed')->nullable();
            $table->string('teeth_numbers')->nullable(); // e.g., "11,12,21,22"
            
            // Prescriptions & Recommendations
            $table->text('prescription')->nullable();
            $table->text('recommendations')->nullable();
            
            // Follow-up
            $table->date('next_visit')->nullable();
            $table->text('notes')->nullable();
            
            // Attachments
            $table->json('attachments')->nullable(); // Store file paths as JSON array
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dental_records');
    }
};
