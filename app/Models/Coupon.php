<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'max_discount',
        'min_order_amount',
        'usage_limit',
        'usage_count',
        'valid_from',
        'valid_until',
        'is_active',
        'description',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
    ];

    /**
     * Kiểm tra xem mã giảm giá có hợp lệ không
     * Không tính đến số tiền đơn hàng, chỉ kiểm tra trạng thái của mã
     * @return bool
     */
    public function isValid(): bool
    {
        // Kiểm tra mã có được kích hoạt không
        if (! $this->is_active) {
            return false;
        }

        $validFrom = $this->normalizeDate($this->valid_from)?->startOfMinute();
        $validUntil = $this->normalizeDate($this->valid_until)?->endOfMinute();

        // Kiểm tra mã đã hết hạn chưa
        if ($validUntil && now()->isAfter($validUntil)) {
            return false;
        }

        // Kiểm tra mã đã đến thời gian sử dụng chưa
        if ($validFrom && now()->isBefore($validFrom)) {
            return false;
        }

        // Kiểm tra lượt sử dụng đã hết chưa
        if ($this->usage_limit !== null && (int) $this->usage_count >= (int) $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Tính toán số tiền giảm giá
     * 
     * @param float $orderAmount Tổng tiền đơn hàng
     * @return array ['valid' => bool, 'message' => string, 'discount' => float, 'final_amount' => float]
     */
    public function calculateDiscount(float $orderAmount): array
    {
        // === BƯỚC 1: KIỂM TRA TRẠNG THÁI MÃ ===
        if (!$this->is_active) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá đã bị vô hiệu hóa',
            ];
        }

        // === BƯỚC 2: KIỂM TRA THỜI GIAN HIỆU LỰC ===
        if ($this->valid_from) {
            $validFrom = $this->normalizeDate($this->valid_from)?->startOfMinute();
            if ($validFrom && now()->isBefore($validFrom)) {
                return [
                    'valid' => false,
                    'message' => 'Mã giảm giá chưa đến thời gian sử dụng',
                ];
            }
        }

        if ($this->valid_until) {
            $validUntil = $this->normalizeDate($this->valid_until)?->endOfMinute();
            if ($validUntil && now()->isAfter($validUntil)) {
                return [
                    'valid' => false,
                    'message' => 'Mã giảm giá đã hết hạn',
                ];
            }
        }

        // === BƯỚC 3: KIỂM TRA LƯỢT SỬ DỤNG ===
        if ($this->usage_limit !== null && $this->usage_count >= $this->usage_limit) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá đã hết lượt sử dụng',
            ];
        }

        // === BƯỚC 4: KIỂM TRA TỔNG TIỀN TỐI THIỂU ===
        if ($this->min_order_amount !== null && $orderAmount < $this->min_order_amount) {
            $minFormatted = number_format($this->min_order_amount, 0, ',', '.');
            return [
                'valid' => false,
                'message' => "Đơn hàng tối thiểu phải là {$minFormatted}đ",
            ];
        }

        // === BƯỚC 5: KIỂM TRA GIÁ TRỊ GIẢM ===
        if ($this->discount_value <= 0) {
            return [
                'valid' => false,
                'message' => 'Giá trị mã giảm giá không hợp lệ',
            ];
        }

        // === BƯỚC 6: TÍNH TIỀN GIẢM ===
        $discount = 0;

        if ($this->discount_type === 'percentage') {
            // Giảm theo %: (tổng × %) / 100
            $discount = ($orderAmount * $this->discount_value) / 100;

            // Nếu có max_discount, không được vượt quá
            if ($this->max_discount !== null && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        } else {
            // Giảm cố định: min(giảm, tổng)
            $discount = min($this->discount_value, $orderAmount);
        }

        // === BƯỚC 7: TÍNH TỔNG TIỀN CUỐI ===
        $finalAmount = max(0, $orderAmount - $discount);

        return [
            'valid' => true,
            'message' => 'Mã giảm giá hợp lệ',
            'discount' => round($discount, 2),
            'final_amount' => round($finalAmount, 2),
        ];
    }

    private function normalizeDate(mixed $value): ?CarbonInterface
    {
        if (! $value) {
            return null;
        }

        if ($value instanceof CarbonInterface) {
            if (str_starts_with($value->format('Y-m-d H:i:s'), '0000-00-00')) {
                return null;
            }

            return $value;
        }

        $stringValue = (string) $value;
        if ($stringValue === '' || str_starts_with($stringValue, '0000-00-00')) {
            return null;
        }

        try {
            return now()->parse($stringValue);
        } catch (\Throwable $exception) {
            return null;
        }
    }

    /**
     * Tăng số lần sử dụng
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }
}
