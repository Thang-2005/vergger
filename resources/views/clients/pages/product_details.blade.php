@extends('layouts.client')

@section('title', 'chi tiết sản phẩm')
@section('breadcrumb', 'chi tiết sản phẩm')

@section('content')

<div class="ltn__shop-details-area pb-85">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="ltn__shop-details-inner mb-60">
                    <div class="row">

                        {{-- ẢNH SẢN PHẨM --}}
                        <div class="col-md-6">
                            <div class="ltn__shop-details-img-gallery">
                                <div class="ltn__shop-details-large-img">
                                    @foreach($product->image as $image)
                                    <div class="single-large-img">
                                        <a href="{{ asset('storage/uploads/product/'.$image->image) }}"
                                            data-rel="lightcase:myCollection">
                                            <img src="{{ asset('storage/uploads/product/'.$image->image) }}"
                                                alt="{{ $product->name }}">
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="ltn__shop-details-small-img slick-arrow-2">
                                    @foreach($product->image as $image)
                                    <div class="single-small-img">
                                        <img src="{{ asset('storage/uploads/product/'.$image->image) }}"
                                            alt="{{ $product->name }}">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- THÔNG TIN SẢN PHẨM --}}
                        <div class="col-md-6">
                            <div class="modal-product-info shop-details-info pl-0">
                                <div class="product-ratting">
                                    <ul>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                        <li class="review-total"><a href="#">( 95 Reviews )</a></li>
                                    </ul>
                                </div>
                                <h3>{{ $product->name }}</h3>
                                <div class="product-price">
                                    <span>{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                    <del>{{ number_format($product->price * 1.1, 0, ',', '.') }}đ</del>
                                </div>
                                <div class="modal-product-meta ltn__product-details-menu-1">
                                    <ul>
                                        <li>
                                            <strong>Danh mục:</strong>
                                            <span>
                                                <a href="javascript:void(0)">{{ $product->category->name }}</a>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="ltn__product-details-menu-2">
                                    <ul>
                                        <li>
                                            <div class="cart-plus-minus">
                                                <input type="number" value="1" min="1"
                                                    name="qtybutton" class="cart-plus-minus-box">
                                            </div>
                                        </li>
                                        <li>
                                            <a href="#" class="theme-btn-1 btn btn-effect-1"
                                                title="Thêm vào giỏ hàng"
                                                data-bs-toggle="modal"
                                                data-bs-target="#add_to_cart_modal-{{ $product->id }}">
                                                <i class="fas fa-shopping-cart"></i>
                                                <span>Thêm vào giỏ hàng</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="ltn__product-details-menu-3">
                                    <ul>
                                        <li>
                                            <a href="#" title="Yêu thích"
                                                class="btn-add-wishlist"
                                                data-bs-toggle="modal"
                                                data-bs-target="#liton_wishlist_modal-{{ $product->id }}"
                                                data-product-id="{{ $product->id }}">
                                                <i class="far fa-heart"></i>
                                                <span>Yêu thích</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <hr>
                                <div class="ltn__social-media">
                                    <ul>
                                        <li>Chia sẻ:</li>
                                        <li><a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="#" title="Linkedin"><i class="fab fa-linkedin"></i></a></li>
                                        <li><a href="#" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                                    </ul>
                                </div>
                                <hr>
                                <div class="ltn__safe-checkout">
                                    <h5>Đảm bảo thanh toán an toàn</h5>
                                    <img src="{{ asset('asset/client/img/icons/payment-2.png') }}" alt="Payment Image">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- TAB MÔ TẢ & ĐÁNH GIÁ --}}
                <div class="ltn__shop-details-tab-inner ltn__shop-details-tab-inner-2">
                    <div class="ltn__shop-details-tab-menu">
                        <div class="nav">
                            <a class="active show" data-bs-toggle="tab"
                                href="#liton_tab_details_decription">Mô tả sản phẩm</a>
                            <a data-bs-toggle="tab" href="#liton_tab_details_review">Đánh giá</a>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="liton_tab_details_decription">
                            <div class="ltn__shop-details-tab-content-inner">
                                <h4 class="title-2">{{ $product->name }}</h4>
                                <p>{{ $product->description }}</p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="liton_tab_details_review">
                            <div class="ltn__shop-details-tab-content-inner">
                                <h4 class="title-2">Đánh giá sản phẩm</h4>
                                <div class="product-ratting">
                                    <ul>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                        <li class="review-total"><a href="#">( 95 Reviews )</a></li>
                                    </ul>
                                </div>
                                <hr>
                                <div class="ltn__comment-area mb-30">
                                    <div class="ltn__comment-inner">
                                        <ul>
                                            <li>
                                                <div class="ltn__comment-item clearfix">
                                                    <div class="ltn__commenter-img">
                                                        <img src="{{ asset('asset/client/img/testimonial/1.jpg') }}" alt="Image">
                                                    </div>
                                                    <div class="ltn__commenter-comment">
                                                        <h6><a href="#">Adam Smit</a></h6>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                                                        <span class="ltn__comment-reply-btn">September 3, 2020</span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="ltn__comment-reply-area ltn__form-box mb-30">
                                    <form action="#">
                                        <h4 class="title-2">Thêm đánh giá</h4>
                                        <div class="input-item input-item-textarea ltn__custom-icon">
                                            <textarea placeholder="Nhận xét của bạn..."></textarea>
                                        </div>
                                        <div class="input-item input-item-name ltn__custom-icon">
                                            <input type="text" placeholder="Họ tên của bạn...">
                                        </div>
                                        <div class="input-item input-item-email ltn__custom-icon">
                                            <input type="email" placeholder="Email của bạn...">
                                        </div>
                                        <div class="btn-wrapper">
                                            <button class="btn theme-btn-1 btn-effect-1 text-uppercase"
                                                type="submit">Gửi đánh giá</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- SẢN PHẨM LIÊN QUAN --}}
