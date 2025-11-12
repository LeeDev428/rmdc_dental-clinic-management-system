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
        Schema::create('tooth_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tooth_record_id')->constrained()->onDelete('cascade');
            $table->string('image_type', 50)->default('xray'); // xray, photo, diagram
            $table->string('file_path');
            $table->string('file_name');
            $table->text('description')->nullable();
            $table->date('image_date')->default(now());
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['tooth_record_id', 'image_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tooth_images');
    }
};
