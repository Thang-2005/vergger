@extends('layouts.client')

@section('title', 'Dịch vụ')
@section('breadcrumb', 'Dịch vụ')

@section('content')

<!-- ABOUT US AREA START -->
<div class="ltn__about-us-area pb-115">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 align-self-center">
                <div class="about-us-img-wrap ltn__img-shape-left about-img-left">
                    <img src="{{ asset('asset/client/img/service/11.jpg') }}" alt="Dịch vụ">
                </div>
            </div>
            <div class="col-lg-7 align-self-center">
                <div class="about-us-info-wrap">
                    <div class="section-title-area ltn__section-title-2">
                        <h6 class="section-subtitle ltn__secondary-color">// DỊCH VỤ ĐÁNG TIN CẬY</h6>
                        <h1 class="section-title">
                            Chúng tôi là đội ngũ<br class="d-none d-md-block">
                            Chuyên nghiệp & Uy tín<span>.</span>
                        </h1>
                        <p>
                            Cung cấp các dịch vụ chất lượng cao, đáp ứng đầy đủ nhu cầu của khách hàng.
                        </p>
                    </div>
                    <div class="about-us-info-wrap-inner about-us-info-devide">
                        <p>
                            Chúng tôi luôn đặt chất lượng và sự hài lòng của khách hàng lên hàng đầu.
                            Với đội ngũ giàu kinh nghiệm cùng quy trình làm việc chuyên nghiệp,
                            chúng tôi cam kết mang đến những sản phẩm và dịch vụ tốt nhất.
                        </p>
                        <div class="list-item-with-icon">
                            <ul>
                                <li><a href="{{ url('/lien-he') }}">Giao hàng tận nhà 24/7</a></li>
                                <li><a href="{{ url('/doi-ngu') }}">Đội ngũ chuyên gia</a></li>
                                <li><a href="{{ url('/dich-vu') }}">Trang thiết bị hiện đại</a></li>
                                <li><a href="{{ url('/san-pham') }}">Sản phẩm đa dạng</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ABOUT US AREA END -->

<!-- SERVICE AREA START -->
<div class="ltn__service-area section-bg-1 pt-115 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2 text-center">
                    <h1 class="section-title white-color---">Dịch vụ của chúng tôi</h1>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            @for ($i = 1; $i <= 6; $i++)
                <div class="col-lg-4 col-sm-6">
                    <div class="ltn__service-item-1">
                        <div class="service-item-img">
                            <a href="{{ url('/chi-tiet-dich-vu') }}">
                                <img src="{{ asset('asset/client/img/service/' . (($i % 3) + 1) . '.jpg') }}" alt="Dịch vụ">
                            </a>
                        </div>
                        <div class="service-item-brief">
                            <h3>
                                <a href="{{ url('/chi-tiet-dich-vu') }}">
                                    Nông sản hữu cơ
                                </a>
                            </h3>
                            <p>
                                Cung cấp nông sản sạch, an toàn, đạt tiêu chuẩn chất lượng cao.
                            </p>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>
<!-- SERVICE AREA END -->

<!-- OUR JOURNEY AREA START -->
<div class="ltn__our-journey-area bg-image bg-overlay-theme-90 pt-280 pb-350 mb-35 plr--9"
     data-bg="{{ asset('asset/client/img/bg/8.jpg') }}">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="ltn__our-journey-wrap">
                    <ul>
                        <li>
                            <span class="ltn__journey-icon">1900</span>
                            <ul>
                                <li>
                                    <div class="ltn__journey-history-item-info clearfix">
                                        <div class="ltn__journey-history-img">
                                            <img src="{{ asset('asset/client/img/service/history-1.jpg') }}" alt="#">
                                        </div>
                                        <div class="ltn__journey-history-info">
                                            <h3>Khởi đầu hành trình</h3>
                                            <p>Bắt đầu xây dựng thương hiệu và định hướng phát triển.</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li class="active">
                            <span class="ltn__journey-icon">1950</span>
                            <ul>
                                <li>
                                    <div class="ltn__journey-history-item-info clearfix">
                                        <div class="ltn__journey-history-img">
                                            <img src="{{ asset('asset/client/img/service/history-1.jpg') }}" alt="#">
                                        </div>
                                        <div class="ltn__journey-history-info">
                                            <h3>Đạt nhiều thành tựu</h3>
                                            <p>Không ngừng phát triển và mở rộng quy mô.</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <span class="ltn__journey-icon">1994</span>
                            <ul>
                                <li>
                                    <div class="ltn__journey-history-item-info clearfix">
                                        <div class="ltn__journey-history-img">
                                            <img src="{{ asset('asset/client/img/service/history-1.jpg') }}" alt="#">
                                        </div>
                                        <div class="ltn__journey-history-info">
                                            <h3>Thương hiệu uy tín</h3>
                                            <p>Được khách hàng tin tưởng và lựa chọn.</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <span class="ltn__journey-icon">2010</span>
                            <ul>
                                <li>
                                    <div class="ltn__journey-history-item-info clearfix">
                                        <div class="ltn__journey-history-img">
                                            <img src="{{ asset('asset/client/img/service/history-1.jpg') }}" alt="#">
                                        </div>
                                        <div class="ltn__journey-history-info">
                                            <h3>Mở rộng dịch vụ</h3>
                                            <p>Đa dạng hóa sản phẩm và dịch vụ cung cấp.</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <span class="ltn__journey-icon">2020</span>
                            <ul>
                                <li>
                                    <div class="ltn__journey-history-item-info clearfix">
                                        <div class="ltn__journey-history-img">
                                            <img src="{{ asset('asset/client/img/service/history-1.jpg') }}" alt="#">
                                        </div>
                                        <div class="ltn__journey-history-info">
                                            <h3>Phát triển bền vững</h3>
                                            <p>Hướng đến giá trị lâu dài và cộng đồng.</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- OUR JOURNEY AREA END -->

@endsection
