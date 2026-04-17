<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the table exists and has the misspelled column
        if (Schema::hasTable('notifications') && Schema::hasColumn('notifications', 'mesage')) {
            // Rename the misspelled column to the correct one
            Schema::table('notifications', function (Blueprint $table) {
                $table->renameColumn('mesage', 'message');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('notifications') && Schema::hasColumn('notifications', 'message')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->renameColumn('message', 'mesage');
            });
        }
    }
};
