<!-- Utilize Cart Menu Start -->
<div id="ltn__utilize-cart-menu" class="ltn__utilize ltn__utilize-cart-menu">
    <div class="ltn__utilize-menu-inner ltn__scrollbar">

        <div class="ltn__utilize-menu-head">
            <span class="ltn__utilize-menu-title">Cart</span>
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
                    <span class="btn-remove-cart"
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
                <p class="mt-2">Giỏ hàng đang trống</p>
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
                    View Cart
                </a>

                <a href="#"
                    class="theme-btn-2 btn btn-effect-2">
                    Checkout
                </a>
            </div>

            <p>Free Shipping on All Orders Over 100.000đ!</p>

        </div>

    </div>
</div>
<!-- Utilize Cart Menu End -->
<header class="ltn__header-area ltn__header-5 ltn__header-transparent-- gradient-color-4---">
    <!-- ltn__header-top-area start -->
    <div class="ltn__header-top-area">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="ltn__top-bar-menu">
                        <ul>
                            <li><a href="locations.html"><i class="icon-placeholder"></i> Ngu Hanh Son, Da
                                    Nang</a></li>
                            <li><a href="mailto:minhdien.dev@gmail.com?Subject=Contact%20with%20to%20you"><i
                                        class="icon-mail"></i> minhdien.dev@gmail.com</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="top-bar-right text-right text-end">
                        <div class="ltn__top-bar-menu">
                            <ul>
                                <li>
                                    <!-- ltn__social-media -->
                                    <div class="ltn__social-media">
                                        <ul>
                                            <li><a href="#" title="Facebook"><i
                                                        class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                                            </li>

                                            <li><a href="#" title="Instagram"><i
                                                        class="fab fa-instagram"></i></a></li>
                                            <li><a href="#" title="Dribbble"><i class="fab fa-dribbble"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ltn__header-top-area end -->

    <!-- ltn__header-middle-area start -->
    <div
        class="ltn__header-middle-area ltn__header-sticky ltn__sticky-bg-white sticky-active-into-mobile ltn__logo-right-menu-option plr--9---">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="site-logo-wrap">
                        <div class="site-logo">
                            <a href="{{ route('home') }}"><img src="{{ asset('asset/client/img/logo.png') }}" alt="Logo"></a>
                        </div>
                    </div>
                </div>
                <div class="col header-menu-column menu-color-white---">
                    <div class="header-menu d-none d-xl-block">
                        <nav>
                            <div class="ltn__main-menu">
                                <ul>
                                    <li class="menu-icon"><a href="{{ route('home') }}">Trang chủ</a> </li>
                                    <li class="menu-icon"><a href="{{ route('about') }}">Về chúng tôi</a>
                                        <ul>
                                            <li><a href="{{ route('about') }}">Về chúng tôi</a></li>
                                            <li><a href="{{ route('service') }}">Dịch vụ</a></li>
                                            <li><a href="{{ route('team') }}">Team</a></li>
                                            <li><a href="{{ route('faq') }}">FAQ</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-icon"><a href="{{ route('product') }}">Cửa hàng</a>
                                    </li>
                                    <li><a href="{{ route('contact') }}">Liên hệ</a></li>
                                    <li class="special-link"><a href="{{ route('contact') }}">GET A QUOTE</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
                <div class="ltn__header-options ltn__header-options-2 mb-sm-20">
                    <!-- header-search-1 -->
                    <div class="header-search-wrap">
                        <div class="header-search-1">
                            <div class="search-icon">
                                <i class="icon-search for-search-show"></i>
                                <i class="icon-cancel  for-search-close"></i>
                            </div>
                        </div>
                        <div class="header-search-1-form">
                            <form id="search-form" method="get" action="{{ route('products.search') }}">
                                <input type="text" name="search" value="" placeholder="Search here..." />
                                <button type="submit">
                                    <span><i class="icon-search"></i></span>
                                </button>
                            </form>
                        </div>
                    </div>
                    <!-- user-menu -->
                    <div class="ltn__drop-menu user-menu">
                        <ul>
                            <li>
                                <a href="{{ route('account') }}"><i class="icon-user"></i></a>
                                <ul>
                                    @if(Auth::check())
                                    <li><a href="{{ route('account') }}">Tài khoản</a></li>
                                    <li>
                                        <a href="{{ route('wishlist.index') }}">
                                            Yêu thích

                                            @php
                                            $wishlistCount = auth()->check()
                                            ? \App\Models\Wishlist::where('user_id', auth()->id())->count()
                                            : 0;
                                            @endphp

                                            @if($wishlistCount > 0)
                                            <sup class="wishlist-count">
                                                {{ $wishlistCount }}
                                            </sup>
                                            @endif

                                        </a>
                                    </li>
                                    <li><a href="#" data-url="{{ route('logout.customer') }}" class="logoutBtn">Đăng xuất</a></li>
                                    @else
                                    <li><a href="{{route('login')}}">Đăng nhập</a></li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!-- mini-cart -->
                    <div class="mini-cart-icon">
                        <a href="#ltn__utilize-cart-menu" class="ltn__utilize-toggle">
                            <i class="icon-shopping-cart"></i>

                            @php
                            if(auth()->check()){
                            $cartCount = \App\Models\CartItem::where(
                            'user_id',
                            auth()->id()
                            )->sum('quantity');
                            } else {
                            $cartCount = session('cart')
                            ? array_sum(array_column(session('cart'), 'quantity'))
                            : 0;
                            }
                            @endphp

                            @if($cartCount && $cartCount > 0)
                            <sup id="cart_count">{{ $cartCount }}</sup>
                            @endif

                        </a>
                    </div>
                    <!-- mini-cart -->
                    <!-- Mobile Menu Button -->
                    <div class="mobile-menu-toggle d-xl-none">
                        <a href="#ltn__utilize-mobile-menu" class="ltn__utilize-toggle">
                            <svg viewBox="0 0 800 600">
                                <path
                                    d="M300,220 C300,220 520,220 540,220 C740,220 640,540 520,420 C440,340 300,200 300,200"
                                    id="top"></path>
                                <path d="M300,320 L540,320" id="middle"></path>
                                <path
                                    d="M300,210 C300,210 520,210 540,210 C740,210 640,530 520,410 C440,330 300,190 300,190"
                                    id="bottom"
                                    transform="translate(480, 320) scale(1, -1) translate(-480, -318) "></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ltn__header-middle-area end -->
</header>
<!-- HEADER AREA END -->

@include ('clients.partials.utilize_mobile')