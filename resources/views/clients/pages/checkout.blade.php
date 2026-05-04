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

        @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Lưu ý!</strong> {{ session('info') }}
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
                            <i class="fas fa-map-marker-alt me-2"></i>{{ 'Địa chỉ' }} giao hàng
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
                                <h5 class="checkout-title-3">{{ $addresses->count() > 0 ? 'Hoặc thêm địa chỉ giao hàng mới' : 'Thêm sản phẩm' . ' địa chỉ giao hàng' }}</h5>
                                <div class="btn-wrapper">
                                    <button type="button" class="theme-btn-1 btn btn-effect-1" data-bs-toggle="modal" data-bs-target="#add_address_modal">
                                        <i class="fas fa-plus-circle me-2"></i> {{ 'Thêm sản phẩm' }} địa chỉ mới
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
                            <div class="mb-3">
                                <label for="coupon_code" class="form-label fw-bold">Mã giảm giá</label>
                                <div class="d-flex gap-2">
                                    <input
                                        type="text"
                                        id="coupon_code"
                                        class="form-control"
                                        placeholder="{{ 'Nhập mã giảm giá' }}"
                                        value="{{ $appliedCoupon['code'] ?? '' }}"
                                    >
                                    <button type="button" id="applyCouponBtn" class="btn btn-outline-primary">Áp dụng</button>
                                    <button type="button" id="removeCouponBtn" class="btn btn-outline-danger" {{ empty($appliedCoupon) ? 'style=display:none;' : '' }}>{{ 'Xóa' }}</button>
                                </div>
                                <small id="couponMessage" class="d-block mt-2 {{ session('error') ? 'text-danger' : 'text-muted' }}">
                                    @if(!empty($appliedCoupon))
                                        Đã áp dụng mã: <strong>{{ $appliedCoupon['code'] }}</strong>
                                    @else
                                        Nhập mã để nhận ưu đãi.
                                    @endif
                                </small>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <strong id="subtotalText">{{ number_format($totalPrice, 0, ',', '.') }}đ</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2" id="discountRow" {{ empty($appliedCoupon) ? 'style=display:none;' : '' }}>
                                <span>Giảm giá:</span>
                                <strong class="text-success" id="discountText">-{{ number_format($discountAmount ?? 0, 0, ',', '.') }}đ</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển:</span>
                                <strong>Miễn phí</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold h5">
                                <span>Tổng cộng:</span>
                                <span class="text-primary" id="finalTotalText">{{ number_format($finalPrice ?? $totalPrice, 0, ',', '.') }}đ</span>
                            </div>
                            <input type="hidden" id="rawSubtotalValue" value="{{ (float) $totalPrice }}">
                        </div>
                        <div class="card-footer border-0 bg-white pt-0">
                            <div id="codPaymentBtn" style="display: none;">
                                <button type="submit" id="submitCod" class="theme-btn-1 btn btn-effect-1 w-100" onclick="return confirm('Bạn có chắc chắn muốn đặt hàng không?')">
                                    <i class="fas fa-check-circle me-2"></i> Đặt hàng
                                </button>
                            </div>
                            <div id="vnpayPaymentBtn" style="display: none;">
                                <button type="submit" id="submitVnpay" class="btn btn-info btn-lg w-100" style="color: white;">
                                    <i class="fas fa-credit-card me-2"></i> Thanh Toán VNPAY
                                </button>
                                <small class="text-muted d-block mt-2 text-center">
                                    <i class="fas fa-lock me-1"></i> Thanh toán an toàn qua VNPAY
                                </small>
                            </div>
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
                            <h5 class="modal-title" id="add_address_modal_label">{{ 'Thêm sản phẩm' }} địa chỉ mới</h5>
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
                                    <label for="address" class="form-label">{{ 'Địa chỉ' }}:</label>
                                    <input type="text" class="form-control" id="address" name="address">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="city" class="form-label">Thành phố:</label>
                                    <input type="text" class="form-control" id="city" name="city">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">{{ 'Số điện thoại' }}:</label>
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

    </div>
</div>
</div>
</div>
</div>







