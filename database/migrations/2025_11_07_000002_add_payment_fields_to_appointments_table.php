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
        Schema::table('appointments', function (Blueprint $table) {
            $table->decimal('total_price', 10, 2)->nullable()->after('procedure');
            $table->decimal('down_payment', 10, 2)->nullable()->after('total_price');
            $table->string('payment_status')->default('unpaid')->after('down_payment'); // unpaid, partially_paid, fully_paid
            $table->boolean('requires_payment')->default(true)->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['total_price', 'down_payment', 'payment_status', 'requires_payment']);
        });
    }
};
