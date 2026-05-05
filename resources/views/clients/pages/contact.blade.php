@extends('layouts.client')

@section('title', 'Liên hệ')
@section('breadcrumb', 'Liên hệ')

@section('content')

        <div class="ltn__contact-address-area mb-90">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                            <div class="ltn__contact-address-icon">
                                <img src="{{ asset('asset/client/img/icons/10.png') }}" alt="Icon Image">
                            </div>
                            <h3> Email</h3>
                            <p>veggie@example.com <br>
                                jobs@webexample.com</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                            <div class="ltn__contact-address-icon">
                                <img src="{{ asset('asset/client/img/icons/11.png') }}" alt="Icon Image">
                            </div>
                            <h3> Số điện thoại</h3>
                            <p>+0123-456789 <br> +987-6543210</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                            <div class="ltn__contact-address-icon">
                                <img src="{{ asset('asset/client/img/icons/12.png') }}" alt="Icon Image">
                            </div>
                            <h3> Địa chỉ văn phòng</h3>
                            <p>Thien Đình, Hà Nội <br>
                                Việt Nam</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CONTACT ADDRESS AREA END -->

        <!-- CONTACT MESSAGE AREA START -->
        <div class="ltn__contact-message-area mb-120 mb--100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ltn__form-box contact-form-box box-shadow white-bg">
                            <h4 class="title-2">Nhận báo giá</h4>

                            <form id="contact-form" action="{{ route('contact.submit') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-item input-item-name ltn__custom-icon">
                                            <input type="text" name="name" placeholder="{{ __('messages.full_name_required') }}" value="{{ old('name') }}">
                                            <span class="error" id="error_name" style="color: red; font-size: 12px;"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-item input-item-email ltn__custom-icon">
                                            <input type="email" name="email" placeholder="{{ __('messages.email_required') }}" value="{{ old('email') }}">
                                            <span class="error" id="error_email" style="color: red; font-size: 12px;"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="input-item input-item-phone ltn__custom-icon">
                                            <input type="text" name="phone" placeholder="{{ __('messages.enter_phone') }}" value="{{ old('phone') }}">
                                            <span class="error" id="error_phone" style="color: red; font-size: 12px;"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-item input-item-textarea ltn__custom-icon">
                                    <textarea name="message" placeholder="{{ __('messages.enter_message') }}">{{ old('message') }}</textarea>
                                    <span class="error" id="error_message" style="color: red; font-size: 12px;"></span>
                                </div>
                                
                                <div class="btn-wrapper mt-0">
                                    <button class="btn theme-btn-1 btn-effect-1 text-uppercase" type="submit">{{ __('messages.send') }}</button>
                                </div>
                                <p class="form-messege mb-0 mt-20"></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CONTACT MESSAGE AREA END -->

        <!-- GOOGLE MAP AREA START -->
        <div class="google-map mb-120">

            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29806.42090624999!2d105.74979925!3d20.9604405!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313452efff394ce3%3A0x391a39d4325be464!2zVHLGsOG7nW5nIMSQ4bqhaSBI4buNYyBQaGVuaWthYQ!5e0!3m2!1svi!2s!4v1775611453723!5m2!1svi!2s"
                width="100%" height="100%" frameborder="0" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>

        </div>
        <!-- GOOGLE MAP AREA END -->

@endsection
