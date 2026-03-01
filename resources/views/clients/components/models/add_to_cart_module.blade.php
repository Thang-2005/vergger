<div class="ltn__modal-area ltn__add-to-cart-modal-area">
    <div class="modal fade" id="add_to_cart_modal-{{ $product->id }}" tabindex="-1">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="ltn__quick-view-modal-inner">
                        <div class="modal-product-item">
                            <div class="row">
                                <div class="col-12">
                                    <div class="modal-product-img">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    </div>
                                    <div class="modal-product-info">
                                        <h5>{{ $product->name }}</h5>
                                        <div class="product-price mb-3">
                                            <span>{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                        </div>

                                        {{-- Số lượng + Nút thêm --}}
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <div class="cart-plus-minus">
                                                <input type="number"
                                                       id="qty-{{ $product->id }}"
                                                       value="1" min="1" max="99"
                                                       class="cart-plus-minus-box">
                                            </div>
                                            <button type="button"
                                                    class="theme-btn-1 btn btn-effect-1 btn-add-to-cart"
                                                    data-product-id="{{ $product->id }}"
                                                    data-qty-id="qty-{{ $product->id }}">
                                                <i class="fas fa-shopping-cart me-1"></i>
                                                Thêm vào giỏ hàng
                                            </button>
                                        </div>

                                        <div class="btn-wrapper">
                                            <a href="{{ route('cart.index') }}" class="theme-btn-2 btn btn-effect-2">
                                                <i class="fas fa-eye me-1"></i> Xem giỏ hàng
                                            </a>
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
</div>