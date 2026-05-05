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
        // Check if the column doesn't already exist
        if (Schema::hasTable('notifications') && !Schema::hasColumn('notifications', 'order_id')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('notifications') && Schema::hasColumn('notifications', 'order_id')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropForeignIdFor(\App\Models\Order::class);
                $table->dropColumn('order_id');
            });
        }
    }
};
