@extends('layouts.client')

@section ('title','Hỗ trợ')
@section ('breadcrumb','Những câu hỏi thường gặp')

@section ('content')

<!-- FAQ AREA START -->
<div class="ltn__faq-area mb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="ltn__faq-inner ltn__faq-inner-2">
                    <div id="accordion_2">

                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-1">
                                Làm thế nào để đặt mua rau củ trên website?
                            </h6>
                            <div id="faq-item-2-1" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>
                                        Bạn chỉ cần chọn sản phẩm rau củ mong muốn, thêm vào giỏ hàng và tiến hành
                                        thanh toán. Hệ thống sẽ xác nhận đơn hàng và giao hàng tận nơi trong thời gian sớm nhất.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- card -->
                        <div class="card">
                            <h6 class="ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-2" aria-expanded="true">
                                Tôi có thể đổi trả hoặc hoàn tiền không?
                            </h6>
                            <div id="faq-item-2-2" class="collapse show" data-parent="#accordion_2">
                                <div class="card-body">
                                    <div class="ltn__video-img alignleft">
                                        <img src="{{ asset('asset/client/img/bg/17.jpg') }}" alt="Hoàn tiền">
                                    </div>
                                    <p>
                                        Chúng tôi hỗ trợ đổi trả hoặc hoàn tiền nếu sản phẩm bị hư hỏng, dập nát
                                        hoặc không đúng mô tả. Vui lòng liên hệ trong vòng 24 giờ sau khi nhận hàng
                                        để được hỗ trợ nhanh chóng.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-3">
                                Rau củ có đảm bảo an toàn và nguồn gốc không?
                            </h6>
                            <div id="faq-item-2-3" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>
                                        Tất cả rau củ đều được nhập từ các trang trại đạt chuẩn VietGAP,
                                        đảm bảo tươi sạch, không hóa chất độc hại và có nguồn gốc rõ ràng.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-4">
                                Thời gian giao hàng mất bao lâu?
                            </h6>
                            <div id="faq-item-2-4" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>
                                        Đơn hàng nội thành thường được giao trong ngày.
                                        Các khu vực khác sẽ nhận hàng trong vòng 1–2 ngày làm việc.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-5">
                                Thông tin cá nhân của tôi có được bảo mật không?
                            </h6>
                            <div id="faq-item-2-5" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>
                                        Chúng tôi cam kết bảo mật tuyệt đối thông tin khách hàng.
                                        Mọi dữ liệu cá nhân chỉ được sử dụng để xử lý đơn hàng và chăm sóc khách hàng.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-6">
                                Mã giảm giá không áp dụng được thì phải làm sao?
                            </h6>
                            <div id="faq-item-2-6" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>
                                        Vui lòng kiểm tra điều kiện áp dụng của mã giảm giá.
                                        Nếu vẫn gặp lỗi, hãy liên hệ bộ phận hỗ trợ để được kiểm tra và xử lý.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-7">
                                Tôi có thể thanh toán bằng những hình thức nào?
                            </h6>
                            <div id="faq-item-2-7" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>
                                        Website hỗ trợ thanh toán khi nhận hàng (COD),
                                        chuyển khoản ngân hàng và các ví điện tử phổ biến.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="need-support text-center mt-100">
                        <h2>Bạn vẫn cần hỗ trợ thêm?</h2>
                        <div class="btn-wrapper mb-30">
                            <a href="/contact" class="theme-btn-1 btn">Liên hệ với chúng tôi</a>
                        </div>
                        <h3><i class="fas fa-phone"></i> 0900 123 456</h3>
                    </div>

                </div>
            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-4">
                <aside class="sidebar-area ltn__right-sidebar">

                    <div class="widget ltn__search-widget ltn__newsletter-widget">
                        <h6 class="ltn__widget-sub-title">// tìm kiếm</h6>
                        <h4 class="ltn__widget-title">Tìm sản phẩm</h4>
                        <form action="#">
                            <input type="text" placeholder="Nhập tên rau củ...">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>

                    <div class="widget ltn__banner-widget">
                        <a href="/shop">
                            <img src="{{ asset('asset/client/img/bg/17.jpg') }}" alt="Rau củ sạch">
                        </a>
                    </div>

                </aside>
            </div>
        </div>
    </div>
</div>
<!-- FAQ AREA END -->

@endsection
