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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2); // Payment amount
            $table->decimal('total_price', 10, 2); // Total procedure price
            $table->string('payment_method'); // gcash, paymaya, card
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $table->string('paymongo_payment_id')->nullable(); // PayMongo payment ID
            $table->string('paymongo_source_id')->nullable(); // PayMongo source ID
            $table->text('payment_details')->nullable(); // JSON details from PayMongo
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
