$(document).ready(function() {
    function getCsrfToken() {
        return $('meta[name="csrf-token"]').attr('content') || '';
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': getCsrfToken()
        }
    });

    function showAjaxError(xhr, fallbackMessage) {
        if (xhr?.status === 419) {
            Swal.fire({
                title: 'Phiên làm việc đã hết hạn',
                text: 'Trang sẽ được tải lại để lấy CSRF token mới.',
                icon: 'warning'
            }).then(() => {
                window.location.reload();
            });
            return;
        }

        const message = xhr?.responseJSON?.message || fallbackMessage;
        Swal.fire('Lỗi!', message, 'error');
    }

/******************manager_user****************
*************************************************/
   $(document).on('click', '.upgrateStart', function() {
    let button=$(this);
    let userId=button.data('user-id');
    Swal.fire({
                title: 'Xác nhận chuyển vai trò?',
                text: "Bạn có chắc chắn muốn chuyển vai trò của người dùng này?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Chuyển vai trò',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/user/upgrade',
                        headers: {
                            'X-CSRF-TOKEN': getCsrfToken()
                        },
                        data: { user_id: userId, _token: getCsrfToken() },
                        type: 'POST',
                        dataType: 'json',
                        success: function (res) {
                            Swal.fire({
                                title: 'Thành công!',
                                text: res.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = res.redirect;
                            });
                        },
                        error: function (xhr) {
                                     showAjaxError(xhr, 'Có lỗi xảy ra khi chuyển vai trò.');
                        }
                    });
                }
            });
    });

    $(document).on('click', '.downgradeStart', function() {
        let button = $(this);
        let userId = button.data('user-id');

        Swal.fire({
            title: 'Xác nhận hạ quyền?',
            text: 'Người dùng này sẽ bị hạ từ Staff xuống Customer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hạ quyền',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/user/downgrade',
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    data: { user_id: userId, _token: getCsrfToken() },
                    type: 'POST',
                    dataType: 'json',
                    success: function (res) {
                        Swal.fire({
                            title: 'Thành công!',
                            text: res.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = res.redirect;
                        });
                    },
                    error: function (xhr) {
                        showAjaxError(xhr, 'Có lỗi xảy ra khi hạ quyền.');
                    }
                });
            }
        });
    });

    $(document).on('click', '.changeStatus', function() {
        let button = $(this);
        let userId = button.data('user-id');
        let status = button.data('status');

        const statusLabel = status === 'banned' ? 'chặn' : 'bỏ chặn';

        Swal.fire({
            title: 'Xác nhận cập nhật trạng thái?',
            text: `Bạn có chắc muốn ${statusLabel} người dùng này?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/user/change-status',
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    data: { user_id: userId, status: status, _token: getCsrfToken() },
                    type: 'POST',
                    dataType: 'json',
                    success: function (res) {
                        Swal.fire({
                            title: 'Thành công!',
                            text: res.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = res.redirect;
                        });
                    },
                    error: function (xhr) {
                        showAjaxError(xhr, 'Có lỗi xảy ra khi cập nhật trạng thái.');
                    }
                });
            }
        });


   });
});