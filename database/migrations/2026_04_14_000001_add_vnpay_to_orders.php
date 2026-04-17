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
        Schema::table('orders', function (Blueprint $table) {
            // Thêm cột thanh toán VNPAY nếu chưa tồn tại
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->default('NULL')->after('id');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->nullable()->default('pending')->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'vnpay_txn_ref')) {
                $table->string('vnpay_txn_ref')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'vnpay_transaction_no')) {
                $table->string('vnpay_transaction_no')->nullable()->after('vnpay_txn_ref');
            }
            if (!Schema::hasColumn('orders', 'vnpay_response_code')) {
                $table->string('vnpay_response_code')->nullable()->after('vnpay_transaction_no');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumnIfExists('payment_method');
            $table->dropColumnIfExists('payment_status');
            $table->dropColumnIfExists('vnpay_txn_ref');
            $table->dropColumnIfExists('vnpay_transaction_no');
            $table->dropColumnIfExists('vnpay_response_code');
        });
    }
};
