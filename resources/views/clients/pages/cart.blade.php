@extends('layouts.client')

@section('title', 'Giỏ hàng | ' . config('app.name'))
@section('breadcrumb', 'Giỏ hàng')

@section('content')

<div class="liton__shoping-cart-area mb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="shoping-cart-inner">
                    <div class="shoping-cart-table table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th></th>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cartItems as $item)
                                <tr class="align-middle">
                                    <td class="cart-product-remove" style="width:40px;">
                                        <a href="#" class="btn-remove-cart" data-cart-id="{{ $item->id }}" title="{{ 'Xóa' }}">
                                            <i class="fas fa-trash-alt" style="color:#d9534f;"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <div style="display:flex; gap:12px; align-items:center;">
                                            <a href="{{ route('product.detail', $item->product->slug) }}" style="flex-shrink:0;">
                                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" style="width:60px; height:60px; object-fit:cover; border-radius:4px;">
                                            </a>
                                            <div>
                                                <h6 class="mb-0">
                                                    <a href="{{ route('product.detail', $item->product->slug) }}">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($item->product->price, 0, ',', '.') }}đ
                                    </td>
                                    <td class="text-center">
                                        <div class="cart-plus-minus d-flex justify-content-center">
                                            <input type="number"
                                                value="{{ $item->quantity }}"
                                                min="1"
                                                class="cart-plus-minus-box cart-qty-input"
                                                data-cart-id="{{ $item->id }}">
                                        </div>
                                    </td>
                                    <td class="text-end item-total" style="font-weight:600;">
                                        {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}đ
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <p class="mb-3">Giỏ hàng của bạn đang trống.</p>
                                        <a href="{{ route('home') }}" class="btn theme-btn-1 btn-effect-1">Tiếp tục mua sắm</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top:20px; display:flex; gap:12px;">
                        <a href="{{ route('home') }}" class="btn theme-btn-2 btn-effect-2">
                            <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                        </a>
                        
                        @if(count($cartItems) > 0)
                        <button type="button" id="btn-clear-cart" class="btn theme-btn-2 btn-effect-2">
                            <i class="fas fa-trash me-2"></i>{{ 'Xóa' }} giỏ hàng
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            @if(count($cartItems) > 0)
            <div class="col-lg-4">
                <div class="shoping-cart-total" style="background:#f8f9fa; padding:20px; border-radius:6px;">
                    <h5 class="mb-4">Tóm tắt đơn hàng</h5>
                    <table class="table table-borderless" style="margin-bottom:0;">
                        <tbody>
                            <tr style="border-bottom:1px solid #e9ecef;">
                                <td class="ps-0">Tạm tính</td>
                                <td id="cart-subtotal" class="text-end pe-0">{{ number_format($total, 0, ',', '.') }}đ</td>
                            </tr>
                            <tr id="cart-discount-row" @style("border-bottom:1px solid #e9ecef;" . (empty($appliedCoupon) ? 'display:none;' : ''))>
                                <td class="ps-0">
                                    Giảm giá
                                    <span id="cart-coupon-code" style="color:#666; font-size:0.85rem;">
                                        {{ !empty($appliedCoupon) ? '(' . $appliedCoupon['code'] . ')' : '' }}
                                    </span>
                                </td>
                                <td id="cart-discount" class="text-end pe-0 text-success">-{{ number_format($discountAmount ?? 0, 0, ',', '.') }}đ</td>
                            </tr>
                            <tr style="border-bottom:1px solid #e9ecef;">
                                <td class="ps-0">Vận chuyển</td>
                                <td class="text-end pe-0 text-success">Miễn phí</td>
                            </tr>
                            <tr style="margin-top:12px;">
                                <td class="ps-0"><strong style="font-size:1.1rem;">Tổng cộng</strong></td>
                                <td class="text-end pe-0"><strong id="cart-final-total" style="font-size:1.1rem; color:#d9534f;">{{ number_format($finalTotal ?? $total, 0, ',', '.') }}đ</strong></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <a href="{{ route('checkout') }}" class="btn theme-btn-1 btn-effect-1 w-100 py-2">
                            <i class="fas fa-credit-card me-2"></i>Thanh toán
                        </a>
                    </div>

                    <hr style="margin:20px 0;">

                    <h6 class="mb-3"><i class="fas fa-tag me-2"></i>Mã ưu đãi</h6>
                    <div class="coupon-input-group">
                        <input
                            type="text"
                            id="cart-coupon-code-input"
                            name="cart-coupon"
                            placeholder="{{ 'Nhập mã giảm giá' }}"
                            value="{{ $appliedCoupon['code'] ?? '' }}"
                            class="form-control"
                            style="border-radius:4px 4px 0 0;">
                        <button type="button" id="apply-cart-coupon-btn" class="btn theme-btn-2 btn-effect-2 w-100" style="border-radius:0; margin-top:0;">Áp dụng</button>
                        <button type="button" id="remove-cart-coupon-btn" class="btn btn-light w-100" style="border-radius:0; margin-top:0; border:1px solid #dee2e6;" {{ empty($appliedCoupon) ? 'hidden' : '' }}>{{ 'Xóa' }} mã</button>
                        <small id="cart-coupon-message" style="display:block; padding:8px; background:#f8f9fa; border-radius:0 0 4px 4px; color:#666; text-align:center;">
                            @if(!empty($appliedCoupon))
                            Đã áp dụng mã: {{ $appliedCoupon['code'] }}
                            @else
                            Nhập mã để được giảm giá
                            @endif
                        </small>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection