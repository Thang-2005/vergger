<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE payments MODIFY payment_method ENUM('cash','cod','vnpay','paypal') NOT NULL");
        DB::statement("ALTER TABLE payments MODIFY status ENUM('pending','completed','failed') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE payments MODIFY payment_method ENUM('cash','paypal') NOT NULL");
        DB::statement("ALTER TABLE payments MODIFY status ENUM('pending','complated','failed') NOT NULL DEFAULT 'pending'");
    }
};