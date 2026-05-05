@extends('layouts.client')

@section('title', 'Chi tiết đơn hàng')
@section('breadcrumb', 'Chi tiết đơn hàng')

@section ('content')
<div class="ltn__checkout-area pt-100 pb-100">
	<div class="container">
		@if (session('success'))
			<div class="alert alert-success mb-4">{{ session('success') }}</div>
		@endif
		@if (session('error'))
			<div class="alert alert-danger mb-4">{{ session('error') }}</div>
		@endif

		<div class="row justify-content-center">
			<div class="col-lg-10">
				<div class="card shadow-sm border-0 mb-4">
					<div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
						<h5 class="mb-0">Đơn hàng #{{ $order->id }}</h5>
						<a href="{{ route('account.orders') }}" class="btn btn-sm btn-outline-secondary">Quay lại danh sách đơn hàng</a>
					</div>

					<div class="card-body">
						<div class="row g-3 mb-4">
							<div class="col-md-3">
								<div class="p-3 border rounded bg-light h-100">
									<small class="text-muted d-block mb-1">Ngày đặt</small>
									<strong>{{ $order->created_at->format('H:i   d/m/Y') }}</strong>
								</div>
							</div>

							<div class="col-md-3">
								<div class="p-3 border rounded bg-light h-100">
									<small class="text-muted d-block mb-1">{{ __('messages.status') }}</small>
									@if ($order->status == 'pending')
										<span class="badge bg-warning text-dark">Chờ xác nhận</span>
									@elseif ($order->status == 'processing' || $order->status == 'confirmed')
										<span class="badge bg-primary">Đã xác nhận</span>
									@elseif ($order->status == 'shipped')
										<span class="badge bg-info text-dark">Đang giao hàng</span>
									@elseif ($order->status == 'delivered')
										<span class="badge bg-success">Đã giao hàng</span>
									@elseif ($order->status == 'completed')
										<span class="badge bg-success">Hoàn tất</span>
									@elseif ($order->status == 'canceled')
										<span class="badge bg-danger">Đã hủy</span>
									@else
										<span class="badge bg-secondary">{{ $order->status }}</span>
									@endif
								</div>
							</div>

							<div class="col-md-3">
								<div class="p-3 border rounded bg-light h-100">
									<small class="text-muted d-block mb-1">Phương thức thanh toán</small>
									<strong>{{ $paymentMethod }}</strong>
								</div>
							</div>

							<div class="col-md-3">
								<div class="p-3 border rounded bg-light h-100">
									<small class="text-muted d-block mb-1">Tổng tiền</small>
									<strong class="text-primary">{{ number_format($order->total_price, 0, ',', '.') }}d</strong>
									@if(($order->discount_amount ?? 0) > 0)
										<small class="d-block text-success mt-1">Giảm: -{{ number_format($order->discount_amount, 0, ',', '.') }}d</small>
										@if($order->coupon_code)
											<small class="d-block text-muted">Mã: {{ $order->coupon_code }}</small>
										@endif
									@endif
								</div>
							</div>
						</div>

						<div class="card border-0 bg-light mb-4">
							<div class="card-body">
								<h6 class="mb-3">{{ __('messages.address') }} nhận hàng</h6>
								@if($order->shippingAddress)
									<p class="mb-1"><strong>{{ $order->shippingAddress->full_name }}</strong></p>
									<p class="mb-1">{{ $order->shippingAddress->phone }}</p>
									<p class="mb-0">{{ $order->shippingAddress->address }}, {{ $order->shippingAddress->city }}</p>
								@else
									<p class="mb-0 text-muted">Khong co thong tin dia chi giao hang.</p>
								@endif
							</div>
						</div>

						<h6 class="mb-3">{{ __('messages.view_details') }} đơn hàng</h6>
						<div class="table-responsive mb-4">
							<table class="table table-bordered align-middle mb-0">
								<thead class="table-light">
									<tr>
                                        <th>Hình ảnh</th>
										<th>Sản phẩm</th>
										<th class="text-center">Số lượng</th>
										<th class="text-end">Đơn giá</th>
										<th class="text-end">Thành tiền</th>
									</tr>
								</thead>
								<tbody>
									@forelse ($order->orderItems as $item)
										<tr>
                                            <td>
                                               @if($item->product && $item->product->firstImage && $item->product->firstImage->image)
													<img src="{{ asset('storage/uploads/product/' . $item->product->firstImage->image) }}" alt="{{ $item->product->name }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
												@else
													<img src="{{ asset('storage/uploads/product/default_product.jpg') }}" alt="No Image" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
												@endif
                                            </td>
											<td>
												@if($item->product)
													<a href="{{ route('product.detail', $item->product->slug) }}">{{ $item->product->name }}</a>
												@else
													 Sản phẩm không còn tồn tại
												@endif
											</td>
											<td class="text-center">{{ $item->quantity }}</td>
											<td class="text-end">{{ number_format($item->price, 0, ',', '.') }}d</td>
											<td class="text-end">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}d</td>
										</tr>
									@empty
										<tr>
											<td colspan="5" class="text-center">Không có sản phẩm trong đơn hàng.</td>
										</tr>
									@endforelse
								</tbody>
								<tfoot>
									<tr>
										<td colspan="4" class="text-end"><strong>Tạm tính</strong></td>
										<td class="text-end"><strong>{{ number_format($order->orderItems->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}d</strong></td>
									</tr>
									@if(($order->discount_amount ?? 0) > 0)
									<tr>
										<td colspan="4" class="text-end text-success"><strong>Giảm giá @if($order->coupon_code) ({{ $order->coupon_code }}) @endif</strong></td>
										<td class="text-end text-success"><strong>-{{ number_format($order->discount_amount, 0, ',', '.') }}d</strong></td>
									</tr>
									@endif
									<tr>
										<td colspan="4" class="text-end"><strong>Tổng thanh toán</strong></td>
										<td class="text-end"><strong>{{ number_format($order->total_price, 0, ',', '.') }}d</strong></td>
									</tr>
								</tfoot>
							</table>
						</div>

						<div class="d-flex gap-2 flex-wrap">
							@if ($order->status === 'pending')
								<button type="button" class="btn btn-outline-danger cancel-order-btn" data-order-id="{{ $order->id }}">{{ __('messages.cancel') }} đơn hàng</button>
							@endif
							<button type="button" class="btn btn-outline-primary" id="downloadInvoiceBtn">Xuất hóa đơn PDF</button>
							<a href="{{ route('account.orders') }}" class="btn btn-outline-secondary">Quay lại danh sách đơn hàng</a>
							<a href="{{ route('home') }}" class="btn theme-btn-1">Tiếp tục mua sắm</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script id="invoice-data" type="application/json">{!! json_encode($invoiceData, JSON_UNESCAPED_UNICODE) !!}</script>

@endsection