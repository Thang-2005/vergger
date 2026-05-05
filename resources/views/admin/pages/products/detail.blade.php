@extends('layouts.admin')

@section('title', 'Chi tiết sản phẩm')

@section('content')

<style>
    .detail-page {
        background: #f8fafc;
        min-height: 100vh;
    }

    .detail-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }

    .detail-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 8px 0;
    }

    .detail-header .subtitle {
        opacity: 0.9;
        font-size: 16px;
    }

    .product-main-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .product-main-image:hover {
        transform: scale(1.02);
    }

    .image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
        gap: 12px;
        margin-top: 20px;
    }

    .gallery-thumb {
        height: 70px;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.3s ease;
        object-fit: cover;
    }

    .gallery-thumb.active,
    .gallery-thumb:hover {
        border-color: #667eea;
        transform: scale(1.05);
    }

    .info-card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
        margin-bottom: 20px;
    }

    .info-row {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        min-width: 120px;
        flex-shrink: 0;
    }

    .info-value {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        flex: 1;
    }

    .price-highlight {
        color: #10b981 !important;
        font-size: 24px !important;
        font-weight: 800 !important;
    }

    .stock-highlight {
        font-size: 32px;
        font-weight: 800;
        background: linear-gradient(135deg, #10b981, #059669);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-instock {
        background: #dcfce7;
        color: #166534;
    }

    .status-outstock {
        background: #fee2e2;
        color: #991b1b;
    }

    .summary-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
    }

    .summary-stat {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .summary-stat:last-child {
        border-bottom: none;
    }

    .stat-number {
        font-size: 28px;
        font-weight: 800;
        line-height: 1;
    }

    .description-content {
        line-height: 1.7;
        color: #475569;
        font-size: 15px;
    }

    .btn-modern {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-secondary-modern {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    @media (max-width: 768px) {
        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        
        .info-label {
            min-width: auto;
        }
    }
</style>

<div class="right_col detail-page" role="main">
    <!-- Header -->
    <div class="detail-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1>{{ $product->name }}</h1>
                <p class="subtitle mb-0">ID: #{{ $product->id }} • {{ $product->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn-modern btn-primary-modern">
                        <i class="fa fa-edit"></i> Sửa sản phẩm
                    </a>
                    <a href="{{ route('admin.products.list') }}" class="btn-modern btn-secondary-modern">
                        <i class="fa fa-arrow-left"></i> Danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Product Images -->
            <div class="info-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0" style="font-size: 20px; font-weight: 700; color: #1e293b;">
                        <i class="fa fa-image"></i> Ảnh sản phẩm
                    </h3>
                </div>
                
                <div class="text-center mb-4">
                    @if($product->firstImage)
                        <img src="{{ asset('storage/uploads/product/' . $product->firstImage->image) }}"
                            alt="{{ $product->name }}" 
                            class="product-main-image" 
                            id="mainProductImage">
                    @else
                        <div class="product-main-image" style="background: #f8fafc; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 18px;">
                            <i class="fa fa-image" style="font-size: 48px; opacity: 0.5;"></i>
                        </div>
                    @endif
                </div>

                @if($product->image->count() > 0)
                <div class="image-gallery">
                    @foreach($product->image as $image)
                    <img src="{{ asset('storage/uploads/product/' . $image->image) }}" 
                         alt="" 
                         class="gallery-thumb {{ $loop->first ? 'active' : '' }}"
                         onclick="changeMainImage('{{ asset('storage/uploads/product/' . $image->image) }}')">
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="info-card">
                <h3 class="mb-4" style="font-size: 20px; font-weight: 700; color: #1e293b;">
                    <i class="fa fa-info-circle"></i> Thông tin chi tiết
                </h3>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label"><i class="fa fa-tag"></i> Danh mục</div>
                            <div class="info-value">
                                @if($product->category)
                                    <span class="status-badge status-instock">{{ $product->category->name }}</span>
                                @else
                                    <span class="text-muted">Chưa phân loại</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label"><i class="fa fa-link"></i> Slug</div>
                            <div class="info-value" style="font-family: 'Courier New', monospace; font-size: 14px;">{{ $product->slug }}</div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-2">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label"><i class="fa fa-dollar-sign"></i> Giá bán</div>
                            <div class="info-value price-highlight">
                                {{ number_format($product->price, 0, ',', '.') }} <span style="font-size: 14px;">đ</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label"><i class="fa fa-boxes"></i> Tồn kho</div>
                            <div class="info-value">
                                <span class="stock-highlight">{{ $product->stock }}</span> 
                                <span style="font-size: 14px;">{{ $product->unit ?? 'cái' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-2">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label"><i class="fa fa-cube"></i> Đơn vị</div>
                            <div class="info-value">{{ $product->unit ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label"><i class="fa fa-toggle-on"></i> Trạng thái</div>
                            <div class="info-value">
                                @if($product->status === 'in_stock')
                                    <span class="status-badge status-instock">Hàng còn</span>
                                @else
                                    <span class="status-badge status-outstock">Hết hàng</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-5 pt-4 border-top">
                    <h4 class="mb-3" style="font-size: 18px; font-weight: 700; color: #1e293b;">
                        <i class="fa fa-align-left"></i> Mô tả sản phẩm
                    </h4>
                    <div class="description-content">
                        {!! $product->description ? nl2br(e($product->description)) : '<span class="text-muted">Chưa có mô tả</span>' !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Summary Card -->
            <div class="summary-card mb-4">
                <h3 class="mb-4" style="font-size: 20px; font-weight: 700;">
                    <i class="fa fa-chart-bar"></i> Tóm tắt
                </h3>
                
                <div class="summary-stat">
                    <div>
                        <div style="font-size: 13px; opacity: 0.9; margin-bottom: 4px;">Mã SP</div>
                        <div class="stat-number">#{{ $product->id }}</div>
                    </div>
                </div>

                <div class="summary-stat">
                    <div>
                        <div style="font-size: 13px; opacity: 0.9; margin-bottom: 4px;">Trạng thái</div>
                        @if($product->status === 'in_stock')
                            <div class="status-badge status-instock" style="background: rgba(255,255,255,0.2); color: white; backdrop-filter: blur(10px);">Hàng còn</div>
                        @else
                            <div class="status-badge status-outstock" style="background: rgba(255,255,255,0.2); color: white; backdrop-filter: blur(10px);">Hết hàng</div>
                        @endif
                    </div>
                </div>

                <div class="summary-stat">
                    <div>
                        <div style="font-size: 13px; opacity: 0.9; margin-bottom: 4px;">Danh mục</div>
                        <div class="stat-number">{{ $product->category?->name ?? 'Chưa phân loại' }}</div>
                    </div>
                </div>

                <div class="summary-stat">
                    <div>
                        <div style="font-size: 13px; opacity: 0.9; margin-bottom: 4px;">Giá bán</div>
                        <div class="stat-number" style="color: #fbbf24; font-size: 24px;">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                    </div>
                </div>

                <div class="summary-stat">
                    <div>
                        <div style="font-size: 13px; opacity: 0.9; margin-bottom: 8px;">Tồn kho</div>
                        <div style="font-size: 36px; font-weight: 900; line-height: 1; color: #10b981;">
                            {{ $product->stock }}
                        </div>
                        <div style="font-size: 13px; opacity: 0.8;">{{ $product->unit ?? 'cái' }}</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="info-card">
                <h3 class="mb-4" style="font-size: 18px; font-weight: 700; color: #1e293b;">
                    <i class="fa fa-bolt"></i> Thao tác nhanh
                </h3>
                <div class="d-grid gap-3">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn-modern btn-primary-modern">
                        <i class="fa fa-edit"></i> Sửa sản phẩm
                    </a>
                    <a href="{{ route('admin.products.list') }}" class="btn-modern btn-secondary-modern">
                        <i class="fa fa-list"></i> Xem danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeMainImage(src) {
    const mainImage = document.getElementById('mainProductImage');
    mainImage.src = src;
    
    // Update active thumbnail
    document.querySelectorAll('.gallery-thumb').forEach(thumb => {
        thumb.classList.remove('active');
    });
    event.target.classList.add('active');
}
</script>

@endsection