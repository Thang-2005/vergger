@extends('layouts.client')

@section ('title','Sản phẩm')
@section ('breadcrumb','Sản phẩm')

@section ('content')



        <!-- PRODUCT DETAILS AREA START -->
        <div class="ltn__product-area ltn__product-gutter">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 order-lg-2 mb-120">
                        <div class="ltn__shop-options">
                            <ul>
                                <li>
                                    <div class="ltn__grid-list-tab-menu ">
                                        <div class="nav">
                                            <a class="active show" data-bs-toggle="tab" href="#liton_product_grid"><i
                                                    class="fas fa-th-large"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="short-by text-center">
                                        <select id="sort-by"class="nice-select">
                                            <option value="default">Sắp xếp mặc định</option>
                                            <option value="latest">Sắp xếp theo mới nhất</option>
                                            <option value="price_asc">Sắp xếp theo giá: thấp đến cao</option>
                                            <option value="price_desc">Sắp xếp theo giá: cao xuống thấp</option>
                                        </select>
                                    </div>
                                </li>
                                <li>
                                    <div class="showing-product-number text-right text-end">
                                        <span>Showing {{ $products->count() }} of {{ $products->total() }} results</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="liton_product_grid">
                               <div class="loading-spinner" id="loading-spinner" style="display: none;">
                                    <div class="loader"></div>
                                </div>

                                <div class="ltn__product-tab-content-inner ltn__product-grid-view" id="product_list">
                                    @include('clients.components.models.product_list', ['products' => $products])
                                </div>
                            </div>
                        </div>
                        <div class="ltn__pagination-area text-center">
                            <div class="ltn__pagination">
                                {{ $products->links('clients.components.panimation.panimation_customer') }}
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4  mb-120">
                        <aside class="sidebar ltn__shop-sidebar">
                            <!-- Category Widget -->
                            <div class="widget ltn__menu-widget">
                                <h4 class="ltn__widget-title ltn__widget-title-border">Danh mục sản phẩm</h4>
                                @foreach($categories as $category)
                                    <ul>
                                        <li><a href="javascript:void(0)" class="category-filter" data-id="{{ $category->id }}">{{ $category->name }} <span><i class="fas fa-long-arrow-alt-right"></i></span></a>
                                        </li>
                                    </ul>
                                @endforeach
                                
                            </div>
                            <!-- Price Filter Widget -->
                             <div class="widget ltn__price-filter-widget">
                                <h4 class="ltn__widget-title ltn__widget-title-border">Lọc theo giá</h4>
                                <div class="price_filter">
                                    <div class="price_slider_amount">
                                        <input type="submit" value="Your range:" />
                                        <input type="text" class="amount" name="price" placeholder="Add Your Price" />
                                    </div>
                                    <div class="slider-range"></div>
                                </div>
                            </div>
                            <!-- Top Rated Product Widget -->
                            <div class="widget ltn__top-rated-product-widget">
                                <h4 class="ltn__widget-title ltn__widget-title-border">Sản phẩm được ưa thích</h4>
                                @if($products_favorite)
                                <ul>
                                   
                                    <li>
                                        <div class="top-rated-product-item clearfix">
                                            <div class="top-rated-product-img">
                                                <a href="product-details.html"><img src="{{ $products_favorite->image_url }}" alt="{{$products_favorite->name}}"></a>
                                            </div>
                                            <div class="top-rated-product-info">
                                                <div class="product-ratting">
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                    </ul>
                                                </div>
                                                <h6><a href="#">{{$products_favorite->name}}</a></h6>
                                                <div class="product-price">
                                                    <span>${{ number_format($products_favorite->price, 0, '.', ',') }}VND</span>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    
                                </ul>
                                @endif
                            </div>
                            <!-- Search Widget -->
                            <div class="widget ltn__search-widget">
                                <h4 class="ltn__widget-title ltn__widget-title-border">Search Objects</h4>
                                <form action="{{ route('products.search') }}" method="get">
                                    <input type="text" name="search" placeholder="Search your keyword...">
                                    <button type="submit"><i class="fas fa-search"></i></button>
                                </form>
                            </div>
                            <!-- Tagcloud Widget -->
                            
                            <!-- Size Widget -->
                            
                            <!-- Color Widget -->
                            
                            <!-- Banner Widget -->
                            <div class="widget ltn__banner-widget">
                                <a href="shop.html"><img src="{{asset('asset/client/img/banner/banner-1.jpg')}}" alt="#"></a>
                            </div>

                        </aside>
                    </div>
                </div>
            </div>
        </div>
        <!-- PRODUCT DETAILS AREA END -->

@endsection