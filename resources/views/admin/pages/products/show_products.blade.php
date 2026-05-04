@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')
@php
    $statusMeta = [
        'in_stock' => ['label' => 'Hàng còn', 'class' => 'success', 'icon' => 'fa-check-circle'],
        'out_of_stock' => ['label' => 'Hết hàng', 'class' => 'danger', 'icon' => 'fa-times-circle'],
    ];

    $statusOptions = [
        '' => 'Tất cả trạng thái',
        'in_stock' => 'Hàng còn',
        'out_of_stock' => 'Hết hàng',
    ];

    $formatPrice = function ($value) {
        return number_format((float) $value, 0, ',', '.') . ' đ';
    };
@endphp

<style>
    .products-page .btn,
    .products-page .btn-sm,
    .products-page .btn-group-sm > .btn {
        padding: 3px 8px !important;
        font-size: 11px !important;
        line-height: 1.2 !important;
        border-radius: 4px !important;
    }

    .products-page .btn-group {
        gap: 4px !important;
    }

    .products-page .btn i {
        font-size: 11px !important;
    }

    .products-page .pagination {
        margin: 8px 0 0 !important;
    }

    .products-page .page-link,
    .products-page .pagination > li > a,
    .products-page .pagination > li > span {
        padding: 4px 9px !important;
        font-size: 11px !important;
        min-width: 28px !important;
        line-height: 1.25 !important;
    }

    .products-page .x_title .label {
        font-size: 11px !important;
        padding: 4px 8px !important;
    }
</style>

