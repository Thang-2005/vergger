@extends('layouts.client_home')

@section ('title','Trang chủ')

@section ('content')

    <div class="ltn__utilize-overlay"></div>

    <!-- KHU VỰC SLIDER BẮT ĐẦU -->
    <div class="ltn__slider-area ltn__slider-3 section-bg-1">
        <div class="ltn__slide-one-active slick-slide-arrow-1 slick-slide-dots-1">

            <!-- Slide 1 -->
            <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-3 ltn__slide-item-3-normal bg-image"
                data-bg="{{ asset('asset/client/img/slider/13.jpg') }}">
                <div class="ltn__slide-item-inner">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 align-self-center">
                                <div class="slide-item-info">
                                    <div class="slide-item-info-inner ltn__slide-animation">
                                        <h6 class="slide-sub-title animated">
                                            <img src="{{ asset('asset/client/img/icons/icon-img/1.png') }}" alt="#">
                                            100% Sản phẩm chính hãng
                                        </h6>
                                        <h1 class="slide-title animated">Thực phẩm yêu thích <br> từ khu vườn của chúng tôi</h1>
                                        <div class="slide-brief animated">
                                            <p>Chúng tôi mang đến những sản phẩm tươi ngon, sạch sẽ và an toàn nhất từ thiên nhiên đến bàn ăn của bạn.</p>
                                        </div>
                                        <div class="btn-wrapper animated">
                                            <a href="#" class="theme-btn-1 btn btn-effect-1 text-uppercase">Khám phá sản phẩm</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-3 ltn__slide-item-3-normal bg-image"
                data-bg="{{ asset('asset/client/img/slider/14.jpg') }}">
                <div class="ltn__slide-item-inner text-right text-end">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 align-self-center">
                                <div class="slide-item-info">
                                    <div class="slide-item-info-inner ltn__slide-animation">
                                        <h6 class="slide-sub-title ltn__secondary-color animated">// THỰC PHẨM SẠCH & TỰ NHIÊN</h6>
                                        <h1 class="slide-title animated">Ngon miệng & Tốt cho sức khỏe <br> Thực phẩm hữu cơ</h1>
                                        <div class="slide-brief animated">
                                            <p>Chúng tôi mang đến những sản phẩm tươi ngon, sạch sẽ và an toàn nhất từ thiên nhiên đến bàn ăn của bạn.</p>
                                        </div>
                                        <div class="btn-wrapper animated">
                                            <a href="#" class="theme-btn-1 btn btn-effect-1 text-uppercase">Khám phá sản phẩm</a>
                                            <a href="#" class="btn btn-transparent btn-effect-3">TÌM HIỂU THÊM</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- KHU VỰC SLIDER KẾT THÚC -->

    <!-- KHU VỰC BANNER BẮT ĐẦU -->
    <div class="ltn__banner-area mt-120 mb-90">
        <div class="container">
            <div class="row ltn__custom-gutter--- justify-content-center">
                <div class="col-lg-6 col-md-6">
                    <div class="ltn__banner-item">
                        <div class="ltn__banner-img">
                            <a href="#">
                                <img src="{{ asset('asset/client/img/banner/13.png') }}" alt="Ảnh Banner">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ltn__banner-item">
                                <div class="ltn__banner-img">
                                    <a href="#">
                                        <img src="{{ asset('asset/client/img/banner/14.png') }}" alt="Ảnh Banner">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="ltn__banner-item">
                                <div class="ltn__banner-img">
                                    <a href="#">
                                        <img src="{{ asset('asset/client/img/banner/15.png') }}" alt="Ảnh Banner">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- KHU VỰC BANNER KẾT THÚC -->

    <!-- KHU VỰC DANH MỤC BẮT ĐẦU -->
    <div class="ltn__category-area section-bg-1-- ltn__primary-bg before-bg-1 bg-image bg-overlay-theme-black-5--0 pt-115 pb-90"
        data-bg="{{ asset('asset/client/img/bg/5.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h1 class="section-title white-color">Danh mục sản phẩm</h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__category-slider-active slick-arrow-1">
                @foreach($categories as $category)
                <div class="ltn__category-item-wrapper">
                    <div class="ltn__category-item ltn__category-item-3 text-center">
                        <div class="ltn__category-item-img">
                            <a href="#">
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                            </a>
                        </div>
                        <div class="ltn__category-item-name">
                            <h5>{{ $category->name }}</h5>
                            <h6>{{ $category->products()->count() }} sản phẩm</h6>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- KHU VỰC DANH MỤC KẾT THÚC -->

    <!-- KHU VỰC SẢN PHẨM NỔI BẬT BẮT ĐẦU -->
    <div class="ltn__product-tab-area ltn__product-gutter pt-115 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h1 class="section-title">Sản phẩm nổi bật</h1>
                    </div>
                    <div class="ltn__tab-menu ltn__tab-menu-2 ltn__tab-menu-top-right-- text-uppercase text-center">
                        <div class="nav">
                            @foreach($categories as $category)
                                <a class="{{ $loop->first ? 'active show' : '' }}"
                                   data-bs-toggle="tab"
                                   href="#tab_{{ $category->id }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-content">
                        @foreach($categories as $category)
                        <div class="tab-pane fade {{ $loop->first ? 'active show' : '' }}" id="tab_{{ $category->id }}">
                            <div class="ltn__product-tab-content-inner">
                                <div class="row ltn__tab-product-slider-one-active slick-arrow-1">
                                    @forelse($category->products as $product)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="ltn__product-item ltn__product-item-3 text-center">
                                            <div class="product-img">
                                                <a href="#" title="{{ $product->name }}"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#quick_view_modal-{{ $product->id }}">
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                                </a>
                                                @if($product->discount)
                                                <div class="product-badge">
                                                    <ul>
                                                        <li class="sale-badge">-{{ $product->discount }}%</li>
                                                    </ul>
                                                </div>
                                                @endif
                                                <div class="product-hover-action">
                                                    <ul>
                                                        <li>
                                                            <a href="#" title="Xem nhanh"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#quick_view_modal-{{ $product->id }}">
                                                                <i class="far fa-eye"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Thêm vào giỏ hàng"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#add_to_cart_modal-{{ $product->id }}">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Yêu thích"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#liton_wishlist_modal-{{ $product->id }}">
                                                                <i class="far fa-heart"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-info">
                                                <div class="product-ratting">
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                                        <li class="review-total">
                                                            <a href="#"> ({{ $product->reviews_count ?? 0 }} đánh giá)</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <h2 class="product-title">
                                                    <a href="#" title="{{ $product->name }}">{{ $product->name }}</a>
                                                </h2>
                                                <div class="product-price">
                                                    <span>{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                                    @if($product->original_price)
                                                    <del>{{ number_format($product->original_price, 0, ',', '.') }}đ</del>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-12 text-center">
                                        <p>Chưa có sản phẩm trong danh mục này.</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- KHU VỰC SẢN PHẨM NỔI BẬT KẾT THÚC -->

    <!-- KHU VỰC THỐNG KÊ BẮT ĐẦU -->
    <div class="ltn__counterup-area bg-image bg-overlay-theme-black-80 pt-115 pb-70"
        data-bg="{{ asset('asset/client/img/bg/5.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 align-self-center">
                    <div class="ltn__counterup-item-3 text-color-white text-center">
                        <div class="counter-icon">
                            <img src="{{ asset('asset/client/img/icons/icon-img/2.png') }}" alt="#">
                        </div>
                        <h1><span class="counter">733</span><span class="counterUp-icon">+</span></h1>
                        <h6>Khách hàng thân thiết</h6>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 align-self-center">
                    <div class="ltn__counterup-item-3 text-color-white text-center">
                        <div class="counter-icon">
                            <img src="{{ asset('asset/client/img/icons/icon-img/3.png') }}" alt="#">
                        </div>
                        <h1><span class="counter">33</span><span class="counterUp-letter">K</span><span class="counterUp-icon">+</span></h1>
                        <h6>Đơn hàng đã xử lý</h6>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 align-self-center">
                    <div class="ltn__counterup-item-3 text-color-white text-center">
                        <div class="counter-icon">
                            <img src="{{ asset('asset/client/img/icons/icon-img/4.png') }}" alt="#">
                        </div>
                        <h1><span class="counter">100</span><span class="counterUp-icon">+</span></h1>
                        <h6>Giải thưởng đạt được</h6>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 align-self-center">
                    <div class="ltn__counterup-item-3 text-color-white text-center">
                        <div class="counter-icon">
                            <img src="{{ asset('asset/client/img/icons/icon-img/5.png') }}" alt="#">
                        </div>
                        <h1><span class="counter">21</span><span class="counterUp-icon">+</span></h1>
                        <h6>Tỉnh thành phủ sóng</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- KHU VỰC THỐNG KÊ KẾT THÚC -->

    <!-- KHU VỰC SẢN PHẨM BÁN CHẠY BẮT ĐẦU -->
    <div class="ltn__product-area ltn__product-gutter pt-115 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h1 class="section-title">Sản phẩm bán chạy</h1>
                    </div>
                </div>
            </div>
            <div class="row slick-arrow-1">
                @forelse($bestSellingProduct as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                    <div class="ltn__product-item ltn__product-item-3 text-left">
                        <div class="product-img">
                            <a href="#" title="{{ $product->name }}"
                               data-bs-toggle="modal"
                               data-bs-target="#quick_view_modal-{{ $product->id }}">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                            </a>
                            <div class="product-badge">
                                <ul>
                                    <li class="sale-badge">Bán chạy</li>
                                </ul>
                            </div>
                            <div class="product-hover-action">
                                <ul>
                                    <li>
                                        <a href="#" title="Xem nhanh"
                                           data-bs-toggle="modal"
                                           data-bs-target="#quick_view_modal-{{ $product->id }}">
                                            <i class="far fa-eye"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="Thêm vào giỏ hàng"
                                           data-bs-toggle="modal"
                                           data-bs-target="#add_to_cart_modal-{{ $product->id }}">
                                            <i class="fas fa-shopping-cart"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="Yêu thích"
                                           data-bs-toggle="modal"
                                           data-bs-target="#liton_wishlist_modal-{{ $product->id }}">
                                            <i class="far fa-heart"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-ratting">
                                <ul>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                    <li><a href="#"><i class="far fa-star"></i></a></li>
                                </ul>
                            </div>
                            <h2 class="product-title">
                                <a href="#" title="{{ $product->name }}"
                                   data-bs-toggle="modal"
                                   data-bs-target="#quick_view_modal-{{ $product->id }}">{{ $product->name }}</a>
                            </h2>
                            <div class="product-price">
                                <span>{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                @if($product->original_price)
                                <del>{{ number_format($product->original_price, 0, ',', '.') }}đ</del>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p>Chưa có sản phẩm bán chạy.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- KHU VỰC SẢN PHẨM BÁN CHẠY KẾT THÚC -->

    <!-- KHU VỰC CALL TO ACTION BẮT ĐẦU -->
    <div class="ltn__call-to-action-area ltn__call-to-action-4 bg-image pt-115 pb-120"
        data-bg="{{ asset('asset/client/img/bg/6.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="call-to-action-inner call-to-action-inner-4 text-center">
                        <div class="section-title-area ltn__section-title-2">
                            <h6 class="section-subtitle ltn__secondary-color">// Nếu bạn có bất kỳ câu hỏi nào //</h6>
                            <h1 class="section-title white-color">0987 789 789</h1>
                        </div>
                        <div class="btn-wrapper">
                            <a href="tel:+0987789789" class="theme-btn-1 btn btn-effect-1">GỌI NGAY</a>
                            <a href="#contact" class="btn btn-transparent btn-effect-4 white-color">LIÊN HỆ CHÚNG TÔI</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ltn__call-to-4-img-1">
            <img src="{{ asset('asset/client/img/bg/11.png') }}" alt="#">
        </div>
        <div class="ltn__call-to-4-img-2">
            <img src="{{ asset('asset/client/img/bg/12.png') }}" alt="#">
        </div>
    </div>
    <!-- KHU VỰC CALL TO ACTION KẾT THÚC -->

    <!-- KHU VỰC TÍNH NĂNG BẮT ĐẦU -->
    <div class="ltn__feature-area before-bg-bottom-2-- mb--30--- plr--5 mb-120">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__feature-item-box-wrap ltn__border-between-column white-bg">
                        <div class="row">
                            <div class="col-xl-3 col-md-6 col-12">
                                <div class="ltn__feature-item ltn__feature-item-8">
                                    <div class="ltn__feature-icon">
                                        <img src="{{ asset('asset/client/img/icons/icon-img/11.png') }}" alt="#">
                                    </div>
                                    <div class="ltn__feature-info">
                                        <h4>Sản phẩm chọn lọc</h4>
                                        <p>Cung cấp sản phẩm chọn lọc chất lượng cao cho mọi đơn hàng</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 col-12">
                                <div class="ltn__feature-item ltn__feature-item-8">
                                    <div class="ltn__feature-icon">
                                        <img src="{{ asset('asset/client/img/icons/icon-img/12.png') }}" alt="#">
                                    </div>
                                    <div class="ltn__feature-info">
                                        <h4>Thủ công tinh tế</h4>
                                        <p>Chúng tôi đảm bảo chất lượng sản phẩm — đó là mục tiêu hàng đầu của chúng tôi</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 col-12">
                                <div class="ltn__feature-item ltn__feature-item-8">
                                    <div class="ltn__feature-icon">
                                        <img src="{{ asset('asset/client/img/icons/icon-img/13.png') }}" alt="#">
                                    </div>
                                    <div class="ltn__feature-info">
                                        <h4>Thực phẩm tự nhiên</h4>
                                        <p>Đổi trả sản phẩm trong vòng 3 ngày cho bất kỳ sản phẩm nào bạn mua</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 col-12">
                                <div class="ltn__feature-item ltn__feature-item-8">
                                    <div class="ltn__feature-icon">
                                        <img src="{{ asset('asset/client/img/icons/icon-img/14.png') }}" alt="#">
                                    </div>
                                    <div class="ltn__feature-info">
                                        <h4>Giao hàng tận nơi miễn phí</h4>
                                        <p>Chúng tôi đảm bảo chất lượng sản phẩm để bạn hoàn toàn yên tâm</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- KHU VỰC TÍNH NĂNG KẾT THÚC -->

@endsection