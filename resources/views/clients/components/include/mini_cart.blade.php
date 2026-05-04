<div id="ltn__utilize-cart-menu" class="ltn__utilize ltn__utilize-cart-menu">
    <div class="ltn__utilize-menu-inner ltn__scrollbar">

        <div class="ltn__utilize-menu-head">
            <span class="ltn__utilize-menu-title">Giỏ hàng</span>
            <button class="ltn__utilize-close">×</button>
        </div>

        {{-- MINI CART LIST --}}
        <div class="mini-cart-product-area ltn__scrollbar">

            @forelse($cartItems as $cart)
            <div class="mini-cart-item clearfix">

                <div class="mini-cart-img">
                    <a href="{{ route('product.detail',$cart->product->slug) }}">
                        <img src="{{ $cart->product->image_url }}"
                            alt="{{ $cart->product->name }}">
                    </a>

                    {{-- nút xoá --}}
                    <span class="mini-cart-item-delete"
                        data-cart-id="{{ $cart->id }}">
                        <i class="icon-cancel"></i>
                    </span>
                </div>

                <div class="mini-cart-info">
                    <h6>
                        <a href="{{ route('product.detail',$cart->product->slug) }}">
                            {{ $cart->product->name }}
                        </a>
                    </h6>

                    <span class="mini-cart-quantity">
                        {{ $cart->quantity }} x
                        {{ number_format($cart->product->price,0,',','.') }}đ
                    </span>
                </div>

            </div>
            @empty

            {{-- khi chưa có sản phẩm --}}
            <div class="text-center p-4">
                <img src="{{ asset('asset/client/img/logo.png') }}"
                    width="80">
                <p class="mt-2">{{ 'Giỏ hàng đang trống' }}</p>
            </div>

            @endforelse

        </div>

        {{-- FOOTER --}}
        <div class="mini-cart-footer">

            <div class="mini-cart-sub-total">
                <h5>
                    Subtotal:
                    <span>
                        {{ number_format($cartTotal,0,',','.') }}đ
                    </span>
                </h5>
            </div>

            <div class="btn-wrapper">
                <a href="{{ route('cart.index') }}"
                    class="theme-btn-1 btn btn-effect-1">
                    {{ 'Xem giỏ hàng' }}
                </a>

                <a href="{{ route('checkout') }}"
                    class="theme-btn-2 btn btn-effect-2">
                    Thanh toán
                </a>
            </div>

            <p>Miễn phí vận chuyển cho tất cả đơn hàng trên 300.000VND</p>

        </div>

    </div>
</div>