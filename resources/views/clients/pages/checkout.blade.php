@extends('layouts.client')

@section('title','Thanh toán')
@section('breadcrumb','Thanh toán')

@section('content')

<div class="ltn__checkout-area pt-100 pb-100">
    <div class="container">
        <!-- ALERT MESSAGES -->
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Lỗi!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Cảnh báo!</strong> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
            @csrf

        <div class="row">
            <!-- CART ITEMS (Left Column) -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart me-2"></i>Giỏ hàng của bạn
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($cartItems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th class="text-center" style="width: 80px;">Giá</th>
                                        <th class="text-center" style="width: 80px;">Số lượng</th>
                                        <th class="text-end" style="width: 120px;">Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItems as $item)
                                    <tr class="align-middle">
                                        <td>
                                            <div class="d-flex gap-3">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ $item->product->image_url }}"
                                                        alt="{{ $item->product->name }}"
                                                        class="rounded"
                                                        style="width: 70px; height: 70px; object-fit: cover;">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                    <small class="text-muted">SKU: #{{ $item->product->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <strong>{{ number_format($item->product->price, 0, ',', '.') }}đ</strong>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            <strong class="text-primary">{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}đ</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox" style="font-size: 48px; color: #ddd;"></i>
                            <p class="text-muted mt-3 mb-0">Giỏ hàng trống</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- SHIPPING & ORDER SUMMARY (Right Column) -->
            <div class="col-lg-4">
                <!-- SHIPPING ADDRESS SECTION -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>Địa chỉ giao hàng
                        </h5>
                    </div>
                    <div class="card-body">

                            <!-- Existing Addresses -->
                            @if ($addresses->count() > 0)
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label fw-bold mb-0">Giao đến:</label>
                                    <button type="button" id="change-address-btn" class="btn btn-sm btn-outline-primary">Thay đổi</button>
                                </div>

                                <!-- Selected Address Display -->
                                <div id="selected-address-view" class="p-3 border rounded bg-light">
                                    @if($defaultAddress)
                                    <strong class="d-block">{{ $defaultAddress->full_name }}</strong>
                                    <small class="text-muted d-block">{{ $defaultAddress->phone }}</small>
                                    <small class="text-muted">{{ $defaultAddress->address }}, {{ $defaultAddress->city }}</small>
                                    @else
                                    <small class="text-muted">Vui lòng chọn một địa chỉ.</small>
                                    @endif
                                </div>

                                <!-- Address List (Initially Hidden) -->
                                <div id="address-list-container" class="mt-3" style="display: none;">
                                    <div class="address-list" style="max-height: 280px; overflow-y: auto;">
                                        @foreach ($addresses as $address)
                                        <div class="form-check mb-3 pb-3 border-bottom">
                                            <input class="form-check-input address-radio"
                                                type="radio"
                                                name="shipping_address_id"
                                                id="address_{{ $address->id }}"
                                                value="{{ $address->id }}"
                                                data-name="{{ $address->full_name }}"
                                                data-phone="{{ $address->phone }}"
                                                data-address="{{ $address->address }}, {{ $address->city }}"
                                                @if ($address->id === $defaultAddress?->id) checked @endif>
                                            <label class="form-check-label w-100" for="address_{{ $address->id }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <strong class="d-block">{{ $address->full_name }}</strong>
                                                        <small class="text-muted d-block">{{ $address->phone }}</small>
                                                        <small class="text-muted">{{ $address->address }}, {{ $address->city }}</small>
                                                    </div>
                                                    @if ($address->default)
                                                    <span class="badge bg-success">Mặc định</span>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="flex: 1; height: 1px; background-color: #ddd;"></div>
                                    <small class="text-muted">hoặc</small>
                                    <div style="flex: 1; height: 1px; background-color: #ddd;"></div>
                                </div>
                            </div>
                            @endif


                            <div class="col-md-12" id="new-address-form">
                                <h5 class="checkout-title-3">{{ $addresses->count() > 0 ? 'Hoặc thêm địa chỉ giao hàng mới' : 'Thêm địa chỉ giao hàng' }}</h5>
                                <div class="btn-wrapper">
                                    <button type="button" class="theme-btn-1 btn btn-effect-1" data-bs-toggle="modal" data-bs-target="#add_address_modal">
                                        <i class="fas fa-plus-circle me-2"></i> Thêm địa chỉ mới
                                    </button>
                                </div>
                            </div>
                    </div>
                </div>

            </div>

            <div class="row g-4 mt-1">
                <!-- PAYMENT METHOD -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0">
                                <i class="fas fa-credit-card me-2"></i>Phương thức thanh toán
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="ltn__payment-method-list">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod" @checked(old('payment_method', 'cod') === 'cod')>
                                    <label class="form-check-label" for="payment_cod">
                                        Thanh toán khi nhận hàng (COD)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_vnpay" value="vnpay" @checked(old('payment_method') === 'vnpay')>
                                    <label class="form-check-label w-100" for="payment_vnpay">
                                        <div class="card border-0 bg-light mt-2">
                                            <div class="card-body py-3 px-3 d-flex align-items-center justify-content-between gap-3">
                                                <div>
                                                    <strong class="d-block">Thanh toán bằng VNPAY</strong>
                                                    <small class="text-muted">Quét QR hoặc thanh toán qua ứng dụng ngân hàng</small>
                                                </div>
                                                <img src="{{ asset('asset/client/img/icons/payment-3.png') }}" alt="VNPAY" style="height: 28px; width: auto;">
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ORDER SUMMARY -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0">
                                <i class="fas fa-file-invoice-dollar me-2"></i>Tóm tắt đơn hàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <strong>{{ number_format($totalPrice, 0, ',', '.') }}đ</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển:</span>
                                <strong>Miễn phí</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold h5">
                                <span>Tổng cộng:</span>
                                <span class="text-primary">{{ number_format($totalPrice, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                        <div class="card-footer border-0 bg-white pt-0">
                            <button type="submit" class="theme-btn-1 btn btn-effect-1 w-100" onclick="return confirm('Bạn có chắc chắn muốn đặt hàng không?')">
                                <i class="fas fa-check-circle me-2"></i> Đặt hàng
                            </button>
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary btn-lg w-100 mt-3">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại giỏ hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

            <!-- Modal -->
            <div class="modal fade" id="add_address_modal" tabindex="-1" role="dialog" aria-labelledby="add_address_modal_label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="add_address_modal_label">Thêm địa chỉ mới</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="add_address_form" method="POST" action="{{ route('account.add_address') }}">
                                @csrf
                                <input type="hidden" name="redirect_url" value="{{ route('checkout') }}">

                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Tên người nhận:</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Địa chỉ:</label>
                                    <input type="text" class="form-control" id="address" name="address">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="city" class="form-label">Thành phố:</label>
                                    <input type="text" class="form-control" id="city" name="city">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Số điện thoại:</label>
                                    <input type="text" class="form-control" id="phone" name="phone">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="default" name="default">
                                    <label class="form-check-label" for="default">Đặt làm địa chỉ mặc định</label>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn theme-btn-1">Lưu địa chỉ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
           
            </form>
        </div>
    </div>

    <!-- PAYMENT INFO -->
    <div class="alert alert-info border-0" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <small>
            <strong>Lưu ý:</strong> Nếu bạn chọn địa chỉ có sẵn, các trường địa chỉ mới sẽ được bỏ qua.
        </small>
    </div>
</div>
</div>
</div>
</div>







@endsection