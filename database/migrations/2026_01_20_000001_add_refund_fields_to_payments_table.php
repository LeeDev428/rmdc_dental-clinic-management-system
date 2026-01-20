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
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('refund_amount', 10, 2)->nullable()->after('paid_at');
            $table->string('refund_status')->nullable()->after('refund_amount'); // pending, processed, failed
            $table->string('paymongo_refund_id')->nullable()->after('refund_status');
            $table->text('refund_reason')->nullable()->after('paymongo_refund_id');
            $table->timestamp('refunded_at')->nullable()->after('refund_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['refund_amount', 'refund_status', 'paymongo_refund_id', 'refund_reason', 'refunded_at']);
        });
    }
};
