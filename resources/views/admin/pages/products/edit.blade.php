@extends('layouts.admin')

@section('title', 'Sửa sản phẩm')

@section('content')

<style>
    .edit-page .x_panel {
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 16px;
    }

    .edit-page .section-title {
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 16px;
    }

    .edit-page .form-group {
        margin-bottom: 16px;
    }

    .edit-page .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .edit-page .form-control {
        border: 1px solid #e5e5e5;
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 14px;
    }

    .edit-page .form-control:focus {
        border-color: #2a6edb;
        box-shadow: 0 0 0 3px rgba(42, 110, 219, 0.1);
    }

    .edit-page textarea.form-control {
        resize: vertical;
        min-height: 120px;
    }

    .edit-page .image-preview {
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        border: 2px dashed #e5e5e5;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        margin-bottom: 12px;
    }

    .edit-page .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .edit-page .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 10px;
    }

    .edit-page .gallery-item {
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #e5e5e5;
        position: relative;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .edit-page .gallery-item:hover {
        border-color: #2a6edb;
    }

    .edit-page .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .edit-page .gallery-item.deleted {
        opacity: 0.5;
        pointer-events: none;
    }

    .edit-page .delete-btn {
        position: absolute;
        top: 2px;
        right: 2px;
        width: 20px;
        height: 20px;
        padding: 0;
        background: #dc3545;
        color: #fff;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .edit-page .btn-group-action {
        display: flex;
        gap: 8px;
        margin-top: 20px;
    }

    .edit-page .btn-group-action .btn {
        flex: 1;
    }

    .edit-page .sidebar-card {
        margin-bottom: 16px;
    }

    .edit-page .info-box {
        background: #f8fafc;
        border-radius: 8px;
        padding: 12px;
        border: 1px solid #e2e8f0;
    }

    .edit-page .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 4px;
    }

    .edit-page .required {
        color: #dc3545;
    }
</style>

<div class="right_col edit-page" role="main">
    <div class="page-title" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div>
                <h3>Sửa sản phẩm</h3>
            </div>
            <div>
                <a href="{{ route('admin.products.list') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" id="editProductForm">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Main Content -->
            <div class="col-md-8">
                <!-- Thông tin cơ bản -->
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Thông tin cơ bản</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="form-group">
                            <label for="name" class="form-label">Tên sản phẩm <span class="required">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="slug" class="form-label">Slug sản phẩm</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                   id="slug" name="slug" value="{{ old('slug', $product->slug) }}"
                                   placeholder="Tự động nếu để trống">
                            @error('slug')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Giá & Tồn kho -->
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Giá & Tồn kho</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price" class="form-label">Giá bán <span class="required">*</span></label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price', $product->price) }}"
                                           step="0.01" min="0" required>
                                    @error('price')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock" class="form-label">Tồn kho <span class="required">*</span></label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                           id="stock" name="stock" value="{{ old('stock', $product->stock) }}"
                                           min="0" required>
                                    @error('stock')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id" class="form-label">Danh mục <span class="required">*</span></label>
                                    <select class="form-control @error('category_id') is-invalid @enderror"
                                            id="category_id" name="category_id" required>
                                        <option value="">Chọn danh mục</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                    @selected(old('category_id', $product->category_id) == $category->id)>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unit" class="form-label">Đơn vị</label>
                                    <input type="text" class="form-control @error('unit') is-invalid @enderror"
                                           id="unit" name="unit" value="{{ old('unit', $product->unit) }}"
                                           placeholder="kg, hộp, túi...">
                                    @error('unit')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label for="status" class="form-label">Trạng thái <span class="required">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror"
                                    id="status" name="status" required>
                                <option value="in_stock" @selected(old('status', $product->status) === 'in_stock')>
                                    Hàng còn
                                </option>
                                <option value="out_of_stock" @selected(old('status', $product->status) === 'out_of_stock')>
                                    Hết hàng
                                </option>
                            </select>
                            @error('status')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Ảnh chính -->
                <div class="x_panel sidebar-card">
                    <div class="x_title">
                        <h2>Ảnh chính</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="image-preview">
                            @if($product->firstImage)
                                <img id="mainImagePreview" src="{{ asset('storage/uploads/product/' . $product->firstImage->image) }}" alt="">
                            @else
                                <div style="color: #999; text-align: center;">Chưa có ảnh</div>
                            @endif
                        </div>
                        <input type="file" id="image_file" name="image_file" class="form-control" accept="image/*">
                        <small style="color: #999; display: block; margin-top: 6px;">Để trống để giữ nguyên</small>
                        @error('image_file')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Ảnh phụ -->
                <div class="x_panel sidebar-card">
                    <div class="x_title">
                        <h2>Thư viện ảnh</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <!-- Current Gallery -->
                        @if($product->image->count() > 1)
                            <div style="margin-bottom: 12px;">
                                <small style="font-weight: 600; color: #0f172a;">Ảnh hiện tại:</small>
                                <div id="editCurrentGallery" class="gallery-grid" style="margin-top: 8px;">
                                    @foreach($product->image->skip(1) as $image)
                                        <div class="gallery-item existing-gallery-item image-item" data-image-id="{{ $image->id }}">
                                            <img src="{{ asset('storage/uploads/product/' . $image->image) }}" alt="">
                                            <button type="button" class="delete-btn delete-image-btn" data-image-id="{{ $image->id }}">×</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- New Images Upload -->
                        <div>
                            <small style="font-weight: 600; color: #0f172a;">Thêm ảnh phụ:</small>
                            <input type="file" id="edit_additional_images" name="additional_images[]" class="form-control" accept="image/*" multiple style="margin-top: 8px;">
                            <small style="color: #999; display: block; margin-top: 6px;">Chọn nhiều ảnh</small>
                            <div id="editAdditionalPreview" class="gallery-grid" style="margin-top: 12px;"></div>
                        </div>

                        <input type="hidden" id="deleted_images" name="deleted_images" value="">

                        @error('additional_images')
                            <div class="error-message" style="margin-top: 8px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="btn-group-action">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Lưu
                    </button>
                    <a href="{{ route('admin.products.list') }}" class="btn btn-default">
                        Hủy
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// ========== MAIN IMAGE PREVIEW ==========
document.getElementById('image_file')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
            const preview = document.getElementById('mainImagePreview');
            if (preview) {
                preview.src = event.target.result;
            } else {
                const container = document.querySelector('.image-preview');
                const img = document.createElement('img');
                img.id = 'mainImagePreview';
                img.src = event.target.result;
                container.innerHTML = '';
                container.appendChild(img);
            }
        };
        reader.readAsDataURL(file);
    }
});

