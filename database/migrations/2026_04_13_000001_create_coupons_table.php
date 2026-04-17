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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã giảm giá
            $table->enum('discount_type', ['percentage', 'fixed']); // Loại giảm: % hoặc tiền cố định
            $table->decimal('discount_value', 8, 2); // Giá trị giảm
            $table->decimal('max_discount', 8, 2)->nullable(); // Giảm tối đa (dùng cho %)
            $table->decimal('min_order_amount', 8, 2)->nullable(); // Đơn hàng tối thiểu
            $table->integer('usage_limit')->nullable(); // Giới hạn số lần sử dụng
            $table->integer('usage_count')->default(0); // Số lần đã sử dụng
            $table->datetime('valid_from')->nullable(); // Ngày bắt đầu
            $table->datetime('valid_until')->nullable(); // Ngày hết hạn
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->text('description')->nullable(); // Mô tả
            $table->timestamps();

            // Indexes
            $table->index('code');
            $table->index('is_active');
            $table->index('valid_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
