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
        // xóa lỗi cũ
        $('.error').text('');
        $('input').removeClass('is-invalid');

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
            success: function (res) {
                toastr.success(res.message);

                setTimeout(function () {
                    window.location.href = res.redirect;
                }, 1500); // đợi toastr hiện xong

            },
            error: function (xhr) {
                // hiển thị lỗi validation inline + toastr
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    let errors = xhr.responseJSON.errors;
                    Object.entries(errors).forEach(([field, msgs]) => {
                        let input = $('[name="' + field + '"]');
                        input.addClass('is-invalid');
                        $('#error_' + field).text(msgs[0]);
                        toastr.error(msgs[0]);
                    });
                    return;
                } else {
                    toastr.error('Có lỗi xảy ra!');
                }
            }
        });
    });


    // ===== CONTACT FORM =====
    $('#contact-form').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let name = $('input[name="name"]');
        let email = $('input[name="email"]');
        let phone = $('input[name="phone"]');
        let message = $('textarea[name="message"]');
        let valid = true;

        // Clear all errors
        $('.error').text('');
        $('input, textarea').removeClass('is-invalid');

        // Validate name
        if (name.val().trim().length < 3) {
            showError(name, 'Tên phải ít nhất 3 ký tự');
            valid = false;
        }

        // Validate email
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.val().trim())) {
            showError(email, 'Email không đúng định dạng');
            valid = false;
        }

        // Validate message
        if (message.val().trim().length < 5) {
            showError(message, 'Tin nhắn phải ít nhất 5 ký tự');
            valid = false;
        }
        if(phone.val().trim().length > 0) {
            let phoneRegex = /^[0-9]{10,11}$/;
            if (!phoneRegex.test(phone.val().trim())) {
                showError(phone, 'Số điện thoại không đúng định dạng');
                valid = false;
            }
        }


        if (!valid) {
            toastr.error('Vui lòng kiểm tra lại thông tin');
            return;
        }

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            headers: {
                'Accept': 'application/json'
            },
            data: form.serialize(),
            success: function (res) {
                toastr.success(res.message);
                form[0].reset();
                $('.error').text('');
                $('input, textarea').removeClass('is-invalid');
                
                setTimeout(function () {
                    window.location.href = res.redirect;
                }, 1500);
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    let errors = xhr.responseJSON.errors;
                    Object.entries(errors).forEach(([field, msgs]) => {
                        let input = $('[name="' + field + '"]');
                        showError(input, msgs[0]);
                        toastr.error(msgs[0]);
                    });
                } else {
                    toastr.error('Có lỗi xảy ra!');
                }
            }
        });
    });

    // ===== LOGIN CUSTOMER =====
    // Đặt trước tất cả AJAX call
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#login_form').on('submit', function (e) {
        e.preventDefault();

        let email = $('input[name="email"]');
        let password = $('input[name="password"]');
        let valid = true;

        clearError(email);
        clearError(password);

        let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!regex.test(email.val().trim())) {
            showError(email, 'Email không đúng định dạng');
            valid = false;
        }

        if (password.val().length < 6) {
            showError(password, 'Mật khẩu tối thiểu 6 ký tự');
            valid = false;
        }

        if (!valid) {
            toastr.error('Vui lòng kiểm tra lại thông tin đăng nhập');
            return;
        }

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
                // 🔑 Xử lý CSRF expired
                if (xhr.status === 419) {
                    toastr.warning('Phiên làm việc hết hạn, đang tải lại...');
                    setTimeout(() => location.reload(), 1500);
                    return;
                }

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    Object.values(xhr.responseJSON.errors).forEach(err => {
                        toastr.error(err[0]);
                    });
                    return;
                }

                let msg = xhr.responseJSON?.message || xhr.responseText || 'Có lỗi xảy ra!';
                toastr.error(msg);
            }
        });
    });
    $(document).ready(function () {
        $('.logoutBtn').on('click', function (e) {
            e.preventDefault();
            let url = $(this).data('url');

            Swal.fire({
                title: 'Xác nhận đăng xuất?',
                text: "Bạn có chắc chắn muốn đăng xuất?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đăng xuất',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
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
                            Swal.fire('Lỗi!', 'Có lỗi xảy ra khi đăng xuất', 'error');
                        }
                    });
                }
            });
        });
    });

    // ===== RESET PASSWORD FORM =====
    $('input[name="email"]').on('blur', function () {
        const form = $(this).closest('form');
        if (form.attr('id') !== 'reset_password_form') return;

        let email = $(this).val().trim();
        let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        !regex.test(email)
            ? showError($(this), 'Email không đúng định dạng')
            : clearError($(this));
    });

    $('form#reset_password_form input[name="password"]').on('blur', function () {
        $(this).val().length < 6
            ? showError($(this), 'Mật khẩu phải có ít nhất 6 ký tự')
            : clearError($(this));
    });

    $('form#reset_password_form input[name="password_confirmation"]').on('blur', function () {
        $(this).val() !== $('form#reset_password_form input[name="password"]').val()
            ? showError($(this), 'Mật khẩu không khớp')
            : clearError($(this));
    });

    // Revalidate confirm password khi password thay đổi
    $('form#reset_password_form input[name="password"]').on('input', function () {
        const confirmInput = $('form#reset_password_form input[name="password_confirmation"]');
        if (confirmInput.val()) {
            confirmInput.blur();
        }
    });

    // Submit reset password form
    $('#reset_password_form').on('submit', function (e) {
        e.preventDefault();

        // clear lỗi cũ
        $('form#reset_password_form .error').text('');
        $('form#reset_password_form input').removeClass('is-invalid');

        // validate tất cả fields
        $('form#reset_password_form input[name="email"]').blur();
        $('form#reset_password_form input[name="password"]').blur();
        $('form#reset_password_form input[name="password_confirmation"]').blur();

        // Nếu có lỗi, không submit
        if ($('form#reset_password_form .is-invalid').length > 0) {
            toastr.error('Vui lòng kiểm tra lại thông tin');
            return;
        }

        // Submit form bình thường
        this.submit();
    });
    // ===== HELPER FUNCTIONS =====
    function showError(input, message) {
        input.addClass('is-invalid');
        input.next('.invalid-feedback').text(message);
    }

    function clearError(input) {
        input.removeClass('is-invalid');
        input.next('.invalid-feedback').text('');
    }

    // ===== UPDATE ACCOUNT FORM =====
    // Khi chọn file mới, cập nhật preview
    $('#avatarInput').on('change', function () {
        let file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $('#avatarPreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    $('#update_account_form').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        $.ajax({
            hearders: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                toastr.success(res.message);
                setTimeout(function () {
                    window.location.reload();
                }, 1500);
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    let errors = xhr.responseJSON.errors;
                    Object.entries(errors).forEach(([field, msgs]) => {
                        let input = $('[name="' + field + '"]');
                        input.addClass('is-invalid');
                        $('#error_' + field).text(msgs[0]);
                        toastr.error(msgs[0]);
                    });
                } else {
                    toastr.error('Có lỗi xảy ra!');
                }
            }
        });
    });


    // ===== CHANGE PASSWORD FORM =====
    $('#update_password_form').on('submit', function (e) {

        e.preventDefault();

        let form = $(this);

        let current = $('input[name="current_password"]');
        let newPass = $('input[name="new_password"]');
        let confirm = $('input[name="new_password_confirmation"]');

        let valid = true;

        if (current.val().trim().length < 6) {
            toastr.error('Mật khẩu hiện tại tối thiểu 6 ký tự');
            valid = false;
        }

        if (newPass.val().trim().length < 6) {
            toastr.error('Mật khẩu mới tối thiểu 6 ký tự');
            valid = false;
        }

        if (newPass.val() !== confirm.val()) {
            toastr.error('Mật khẩu xác nhận không khớp');
            valid = false;
        }

        if (!valid) {
            toastr.error('Vui lòng kiểm tra lại thông tin');
            return;
        }

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),

            success: function (res) {

                toastr.success(res.message);

                form[0].reset();
                $('.error-message').text('');
                $('input').removeClass('is-invalid');

            },

            error: function (xhr) {

                if (xhr.status === 422) {

                    let errors = xhr.responseJSON.errors;

                    Object.entries(errors).forEach(([field, msgs]) => {
                        let input = $('[name="' + field + '"]');
                        showError(input, msgs[0]);
                        toastr.error(msgs[0]);
                    });

                } else {
                    toastr.error('Có lỗi xảy ra!');
                }
            }
        });

        // ===== TOGGLE PASSWORD VISIBILITY =====
        $(document).ready(function () {
            // Function to toggle password visibility
            window.togglePasswordVisibility = function (selector) {
                const input = $(selector);
                const btn = input.next('.toggle-password-btn');
                const icon = btn.find('.toggle-password-icon');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            };

            // Auto-initialize password toggle for all password inputs with class
            $(document).on('click', '.toggle-password-btn', function (e) {
                e.preventDefault();
                const input = $(this).prev('input[type="password"], input[type="text"]');
                if (input.length) {
                    const selector = '#' + input.attr('id');
                    window.togglePasswordVisibility(selector);
                }
            });
        });

    });


    //======= validate form address=======
    $(document).ready(function () {

        // ===== ADD ADDRESS FORM =====
        $('#add_address_form').on('submit', function (e) {
            e.preventDefault();

            // Clear lỗi cũ
            $('#add_address_form .invalid-feedback').text('');
            $('#add_address_form input').removeClass('is-invalid');

            // Validate tất cả inputs
            let form = $(this);
            let fullName = form.find('[name="full_name"]');
            let phone = form.find('[name="phone"]');
            let address = form.find('[name="address"]');
            let city = form.find('[name="city"]');

            let phoneRegex = /^\d{10,}$/;
            let isValid = true;

            // FULL NAME
            if (fullName.val().trim().length < 3) {
                fullName.addClass('is-invalid').next().text('Tên người nhận phải ít nhất 3 ký tự');
                isValid = false;
            }

            // ADDRESS
            if (address.val().trim().length < 5) {
                address.addClass('is-invalid').next().text('Vui lòng nhập địa chỉ chi tiết');
                isValid = false;
            }

            // CITY
            if (city.val().trim().length < 2) {
                city.addClass('is-invalid').next().text('Vui lòng nhập tỉnh/thành phố');
                isValid = false;
            }

            // PHONE
            if (!phoneRegex.test(phone.val().trim())) {
                phone.addClass('is-invalid').next().text('Số điện thoại không hợp lệ (≥10 số)');
                isValid = false;
            }

            // Nếu có lỗi validation, không submit
            if (!isValid) {
                toastr.error('Vui lòng kiểm tra lại thông tin');
                return;
            }

            // AJAX Submit
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function (res) {
                    toastr.success(res.message);
                    form[0].reset();
                    $('#add_address').modal('hide');

                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                },
                error: function (xhr) {
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        let errors = xhr.responseJSON.errors;

                        Object.entries(errors).forEach(([field, msgs]) => {
                            let input = form.find('[name="' + field + '"]');
                            if (input.length) {
                                input.addClass('is-invalid').next().text(msgs[0]);
                                toastr.error(`${field}: ${msgs[0]}`);
                            }
                        });
                        return;
                    }

                    // Nếu không có errors object, hiển thị message chung
                    if (xhr.responseJSON?.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('Có lỗi xảy ra!');
                    }
                }
            });
        });


        // ===== DELETE ADDRESS HANDLER =====
        $(document).on('click', '.delete-address-btn', function (e) {
            e.preventDefault();

            let btn = $(this);
            let addressId = btn.data('address-id');
            let addressName = btn.data('address-name');

            Swal.fire({
                title: 'Xác nhận xóa',
                text: `Bạn có chắc muốn xóa địa chỉ: ${addressName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/account/delete-address/' + addressId,
                        type: 'DELETE',
                        success: function (res) {
                            toastr.success(res.message);
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
                        },
                        error: function (xhr) {
                            if (xhr.responseJSON?.message) {
                                toastr.error(xhr.responseJSON.message);
                            } else {
                                toastr.error('Có lỗi xảy ra!');
                            }
                        }
                    });
                }
            });
        });

        // ===== SET DEFAULT ADDRESS HANDLER =====
        $(document).on('click', '.set-default-btn', function (e) {
            e.preventDefault();

            let btn = $(this);
            let addressId = btn.data('address-id');

            Swal.fire({
                title: 'Đặt làm mặc định',
                text: 'Bạn có chắc muốn đặt địa chỉ này làm mặc định?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/account/set-default-address/' + addressId,
                        type: 'PUT',
                        success: function (res) {
                            toastr.success(res.message);
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
                        },
                        error: function (xhr) {
                            if (xhr.responseJSON?.message) {
                                toastr.error(xhr.responseJSON.message);
                            } else {
                                toastr.error('Có lỗi xảy ra!');
                            }
                        }
                    });
                }
            });
        });

        // ===== CANCEL ORDER HANDLER =====
        $(document).on('click', '.cancel-order-btn', function (e) {
            e.preventDefault();

            let orderId = $(this).data('order-id');

            Swal.fire({
                title: 'Xác nhận hủy đơn',
                text: 'Bạn có chắc muốn hủy đơn hàng này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Hủy đơn',
                cancelButtonText: 'Đóng'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/account/orders/' + orderId + '/cancel',
                        type: 'POST',
                        success: function (res) {
                            toastr.success(res.message || 'Đơn hàng đã được hủy thành công');
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
                        },
                        error: function (xhr) {
                            if (xhr.responseJSON?.message) {
                                toastr.error(xhr.responseJSON.message);
                            } else {
                                toastr.error('Có lỗi xảy ra khi hủy đơn hàng!');
                            }
                        }
                    });
                }
            });
        });


    });



    //======= page product=======

    let currentPage = 1; // ✅ biến toàn cục lưu trang hiện tại

    $(document).ready(function () {

        // CATEGORY FILTER
        $(document).on("click", ".category-filter", function () {
            $(".category-filter").removeClass("active");
            $(this).addClass("active");
            currentPage = 1; // reset trang
            fetchProduct();
        });

        // SORT FILTER
        $(document).on("change", "#sort-by", function () {
            currentPage = 1; // reset trang
            fetchProduct();
        });

        // ✅ PAGINATION - dùng data-page thay vì href
        $(document).on('click', '.pagination-link', function () {
            currentPage = $(this).data('page');
            fetchProduct();
        });

        // PRICE SLIDER
        $(".slider-range").slider({
            range: true,
            min: 0,
            max: 300000,
            values: [0, 300000],

            slide: function (event, ui) {
                $(".amount").val(
                    formatVND(ui.values[0]) + " - " +
                    formatVND(ui.values[1]) + " VND"
                );
            },

            change: function (event, ui) {
                $(".amount").val(
                    formatVND(ui.values[0]) + " - " +
                    formatVND(ui.values[1]) + " VND"
                );
                if (event.originalEvent) {
                    currentPage = 1; // reset trang
                    fetchProduct();
                }
            }
        });

        $(".amount").val(
            formatVND($(".slider-range").slider("values", 0)) +
            " - " +
            formatVND($(".slider-range").slider("values", 1)) +
            " VND"
        );
    });

    function formatVND(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function parsePrice(str) {
        return parseInt(str.replace(/[^0-9]/g, '')) || 0;
    }

    function fetchProduct() {

        let categoryId = $('.category-filter.active').data('id') || '';
        let priceText = $('.amount').val() || "0 - 300000 VND";
        let parts = priceText.split(' - ');
        let minPrice = parsePrice(parts[0]);
        let maxPrice = parsePrice(parts[1]) || 300000;
        let sortBy = $('#sort-by').val() || 'default';

        console.log('Fetching:', { categoryId, minPrice, maxPrice, sortBy, page: currentPage });

        $.ajax({
            url: '/products/filter',
            type: 'GET',
            data: {
                category_id: categoryId,
                min_price: minPrice,
                max_price: maxPrice,
                sort_by: sortBy,
                page: currentPage  // ✅ gửi kèm trang
            },

            beforeSend: function () {
                $('#loading-spinner').css('display', 'flex');
            },

            success: function (res) {
                if (res.products) {
                    $('#product_list').html(res.products);
                }
                if (res.pagination) {
                    // ✅ replace đúng phần ul bên trong
                    $('.ltn__pagination ul').replaceWith(res.pagination);
                }
                if (res.total !== undefined) {
                    $('.showing-product-number span').text(
                        'Showing ' + res.showing + ' of ' + res.total + ' results'
                    );
                }

                // Scroll lên đầu danh sách
                $('html, body').animate({ scrollTop: $('#product_list').offset().top - 100 }, 300);
            },

            complete: function () {
                $('#loading-spinner').hide();
            },

            error: function (xhr) {
                console.error('Filter error:', xhr.responseText);
                toastr.error('Có lỗi xảy ra khi lọc sản phẩm');
            }
        });
    }
});

(function () {
    window.confirmEmail = function () {
        var emailInput = document.querySelector('input[name="email"]');
        var email = emailInput ? emailInput.value.trim() : '';

        if (!email) {
            alert('Vui lòng nhập email');
            return false;
        }

        return confirm('Có chắc chắn gửi link đặt lại mật khẩu đến email:\n' + email + '?');
    };

    window.togglePasswordVisibility = function (selector) {
        var input = document.querySelector(selector);
        if (!input) {
            return;
        }

        var btn = input.parentElement ? input.parentElement.querySelector('.toggle-password-btn') : null;
        var icon = btn ? btn.querySelector('.toggle-password-icon, i') : null;

        if (input.type === 'password') {
            input.type = 'text';
            if (icon) {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        } else {
            input.type = 'password';
            if (icon) {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    };

    window.editReview = function (rating, comment) {
        var formSection = document.getElementById('review-form-section');
        var reviewHint = document.getElementById('review-edit-hint');
        var formTitle = document.getElementById('form-title');
        var submitText = document.getElementById('submit-text');
        var ratingInput = document.getElementById('rating-input');
        var commentInput = document.getElementById('comment');
        var stars = document.querySelectorAll('.star-item');

        if (formSection) {
            formSection.classList.remove('d-none');
        }
        if (reviewHint) {
            reviewHint.classList.add('d-none');
        }
        if (ratingInput) {
            ratingInput.value = rating;
        }
        if (commentInput) {
            commentInput.value = comment;
        }

        stars.forEach(function (star) {
            var starValue = parseInt(star.getAttribute('data-value'), 10);
            if (starValue <= rating) {
                star.classList.remove('far');
                star.classList.add('fas');
            } else {
                star.classList.remove('fas');
                star.classList.add('far');
            }
        });

        if (formTitle) {
            formTitle.textContent = 'Chỉnh sửa đánh giá';
        }
        if (submitText) {
            submitText.textContent = 'Cập nhật đánh giá';
        }
        if (formSection) {
            formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    window.cancelEdit = function () {
        var form = document.getElementById('review-form');
        var formSection = document.getElementById('review-form-section');
        var reviewHint = document.getElementById('review-edit-hint');
        var hasReview = form ? form.getAttribute('data-has-review') === '1' : false;

        if (!hasReview) {
            return;
        }

        var originalRating = form.getAttribute('data-original-rating');
        var originalComment = form.getAttribute('data-original-comment');
        var ratingInput = document.getElementById('rating-input');
        var commentInput = document.getElementById('comment');
        var stars = document.querySelectorAll('.star-item');

        if (ratingInput) {
            ratingInput.value = originalRating;
        }
        if (commentInput) {
            commentInput.value = originalComment;
        }

        stars.forEach(function (star) {
            var starValue = parseInt(star.getAttribute('data-value'), 10);
            if (starValue <= parseInt(originalRating, 10)) {
                star.classList.remove('far');
                star.classList.add('fas');
            } else {
                star.classList.remove('fas');
                star.classList.add('far');
            }
        });

        if (formSection) {
            formSection.classList.add('d-none');
        }
        if (reviewHint) {
            reviewHint.classList.remove('d-none');
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        var bars = document.querySelectorAll('.review-rating-bar');
        if (bars.length) {
            bars.forEach(function (bar) {
                var width = bar.getAttribute('data-width');
                bar.style.width = width + '%';
            });
        }

        var stars = document.querySelectorAll('.star-item');
        var ratingInput = document.getElementById('rating-input');
        stars.forEach(function (star) {
            star.addEventListener('click', function () {
                var value = this.getAttribute('data-value');
                if (ratingInput) {
                    ratingInput.value = value;
                }

                stars.forEach(function (s) {
                    var starValue = s.getAttribute('data-value');
                    if (starValue <= value) {
                        s.classList.remove('far');
                        s.classList.add('fas');
                    } else {
                        s.classList.remove('fas');
                        s.classList.add('far');
                    }
                });
            });

            star.addEventListener('mouseenter', function () {
                var value = this.getAttribute('data-value');
                stars.forEach(function (s) {
                    var starValue = s.getAttribute('data-value');
                    s.style.transform = starValue <= value ? 'scale(1.2)' : 'scale(1)';
                });
            });
        });

        var starContainer = document.getElementById('star-rating');
        if (starContainer) {
            starContainer.addEventListener('mouseleave', function () {
                stars.forEach(function (s) { s.style.transform = 'scale(1)'; });
            });
        }

        var editButtons = document.querySelectorAll('.edit-review-trigger');
        editButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var rating = this.getAttribute('data-rating');
                var comment = this.getAttribute('data-comment');
                window.editReview(rating, comment);

                var actionMenu = this.closest('.review-action-menu');
                if (actionMenu) {
                    actionMenu.classList.add('d-none');
                }
            });
        });

        var menuToggles = document.querySelectorAll('[data-review-menu-toggle]');
        if (menuToggles.length) {
            var closeAllMenus = function () {
                document.querySelectorAll('[data-review-menu]').forEach(function (menu) {
                    menu.classList.add('d-none');
                });
                menuToggles.forEach(function (toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                });
            };

            menuToggles.forEach(function (toggle) {
                toggle.addEventListener('click', function (event) {
                    event.stopPropagation();
                    var reviewId = this.getAttribute('data-review-menu-toggle');
                    var menu = document.querySelector('[data-review-menu="' + reviewId + '"]');
                    var willOpen = menu && menu.classList.contains('d-none');

                    closeAllMenus();
                    if (menu && willOpen) {
                        menu.classList.remove('d-none');
                        this.setAttribute('aria-expanded', 'true');
                    }
                });
            });

            document.addEventListener('click', closeAllMenus);
            document.querySelectorAll('[data-review-menu]').forEach(function (menu) {
                menu.addEventListener('click', function (event) {
                    event.stopPropagation();
                });
            });
        }

        var downloadBtn = document.getElementById('downloadInvoiceBtn');
        var invoiceDataElement = document.getElementById('invoice-data');
        if (downloadBtn && invoiceDataElement && typeof pdfMake !== 'undefined') {
            var order = JSON.parse(invoiceDataElement.textContent || '{}');
            var currencyFormatter = new Intl.NumberFormat('vi-VN');
            var paymentMethodLabel = {
                cash: 'Thanh toán khi nhận hàng',
                cod: 'Thanh toán khi nhận hàng',
                vnpay: 'VNPAY',
                paypal: 'PayPal'
            }[order.payment_method] || 'Chưa cập nhật';

            var statusLabel = {
                pending: 'Chờ xác nhận',
                processing: 'Đang xử lý',
                shipped: 'Đang giao',
                completed: 'Hoàn thành',
                cancelled: 'Đã hủy',
                canceled: 'Đã hủy'
            }[order.status] || order.status;

            downloadBtn.addEventListener('click', function () {
                var rows = (order.items || []).map(function (item, index) {
                    return [
                        { text: String(index + 1), alignment: 'center' },
                        item.name,
                        { text: String(item.quantity), alignment: 'center' },
                        { text: currencyFormatter.format(item.price) + ' đ', alignment: 'right' },
                        { text: currencyFormatter.format(item.total) + ' đ', alignment: 'right' },
                    ];
                });

                var docDefinition = {
                    pageSize: 'A4',
                    pageMargins: [40, 48, 40, 48],
                    content: [
                        { text: 'VEGGIE', style: 'brand' },
                        { text: 'HÓA ĐƠN BÁN HÀNG', style: 'title' },
                        {
                            columns: [
                                [
                                    { text: 'Thông tin đơn hàng', style: 'sectionTitle' },
                                    { text: 'Mã đơn: #' + order.id },
                                    { text: 'Ngày đặt: ' + (order.created_at || '-') },
                                    { text: 'Trạng thái: ' + statusLabel },
                                ],
                                [
                                    { text: 'Thông tin thanh toán', style: 'sectionTitle' },
                                    { text: 'Phương thức: ' + paymentMethodLabel },
                                    { text: 'Trạng thái: ' + (order.payment_status_label || order.payment_status || 'pending') },
                                ]
                            ],
                            columnGap: 24,
                            margin: [0, 0, 0, 16]
                        },
                        {
                            columns: [
                                [
                                    { text: 'Người nhận', style: 'sectionTitle' },
                                    { text: order.shipping ? order.shipping.full_name : 'Chưa có thông tin' },
                                    { text: order.shipping ? order.shipping.phone : '' },
                                    { text: order.shipping ? (order.shipping.address + ', ' + order.shipping.city) : '' },
                                ],
                                [
                                    { text: 'Cửa hàng', style: 'sectionTitle' },
                                    { text: 'Veggie' },
                                    { text: 'Hotline: 0900 000 000' },
                                    { text: 'Email: support@veggie.local' },
                                ]
                            ],
                            columnGap: 24,
                            margin: [0, 0, 0, 18]
                        },
                        {
                            table: {
                                headerRows: 1,
                                widths: [30, '*', 50, 85, 85],
                                body: [
                                    [
                                        { text: '#', style: 'tableHeader' },
                                        { text: 'Sản phẩm', style: 'tableHeader' },
                                        { text: 'SL', style: 'tableHeader' },
                                        { text: 'Đơn giá', style: 'tableHeader' },
                                        { text: 'Thành tiền', style: 'tableHeader' },
                                    ],
                                ].concat(rows).concat([
                                    [
                                        { text: 'Tổng cộng', colSpan: 4, alignment: 'right', bold: true },
                                        {},
                                        {},
                                        {},
                                        { text: currencyFormatter.format(order.total_price) + ' đ', alignment: 'right', bold: true },
                                    ]
                                ])
                            },
                            layout: 'lightHorizontalLines'
                        },
                        {
                            text: 'Cảm ơn bạn đã mua sắm tại Veggie.',
                            margin: [0, 18, 0, 0],
                            italics: true,
                        }
                    ],
                    styles: {
                        brand: { fontSize: 18, bold: true, color: '#2f6d3a', alignment: 'center' },
                        title: { fontSize: 15, bold: true, alignment: 'center', margin: [0, 2, 0, 18] },
                        sectionTitle: { fontSize: 11, bold: true, color: '#2f6d3a', margin: [0, 0, 0, 4] },
                        tableHeader: { bold: true, fillColor: '#eef4ef' }
                    },
                    defaultStyle: { fontSize: 10 }
                };

                pdfMake.createPdf(docDefinition).download('hoa-don-don-hang-' + order.id + '.pdf');
            });
        }
    });
})();



// ============================================
// GIỎ HÀNG -
// ============================================

$(document).ready(function () {

    // Load số lượng Yeru thích khi load trang
    updateCartBadge();

    // ===== THÊM VÀO GIỎ HÀNG =====
    $(document).on('click', '.btn-add-to-cart', function () {
        const productId = $(this).data('product-id');
        const qtyId = $(this).data('qty-id');
        const quantity = parseInt($('#' + qtyId).val()) || 1;
        const $btn = $(this);

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang thêm...');

        $.ajax({
            url: '/cart/add',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
            },
            success: function (res) {
                toastr.success(res.message);
                updateCartBadge(res.cart_count);
            },
            error: function (xhr) {
                if (xhr.status === 401) {
                    toastr.warning(xhr.responseJSON?.message || 'Vui lòng đăng nhập');
                    setTimeout(() => {
                        window.location.href = xhr.responseJSON?.redirect || '/login';
                    }, 1500);
                } else if (xhr.status === 419) {
                    toastr.warning('Phiên làm việc hết hạn, đang tải lại...');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    toastr.error(xhr.responseJSON?.message || 'Có lỗi xảy ra!');
                }
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="fas fa-shopping-cart me-1"></i> Thêm vào giỏ');
            }
        });
    });

    // ===== CẬP NHẬT SỐ LƯỢNG TRONG TRANG GIỎ HÀNG =====
    $(document).on('change', '.cart-qty-input', function () {
        const cartId = $(this).data('cart-id');
        const qty = parseInt($(this).val());
        const $row = $(this).closest('tr');

        if (qty < 1) {
            $(this).val(1);
            return;
        }

        $.ajax({
            url: '/cart/update/' + cartId,
            type: 'PATCH',
            data: { quantity: qty },
            success: function (res) {
                toastr.success(res.message);
                $row.find('.item-total').text(res.item_total);
                $('#cart-total').text(res.total);
                updateCartBadge(res.cart_count);
            },
            error: function () {
                toastr.error('Không thể cập nhật số lượng');
            }
        });
    });

    // ===== XÓA 1 SẢN PHẨM KHỎI GIỎ =====
    $(document).on('click', '.btn-remove-cart', function () {
        const cartId = $(this).data('cart-id');
        const $row = $(this).closest('tr');

        Swal.fire({
            title: 'Xóa sản phẩm?',
            text: 'Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            confirmButtonColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/cart/remove/' + cartId,
                    type: 'DELETE',
                    success: function (res) {
                        toastr.success(res.message);
                        $row.fadeOut(300, function () { $(this).remove(); });
                        $('#cart-total').text(res.total);
                        updateCartBadge(res.cart_count);

                        // Nếu giỏ trống thì reload
                        if (res.cart_count === 0) {
                            setTimeout(() => location.reload(), 800);
                        }
                    },
                    error: function () {
                        toastr.error('Không thể xóa sản phẩm');
                    }
                });
            }
        });
    });

    // ===== XÓA TOÀN BỘ GIỎ =====
    $(document).on('click', '#btn-clear-cart', function () {
        Swal.fire({
            title: 'Xóa toàn bộ giỏ hàng?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa hết',
            cancelButtonText: 'Hủy',
            confirmButtonColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/cart/clear',
                    type: 'DELETE',
                    success: function (res) {
                        toastr.success(res.message);
                        updateCartBadge(0);
                        setTimeout(() => location.reload(), 800);
                    },
                    error: function () {
                        toastr.error('Không thể xóa giỏ hàng');
                    }
                });
            }
        });
    });

    // ===== CẬP NHẬT BADGE SỐ LƯỢNG GIỎ TRÊN HEADER =====
    function updateCartBadge(count) {
        if (count !== undefined) {
            $('.cart-count-badge').text(count);
            if (count > 0) {
                $('.cart-count-badge').show();
            } else {
                $('.cart-count-badge').hide();
            }
            return;
        }

        // Nếu không truyền count thì gọi API
        $.get('/cart/count', function (res) {
            $('.cart-count-badge').text(res.cart_count);
            if (res.cart_count > 0) {
                $('.cart-count-badge').show();
            } else {
                $('.cart-count-badge').hide();
            }
        });
    }

});


// ============================================
// Yêu thích -
// ============================================
$(document).ready(function () {

    updateWishlistBadge();

    // ===== THÊM VÀO YÊU THÍCH =====
    $(document).on('click', '.btn-add-wishlist, [data-bs-target^="#liton_wishlist_modal"]', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const productId = $(this).data('product-id');              // đọc data-product-id
        const modalId = $(this).attr('data-bs-target');          // ✅ SỬA: dùng .attr() không phải .data()

        if (!productId) {
            toastr.error('Không tìm thấy sản phẩm');
            return;
        }

        $.ajax({
            url: '/wishlist/add',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                product_id: productId,
            },
            success: function (res) {
                updateWishlistBadge(res.wishlist_count);
                toastr.success(res.message);
                $(modalId).modal('show');                           // mở modal thông báo
            },
            error: function (xhr) {
                if (xhr.status === 401) {
                    toastr.warning(xhr.responseJSON?.message || 'Vui lòng đăng nhập');
                    setTimeout(() => {
                        window.location.href = xhr.responseJSON?.redirect || '/login';
                    }, 1500);
                } else if (xhr.status === 419) {
                    toastr.warning('Phiên làm việc hết hạn, đang tải lại...');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    toastr.error(xhr.responseJSON?.message || 'Có lỗi xảy ra!');
                }
            }
        });
    });

    // ===== XÓA 1 SẢN PHẨM =====
    $(document).on('click', '.btn-remove-wishlist', function (e) {
        e.preventDefault();

        const id = $(this).data('id');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Xóa sản phẩm yêu thích?',
            text: 'Bạn có chắc muốn xóa sản phẩm này khỏi yêu thích?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            confirmButtonColor: '#d33'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: '/wishlist/remove/' + id,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },

                    success: function (res) {

                        toastr.success(res.message);

                        row.fadeOut(300, function () {
                            $(this).remove();

                            // nếu wishlist trống
                            if ($('tbody tr').length === 0) {
                                $('tbody').html(`
                                <tr id="wishlist-empty-row">
                                    <td colspan="7" class="text-center py-4">
                                        <i class="far fa-heart fa-2x mb-2 d-block text-muted"></i>
                                        Danh sách yêu thích của bạn đang trống.
                                    </td>
                                </tr>
                            `);

                                $('#btn-clear-wishlist').hide();
                            }
                        });

                        updateWishlistBadge(res.wishlist_count);
                    },

                    error: function () {
                        toastr.error('Không thể xóa sản phẩm');
                    }
                });

            }
        });
    });

    // ===== XÓA TOÀN BỘ =====
    $(document).on('click', '#btn-clear-wishlist', function () {

        Swal.fire({
            title: 'Xóa toàn bộ yêu thích?',
            text: 'Tất cả sản phẩm yêu thích sẽ bị xóa.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa hết',
            cancelButtonText: 'Hủy',
            confirmButtonColor: '#d33'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: '/wishlist/clear',
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },

                    success: function (res) {

                        toastr.success(res.message);

                        // ✅ Xóa toàn bộ sản phẩm khỏi table
                        $('tbody').html(`
                        <tr id="wishlist-empty-row">
                            <td colspan="7" class="text-center py-4">
                                <i class="far fa-heart fa-2x mb-2 d-block text-muted"></i>
                                Danh sách yêu thích của bạn đang trống.
                            </td>
                        </tr>
                    `);

                        // ✅ reset badge wishlist
                        updateWishlistBadge(0);

                        // ✅ ẩn nút clear
                        $('#btn-clear-wishlist').hide();
                        (location.reload(), 500);
                    },

                    error: function () {
                        toastr.error('Không thể xóa yêu thích');
                    }
                });

            }
        });
    });
    // ===== CẬP NHẬT BADGE =====
    function updateWishlistBadge(count) {
        const badgeSelector = '.wishlist-count, .wishlist-badge';

        if (count !== undefined) {
            $(badgeSelector).text(count);
            return;
        }
        $.get('/wishlist/count', function (res) {
            $(badgeSelector).text(res.count || 0);
        });
    }

});



// ============================================
// cart
// ============================================
//mini_cart

$(document).on('click', '.mini-cart-item-delete', function () {

    const cartId = $(this).data('cart-id');
    const $row = $(this).closest('.mini-cart-item');

    Swal.fire({
        title: 'Xóa sản phẩm?',
        text: 'Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy',
        confirmButtonColor: '#d33',
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: '/cart/remove/' + cartId,
                type: 'DELETE',

                success: function (res) {

                    toastr.success(res.message);

                    $row.fadeOut(300, function () {
                        $(this).remove();
                    });

                    $('#cart-total').text(res.total);
                    updateCartBadge(res.cart_count);

                    if (res.cart_count === 0) {
                        setTimeout(() => location.reload(), 800);
                    }
                },

                error: function () {
                    toastr.error('Không thể xóa sản phẩm');
                }
            });

        }

    });

});

// ==============contact=============================
// $(document).ready(function () {
//     $('#contact_form').on('submit', function (e) {
//         e.preventDefault();

//         let name = $('input[name="name"]');
//         let email = $('input[name="email"]');
//         let phone = $('input[name="phone"]');
//         let message = $('textarea[name="message"]');

//         let valid = true;

//         if (name.val().trim().length < 3) {
//             toastr.error('Tên phải có ít nhất 3 ký tự');
//             valid = false;
//         }

//         let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
//         if (!emailRegex.test(email.val().trim())) {
//             toastr.error('Email không đúng định dạng');
//             valid = false;
//         }
//         let phoneRegex = /^\d{10,}$/;
//         if (!phoneRegex.test(phone.val().trim())) {
//             toastr.error('Số điện thoại không hợp lệ (≥10 số)');
//             valid = false;
//         }

//         if (message.val().trim().length < 10) {
//             toastr.error('Nội dung phải có ít nhất 10 ký tự');
//             valid = false;
//         }

//         if (!valid) {
//             toastr.error('Vui lòng kiểm tra lại thông tin liên hệ');
//             return;
//         }

//         $.ajax({
//             url: '/contact/submit',
//             type: 'POST',
//             data: $(this).serialize(),
//             success: function (res) {
//                 toastr.success(res.message);
//                 $('#contact_form')[0].reset();
//             },
//             error: function (xhr) {
//                 if (xhr.status === 422 && xhr.responseJSON?.errors) {
//                     Object.values(xhr.responseJSON.errors).forEach(err => {
//                         toastr.error(err[0]);
//                     });
//                     return;
//                 }

//                 let msg = xhr.responseJSON?.message || xhr.responseText || 'Có lỗi xảy ra!';
//                 toastr.error(msg);
//             }
//         });
//     });
// });



