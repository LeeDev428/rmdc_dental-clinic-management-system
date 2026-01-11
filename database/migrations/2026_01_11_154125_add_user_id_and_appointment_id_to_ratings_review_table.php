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
        Schema::table('ratings_review', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->after('id');
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->onDelete('cascade')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings_review', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['appointment_id']);
            $table->dropColumn(['user_id', 'appointment_id']);
        });
    }
};
