@extends('layouts.admin')

@section('title', __('messages.view_details') . ' liên hệ')
@section('content')

<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>{{ __('messages.view_details') }} liên hệ</h3>
        </div>
        <div class="title_right">
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $contact->full_name }} <small class="text-muted">{{ $contact->email }}</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <!-- Người gửi thông tin -->
                    <div class="alert alert-info">
                        <strong>📧 Email:</strong> {{ $contact->email }}<br>
                        <strong>📱 Điện thoại:</strong> {{ $contact->phone_number ?? 'Không có' }}<br>
                        <strong>📅 Ngày gửi:</strong> {{ $contact->created_at->format('d/m/Y H:i:s') }}
                    </div>

                    <!-- Nội dung tin nhắn -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-envelope"></i> {{ __('messages.customer_message') }}
                                @if($contact->is_Reply == 1)
                                    <span class="badge badge-success">Đã phản hồi</span>
                                @else
                                    <span class="badge badge-warning">Chưa phản hồi</span>
                                @endif
                            </h4>
                        </div>
                        <div class="panel-body contact-message-body">
                            {!! nl2br(e($contact->message)) !!}
                        </div>
                    </div>

                    <!-- Hiển thị Phản hồi đã gửi nếu đã phản hồi -->
                    @if($contact->is_Reply && $contact->reply_content)
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <i class="fa fa-check-circle"></i> Phản hồi đã gửi
                                </h4>
                            </div>
                            <div class="panel-body" style="background-color: #f8fff8; border-left: 4px solid #5cb85c;">
                                <div style="margin-bottom: 15px;">
                                    <small class="text-muted">
                                        <i class="fa fa-calendar"></i> 
                                        {{ __('messages.send') }} vào: {{ $contact->updated_at->format('d/m/Y H:i:s') }}
                                    </small>
                                </div>
                                <div class="contact-message-body">
                                    {!! nl2br(e($contact->reply_content)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Phần phản hồi -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-reply"></i> {{ __('messages.reply_to_customer') }}
                            </h4>
                        </div>
                        <div class="panel-body">
                            <form id="replyForm" method="POST" action="{{ route('admin.contacts.reply', $contact->id) }}">
                                @csrf

                                <div class="form-group">
                                    <label><strong>{{ __('messages.send') }} tới:</strong> {{ $contact->email }}</label>
                                </div>

                                <div class="form-group">
                                    <label for="reply_content"><strong>Nội dung phản hồi:</strong></label>
                                    <small class="form-text text-muted d-block mb-3">
                                        {{ __('messages.customer_will_receive_email') }}
                                    </small>
                                    <textarea id="reply_content" name="reply_content" class="form-control" rows="10" 
                                        placeholder="{{ __('messages.reply_placeholder') }}" required>{{ $contact->reply_content ?? '' }}</textarea>
                                </div>

                                <div class="form-group">
                                    @if($contact->is_Reply)
                                        <button type="submit" class="btn btn-warning btn-lg">
                                            <i class="fa fa-refresh"></i> Cập nhật phản hồi
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fa fa-send"></i> {{ __('messages.send') }} phản hồi
                                        </button>
                                    @endif
                                    <button type="reset" class="btn btn-secondary btn-lg">
                                        <i class="fa fa-refresh"></i> {{ __('messages.refresh') }}
                                    </button>
                                   
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="x_panel contact-sidebar">
                <div class="x_title">
                    <h2><i class="fa fa-info-circle"></i> Thông tin</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <strong>👤 Họ tên:</strong><br>
                            {{ $contact->full_name }}
                        </li>
                        <li class="mb-3">
                            <strong>📧 Email:</strong><br>
                            <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                        </li>
                        <li class="mb-3">
                            <strong>📱 Điện thoại:</strong><br>
                            @if($contact->phone_number)
                                <a href="tel:{{ $contact->phone_number }}">{{ $contact->phone_number }}</a>
                            @else
                                <span class="text-muted">Không có</span>
                            @endif
                        </li>
                        <li class="mb-3">
                            <strong>📅 Ngày gửi:</strong><br>
                            {{ $contact->created_at->format('d/m/Y') }}
                        </li>
                        <li>
                            <strong>✉️ {{ __('messages.status') }}:</strong><br>
                            @if($contact->is_Reply == 1)
                                <span class="label label-success">Đã phản hồi</span>
                            @else
                                <span class="label label-warning">Chưa phản hồi</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Actions -->
            <div class="x_panel">
                <div class="x_title">
                    <h2>Hành động</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content contact-actions">
                    <a href="mailto:{{ $contact->email }}" class="btn btn-info btn-block mb-2">
                        <i class="fa fa-envelope"></i> {{ __('messages.send') }} email trực tiếp
                    </a>
                    <a href="tel:{{ $contact->phone_number }}" class="btn btn-primary btn-block">
                        <i class="fa fa-phone"></i> Gọi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Summernote CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/lang/summernote-vi-VN.min.js"></script>

@endsection