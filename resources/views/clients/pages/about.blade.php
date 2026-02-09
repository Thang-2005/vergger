@extends('layouts.client')

@section ('title','Về chúng tôi')
@section ('breadcrumb','Về chúng tôi')

@section ('content')

<!-- ABOUT US AREA START -->
<div class="ltn__about-us-area pt-120--- pb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 align-self-center">
                <div class="about-us-img-wrap about-img-left">
                    <img src="{{ asset('asset/client/img/others/6.png') }}" alt="Về chúng tôi">
                </div>
            </div>
            <div class="col-lg-6 align-self-center">
                <div class="about-us-info-wrap">
                    <div class="section-title-area ltn__section-title-2">
                        <h6 class="section-subtitle ltn__secondary-color">Tìm hiểu thêm về cửa hàng</h6>
                        <h1 class="section-title">
                            Cửa hàng <br class="d-none d-md-block">
                            Thực phẩm sạch & Rau củ hữu cơ
                        </h1>
                        <p>
                            Chúng tôi chuyên cung cấp rau củ tươi, thực phẩm sạch mỗi ngày,
                            đảm bảo an toàn cho sức khỏe gia đình bạn.
                        </p>
                    </div>
                    <p>
                        Với mong muốn mang đến nguồn thực phẩm an toàn và minh bạch,
                        chúng tôi hợp tác trực tiếp với các trang trại đạt chuẩn,
                        không qua trung gian, giúp giữ trọn độ tươi ngon và giá cả hợp lý.
                    </p>
                    <div class="about-author-info d-flex">
                        <div class="author-name-designation align-self-center">
                            <h4 class="mb-0">Nguyễn Văn A</h4>
                            <small>/ Người sáng lập</small>
                        </div>
                        <div class="author-sign">
                            <img src="{{ asset('asset/client/img/icons/icon-img/author-sign.png') }}" alt="Chữ ký">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ABOUT US AREA END -->

<!-- FEATURE AREA START -->
<div class="ltn__feature-area section-bg-1 pt-115 pb-90">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2 text-center">
                    <h6 class="section-subtitle ltn__secondary-color">// lợi ích //</h6>
                    <h1 class="section-title">Vì sao chọn chúng tôi<span>.</span></h1>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">

            <div class="col-lg-4 col-sm-6 col-12">
                <div class="ltn__feature-item ltn__feature-item-7">
                    <div class="ltn__feature-icon-title">
                        <div class="ltn__feature-icon">
                            <span><img src="{{ asset('asset/client/img/others/6.png') }}" alt=""></span>
                        </div>
                        <h3>Đa dạng sản phẩm</h3>
                    </div>
                    <div class="ltn__feature-info">
                        <p>
                            Cung cấp đầy đủ rau củ, trái cây, thực phẩm tươi sống
                            phục vụ nhu cầu hàng ngày của gia đình.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 col-12">
                <div class="ltn__feature-item ltn__feature-item-7">
                    <div class="ltn__feature-icon-title">
                        <div class="ltn__feature-icon">
                            <span><img src="{{ asset('asset/client/img/others/6.png') }}" alt=""></span>
                        </div>
                        <h3>Chọn lọc kỹ lưỡng</h3>
                    </div>
                    <div class="ltn__feature-info">
                        <p>
                            Sản phẩm được kiểm tra chất lượng nghiêm ngặt
                            trước khi đến tay khách hàng.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 col-12">
                <div class="ltn__feature-item ltn__feature-item-7">
                    <div class="ltn__feature-icon-title">
                        <div class="ltn__feature-icon">
                            <span><img src="{{ asset('asset/client/img/others/6.png') }}" alt=""></span>
                        </div>
                        <h3>Không thuốc trừ sâu</h3>
                    </div>
                    <div class="ltn__feature-info">
                        <p>
                            Rau củ được trồng theo hướng tự nhiên,
                            hạn chế tối đa hóa chất độc hại.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- FEATURE AREA END -->

<!-- TEAM AREA START -->
<div class="ltn__team-area pt-115 pb-90">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2 text-center">
                    <h1 class="section-title">Đội ngũ của chúng tôi</h1>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">

            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="ltn__team-item">
                    <div class="team-img">
                        <img src="{{ asset('asset/client/img/others/6.png') }}" alt="">
                    </div>
                    <div class="team-info">
                        <h6 class="ltn__secondary-color">// sáng lập //</h6>
                        <h4>Nguyễn Văn A</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="ltn__team-item">
                    <div class="team-img">
                        <img src="{{ asset('asset/client/img/others/6.png') }}" alt="">
                    </div>
                    <div class="team-info">
                        <h6 class="ltn__secondary-color">// quản lý //</h6>
                        <h4>Trần Thị B</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="ltn__team-item">
                    <div class="team-img">
                        <img src="{{ asset('asset/client/img/others/6.png') }}" alt="">
                    </div>
                    <div class="team-info">
                        <h6 class="ltn__secondary-color">// vận hành //</h6>
                        <h4>Lê Văn C</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="ltn__team-item">
                    <div class="team-img">
                        <img src="{{ asset('asset/client/img/others/6.png') }}" alt="">
                    </div>
                    <div class="team-info">
                        <h6 class="ltn__secondary-color">// chăm sóc khách //</h6>
                        <h4>Phạm Thị D</h4>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- TEAM AREA END -->

@endsection
    