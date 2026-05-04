@extends('layouts.client')

@section('title', 'Yêu thích')
@section('breadcrumb', 'Yêu thích')

@section('content')
<div class="liton__wishlist-area mb-105">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping-cart-inner">
                    <div class="shoping-cart-table table-responsive">
                        <table class="table">
                            <tbody>
                                @forelse ($wishlist as $item)
                                <tr id="wishlist-row-{{ $item->id }}">
                                    <td class="cart-product-remove">
                                        <a href="#"
                                            class="btn-remove-wishlist"
                                            data-id="{{ $item->id }}">x</a>
                                    </td>

                                    <td class="cart-product-image">
                                        <a href="{{ route('product.detail', $item->product->slug) }}">
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                                        </a>
                                    </td>

                                    <td class="cart-product-info">
                                        <h4>
                                            <a href="{{ route('product.detail', $item->product->slug) }}">
                                                {{ $item->product->name }}
                                            </a>
                                        </h4>
                                    </td>

                                    <td class="cart-product-price">
                                        {{ number_format($item->product->price, 0, ',', '.') }}đ
                                    </td>

                                    <td class="cart-product-stock">
                                        {{ $item->product->stock }}
                                    </td>

                                    <td class="cart-product-add-cart">
                                        <button class="submit-button-1 btn-add-to-cart"
                                            data-product-id="{{ $item->product->id }}">
                                            {{ 'Thêm sản phẩm' }} vào giỏ hàng
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr id="wishlist-empty-row">
                                    <td colspan="7" class="text-center py-4">
                                        <i class="far fa-heart fa-2x mb-2 d-block text-muted"></i>

                                        <p>Danh sách yêu thích của bạn đang trống.</p>

                                        <a href="{{ route('product') }}" class="theme-btn-1 btn btn-effect-1 mt-2">
                                            Quay lại mua hàng
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @if ($wishlist->count())
                        <div class="text-end mt-3" >
                            <button id="btn-clear-wishlist" class="btn btn-outline-danger">
                                <i class="far fa-trash-alt me-1"></i> {{ 'Xóa' }} tất cả
                            </button>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection