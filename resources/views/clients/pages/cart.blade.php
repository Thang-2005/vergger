@extends('layouts.client')

@section('title', 'Giỏ hàng | ' . config('app.name'))
@section('breadcrumb', 'Giỏ hàng')

@section('content')

<div class="liton__shoping-cart-area mb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping-cart-inner">
                    <div class="shoping-cart-table table-responsive">
                        <table class="table">
                            <tbody>
                                @forelse ($cartItems as $item)
                                <tr>
                                    {{-- JS: $(document).on('click', '.btn-remove-cart') + data-cart-id --}}
                                    <td class="cart-product-remove">
                                        <a href="#" class="btn-remove-cart" data-cart-id="{{ $item->id }}">x</a>
                                    </td>
                                    <td class="cart-product-image">
                                        <a href="{{ route('product.detail', $item->product->id) }}">
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                                        </a>
                                    </td>
                                    <td class="cart-product-info">
                                        <h4>
                                            <a href="{{ route('product.detail', $item->product->id) }}">
                                                {{ $item->product->name }}
                                            </a>
                                        </h4>
                                    </td>
                                    <td class="cart-product-price">
                                        {{ number_format($item->product->price, 0, ',', '.') }}đ
                                    </td>
                                    <td class="cart-product-quantity">
                                        <div class="cart-plus-minus">
                                            {{-- JS: $(document).on('change', '.cart-qty-input') + data-cart-id --}}
                                            <input type="number"
                                                   value="{{ $item->quantity }}"
                                                   min="1"
                                                   class="cart-plus-minus-box cart-qty-input"
                                                   data-cart-id="{{ $item->id }}">
                                        </div>
                                    </td>
                                    {{-- JS: $row.find('.item-total').text(res.item_total) --}}
                                    <td class="cart-product-subtotal item-total">
                                        {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}đ
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        Giỏ hàng của bạn đang trống.
                                        <a href="{{ route('home') }}">Tiếp tục mua sắm</a>
                                    </td>
                                </tr>
                                @endforelse

                                <tr class="cart-coupon-row">
                                    <td colspan="6">
                                        <div class="cart-coupon">
                                            <input type="text" name="cart-coupon" placeholder="Coupon code">
                                            <button type="button" class="btn theme-btn-2 btn-effect-2">Apply Coupon</button>
                                        </div>
                                    </td>
                                    <td>
                                        {{-- JS: $(document).on('click', '#btn-clear-cart') --}}
                                        <button type="button" id="btn-clear-cart" class="btn theme-btn-2 btn-effect-2">
                                            Xóa giỏ hàng
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="shoping-cart-total mt-50">
                        <h4>Tổng giỏ hàng</h4>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Tạm tính</td>
                                    {{-- JS: $('#cart-total').text(res.total) --}}
                                    <td id="cart-total">{{ number_format($total, 0, ',', '.') }}đ</td>
                                </tr>
                                <tr>
                                    <td>Phí vận chuyển</td>
                                    <td>Miễn phí</td>
                                </tr>
                                <tr>
                                    <td><strong>Tổng cộng</strong></td>
                                    <td><strong>{{ number_format($total, 0, ',', '.') }}đ</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="btn-wrapper text-right text-end">
                            <a href="#" class="theme-btn-1 btn btn-effect-1">
                                Tiến hành thanh toán
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection