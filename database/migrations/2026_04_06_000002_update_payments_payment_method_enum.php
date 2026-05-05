<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE payments MODIFY payment_method ENUM('cash','cod','vnpay','paypal') NOT NULL");
            DB::statement("ALTER TABLE payments MODIFY status ENUM('pending','completed','failed') NOT NULL DEFAULT 'pending'");
        } else {
            Schema::table('payments', function (Blueprint $table) {
                $table->string('payment_method')->change();
                $table->string('status')->default('pending')->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE payments MODIFY payment_method ENUM('cash','paypal') NOT NULL");
            DB::statement("ALTER TABLE payments MODIFY status ENUM('pending','completed','failed') NOT NULL DEFAULT 'pending'");
        } else {
            Schema::table('payments', function (Blueprint $table) {
                $table->string('payment_method')->change();
                $table->string('status')->default('pending')->change();
            });
        }
    }
};