@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // === COUPON DISCOUNT HANDLER ===
        var applyBtn = document.getElementById('applyCouponBtn');
        var removeBtn = document.getElementById('removeCouponBtn');
        var codeInput = document.getElementById('coupon_code');
        var messageEl = document.getElementById('couponMessage');
        var discountRow = document.getElementById('discountRow');
        var discountText = document.getElementById('discountText');
        var finalTotalText = document.getElementById('finalTotalText');

        if (!applyBtn || !codeInput) {
            return;
        }

        // Lấy tổng tiền gốc từ HTML
        var rawSubtotal = Number(document.getElementById('rawSubtotalValue')?.value || 0);

        /**
         * Định dạng số tiền theo tiêu chuẩn Việt Nam (VND)
         * Ví dụ: 1500000 -> 1.500.000đ
         * Làm tròn thành số nguyên để không hiển thị chữ số thập phân
         * 
         * @param {number} value - Giá trị số cần định dạng
         * @returns {string} Chuỗi định dạng VND
         */
        function formatVND(value) {
            // Làm tròn thành số nguyên rồi mới định dạng
            return Math.round(Number(value)).toLocaleString('vi-VN') + 'đ';
        }

        /**
         * Cập nhật thông báo trạng thái áp dụng mã giảm giá
         * 
         * @param {string} text - Nội dung thông báo
         * @param {boolean} isError - True nếu là thông báo lỗi, false nếu thành công
         */
        function setMessage(text, isError) {
            messageEl.textContent = text;
            messageEl.classList.remove('text-danger', 'text-success', 'text-muted');
            messageEl.classList.add(isError ? 'text-danger' : 'text-success');
        }

        /**
         * Cập nhật giao diện hiển thị giảm giá và tổng tiền
         * 
         * @param {number} discountAmount - Số tiền được giảm
         * @param {number} finalAmount - Tổng tiền sau khi giảm
         */
        function updateDiscountDisplay(discountAmount, finalAmount) {
            // Hiển thị dòng giảm giá
            discountRow.style.display = '';
            
            // Hiển thị tiền giảm (âm)
            discountText.textContent = '-' + formatVND(discountAmount);
            
            // Hiển thị tổng tiền cuối cùng
            finalTotalText.textContent = formatVND(finalAmount);
            
            // Hiển thị nút xóa mã
            removeBtn.style.display = '';
        }

        /**
         * {{ 'Xóa' }} giao diện hiển thị giảm giá
         */
        function clearDiscountDisplay() {
            discountRow.style.display = 'none';
            discountText.textContent = '-0đ';
            finalTotalText.textContent = formatVND(rawSubtotal);
            removeBtn.style.display = 'none';
        }

        // === SỰ KIỆN: CLICK BUTTON "ÁP DỤNG" ===
        applyBtn.addEventListener('click', function () {
            var couponCode = (codeInput.value || '').trim();
            
            // Kiểm tra người dùng có nhập mã không
            if (!couponCode) {
                setMessage('Vui lòng nhập mã giảm giá.', true);
                return;
            }

            // {{ 'Gửi' }} request áp dụng mã lên server
            fetch("{{ route('coupon.apply') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    coupon_code: couponCode,
                    total_amount: rawSubtotal
                })
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                // Nếu mã không hợp lệ
                if (!data.valid) {
                    setMessage(data.message || 'Áp dụng mã thất bại.', true);
                    clearDiscountDisplay();
                    return;
                }

                // Mã hợp lệ - tính toán từ server
                var discountAmount = Number(data.discount || 0);
                // Tính lại ở client để đảm bảo chính xác: tổng cuối = tổng gốc - giảm
                var finalAmount = Math.max(0, rawSubtotal - discountAmount);

                updateDiscountDisplay(discountAmount, finalAmount);
                setMessage(data.message || 'Áp dụng mã giảm giá thành công!', false);
            })
            .catch(function (error) {
                console.error('Lỗi:', error);
                setMessage('Không thể áp dụng mã lúc này. Vui lòng thử lại.', true);
                clearDiscountDisplay();
            });
        });

        // === SỰ KIỆN: CLICK BUTTON "XÓA" ===
        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                // {{ 'Gửi' }} request xóa mã lên server
                fetch("{{ route('coupon.remove') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    // {{ 'Xóa' }} thành công - reset giao diện
                    clearDiscountDisplay();
                    codeInput.value = '';
                    setMessage('Đã xóa mã giảm giá.', false);
                })
                .catch(function (error) {
                    console.error('Lỗi:', error);
                    setMessage('Không thể xóa mã lúc này. Vui lòng thử lại.', true);
                });
            });
        }
    });

    // === VNPAY PAYMENT METHOD HANDLER ===
    document.addEventListener('DOMContentLoaded', function () {
        const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
        const checkoutForm = document.getElementById('checkoutForm');
        const codBtn = document.getElementById('codPaymentBtn');
        const vnpayBtn = document.getElementById('vnpayPaymentBtn');
        const submitCod = document.getElementById('submitCod');
        const submitVnpay = document.getElementById('submitVnpay');
        
        /**
         * Cập nhật nút thanh toán dựa trên phương thức được chọn
         * - COD: Hiển thị nút "Đặt hàng"
         * - VNPAY: Hiển thị nút "Thanh toán VNPAY"
         */
        function updatePaymentButton() {
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
            
            if (selectedPayment?.value === 'vnpay') {
                // VNPAY - ẩn COD, hiển thị VNPAY
                codBtn.style.display = 'none';
                vnpayBtn.style.display = 'block';
                
                // {{ 'Xóa' }} sự kiện confirm cho VNPAY
                if (submitVnpay) {
                    submitVnpay.onclick = null;
                }
            } else {
                // COD - hiển thị COD, ẩn VNPAY
                codBtn.style.display = 'block';
                vnpayBtn.style.display = 'none';
            }
        }
        
        // Lắng nghe thay đổi phương thức thanh toán
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', updatePaymentButton);
        });

        // Đặt trạng thái nút ban đầu
        updatePaymentButton();

        // === XỬ LÝ GỬI FORM THANH TOÁN ===
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;
                
                if (paymentMethod === 'vnpay') {
                    // VNPAY - vô hiệu hóa giao diện để tránh gửi lại
                    e.target.style.opacity = '0.6';
                    e.target.style.pointerEvents = 'none';
                    
                    if (submitVnpay) {
                        submitVnpay.disabled = true;
                        submitVnpay.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Đang chuyển hướng tới VNPAY...';
                    }
                }
            });
        }
    });
</script>
@endsection