@if($relatedProduct->count() > 0)
<div class="ltn__product-slider-area ltn__product-gutter pb-70 pt-30">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2">
                    <h6 class="section-subtitle ltn__secondary-color">// Khám phá thêm</h6>
                    <h2 class="section-title">Sản phẩm liên quan<span>.</span></h2>
                </div>
            </div>
        </div>
        <div class="row ltn__related-product-slider-one-active slick-arrow-1">
            @foreach($relatedProduct as $related)
            <div class="ltn__product-item ltn__product-item-3 text-center">
                <div class="product-img">
                    <a href="{{ route('product.detail', $related->slug) }}">
                        <img src="{{ $related->image_url }}" alt="{{ $related->name }}" loading="lazy">
                    </a>
                    <div class="product-badge">
                        <ul>
                            <li class="sale-badge">Mới</li>
                        </ul>
                    </div>
                    <div class="product-hover-action">
                        <ul>
                            <li>
                                <a href="javascript:void(0)" title="Xem nhanh"
                                    data-bs-toggle="modal"
                                    data-bs-target="#quick_view_modal-{{ $related->id }}">
                                    <i class="far fa-eye"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" title="Thêm vào giỏ hàng"
                                    data-bs-toggle="modal"
                                    data-bs-target="#add_to_cart_modal-{{ $related->id }}">
                                    <i class="fas fa-shopping-cart"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" title="Yêu thích"
                                    class="btn-add-wishlist"
                                    data-bs-toggle="modal"
                                    data-bs-target="#liton_wishlist_modal-{{ $related->id }}"
                                    data-product-id="{{ $related->id }}">
                                    <i class="far fa-heart"></i>
                                    
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="product-info">
                    <h3 class="product-title">
                        <a href="{{ route('product.detail', $related->slug) }}">{{ $related->name }}</a>
                    </h3>
                    <div class="product-price">
                        <span>{{ number_format($related->price, 0, ',', '.') }}đ</span>
                        <del>{{ number_format($related->price * 1.1, 0, ',', '.') }}đ</del>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- MODAL cho sản phẩm chính --}}
@include('clients.components.include.include_models', ['product' => $product])

{{-- MODAL cho sản phẩm liên quan --}}
@foreach($relatedProduct as $related)
@include('clients.components.include.include_models', ['product' => $related])
@endforeach

@endsection