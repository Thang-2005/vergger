@extends('layouts.client')

@section('title', '404')
@section('breadcrumb', '404')

@section('content')

<div class="ltn__about-us-area pt-120 pb-120">
    <div class="container text-center">
        <h1 class="section-title">404 - Trang không tồn tại</h1>
        <p><a href="{{ route('home') }}" class="theme-btn-1 btn">Về trang chủ</a></p>
    </div>
</div>

@endsection
