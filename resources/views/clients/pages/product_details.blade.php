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
                                    @forelse($product->image as $image)
                                    <div class="single-large-img">
                                        <a href="{{ asset('storage/uploads/product/'.$image->image) }}"
                                            data-rel="lightcase:myCollection">
                                            <img src="{{ asset('storage/uploads/product/'.$image->image) }}"
                                                alt="{{ $product->name }}">
                                        </a>
                                    </div>
                                    @empty
                                    <div class="single-large-img">
                                        <a href="{{ asset('storage/uploads/product/default_product.jpg') }}"
                                            data-rel="lightcase:myCollection">
                                            <img src="{{ asset('storage/uploads/product/default_product.jpg') }}"
                                                alt="{{ $product->name }}">
                                        </a>
                                    </div>
                                    @endforelse
                                </div>
                                <div class="ltn__shop-details-small-img slick-arrow-2">
                                    @forelse($product->image as $image)
                                    <div class="single-small-img">
                                        <img src="{{ asset('storage/uploads/product/'.$image->image) }}"
                                            alt="{{ $product->name }}">
                                    </div>
                                    @empty
                                    <div class="single-small-img">
                                        <img src="{{ asset('storage/uploads/product/default_product.jpg') }}"
                                            alt="{{ $product->name }}">
                                    </div>
                                    @endforelse
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
                                            <strong>{{ __('messages.category') }}:</strong>
                                            <span>
                                                <a href="{{ route('category', $product->category->slug) }}">{{ $product->category->name }}</a>
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
                                                title="{{ __('messages.add_product') }} vào giỏ hàng"
                                                data-bs-toggle="modal"
                                                data-bs-target="#add_to_cart_modal-{{ $product->id }}">
                                                <i class="fas fa-shopping-cart"></i>
                                                <span>{{ __('messages.add_product') }} vào giỏ hàng</span>
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
                                href="#liton_tab_details_decription">{{ __('messages.description') }} sản phẩm</a>
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
                                <h4 class="title-2 mb-4">Đánh giá sản phẩm</h4>
                                
                                {{-- ALERT MESSAGES --}}
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                @if (session('warning'))
                                    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        {{ session('warning') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                {{-- RATING SUMMARY --}}
                                @if($product->reviews->count() > 0)
                                    <div class="card review-rating-summary p-4 mb-5">
                                        <div class="row align-items-center g-4">
                                            <div class="col-lg-4">
                                                <div class="text-center">
                                                    <div class="review-rating-value">
                                                        {{ number_format($product->reviews->avg('rating'), 1) }}
                                                    </div>
                                                    <div class="review-rating-stars mb-3">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $product->reviews->avg('rating'))
                                                                <i class="fas fa-star text-warning"></i>
                                                            @else
                                                                <i class="far fa-star text-warning"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <small class="review-rating-count">{{ $product->reviews->count() }} đánh giá</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                @for($i = 5; $i >= 1; $i--)
                                                    @php
                                                        $count = $product->reviews->where('rating', $i)->count();
                                                        $percentage = $product->reviews->count() > 0 ? ($count / $product->reviews->count()) * 100 : 0;
                                                        $percentText = (int)$percentage;
                                                    @endphp
                                                    <div class="review-rating-bar-group">
                                                        <small class="review-rating-bar-label">{{ $i }} <i class="fas fa-star text-warning"></i></small>
                                                        <div class="review-rating-bar-container">
                                                            <div class="review-rating-bar" data-width="{{ $percentText }}"></div>
                                                        </div>
                                                        <small class="review-rating-bar-count">{{ $count }}</small>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- REVIEWS LIST --}}
                                @if($product->reviews->count() > 0)
                                    <div class="review-list">
                                        <h5 class="review-list-title">Những đánh giá gần đây</h5>
                                        @foreach($product->reviews->sortByDesc('created_at') as $review)
                                            <div class="review-item">
                                                <div class="row align-items-start">
                                                    <div class="col-auto">
                                                        <img src="{{ $review->user->avatar_url }}" 
                                                             alt="{{ $review->user->name }}"
                                                             class="review-item-avatar">
                                                    </div>
                                                    <div class="col">
                                                        <div class="review-item-header">
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <div>
                                                                    <h6 class="review-item-name">{{ $review->user->name }}</h6>
                                                                    <div class="review-item-stars">
                                                                        @for($i = 1; $i <= 5; $i++)
                                                                            @if($i <= $review->rating)
                                                                                <i class="fas fa-star review-item-star-icon"></i>
                                                                            @else
                                                                                <i class="far fa-star review-item-star-icon"></i>
                                                                            @endif
                                                                        @endfor
                                                                    </div>
                                                                </div>
                                                                <div class="review-item-time-actions">
                                                                    <small class="review-item-time">{{ $review->created_at->diffForHumans() }}</small>
                                                                    @if(Auth::check() && $review->user_id == Auth::id())
                                                                        <div class="review-action-menu-wrapper">
                                                                            <button type="button"
                                                                                    class="review-action-menu-toggle"
                                                                                    data-review-menu-toggle="{{ $review->id }}"
                                                                                    aria-label="Tùy chọn đánh giá"
                                                                                    aria-expanded="false">
                                                                                <i class="fas fa-ellipsis-v"></i>
                                                                            </button>
                                                                            <div class="review-action-menu d-none" data-review-menu="{{ $review->id }}">
                                                                                <button type="button"
                                                                                        class="review-action-menu-item edit-review-trigger"
                                                                                        data-review-id="{{ $review->id }}"
                                                                                        data-rating="{{ $review->rating }}"
                                                                                        data-comment="{{ htmlspecialchars($review->comment) }}">
                                                                                    <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                                                                                </button>
                                                                                <form action="{{ route('review.destroy', $review->id) }}"
                                                                                      method="POST"
                                                                                      onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit" class="review-action-menu-item review-action-menu-item-danger">
                                                                                        <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                                                                                    </button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="review-item-comment">{{ $review->comment }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="review-alert review-alert-info" role="alert">
                                        <i class="fas fa-info-circle review-alert-icon"></i>
                                        Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên đánh giá!
                                    </div>
                                @endif

                                {{-- ADD/EDIT REVIEW FORM --}}
                                @if(Auth::check())
                                    @php
                                        $userReview = $product->reviews->where('user_id', Auth::id())->first();
                                        $showReviewForm = !$userReview || $errors->has('rating') || $errors->has('comment');
                                    @endphp
                                    @if($userReview && !$showReviewForm)
                                        <div class="review-alert review-alert-info" id="review-edit-hint" role="alert">
                                            <i class="fas fa-pen review-alert-icon"></i>
                                            Nhấn nút <strong>{{ __('messages.edit') }}</strong> ở đánh giá của bạn để chỉnh sửa nội dung.
                                        </div>
                                    @endif
                                    <div class="add-review-section {{ $showReviewForm ? '' : 'd-none' }}" id="review-form-section">
                                        <h4 class="add-review-title" id="form-title">
                                            @if($userReview)
                                                Chỉnh sửa đánh giá
                                            @else
                                                Chia sẻ đánh giá của bạn
                                            @endif
                                        </h4>
                                            <form action="{{ $userReview ? route('review.update', $userReview->id) : route('review.store') }}"
                                                method="POST"
                                                id="review-form"
                                                data-has-review="{{ $userReview ? '1' : '0' }}"
                                                data-original-rating="{{ $userReview->rating ?? '' }}"
                                                data-original-comment="{{ $userReview->comment ?? '' }}">
                                            @csrf
                                            @if($userReview)
                                                @method('PUT')
                                            @endif
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            
                                            {{-- RATING STARS --}}
                                            <div class="mb-4">
                                                <label class="review-form-label">
                                                    Đánh giá <span class="review-form-required">*</span>
                                                </label>
                                                <div class="star-rating" id="star-rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="@if($userReview && $i <= $userReview->rating)fas @else far @endif fa-star star-item" data-value="{{ $i }}"></i>
                                                    @endfor
                                                </div>
                                                <input type="hidden" name="rating" id="rating-input" value="{{ $userReview->rating ?? '' }}">
                                                @error('rating')
                                                    <div class="text-danger small mt-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- COMMENT --}}
                                            <div class="mb-4">
                                                <label for="comment" class="review-form-label">
                                                    Nhận xét <span class="review-form-required">*</span>
                                                </label>
                                                <textarea class="form-control review-form-textarea @error('comment') is-invalid @enderror" 
                                                          id="comment" 
                                                          name="comment" 
                                                          placeholder="{{ __('messages.share_experience') }}"
                                                          required>{{ $userReview->comment ?? '' }}</textarea>
                                                <small class="review-form-help-text">Tối thiểu 10 ký tự</small>
                                                @error('comment')
                                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- SUBMIT BUTTON --}}
                                            <div class="btn-wrapper">
                                                <button class="review-form-submit-btn" type="submit" id="submit-btn">
                                                    <i class="fas fa-paper-plane"></i> 
                                                    <span id="submit-text">{{ $userReview ? 'Cập nhật đánh giá' : '{{ __('messages.send') }} đánh giá' }}</span>
                                                </button>
                                                @if($userReview)
                                                    <button class="review-form-cancel-btn" type="button" onclick="cancelEdit()">
                                                        <i class="fas fa-times"></i> {{ __('messages.cancel') }}
                                                    </button>
                                                @endif
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    <div class="review-alert review-alert-warning" role="alert">
                                        <i class="fas fa-sign-in-alt review-alert-icon"></i>
                                        <a href="{{ route('login') }}" class="review-alert-link">Đăng nhập</a> để đánh giá sản phẩm này.
                                    </div>
                                @endif
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
                                <a href="javascript:void(0)" title="{{ __('messages.add_product') }} vào giỏ hàng"
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