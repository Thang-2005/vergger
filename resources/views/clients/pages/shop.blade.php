@extends('layouts.client')

@section('title', 'Cửa hàng')
@section('breadcrumb', 'Cửa hàng')

@section('content')

<div class="ltn__about-us-area pt-120 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="section-title-area ltn__section-title-2">
                    <h1 class="section-title">Cửa hàng</h1>
                    <p>Xem danh sách sản phẩm của chúng tôi.</p>
                    <a href="{{ route('product') }}" class="theme-btn-1 btn btn-block">Xem sản phẩm</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
