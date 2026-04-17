<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Coupon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    /**
     * Danh sách mã giảm giá
     */
    public function index(Request $request)
    {
        $couponsQuery = Coupon::query()->orderByDesc('id');

        if ($request->filled('keyword')) {
            $keyword = trim($request->input('keyword'));
            $couponsQuery->where(function ($query) use ($keyword) {
                $query->where('code', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $couponsQuery->where('is_active', true);
            } elseif ($request->input('status') === 'expired') {
                $couponsQuery->where('valid_until', '<', now());
            }
        }

        $coupons = $couponsQuery->paginate(15)->appends($request->query());

        return view('admin.pages.coupons.index', [
            'coupons' => $coupons,
        ]);
    }

    /**
     * Lưu mã giảm giá mới
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'unique:coupons,code', 'max:50'],
            'discount_type' => ['required', Rule::in(['percentage', 'fixed'])],
            'discount_value' => ['required', 'numeric', 'min:0.01'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'is_active' => ['boolean'],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'code.required' => 'Vui lòng nhập mã giảm giá',
            'code.unique' => 'Mã giảm giá này đã tồn tại',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm',
            'discount_type.required' => 'Vui lòng chọn loại giảm',
        ]);

        // === KIỂM TRA BUSINESS LOGIC ===
        
        if ($validated['discount_type'] === 'percentage') {
            // Khi giảm theo %: discount_value là %, max_discount là tiền
            // Chỉ kiểm tra % không vượt 100%
            if ((float)$validated['discount_value'] > 100) {
                flash('Giảm giá theo % không được vượt quá 100%', 'error');
                return redirect()->back()->withInput();
            }
        } else {
            // Khi giảm cố định: cả discount_value và max_discount đều là tiền
            // Kiểm tra max_discount không lớn hơn discount_value
            if (!empty($validated['max_discount']) && (float)$validated['max_discount'] > (float)$validated['discount_value']) {
                flash('Giảm giá tối đa không được lớn hơn giá trị giảm giá', 'error');
                return redirect()->back()->withInput();
            }
        }

        Coupon::create([
            'code' => strtoupper(trim($validated['code'])),
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'max_discount' => $this->toNullableNumber($validated['max_discount'] ?? null),
            'min_order_amount' => $this->toNullableNumber($validated['min_order_amount'] ?? null),
            'usage_limit' => $this->toNullableInteger($validated['usage_limit'] ?? null),
            'valid_from' => $this->toStartOfMinute($validated['valid_from'] ?? null),
            'valid_until' => $this->toEndOfMinute($validated['valid_until'] ?? null),
            'is_active' => $validated['is_active'] ?? true,
            'description' => $this->toNullableString($validated['description'] ?? null),
        ]);

        flash('Tạo mã giảm giá thành công!', 'success');

        return redirect()->route('admin.coupons.index');
    }

    /**
     * Cập nhật mã giảm giá
     */
    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', Rule::unique('coupons', 'code')->ignore($coupon->id), 'max:50'],
            'discount_type' => ['required', Rule::in(['percentage', 'fixed'])],
            'discount_value' => ['required', 'numeric', 'min:0.01'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'is_active' => ['boolean'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        // === KIỂM TRA BUSINESS LOGIC ===
        
        if ($validated['discount_type'] === 'percentage') {
            // Khi giảm theo %: discount_value là %, max_discount là tiền
            // Chỉ kiểm tra % không vượt 100%
            if ((float)$validated['discount_value'] > 100) {
                flash('Giảm giá theo % không được vượt quá 100%', 'error');
                return redirect()->back()->withInput();
            }
        } else {
            // Khi giảm cố định: cả discount_value và max_discount đều là tiền
            // Kiểm tra max_discount không lớn hơn discount_value
            if (!empty($validated['max_discount']) && (float)$validated['max_discount'] > (float)$validated['discount_value']) {
                flash('Giảm giá tối đa không được lớn hơn giá trị giảm giá', 'error');
                return redirect()->back()->withInput();
            }
        }

        $coupon->update([
            'code' => strtoupper(trim($validated['code'])),
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'max_discount' => $this->toNullableNumber($validated['max_discount'] ?? null),
            'min_order_amount' => $this->toNullableNumber($validated['min_order_amount'] ?? null),
            'usage_limit' => $this->toNullableInteger($validated['usage_limit'] ?? null),
            'valid_from' => $this->toStartOfMinute($validated['valid_from'] ?? null),
            'valid_until' => $this->toEndOfMinute($validated['valid_until'] ?? null),
            'is_active' => $validated['is_active'] ?? true,
            'description' => $this->toNullableString($validated['description'] ?? null),
        ]);

        flash('Cập nhật mã giảm giá thành công!', 'success');

        return redirect()->route('admin.coupons.index');
    }

    /**
     * Xóa mã giảm giá
     */
    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        flash('Xóa mã giảm giá thành công!', 'success');

        return redirect()->route('admin.coupons.index');
    }

    /**
     * Bật/Tắt mã giảm giá
     */
    public function toggle(Coupon $coupon): RedirectResponse
    {
        $coupon->update([
            'is_active' => !$coupon->is_active,
        ]);

        $message = $coupon->is_active ? 'Kích hoạt mã giảm giá thành công!' : 'Vô hiệu hóa mã giảm giá thành công!';
        flash($message, 'success');

        return redirect()->route('admin.coupons.index');
    }

    private function toNullableString(mixed $value): ?string
    {
        $string = trim((string) ($value ?? ''));

        return $string === '' ? null : $string;
    }

    private function toNullableNumber(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (float) $value;
    }

    private function toNullableInteger(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    private function toStartOfMinute(mixed $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        return Carbon::parse((string) $value)->startOfMinute();
    }

    private function toEndOfMinute(mixed $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        return Carbon::parse((string) $value)->endOfMinute();
    }
}
