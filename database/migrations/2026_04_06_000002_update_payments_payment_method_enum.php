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
            $table->enum('payment_method', ['cash', 'cod', 'vnpay', 'paypal'])->nullable(false)->change();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('payment_method', ['cash', 'paypal'])->nullable(false)->change();
            $table->enum('status', ['pending', 'complated', 'failed'])->default('pending')->nullable(false)->change();
        });
    }
};