$(document).ready(function () {
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function showError(input, message) {
        input.addClass('is-invalid');
        $('#error_' + input.attr('name')).text(message);
    }

    function clearError(input) {
        input.removeClass('is-invalid');
        $('#error_' + input.attr('name')).text('');
    }

    function showCheckboxError(id, message) {
        $('#' + id).addClass('is-invalid');
        $('#error_' + id).text(message);
    }

    function clearCheckboxError(id) {
        $('#' + id).removeClass('is-invalid');
        $('#error_' + id).text('');
    }

    // validate blur
    $('input[name="full_name"]').blur(function () {
        $(this).val().trim().length < 3
            ? showError($(this), 'Họ tên phải ít nhất 3 ký tự')
            : clearError($(this));
    });

    $('input[name="email"]').blur(function () {
        let email = $(this).val().trim();
        let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        !regex.test(email)
            ? showError($(this), 'Email không đúng định dạng')
            : clearError($(this));
    });

    $('input[name="password"]').blur(function () {
        $(this).val().length < 6
            ? showError($(this), 'Mật khẩu ít nhất 6 ký tự')
            : clearError($(this));
    });

    $('input[name="password_confirmation"]').blur(function () {
        $(this).val() !== $('input[name="password"]').val()
            ? showError($(this), 'Mật khẩu xác nhận không khớp')
            : clearError($(this));
    });

    $('#checkbox1').change(function () {
        !$(this).is(':checked')
            ? showCheckboxError('checkbox1', 'Bạn phải đồng ý xử lý thông tin cá nhân')
            : clearCheckboxError('checkbox1');
    });

    $('#checkbox2').change(function () {
        !$(this).is(':checked')
            ? showCheckboxError('checkbox2', 'Bạn phải đồng ý chính sách bảo mật')
            : clearCheckboxError('checkbox2');
    });

    // 🔥 CHỈ 1 SUBMIT DUY NHẤT
    $('#register_form').on('submit', function (e) {
        e.preventDefault();

        $('input').blur();
        $('#checkbox1').trigger('change');
        $('#checkbox2').trigger('change');

        if ($('.is-invalid').length > 0 ||
            !$('#checkbox1').is(':checked') ||
            !$('#checkbox2').is(':checked')) {
            toastr.error('Vui lòng kiểm tra lại thông tin');
            return;
        }

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
            toastr.success(res.message);

            setTimeout(function () {
                window.location.href = res.redirect;
            }, 1500); // đợi toastr hiện xong

            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    Object.values(errors).forEach(err => {
                        toastr.error(err[0]);
                    });
                } else {
                    toastr.error('Có lỗi xảy ra!');
                }
            }
        });
    });
 

    // ===== LOGIN CUSTOMER =====
    $('#login_form').on('submit', function (e) {
        e.preventDefault();

        let email = $('input[name="email"]');
        let password = $('input[name="password"]');
        let valid = true;

        // clear lỗi cũ
        clearError(email);
        clearError(password);

        // email
        let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!regex.test(email.val().trim())) {
            showError(email, 'Email không đúng định dạng');
            valid = false;
        }

        // password
        if (password.val().length < 6) {
            showError(password, 'Mật khẩu tối thiểu 6 ký tự');
            valid = false;
        }

        if (!valid) {
            toastr.error('Vui lòng kiểm tra lại thông tin đăng nhập');
            return;
        }

        // AJAX LOGIN
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),

            success: function (res) {
                toastr.success(res.message);

                if (res.redirect) {
                    setTimeout(() => {
                        window.location.href = res.redirect;
                    }, 1200);
                }
            },

           error: function (xhr) {

            // Laravel validation
            if (xhr.status === 422 && xhr.responseJSON?.errors) {
                Object.values(xhr.responseJSON.errors).forEach(err => {
                    toastr.error(err[0]);
                });
                return;
            }

            // ⚠️ TẤT CẢ lỗi khác (401, 403, 500...)
            let msg =
                xhr.responseJSON?.message ||
                xhr.responseText ||
                'Có lỗi xảy ra!';

            toastr.error(msg);
        }

        });
        ;
    });


});