// ========== GALLERY IMAGES PREVIEW ==========
document.getElementById('edit_additional_images')?.addEventListener('change', function (e) {
    const files = e.target.files;
    const preview = document.getElementById('editAdditionalPreview');
    preview.innerHTML = '';

    const currentCount = document.querySelectorAll('.existing-gallery-item:not(.deleted)').length;
    const totalWillBe = currentCount + files.length;

    if (totalWillBe > 5) {
        alert('⚠️ Tối đa 5 ảnh tổng cộng. Hiện có ' + currentCount + ' ảnh, bạn thêm ' + files.length + ' = ' + totalWillBe + ' ảnh.');
        this.value = '';
        return;
    }

    Array.from(files).slice(0, 5 - currentCount).forEach((file) => {
        const reader = new FileReader();
        reader.onload = (event) => {
            const div = document.createElement('div');
            div.className = 'gallery-item';
            div.style.cssText = 'aspect-ratio: 1; border-radius:8px; overflow:hidden; border:2px solid #e5e5e5; position: relative;';
            div.innerHTML = `
                <img src="${event.target.result}" alt="preview" style="width:100%; height:100%; object-fit:cover;">
                <button type="button" class="delete-btn delete-preview-btn" style="position:absolute; top:2px; right:2px; width:20px; height:20px; padding:0; background:#dc3545; color:#fff; border:none; border-radius:50%; cursor:pointer; font-size:12px; display:flex; align-items:center; justify-content:center;">×</button>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});

// ========== DELETE IMAGE HANDLERS ==========
function attachDeleteImageHandlers() {
    document.querySelectorAll('.delete-image-btn').forEach(btn => {
        btn.removeEventListener('click', handleDeleteImage);
        btn.addEventListener('click', handleDeleteImage);
    });
}

function handleDeleteImage(e) {
    e.preventDefault();
    const imageId = this.dataset.imageId;
    const container = this.closest('.image-item, .existing-gallery-item');

    if (!container) return;

    const deletedInput = document.getElementById('deleted_images');
    let deletedIds = deletedInput.value ? deletedInput.value.split(',').filter(id => id) : [];

    if (!deletedIds.includes(imageId)) {
        deletedIds.push(imageId);
    }
    deletedInput.value = deletedIds.join(',');

    container.classList.add('deleted');
}

// Initialize delete handlers
attachDeleteImageHandlers();
</script>

@endsection
