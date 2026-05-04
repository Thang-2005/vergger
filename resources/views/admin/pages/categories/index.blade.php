@extends('layouts.admin')

@section('title', 'Quản Lý Danh Mục')

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>{{ 'Quản Lý Danh Mục' }}</h3>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row mb-3">
        <div class="col-md-4 col-sm-6">
            <div class="x_panel">
                <div class="x_content">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <div>
                            <p style="margin:0 0 4px; color:#7a7a7a;">{{ 'Tổng Danh Mục' }}</p>
                            <h2 style="margin:0;">{{ $totalCategories }}</h2>
                        </div>
                        <i class="fa fa-folder-open fa-2x" style="color:#2a6edb;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="x_panel">
                <div class="x_content">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <div>
                            <p style="margin:0 0 4px; color:#7a7a7a;">{{ 'Đang Hoạt Động' }}</p>
                            <h2 style="margin:0;">{{ $activeCategories }}</h2>
                        </div>
                        <i class="fa fa-check-circle fa-2x" style="color:#1abb9c;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="x_panel">
                <div class="x_content">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <div>
                            <p style="margin:0 0 4px; color:#7a7a7a;">{{ 'Đang Ẩn' }}</p>
                            <h2 style="margin:0;">{{ $inactiveCategories }}</h2>
                        </div>
                        <i class="fa fa-eye-slash fa-2x" style="color:#f39c12;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title d-flex align-items-center justify-content-between">
                    <div>
                        <h2>{{ 'Danh Sách Danh Mục' }}</h2>
                        <div class="clearfix"></div>
                    </div>
                    @if($canCreateCategory)
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#createCategoryModal">
                            <i class="fa fa-plus"></i> {{ 'Thêm Danh Mục' }}
                        </button>
                    @endif
                </div>

                <div class="x_content">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:70px;">#</th>
                                    <th style="width:90px;">{{ 'Ảnh' }}</th>
                                    <th>{{ 'Tên Danh Mục' }}</th>
                                    <th>{{ 'Slug' }}</th>
                                    <th>{{ 'Sản Phẩm' }}</th>
                                    <th>{{ 'Trạng thái' }}</th>
                                    <th style="width:280px;">{{ 'Hành động' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($category->image)
                                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" style="width:60px; height:60px; object-fit:cover; border-radius:10px; border:1px solid #e5e5e5;">
                                            @else
                                                <div style="width:60px; height:60px; border-radius:10px; border:1px dashed #d9d9d9; display:flex; align-items:center; justify-content:center; color:#999; font-size:12px; text-align:center; padding:4px;">{{ 'Không có ảnh' }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="display:flex; flex-direction:column;">
                                                <strong>{{ $category->name }}</strong>
                                                @if($category->description)
                                                    <small style="color:#777;">{{ \Illuminate\Support\Str::limit($category->description, 70) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td><code>{{ $category->slug }}</code></td>
                                        <td><span class="label label-info">{{ $category->products_count }}</span></td>
                                        <td>
                                            @if($category->status)
                                                <span class="label label-success">{{ 'Hoạt Động' }}</span>
                                            @else
                                                <span class="label label-default">{{ 'Đã Ẩn' }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" style="display:flex; flex-wrap:wrap; gap:6px;">
                                                @if($canToggleCategory)
                                                    <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST" class="toggle-status-form" style="display:inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn {{ $category->status ? 'btn-warning' : 'btn-success' }} toggle-status-btn"
                                                            data-label=\"{{ $category->status ? 'Ẩn' : 'Kích Hoạt' }}\"
                                                            data-name="{{ $category->name }}">
                                                            <i class="fa {{ $category->status ? 'fa-eye-slash' : 'fa-check' }}"></i>
                                                            {{ $category->status ? 'Ẩn' : 'Kích Hoạt' }}
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($canUpdateCategory)
                                                    <button
                                                        type="button"
                                                        class="btn btn-info edit-category-btn"
                                                        data-toggle="modal"
                                                        data-target="#editCategoryModal"
                                                        data-id="{{ $category->id }}"
                                                        data-name="{{ e($category->name) }}"
                                                        data-slug="{{ e($category->slug) }}"
                                                        data-description="{{ e($category->description) }}"
                                                        data-image-url="{{ $category->image ? asset('storage/' . $category->image) : '' }}"
                                                        data-status="{{ $category->status ? 1 : 0 }}"
                                                    >
                                                        <i class="fa fa-edit"></i> {{ 'Sửa' }}
                                                    </button>
                                                @endif

                                                @if($canDeleteCategory)
                                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="delete-category-form" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger delete-category-btn" data-name="{{ $category->name }}">
                                                            <i class="fa fa-trash"></i> {{ 'Xóa' }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Chua co danh muc nao.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="createCategoryModalLabel">Them danh muc moi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if($canCreateCategory)
                    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Ten danh muc</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="slug">{{ 'Slug sản phẩm' }} (tu dong neu de trong)</label>
                            <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug') }}">
                        </div>
                        <div class="form-group">
                            <label for="description">Mo ta</label>
                            <textarea id="description" name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="image_file">Chon anh danh muc</label>
                            <div style="margin-bottom:10px;">
                                <div id="createCategoryPreview" style="width:100%; height:180px; border-radius:12px; border:1px dashed #d9d9d9; display:flex; align-items:center; justify-content:center; color:#999; text-align:center; padding:12px;">
                                    Chua co anh duoc chon
                                </div>
                            </div>
                            <input type="file" id="image_file" name="image_file" class="form-control" accept="image/*" data-preview-input="createCategoryPreview">
                        </div>
                        <div class="form-group">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                                Kich hoat
                            </label>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Dong</button>
                            <button type="submit" class="btn btn-success">Luu danh muc</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning" style="margin-bottom:0;">Ban khong co quyen them danh muc.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editCategoryModalLabel">Chinh sua danh muc</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if($canUpdateCategory)
                    <form method="POST" action="" id="editCategoryForm" enctype="multipart/form-data" data-base-url="{{ url('/admin/categories') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="edit_name">Ten danh muc</label>
                            <input type="text" id="edit_name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_slug">{{ 'Slug sản phẩm' }}</label>
                            <input type="text" id="edit_slug" name="slug" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="edit_description">Mo ta</label>
                            <textarea id="edit_description" name="description" rows="4" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_image_file">Anh danh muc</label>
                            <div style="margin-bottom:10px;">
                                <div id="editCategoryPreview" style="width:100%; height:180px; border-radius:12px; border:1px dashed #d9d9d9; display:flex; align-items:center; justify-content:center; color:#999; text-align:center; padding:12px;">
                                    Chua co anh
                                </div>
                            </div>
                            <input type="file" id="edit_image_file" name="image_file" class="form-control" accept="image/*" data-preview-input="editCategoryPreview">
                            <p class="help-block" style="margin-bottom:0;">Bo trong neu khong muon doi anh.</p>
                        </div>
                        <div class="form-group">
                            <label class="checkbox-inline">
                                <input type="checkbox" id="edit_status" name="status" value="1">
                                Kich hoat
                            </label>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Dong</button>
                            <button type="submit" class="btn btn-primary">Luu thay doi</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning" style="margin-bottom:0;">Ban khong co quyen chinh sua danh muc.</div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
