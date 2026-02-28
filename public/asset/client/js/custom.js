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
            success: function(res){
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
    $(document).ready(function() {
        $('.logoutBtn').on('click', function(e) {
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
                        success: function(res) {
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
                        error: function(xhr) {
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
            success: function(res){
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
                    let input = $('[name="'+field+'"]');
                    showError(input, msgs[0]);
                    toastr.error(msgs[0]);
                });

            } else {
                toastr.error('Có lỗi xảy ra!');
            }
        }
    });

// ===== TOGGLE PASSWORD VISIBILITY =====
$(document).ready(function() {
    // Function to toggle password visibility
    window.togglePasswordVisibility = function(selector) {
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
    $(document).on('click', '.toggle-password-btn', function(e) {
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
            success: function(res) {
                toastr.success(res.message);
                form[0].reset();
                $('#add_address').modal('hide');
                
                setTimeout(function() {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr) {
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
    $(document).on('click', '.delete-address-btn', function(e) {
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
                    success: function(res) {
                        toastr.success(res.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
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
    $(document).on('click', '.set-default-btn', function(e) {
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
                    success: function(res) {
                        toastr.success(res.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
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
    let priceText  = $('.amount').val() || "0 - 300000 VND";
    let parts      = priceText.split(' - ');
    let minPrice   = parsePrice(parts[0]);
    let maxPrice   = parsePrice(parts[1]) || 300000;
    let sortBy     = $('#sort-by').val() || 'default';

    console.log('Fetching:', { categoryId, minPrice, maxPrice, sortBy, page: currentPage });

    $.ajax({
        url: '/products/filter',
        type: 'GET',
        data: {
            category_id : categoryId,
            min_price   : minPrice,
            max_price   : maxPrice,
            sort_by     : sortBy,
            page        : currentPage  // ✅ gửi kèm trang
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


