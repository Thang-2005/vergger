<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'brand_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropForeign(['brand_id']);
                $table->dropColumn('brand_id');
            });
        }

        if (Schema::hasTable('brands')) {
            Schema::dropIfExists('brands');
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('brands')) {
            Schema::create('brands', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('categories') && !Schema::hasColumn('categories', 'brand_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->foreignId('brand_id')->nullable()->after('id')->constrained('brands')->nullOnDelete();
            });
        }
    }
};