<div class="right_col products-page" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>Quản lý sản phẩm</h3>
            <p style="margin:6px 0 0; color:#7a7a7a;">Tìm kiếm và quản lý</p>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row mb-3">
        <div class="col-md-3 col-sm-6">
            <div class="x_panel">
                <div class="x_content">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                        <div>
                            <p style="margin:0 0 4px; color:#7a7a7a;">Tổng sản phẩm</p>
                            <h2 style="margin:0;">{{ $totalProducts }}</h2>
                        </div>
                        <i class="fa fa-cubes fa-2x" style="color:#2a6edb;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="x_panel">
                <div class="x_content">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                        <div>
                            <p style="margin:0 0 4px; color:#7a7a7a;">Kết quả lọc</p>
                            <h2 style="margin:0;">{{ $filteredProductsCount }}</h2>
                        </div>
                        <i class="fa fa-filter fa-2x" style="color:#f39c12;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="x_panel">
                <div class="x_content">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                        <div>
                            <p style="margin:0 0 4px; color:#7a7a7a;">Hàng còn</p>
                            <h2 style="margin:0;">{{ $inStockProducts }}</h2>
                        </div>
                        <i class="fa fa-check-circle fa-2x" style="color:#1abb9c;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="x_panel">
                <div class="x_content">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                        <div>
                            <p style="margin:0 0 4px; color:#7a7a7a;">Hết hàng</p>
                            <h2 style="margin:0;">{{ $outOfStockProducts }}</h2>
                        </div>
                        <i class="fa fa-exclamation-triangle fa-2x" style="color:#d9534f;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title" style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
                    <div>
                        <h2>Danh sách sản phẩm</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                        <span class="label label-default" style="font-size:12px; padding:6px 10px;">Tổng tồn kho: {{ $totalStock }}</span>
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#createProductModal">
                            <i class="fa fa-plus"></i> Thêm sản phẩm
                        </button>
                    </div>
                </div>

                <div class="x_content">
                    <form method="GET" action="{{ route('admin.products.list') }}" class="row" style="margin-bottom:18px;">
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="keyword">Từ khóa</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" value="{{ request('keyword') }}" placeholder="Tìm kiếm sản phẩm">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="category_id">Danh mục</label>
                                <select id="category_id" name="category_id" class="form-control">
                                    <option value="">Tất cả danh mục</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="status">Trạng thái</label>
                                <select id="status" name="status" class="form-control">
                                    @foreach($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ request('status', '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <div class="form-group" style="margin-top:25px; display:flex; gap:8px;">
                                <button type="submit" class="btn btn-primary" style="width:100%;">
                                    <i class="fa fa-search"></i> Lọc
                                </button>
                                <a href="{{ route('admin.products.list') }}" class="btn btn-default" style="width:100%;">
                                    <i class="fa fa-refresh"></i>
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:70px;">#</th>
                                    <th style="width:100px;">Ảnh sản phẩm</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th style="width:140px;">Giá</th>
                                    <th style="width:120px;">Tồn kho</th>
                                    <th style="width:130px;">Trạng thái</th>
                                    <th style="width:260px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    @php
                                        $statusInfo = $statusMeta[$product->status] ?? ['label' => ucfirst(str_replace('_', ' ', $product->status ?? 'unknown')), 'class' => 'warning', 'icon' => 'fa-info-circle'];
                                        $imageUrl = $product->firstImage && $product->firstImage->image
                                            ? asset('storage/uploads/product/' . $product->firstImage->image)
                                            : asset('storage/uploads/product/default_product.jpg');
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration + (($products->currentPage() - 1) * $products->perPage()) }}</td>
                                        <td>
                                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" style="width:70px; height:70px; object-fit:cover; border-radius:12px; border:1px solid #e5e5e5;">
                                        </td>
                                        <td>
                                            <div style="display:flex; flex-direction:column; gap:4px;">
                                                <strong>{{ $product->name }}</strong>
                                                <small style="color:#777;">{{ \Illuminate\Support\Str::limit($product->description ?? '', 90) ?: 'Chưa có mô tả.' }}</small>
                                                <small style="color:#999;">Slug sản phẩm: <code>{{ $product->slug }}</code></small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="label label-info">{{ $product->category?->name ?? 'Chưa phân loại' }}</span>
                                        </td>
                                        <td>
                                            <strong style="color:#2a6edb;">{{ $formatPrice($product->price) }}</strong>
                                            @if($product->unit)
                                                <div><small style="color:#777;">/ {{ $product->unit }}</small></div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="label label-default">{{ $product->stock }}</span>
                                        </td>
                                        <td>
                                            <span class="label label-{{ $statusInfo['class'] }}">
                                                <i class="fa {{ $statusInfo['icon'] }}"></i>
                                                {{ $statusInfo['label'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" style="display:flex; flex-wrap:wrap; gap:6px;">
                                                <a href="{{ route('admin.products.detail', $product) }}" class="btn btn-primary">
                                                    <i class="fa fa-eye"></i> Xem chi tiết
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-info">
                                                    <i class="fa fa-edit"></i> Sửa
                                                </a>
                                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="delete-product-form" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger delete-product-btn" data-name="{{ $product->name }}">
                                                        <i class="fa fa-trash"></i> Xóa
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center" style="padding:30px 15px;">Không có sản phẩm</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="createProductModalLabel">Thêm sản phẩm mới</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(auth('admin')->user()?->hasPermission('products.create'))
                    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="product_id" value="">
                        <div class="form-group">
                            <label for="create_name">Tên sản phẩm</label>
                            <input type="text" id="create_name" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="create_slug">Slug sản phẩm</label>
                            <input type="text" id="create_slug" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="Tự động nếu trống">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create_price">Giá</label>
                                    <input type="number" id="create_price" name="price" class="form-control" min="0" step="0.01" value="{{ old('price') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create_stock">Tồn kho</label>
                                    <input type="number" id="create_stock" name="stock" class="form-control" min="0" step="1" value="{{ old('stock', 0) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create_category_id">Danh mục</label>
                                    <select id="create_category_id" name="category_id" class="form-control" required>
                                        <option value="">Chọn danh mục</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ (string) old('category_id') === (string) $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create_status">Trạng thái</label>
                                    <select id="create_status" name="status" class="form-control" required>
                                        <option value="in_stock" {{ old('status', 'in_stock') === 'in_stock' ? 'selected' : '' }}>Hàng còn</option>
                                        <option value="out_of_stock" {{ old('status') === 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="create_unit">Đơn vị</label>
                            <input type="text" id="create_unit" name="unit" class="form-control" value="{{ old('unit') }}" placeholder="Ví dụ: kg, hộp, túi">
                        </div>
                        <div class="form-group">
                            <label for="create_description">Mô tả</label>
                            <textarea id="create_description" name="description" rows="5" class="form-control" style="resize:vertical;">{{ old('description') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="create_image_file">Ảnh sản phẩm (Ảnh chính)</label>
                            <div style="margin-bottom:10px;">
                                <div id="createProductPreview" style="width:100%; height:220px; border-radius:14px; border:1px dashed #d9d9d9; display:flex; align-items:center; justify-content:center; overflow:hidden; background:#fafafa;">
                                    <span style="color:#999;">Không có ảnh</span>
                                </div>
                            </div>
                            <input type="file" id="create_image_file" name="image_file" class="form-control" accept="image/*" data-preview-input="createProductPreview" required>
                        </div>
                        <div class="form-group">
                            <label for="create_additional_images">Ảnh phụ (tối đa 5 ảnh)</label>
                            <div class="input-group">
                                <input type="file" id="create_additional_images" name="additional_images[]" class="form-control" accept="image/*" multiple>
                                <span class="input-group-text" style="padding:6px 12px; color:#999; font-size:12px;">Chọn nhiều ảnh</span>
                            </div>
                            <small class="form-text text-muted">Bạn có thể thêm nhiều ảnh phụ để hiển thị trong gallery sản phẩm</small>
                            <div id="createAdditionalPreview" style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;"></div>
                        </div>
                        <div class="text-right" style="display:flex; gap:8px; justify-content:flex-end; flex-wrap:wrap;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-success">Lưu sản phẩm</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning" style="margin-bottom:0;">Bạn không có quyền thêm sản phẩm.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editProductModalLabel">Sửa sản phẩm</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(auth('admin')->user()?->hasPermission('products.update'))
                    <form method="POST" action="" id="editProductForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="product_id" id="edit_product_id" value="{{ old('product_id', $selectedProduct?->id) }}">
                        <div class="form-group">
                            <label for="edit_name">Tên sản phẩm</label>
                            <input type="text" id="edit_name" name="name" class="form-control" value="{{ old('name', $selectedProduct?->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_slug">Slug sản phẩm</label>
                            <input type="text" id="edit_slug" name="slug" class="form-control" value="{{ old('slug', $selectedProduct?->slug) }}" placeholder="Tự động nếu trống">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_price">Giá</label>
                                    <input type="number" id="edit_price" name="price" class="form-control" min="0" step="0.01" value="{{ old('price', $selectedProduct?->price) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_stock">Tồn kho</label>
                                    <input type="number" id="edit_stock" name="stock" class="form-control" min="0" step="1" value="{{ old('stock', $selectedProduct?->stock ?? 0) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_category_id">Danh mục</label>
                                    <select id="edit_category_id" name="category_id" class="form-control" required>
                                        <option value="">Chọn danh mục</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ (string) old('category_id', $selectedProduct?->category_id) === (string) $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_status">Trạng thái</label>
                                    <select id="edit_status" name="status" class="form-control" required>
                                        <option value="in_stock" {{ old('status', $selectedProduct?->status ?? 'in_stock') === 'in_stock' ? 'selected' : '' }}>Hàng còn</option>
                                        <option value="out_of_stock" {{ old('status', $selectedProduct?->status ?? 'in_stock') === 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_unit">Đơn vị</label>
                            <input type="text" id="edit_unit" name="unit" class="form-control" value="{{ old('unit', $selectedProduct?->unit) }}" placeholder="Ví dụ: kg, hộp, túi">
                        </div>
                        <div class="form-group">
                            <label for="edit_description">Mô tả</label>
                            <textarea id="edit_description" name="description" rows="5" class="form-control" style="resize:vertical;">{{ old('description', $selectedProduct?->description) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_image_file">Ảnh sản phẩm (Ảnh chính)</label>
                            <div style="margin-bottom:10px;">
                                <div id="editProductPreview" style="width:100%; height:220px; border-radius:14px; border:1px dashed #d9d9d9; display:flex; align-items:center; justify-content:center; overflow:hidden; background:#fafafa;">
                                    @if($selectedProduct?->firstImage && $selectedProduct?->firstImage->image)
                                        <img src="{{ asset('storage/uploads/product/' . $selectedProduct->firstImage->image) }}" alt="{{ $selectedProduct->name }}" style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <span style="color:#999;">Không có ảnh</span>
                                    @endif
                                </div>
                            </div>
                            <input type="file" id="edit_image_file" name="image_file" class="form-control" accept="image/*" data-preview-input="editProductPreview">
                            <p class="help-block" style="margin-bottom:0;">Bỏ trống nếu không muốn đổi ảnh chính.</p>
                        </div>

                        <div class="form-group">
                            <label for="edit_additional_images">Ảnh phụ (Gallery)</label>
                            <div class="row" style="margin-bottom:12px;">
                                <div class="col-12">
                                    <label style="margin-bottom:8px; display:block; font-weight:600;">Ảnh hiện tại:</label>
                                    <div id="editCurrentGallery" style="display:flex; gap:10px; flex-wrap:wrap;">
                                        @forelse($selectedProduct?->image ?? [] as $index => $img)
                                            @if($index > 0)
                                            <div class="existing-gallery-item image-item" data-image-id="{{ $img->id }}" style="position:relative; width:80px; height:80px; border-radius:8px; overflow:hidden; border:1px solid #ddd;">
                                                <img src="{{ asset('storage/uploads/product/' . $img->image) }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                                                <button type="button" class="delete-image-btn" data-image-id="{{ $img->id }}" style="position:absolute; top:2px; right:2px; width:20px; height:20px; padding:0; background:#dc3545; color:#fff; border:none; border-radius:50%; cursor:pointer; font-size:12px; display:flex; align-items:center; justify-content:center;" title="Xóa ảnh">×</button>
                                            </div>
                                            @endif
                                        @empty
                                        @endforelse
                                    </div>
                                    @if(!$selectedProduct?->image || count($selectedProduct->image) <= 1)
                                    <p style="color:#999; font-size:13px; margin:8px 0 0;">Chưa có ảnh phụ</p>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label style="margin-bottom:8px; display:block; font-weight:600;">Thêm ảnh phụ mới:</label>
                                    <input type="file" id="edit_additional_images" name="additional_images[]" class="form-control" accept="image/*" multiple>
                                    <small class="form-text text-muted">Chọn nhiều ảnh để thêm vào gallery</small>
                                    <div id="editAdditionalPreview" style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;"></div>
                                </div>
                            </div>
                            <input type="hidden" name="deleted_images" id="deleted_images" value="">
                        </div>
                        <div class="text-right" style="display:flex; gap:8px; justify-content:flex-end; flex-wrap:wrap;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning" style="margin-bottom:0;">Bạn không có quyền sửa sản phẩm</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="productDetailModal" tabindex="-1" role="dialog" aria-labelledby="productDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="productDetailModalLabel">Xem chi tiết sản phẩm</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <div id="detailProductPreview" style="width:100%; height:280px; border-radius:16px; border:1px solid #e5e5e5; overflow:hidden; background:#f8f8f8; display:flex; align-items:center; justify-content:center;">
                            <span style="color:#999;">Không có ảnh</span>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div style="background:#fff; border:1px solid #eef1f4; border-radius:16px; padding:18px 20px; box-shadow:0 8px 24px rgba(15,23,42,0.04);">
                            <div style="margin-bottom:12px; padding-bottom:12px; border-bottom:1px solid #f1f5f9;">
                                <div style="font-size:12px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#64748b; margin-bottom:4px;">Tên sản phẩm</div>
                                <div id="detailProductName" style="font-size:20px; font-weight:700; color:#0f172a; line-height:1.35;"></div>
                            </div>

                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px 16px; margin-bottom:14px;">
                                <div>
                                    <div style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#64748b; margin-bottom:3px;">Slug sản phẩm</div>
                                    <div id="detailProduct" style="font-size:14px; color:#334155; word-break:break-word;"></div>
                                </div>
                                <div>
                                    <div style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#64748b; margin-bottom:3px;">Danh mục</div>
                                    <div id="detailProductCategory" style="font-size:14px; color:#334155;"></div>
                                </div>
                                <div>
                                    <div style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#64748b; margin-bottom:3px;">Giá</div>
                                    <div id="detailProductPrice" style="font-size:14px; font-weight:700; color:#0f766e;"></div>
                                </div>
                                <div>
                                    <div style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#64748b; margin-bottom:3px;">Tồn kho</div>
                                    <div id="detailProductStock" style="font-size:14px; color:#334155;"></div>
                                </div>
                                <div>
                                    <div style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#64748b; margin-bottom:3px;">Trạng thái</div>
                                    <div id="detailProductStatus" style="font-size:14px; font-weight:700;"></div>
                                </div>
                                <div>
                                    <div style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#64748b; margin-bottom:3px;">Đơn vị</div>
                                    <div id="detailProductUnit" style="font-size:14px; color:#334155;"></div>
                                </div>
                            </div>

                            <div>
                                <div style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#64748b; margin-bottom:6px;">Mô tả</div>
                                <div id="detailProductDescription" style="font-size:14px; line-height:1.8; color:#475569; white-space:pre-line; background:#f8fafc; border-radius:12px; padding:14px 16px; border:1px solid #e2e8f0; min-height:72px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Handle delete product confirmation
document.querySelectorAll('.delete-product-form').forEach(form => {
    form.addEventListener('submit', function (e) {
        const productName = this.querySelector('.delete-product-btn').dataset.name;
        if (!confirm(`Bạn chắc chắn muốn xóa sản phẩm "${productName}"?\nTất cả ảnh sẽ bị xóa theo.`)) {
            e.preventDefault();
        }
    });
});
</script>
@endsection

