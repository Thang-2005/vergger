@extends('layouts.admin')

@section('title', __('messages.product_management'))

@section('content')
@php
    $statusMeta = [
        'in_stock' => ['label' => __('messages.in_stock'), 'class' => 'success', 'icon' => 'fa-check-circle'],
        'out_of_stock' => ['label' => __('messages.out_of_stock'), 'class' => 'danger', 'icon' => 'fa-times-circle'],
    ];

    $statusOptions = [
        '' => __('messages.all_status'),
        'in_stock' => __('messages.in_stock'),
        'out_of_stock' => __('messages.out_of_stock'),
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
            <h3>{{ __('messages.product_management') }}</h3>
            <p style="margin:6px 0 0; color:#7a7a7a;">{{ __('messages.search_and_manage') }}</p>
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
                            <p style="margin:0 0 4px; color:#7a7a7a;">{{ __('messages.in_stock') }}</p>
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
                            <p style="margin:0 0 4px; color:#7a7a7a;">{{ __('messages.out_of_stock') }}</p>
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
                        <h2>{{ __('messages.product_list') }}</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                        <span class="label label-default" style="font-size:12px; padding:6px 10px;">Tổng tồn kho: {{ $totalStock }}</span>
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#createProductModal">
                            <i class="fa fa-plus"></i> {{ __('messages.add_product') }}
                        </button>
                    </div>
                </div>

                <div class="x_content">
                    <form method="GET" action="{{ route('admin.products.list') }}" class="row" style="margin-bottom:18px;">
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="keyword">Từ khóa</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" value="{{ request('keyword') }}" placeholder="{{ __('messages.search_product') }}">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="category_id">{{ __('messages.category') }}</label>
                                <select id="category_id" name="category_id" class="form-control">
                                    <option value="">{{ __('messages.all_categories') }}</option>
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
                                <label for="status">{{ __('messages.status') }}</label>
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
                                    <i class="fa fa-search"></i> {{ __('messages.filter') }}
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
                                    <th style="width:100px;">{{ __('messages.product_image') }}</th>
                                    <th>{{ __('messages.product_name') }}</th>
                                    <th>{{ __('messages.category') }}</th>
                                    <th style="width:140px;">{{ __('messages.price') }}</th>
                                    <th style="width:120px;">{{ __('messages.inventory') }}</th>
                                    <th style="width:130px;">{{ __('messages.status') }}</th>
                                    <th style="width:260px;">{{ __('messages.action') }}</th>
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
                                                <small style="color:#999;">{{ __('messages.product_slug') }}: <code>{{ $product->slug }}</code></small>
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
                                                <button
                                                    type="button"
                                                    class="btn btn-primary btn-show-product"
                                                    data-name="{{ e($product->name) }}"
                                                    data-slug="{{ e($product->slug) }}"
                                                    data-category="{{ e($product->category?->name ?? 'Chưa phân loại') }}"
                                                    data-price="{{ e($formatPrice($product->price)) }}"
                                                    data-stock="{{ e($product->stock) }}"
                                                    data-status="{{ e($statusInfo['label']) }}"
                                                    data-unit="{{ e($product->unit ?? '') }}"
                                                    data-description="{{ e($product->description ?? '') }}"
                                                    data-image-url="{{ e($imageUrl) }}"
                                                    onclick="openProductDetail(this); return false;"
                                                >
                                                    <i class="fa fa-eye"></i> {{ __('messages.view_details') }}
                                                </button>
                                                <button
                                                    type="button"
                                                    class="btn btn-info btn-edit-product"
                                                    data-id="{{ $product->id }}"
                                                    data-name="{{ e($product->name) }}"
                                                    data-slug="{{ e($product->slug) }}"
                                                    data-price="{{ e($product->price) }}"
                                                    data-stock="{{ e($product->stock) }}"
                                                    data-category-id="{{ e($product->category_id) }}"
                                                    data-status="{{ e($product->status) }}"
                                                    data-unit="{{ e($product->unit ?? '') }}"
                                                    data-description="{{ e($product->description ?? '') }}"
                                                    data-image-url="{{ e($imageUrl) }}"
                                                    onclick="openProductEdit(this); return false;"
                                                >
                                                    <i class="fa fa-edit"></i> {{ __('messages.edit') }}
                                                </button>
                                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="delete-product-form" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger delete-product-btn" data-name="{{ $product->name }}">
                                                        <i class="fa fa-trash"></i> {{ __('messages.delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center" style="padding:30px 15px;">{{ __('messages.no_products') }}</td>
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
                <h4 class="modal-title" id="createProductModalLabel">{{ __('messages.add_new_product') }}</h4>
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
                            <label for="create_name">{{ __('messages.product_name') }}</label>
                            <input type="text" id="create_name" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="create_slug">{{ __('messages.product_slug') }}</label>
                            <input type="text" id="create_slug" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="{{ __('messages.auto_if_empty') }}">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create_price">{{ __('messages.price') }}</label>
                                    <input type="number" id="create_price" name="price" class="form-control" min="0" step="0.01" value="{{ old('price') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create_stock">{{ __('messages.inventory') }}</label>
                                    <input type="number" id="create_stock" name="stock" class="form-control" min="0" step="1" value="{{ old('stock', 0) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create_category_id">{{ __('messages.category') }}</label>
                                    <select id="create_category_id" name="category_id" class="form-control" required>
                                        <option value="">{{ __('messages.select_category') }}</option>
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
                                    <label for="create_status">{{ __('messages.status') }}</label>
                                    <select id="create_status" name="status" class="form-control" required>
                                        <option value="in_stock" {{ old('status', 'in_stock') === 'in_stock' ? 'selected' : '' }}>{{ __('messages.in_stock') }}</option>
                                        <option value="out_of_stock" {{ old('status') === 'out_of_stock' ? 'selected' : '' }}>{{ __('messages.out_of_stock') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="create_unit">{{ __('messages.unit') }}</label>
                            <input type="text" id="create_unit" name="unit" class="form-control" value="{{ old('unit') }}" placeholder="Ví dụ: kg, hộp, túi">
                        </div>
                        <div class="form-group">
                            <label for="create_description">{{ __('messages.description') }}</label>
                            <textarea id="create_description" name="description" rows="5" class="form-control" style="resize:vertical;">{{ old('description') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="create_image_file">{{ __('messages.product_image') }} sản phẩm</label>
                            <div style="margin-bottom:10px;">
                                <div id="createProductPreview" style="width:100%; height:220px; border-radius:14px; border:1px dashed #d9d9d9; display:flex; align-items:center; justify-content:center; overflow:hidden; background:#fafafa;">
                                    <span style="color:#999;">{{ __('messages.no_image') }}</span>
                                </div>
                            </div>
                            <input type="file" id="create_image_file" name="image_file" class="form-control" accept="image/*" data-preview-input="createProductPreview" required>
                        </div>
                        <div class="text-right" style="display:flex; gap:8px; justify-content:flex-end; flex-wrap:wrap;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-success">{{ __('messages.save_product') }}</button>
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
                <h4 class="modal-title" id="editProductModalLabel">{{ __('messages.edit_product') }}</h4>
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
                            <label for="edit_name">{{ __('messages.product_name') }}</label>
                            <input type="text" id="edit_name" name="name" class="form-control" value="{{ old('name', $selectedProduct?->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_slug">{{ __('messages.product_slug') }}</label>
                            <input type="text" id="edit_slug" name="slug" class="form-control" value="{{ old('slug', $selectedProduct?->slug) }}" placeholder="{{ __('messages.auto_if_empty') }}">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_price">{{ __('messages.price') }}</label>
                                    <input type="number" id="edit_price" name="price" class="form-control" min="0" step="0.01" value="{{ old('price', $selectedProduct?->price) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_stock">{{ __('messages.inventory') }}</label>
                                    <input type="number" id="edit_stock" name="stock" class="form-control" min="0" step="1" value="{{ old('stock', $selectedProduct?->stock ?? 0) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_category_id">{{ __('messages.category') }}</label>
                                    <select id="edit_category_id" name="category_id" class="form-control" required>
                                        <option value="">{{ __('messages.select_category') }}</option>
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
                                    <label for="edit_status">{{ __('messages.status') }}</label>
                                    <select id="edit_status" name="status" class="form-control" required>
                                        <option value="in_stock" {{ old('status', $selectedProduct?->status ?? 'in_stock') === 'in_stock' ? 'selected' : '' }}>{{ __('messages.in_stock') }}</option>
                                        <option value="out_of_stock" {{ old('status', $selectedProduct?->status ?? 'in_stock') === 'out_of_stock' ? 'selected' : '' }}>{{ __('messages.out_of_stock') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_unit">{{ __('messages.unit') }}</label>
                            <input type="text" id="edit_unit" name="unit" class="form-control" value="{{ old('unit', $selectedProduct?->unit) }}" placeholder="Ví dụ: kg, hộp, túi">
                        </div>
                        <div class="form-group">
                            <label for="edit_description">{{ __('messages.description') }}</label>
                            <textarea id="edit_description" name="description" rows="5" class="form-control" style="resize:vertical;">{{ old('description', $selectedProduct?->description) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_image_file">{{ __('messages.product_image') }} sản phẩm</label>
                            <div style="margin-bottom:10px;">
                                <div id="editProductPreview" style="width:100%; height:220px; border-radius:14px; border:1px dashed #d9d9d9; display:flex; align-items:center; justify-content:center; overflow:hidden; background:#fafafa;">
                                    @if($selectedProduct?->firstImage && $selectedProduct?->firstImage->image)
                                        <img src="{{ asset('storage/uploads/product/' . $selectedProduct->firstImage->image) }}" alt="{{ $selectedProduct->name }}" style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <span style="color:#999;">{{ __('messages.no_image') }}</span>
                                    @endif
                                </div>
                            </div>
                            <input type="file" id="edit_image_file" name="image_file" class="form-control" accept="image/*" data-preview-input="editProductPreview">
                            <p class="help-block" style="margin-bottom:0;">Bo trong neu khong muon doi anh.</p>
                        </div>
                        <div class="text-right" style="display:flex; gap:8px; justify-content:flex-end; flex-wrap:wrap;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">{{ __('messages.save_changes') }}</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning" style="margin-bottom:0;">{{ __('messages.no_permission_edit_product') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="productDetailModal" tabindex="-1" role="dialog" aria-labelledby="productDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="productDetailModalLabel">{{ __('messages.view_details') }} sản phẩm</h4>
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
                                <div style="font-size:12px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#64748b; margin-bottom:4px;">{{ __('messages.product_name') }}</div>
                                <div id="detailProductName" style="font-size:20px; font-weight:700; color:#0f172a; line-height:1.35;"></div>
                            </div>

                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px 16px; margin-bottom:14px;">
                                <div>
                                    <div style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#64748b; margin-bottom:3px;">{{ __('messages.product_slug') }}</div>
                                    <div id="detailProduct{{ __('messages.product_slug') }}" style="font-size:14px; color:#334155; word-break:break-word;"></div>
                                </div>
                                <div>
                                    <div style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#64748b; margin-bottom:3px;">{{ __('messages.category') }}</div>
                                    <div id="detailProductCategory" style="font-size:14px; color:#334155;"></div>
                                </div>
                                <div>
                                    <div style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#64748b; margin-bottom:3px;">{{ __('messages.price') }}</div>
                                    <div id="detailProductPrice" style="font-size:14px; font-weight:700; color:#0f766e;"></div>
                                </div>
                                <div>
                                    <div style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#64748b; margin-bottom:3px;">{{ __('messages.inventory') }}</div>
                                    <div id="detailProductStock" style="font-size:14px; color:#334155;"></div>
                                </div>
                                <div>
                                    <div style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#64748b; margin-bottom:3px;">{{ __('messages.status') }}</div>
                                    <div id="detailProductStatus" style="font-size:14px; font-weight:700;"></div>
                                </div>
                                <div>
                                    <div style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#64748b; margin-bottom:3px;">{{ __('messages.unit') }}</div>
                                    <div id="detailProductUnit" style="font-size:14px; color:#334155;"></div>
                                </div>
                            </div>

                            <div>
                                <div style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#64748b; margin-bottom:6px;">{{ __('messages.description') }}</div>
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

@endsection
