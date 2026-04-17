<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Áp dụng mã giảm giá
     */
    public function apply(Request $request)
    {
        // Validate input
        $request->validate([
            'coupon_code' => 'required|string|max:50',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $couponCode = strtoupper(trim($request->coupon_code));
        $totalAmount = (float) $request->total_amount;

        // Tìm mã
        $coupon = Coupon::where('code', $couponCode)->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Mã giảm giá không tồn tại',
            ], 404);
        }

        // Tính tiền giảm
        $result = $coupon->calculateDiscount($totalAmount);

        // Nếu mã không hợp lệ
        if (!$result['valid']) {
            return response()->json([
                'valid' => false,
                'message' => $result['message'],
            ], 400);
        }

        // Lưu vào session
        session(['applied_coupon' => [
            'code' => $coupon->code,
            'id' => $coupon->id,
            'discount_value' => (float) $coupon->discount_value,
            'discount_type' => $coupon->discount_type,
            'discount_amount' => (float) $result['discount'],
        ]]);

        return response()->json([
            'valid' => true,
            'message' => 'Áp dụng mã giảm giá thành công',
            'discount' => (float) $result['discount'],
            'final_amount' => (float) $result['final_amount'],
            'discount_text' => $coupon->discount_type === 'percentage' 
                ? ($coupon->discount_value . '% - ' . number_format($result['discount'], 0, ',', '.') . 'đ')
                : number_format($result['discount'], 0, ',', '.') . 'đ',
        ]);
    }

    /**
     * Xóa mã giảm giá
     */
    public function remove()
    {
        session()->forget('applied_coupon');

        return response()->json([
            'valid' => true,
            'message' => 'Đã xóa mã giảm giá',
        ]);
    }
}
