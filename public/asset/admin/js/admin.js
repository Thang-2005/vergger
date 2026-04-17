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


   
/******************manager_roles****************
*************************************************/
$(document).ready(function () {
    const csrfToken = '{{ csrf_token() }}';
    const storeRoleUrl = "{{ route('admin.roles.store') }}";
    const roleBaseUrl = "{{ url('admin/roles') }}";

    $('#btn-open-create-role').on('click', function () {
        Swal.fire({
            title: 'Thêm vai trò mới',
            input: 'text',
            inputLabel: 'Tên vai trò',
            inputPlaceholder: 'Ví dụ: Kế toán, Quản lý kho',
            showCancelButton: true,
            confirmButtonText: 'Tạo vai trò',
            cancelButtonText: 'Hủy',
            preConfirm: (value) => {
                const roleName = (value || '').trim();
                if (!roleName) {
                    Swal.showValidationMessage('Vui lòng nhập tên vai trò.');
                }
                return roleName;
            }
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            Swal.fire({
                title: 'Xác nhận tạo vai trò?',
                text: `Bạn có chắc muốn tạo vai trò "${result.value}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((confirmResult) => {
                if (!confirmResult.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: storeRoleUrl,
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        name: result.value,
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: response.message,
                        }).then(() => window.location.reload());
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON?.message || 'Không thể tạo vai trò.';
                        Swal.fire('Lỗi', message, 'error');
                    }
                });
            });
        });
    });

    $(document).find('.btn-edit-role').on('click', function () {
        const roleId = $(this).data('id');
        const currentName = $(this).data('name');

            Swal.fire({
                title: 'Đổi tên vai trò',
                input: 'text',
                inputLabel: 'Tên vai trò mới',
                inputValue: currentName,
                showCancelButton: true,
                confirmButtonText: 'Cập nhật',
                cancelButtonText: 'Hủy',
                preConfirm: (value) => {
                    const roleName = (value || '').trim();
                    if (!roleName) {
                        Swal.showValidationMessage('Vui lòng nhập tên vai trò.');
                    }
                    return roleName;
                }
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                Swal.fire({
                    title: 'Xác nhận cập nhật?',
                    text: `Bạn có chắc muốn đổi tên vai trò "${currentName}" thành "${result.value}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy'
                }).then((confirmResult) => {
                    if (!confirmResult.isConfirmed) {
                        return;
                    }

                    $.ajax({
                        url: `${roleBaseUrl}/${roleId}`,
                        method: 'POST',
                        data: {
                            _token: csrfToken,
                            _method: 'PUT',
                            name: result.value,
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: response.message,
                            }).then(() => window.location.reload());
                        },
                        error: function (xhr) {
                            const message = xhr.responseJSON?.message || 'Không thể cập nhật vai trò.';
                            Swal.fire('Lỗi', message, 'error');
                        }
                    });
                });
            });
        });
    });

    $(document).find('.btn-delete-role').on('click', function () {
        const roleId = $(this).data('id');
        const roleName = $(this).data('name');
        const usersCount = parseInt($(this).data('usersCount') || '0', 10);

            if (usersCount > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Không thể xóa',
                    text: `Vai trò "${roleName}" đang được gán cho ${usersCount} người dùng.`
                });
                return;
            }

            Swal.fire({
                title: 'Xác nhận xóa vai trò?',
                text: `Bạn có chắc muốn xóa vai trò "${roleName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xoa',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: `${roleBaseUrl}/${roleId}`,
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        _method: 'DELETE',
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: response.message,
                        }).then(() => window.location.reload());
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON?.message || 'Không thể xóa vai trò.';
                        Swal.fire('Lỗi', message, 'error');
                    }
                });
            });
        });
    });


/******************manager_product****************
*************************************************/
$(document).ready(function () {
    function updatePreview(previewId, imageUrl, fallbackText) {
        const preview = document.getElementById(previewId);
        if (!preview) {
            return;
        }

        if (imageUrl) {
            preview.innerHTML = '<img src="' + imageUrl + '" alt="Preview" style="width:100%; height:100%; object-fit:cover;">';
            return;
        }

        preview.innerHTML = '<span style="color:#999;">' + (fallbackText || 'Chưa có ảnh') + '</span>';
    }

    function fillEditForm(data) {
        const map = {
            edit_product_id: data.id || '',
            edit_name: data.name || '',
            edit_slug: data.slug || '',
            edit_price: data.price || '',
            edit_stock: data.stock || '0',
            edit_category_id: data.category_id || '',
            edit_status: data.status || 'in_stock',
            edit_unit: data.unit || '',
            edit_description: data.description || ''
        };

        Object.keys(map).forEach(function (fieldId) {
            const el = document.getElementById(fieldId);
            if (el) {
                el.value = map[fieldId];
            }
        });

        updatePreview('editProductPreview', data.image_url || '', 'Chưa có ảnh');
    }

    function fillDetailModal(data) {
        const statusLabel = data.status || '';
        const statusClass = statusLabel === 'Còn hàng' ? 'success' : 'danger';

        const nameEl = document.getElementById('detailProductName');
        const slugEl = document.getElementById('detailProductSlug');
        const categoryEl = document.getElementById('detailProductCategory');
        const priceEl = document.getElementById('detailProductPrice');
        const stockEl = document.getElementById('detailProductStock');
        const statusEl = document.getElementById('detailProductStatus');
        const unitEl = document.getElementById('detailProductUnit');
        const descriptionEl = document.getElementById('detailProductDescription');

        if (nameEl) nameEl.textContent = data.name || '';
        if (slugEl) slugEl.textContent = data.slug || '';
        if (categoryEl) categoryEl.textContent = data.category_name || '';
        if (priceEl) priceEl.textContent = data.price || '';
        if (stockEl) stockEl.textContent = data.stock !== undefined ? data.stock : '';
        if (statusEl) {
            statusEl.innerHTML = statusLabel
                ? '<span class="label label-' + statusClass + '" style="display:inline-block; padding:6px 12px; border-radius:999px; font-size:12px; letter-spacing:.03em;">' + statusLabel + '</span>'
                : '';
        }
        if (unitEl) unitEl.textContent = data.unit || 'Sản phẩm';
        if (descriptionEl) descriptionEl.textContent = data.description || 'Chưa có mô tả.';

        updatePreview('detailProductPreview', data.image_url || '', 'Không có ảnh');
    }

    window.openProductDetail = function (button) {
        fillDetailModal({
            name: button.dataset.name || '',
            slug: button.dataset.slug || '',
            category_name: button.dataset.category || '',
            price: button.dataset.price || '',
            stock: button.dataset.stock || '',
            status: button.dataset.status || '',
            unit: button.dataset.unit || '',
            description: button.dataset.description || '',
            image_url: button.dataset.imageUrl || ''
        });

        $('#productDetailModal').modal('show');
    };

    window.openProductEdit = function (button) {
        const productId = button.dataset.id || '';
        const editForm = document.getElementById('editProductForm');

        if (editForm && productId) {
            editForm.action = '/admin/products/' + productId;
        }

        fillEditForm({
            id: productId,
            name: button.dataset.name || '',
            slug: button.dataset.slug || '',
            price: button.dataset.price || '',
            stock: button.dataset.stock || '0',
            category_id: button.dataset.categoryId || '',
            status: button.dataset.status || 'in_stock',
            unit: button.dataset.unit || '',
            description: button.dataset.description || '',
            image_url: button.dataset.imageUrl || ''
        });

        $('#editProductModal').modal('show');
    };

    $(document).on('change', '[data-preview-input]', function () {
        const input = this;
        const previewId = input.getAttribute('data-preview-input');
        const preview = document.getElementById(previewId);
        const file = input.files && input.files[0];

        if (!preview || !file) {
            return;
        }

        const reader = new FileReader();
        reader.onload = function (event) {
            preview.innerHTML = '<img src="' + event.target.result + '" alt="Preview" style="width:100%; height:100%; object-fit:cover;">';
        };
        reader.readAsDataURL(file);
    });

    $(document).on('submit', '.delete-product-form', function (event) {
        event.preventDefault();

        const form = this;
        const button = form.querySelector('.delete-product-btn');
        const name = button ? (button.dataset.name || 'sản phẩm') : 'sản phẩm';

        Swal.fire({
            title: 'Xóa sản phẩm?',
            text: 'Sản phẩm "' + name + '" sẽ bị xóa vĩnh viễn.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d'
        }).then(function (result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

(function () {
    function csrfToken() {
        var tokenNode = document.querySelector('meta[name="csrf-token"]');
        return tokenNode ? tokenNode.getAttribute('content') : '';
    }

    function togglePasswordVisibility(selector) {
        var field = document.querySelector(selector);
        if (!field) {
            return;
        }
        var button = field.parentElement ? field.parentElement.querySelector('.toggle-password-btn') : null;
        var icon = button ? button.querySelector('i') : null;

        if (field.type === 'password') {
            field.type = 'text';
            if (icon) {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        } else {
            field.type = 'password';
            if (icon) {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    }

    window.togglePasswordVisibility = window.togglePasswordVisibility || togglePasswordVisibility;
    window.confirmEmail = function () {
        var emailInput = document.querySelector('input[name="email"]');
        var email = emailInput ? emailInput.value.trim() : '';

        if (!email) {
            alert('Vui lòng nhập email');
            return false;
        }

        return confirm('Có chắc chắn gửi link đặt lại mật khẩu đến email:\n' + email + '?');
    };

    document.addEventListener('DOMContentLoaded', function () {
        var redirectNode = document.querySelector('.js-auto-redirect[data-auto-redirect-url]');
        if (redirectNode) {
            var url = redirectNode.getAttribute('data-auto-redirect-url');
            var delay = parseInt(redirectNode.getAttribute('data-auto-redirect-delay') || '2000', 10);
            if (url) {
                window.setTimeout(function () {
                    window.location.href = url;
                }, delay);
            }
        }

        var permissionForm = document.getElementById('role-permissions-form');
        var saveButton = document.getElementById('save-role-permissions');

        if (permissionForm && saveButton) {
            var checkboxes = document.querySelectorAll('.role-permission-checkbox');
            var searchInput = document.getElementById('permission-search');
            var groupTitles = document.querySelectorAll('[data-toggle-group]');
            var expandAllButton = document.getElementById('expand-all-groups');
            var collapseAllButton = document.getElementById('collapse-all-groups');

            function updateGroupSelectedCounts() {
                document.querySelectorAll('.group-selected-count').forEach(function (badge) {
                    var groupId = badge.dataset.groupId;
                    var body = document.getElementById(groupId);
                    if (!body) {
                        return;
                    }
                    var groupCheckboxes = body.querySelectorAll('.role-permission-checkbox');
                    var checkedCount = body.querySelectorAll('.role-permission-checkbox:checked').length;
                    badge.textContent = checkedCount + '/' + groupCheckboxes.length + ' đã chọn';
                });
            }

            function setGroupState(groupId, expand) {
                var body = document.getElementById(groupId);
                if (!body) {
                    return;
                }
                var title = document.querySelector('[data-toggle-group="' + groupId + '"]');
                var icon = title ? title.querySelector('.permission-toggle-icon') : null;

                body.style.display = expand ? 'block' : 'none';
                if (icon) {
                    icon.classList.toggle('fa-chevron-up', expand);
                    icon.classList.toggle('fa-chevron-down', !expand);
                }
            }

            function applySearchFilter() {
                var query = (searchInput ? searchInput.value : '').trim().toLowerCase();
                document.querySelectorAll('.permission-group-block').forEach(function (groupBlock) {
                    var rows = groupBlock.querySelectorAll('.permission-row');
                    var visibleRows = 0;

                    rows.forEach(function (row) {
                        var haystack = row.dataset.search || '';
                        var isMatch = !query || haystack.includes(query);
                        row.style.display = isMatch ? '' : 'none';
                        if (isMatch) {
                            visibleRows += 1;
                        }
                    });

                    groupBlock.style.display = visibleRows > 0 ? '' : 'none';
                });
            }

            groupTitles.forEach(function (title) {
                title.addEventListener('click', function () {
                    var groupId = this.dataset.toggleGroup;
                    var body = document.getElementById(groupId);
                    if (!body) {
                        return;
                    }
                    var shouldExpand = body.style.display === 'none';
                    setGroupState(groupId, shouldExpand);
                });
            });

            if (expandAllButton) {
                expandAllButton.addEventListener('click', function () {
                    groupTitles.forEach(function (title) { setGroupState(title.dataset.toggleGroup, true); });
                });
            }

            if (collapseAllButton) {
                collapseAllButton.addEventListener('click', function () {
                    groupTitles.forEach(function (title) { setGroupState(title.dataset.toggleGroup, false); });
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', applySearchFilter);
            }

            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    saveButton.disabled = false;
                    updateGroupSelectedCounts();
                });
            });

            saveButton.addEventListener('click', function () {
                var selectedPermissions = Array.from(document.querySelectorAll('.role-permission-checkbox:checked'))
                    .map(function (checkbox) { return parseInt(checkbox.dataset.permissionId, 10); });

                Swal.fire({
                    title: 'Xác nhận cập nhật quyền?',
                    text: 'Bạn có chắc muốn cập nhật bộ quyền cho vai trò này?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy'
                }).then(function (result) {
                    if (!result.isConfirmed) {
                        return;
                    }

                    $.ajax({
                        url: permissionForm.dataset.updateUrl,
                        method: 'POST',
                        data: {
                            _token: csrfToken(),
                            permissions: selectedPermissions,
                        },
                        success: function (response) {
                            Swal.fire({ icon: 'success', title: 'Thành công', text: response.message });
                            saveButton.disabled = true;
                        },
                        error: function (xhr) {
                            var message = (xhr.responseJSON && xhr.responseJSON.message) || 'Có lỗi xảy ra khi lưu quyền hạn.';
                            Swal.fire({ icon: 'error', title: 'Lỗi', text: message });
                        }
                    });
                });
            });

            updateGroupSelectedCounts();
        }

        var editCategoryForm = document.getElementById('editCategoryForm');
        if (editCategoryForm) {
            document.querySelectorAll('[data-preview-input]').forEach(function (input) {
                input.addEventListener('change', function () {
                    var targetId = input.getAttribute('data-preview-input');
                    var target = document.getElementById(targetId);
                    var file = input.files && input.files[0];

                    if (!target || !file) {
                        return;
                    }

                    var reader = new FileReader();
                    reader.onload = function (event) {
                        target.innerHTML = '<img src="' + event.target.result + '" alt="Preview" style="width:100%; height:100%; object-fit:cover; border-radius:12px;">';
                    };
                    reader.readAsDataURL(file);
                });
            });

            document.querySelectorAll('.toggle-status-btn').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    var form = button.closest('form');
                    var name = button.dataset.name || 'danh muc';
                    var label = button.dataset.label || 'cap nhat';

                    Swal.fire({
                        title: 'Xác nhận ' + label + '?',
                        text: 'Bạn có chắc muốn ' + label + ' danh mục "' + name + '"?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Xác nhận',
                        cancelButtonText: 'Hủy',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33'
                    }).then(function (result) {
                        if (result.isConfirmed && form) {
                            form.submit();
                        }
                    });
                });
            });

            document.querySelectorAll('.delete-category-btn').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    var form = button.closest('form');
                    var name = button.dataset.name || 'danh muc';

                    Swal.fire({
                        title: 'Xóa danh mục?',
                        text: 'Danh mục "' + name + '" sẽ bị xóa.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Xóa',
                        cancelButtonText: 'Hủy',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d'
                    }).then(function (result) {
                        if (result.isConfirmed && form) {
                            form.submit();
                        }
                    });
                });
            });

            document.querySelectorAll('.edit-category-btn').forEach(function (button) {
                button.addEventListener('click', function () {
                    var preview = document.getElementById('editCategoryPreview');
                    var nameInput = document.getElementById('edit_name');
                    var slugInput = document.getElementById('edit_slug');
                    var descriptionInput = document.getElementById('edit_description');
                    var statusInput = document.getElementById('edit_status');
                    var imageInput = document.getElementById('edit_image_file');
                    var baseUrl = editCategoryForm.dataset.baseUrl || '/admin/categories';

                    editCategoryForm.action = baseUrl + '/' + button.dataset.id;
                    if (nameInput) nameInput.value = button.dataset.name || '';
                    if (slugInput) slugInput.value = button.dataset.slug || '';
                    if (descriptionInput) descriptionInput.value = button.dataset.description || '';
                    if (statusInput) statusInput.checked = button.dataset.status === '1';

                    var imageUrl = button.dataset.imageUrl || '';
                    if (preview) {
                        preview.innerHTML = imageUrl
                            ? '<img src="' + imageUrl + '" alt="Preview" style="width:100%; height:100%; object-fit:cover; border-radius:12px;">'
                            : 'Chua co anh';
                    }

                    if (imageInput) {
                        imageInput.value = '';
                    }
                });
            });
        }
    });
})();

/******************contact_reply_summernote**
*****initialize summernote and handle form submission*****/
$(document).ready(function() {
    // Initialize Summernote if element exists
    if ($('#reply_content').length) {
        console.log('🔄 Initializing Summernote...');
        
        $('#reply_content').summernote({
            height: 300,
            minHeight: 250,
            lang: 'vi-VN',
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'undo', 'redo']]
            ],
            callbacks: {
                onInit: function() {
                    console.log('✅ Summernote initialized successfully');
                }
            }
        });

        // Form submission with confirmation
        $('#replyForm').on('submit', function(e) {
            e.preventDefault();

            const replyContent = $('#reply_content').summernote('code');

            if (!replyContent || replyContent.trim() === '<p><br></p>' || !replyContent.replace(/<[^>]*>/g, '').trim()) {
                alert('❌ Vui lòng nhập nội dung phản hồi!');
                return;
            }

            // Ask for confirmation
            const confirmText = prompt('⚠️ Nhập "tôi chắc chắn" để xác nhận gửi phản hồi:');
            
            if (confirmText === null) {
                return; // User cancelled
            }

            if (confirmText !== 'tôi chắc chắn') {
                alert('❌ Xác nhận không chính xác!');
                return;
            }

            console.log('📧 Submitting reply...');

            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang gửi...');

            $.ajax({
                url: $('#replyForm').attr('action'),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({
                    reply_content: replyContent
                }),
                success: function(response) {
                    console.log('✅ Success:', response);
                    alert('✅ ' + response.message);
                    setTimeout(() => {
                        window.location.href = response.redirect || '/admin/contacts';
                    }, 1000);
                },
                error: function(error) {
                    console.error('❌ Error:', error.responseJSON);
                    const errorMsg = error.responseJSON?.message || 'Có lỗi xảy ra';
                    alert('❌ ' + errorMsg);
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Delete contact
        window.deleteContact = function(contactId) {
            if (!confirm('⚠️ Bạn chắc chắn muốn xóa tin nhắn này không? Hành động này không thể hoàn tác!')) {
                return;
            }

            $.ajax({
                url: '/admin/contacts/' + contactId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert('✅ Xóa thành công');
                    window.location.href = '/admin/contacts';
                },
                error: function(error) {
                    alert('❌ Lỗi: ' + (error.responseJSON?.message || 'Không thể xóa'));
                }
            });
        };
    }
});


