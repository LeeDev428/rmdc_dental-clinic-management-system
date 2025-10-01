<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();  // Valid ID will be automatically generated
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key for users table
            $table->string('title');
            $table->string('procedure');
            $table->integer('duration'); // New column for procedure duration in minutes
            $table->time('time'); // Time column
            $table->dateTime('start');
            $table->dateTime('end');
            $table->string('status')->default('pending');
            $table->string('image_path')->nullable(); // Add image path column
            $table->string('teeth_layout')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
