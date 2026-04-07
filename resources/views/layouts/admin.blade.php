<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Dashboard - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    
    <!-- Bootstrap & CSS -->
    <link rel="stylesheet" href="{{asset('backend/css/bootstrap.min.css')}}">
    <link href="{{asset('backend/css/font-awesome.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('backend/css/morris.css')}}" type="text/css"/>
    <link rel="stylesheet" href="{{asset('backend/css/monthly.css')}}">
    <link rel="stylesheet" href="{{asset('backend/css/layout.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="{{asset('backend/js/jquery2.0.3.min.js')}}"></script>
    <script src="{{asset('backend/js/raphael-min.js')}}"></script>
    <script src="{{asset('backend/js/morris.js')}}"></script>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{URL::to('/dashboard')}}" class="logo">
                <div class="logo-icon">
                    <i class="fa fa-shopping-bag"></i>
                </div>
                <span class="logo-text">Trang quản trị</span>
            </a>
            
            <div class="toggle-btn" onclick="toggleSidebar()">
                <i class="fa fa-angle-left"></i>
            </div>
        </div>

        <ul class="sidebar-menu">
           

            <!-- Dashboard -->
            <li class="menu-item">
                <a href="{{URL::to('/dashboard')}}" class="menu-link active">
                    <i class="fa fa-dashboard menu-icon"></i>
                    <span class="menu-text">Tổng quan</span>
                </a>
            </li>

           
            <!-- Categories -->
           
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link" onclick="toggleSubmenu(this)">
                    <i class="fa fa-list menu-icon"></i>
                    <span class="menu-text">Danh mục</span>
                    <i class="fa fa-angle-right menu-arrow"></i>
                </a>
                <ul class="submenu">
                    
                    <li><a href="{{URL::to('/add-category')}}" class="submenu-link">
                        <i class="fa fa-plus"></i> Thêm danh mục
                    </a></li>
                    
                    <li><a href="{{URL::to('/all-category')}}" class="submenu-link">
                        <i class="fa fa-list"></i> Danh sách danh mục
                    </a></li>
                </ul>
            </li>
            <!-- Brands -->
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link" onclick="toggleSubmenu(this)">
                    <i class="fa fa-list menu-icon"></i>
                    <span class="menu-text">Thương hiệu</span>
                    <i class="fa fa-angle-right menu-arrow"></i>
                </a>
                <ul class="submenu">
                    
                    <li><a href="{{URL::to('/add-brand')}}" class="submenu-link">
                        <i class="fa fa-plus"></i> Thêm thương hiệu
                    </a></li>
                    
                    <li><a href="{{URL::to('/all-brand')}}" class="submenu-link">
                        <i class="fa fa-list"></i> Danh sách thương hiệu
                    </a></li>
                </ul>
            </li>
            <!-- Banners -->
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link" onclick="toggleSubmenu(this)">
                    <i class="fa fa-image menu-icon"></i>
                    <span class="menu-text">Banner</span>
                    <i class="fa fa-angle-right menu-arrow"></i>
                </a>
                <ul class="submenu">
                    
                    <li><a href="{{URL::to('/add-banner')}}" class="submenu-link">
                        <i class="fa fa-plus"></i> Thêm banner
                    </a></li>
                    
                    <li><a href="{{URL::to('/all-banner')}}" class="submenu-link">
                        <i class="fa fa-list"></i> Danh sách banner
                    </a></li>
                </ul>
            </li> 
             <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link" onclick="toggleSubmenu(this)">
                    <i class="fa fa-image ship-icon"></i>
                    <span class="menu-text">Vận chuyển</span>
                    <i class="fa fa-angle-right menu-arrow"></i>
                </a>
                <ul class="submenu">
                    
                    <li><a href="{{URL::to('/add-delivery')}}" class="submenu-link">
                        <i class="fa fa-plus"></i> Thêm vận chuyển
                    </a></li>             
                    
                </ul>
            </li> 
           
             <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link" onclick="toggleSubmenu(this)">
                    <i class="fa fa-image menu-icon"></i>
                    <span class="menu-text">Sản phẩm</span>
                    <i class="fa fa-angle-right menu-arrow"></i>
                </a>
                <ul class="submenu">
                    
                    <li><a href="{{URL::to('/add-product')}}" class="submenu-link">
                        <i class="fa fa-plus"></i> Thêm sản phẩm
                    </a></li>
                    
                    <li><a href="{{URL::to('/all-product')}}" class="submenu-link">
                        <i class="fa fa-list"></i> Danh sách sản phẩm
                    </a></li>
                    <li><a href="{{URL::to('/archive-product')}}" class="submenu-link">
                        <i class="fa fa-trash"></i> Danh sách sản phẩm đã xóa
                    </a></li>
                </ul>
            </li> 
            <!-- Admin -->
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link" onclick="toggleSubmenu(this)">
                    <i class="fa fa-user menu-icon"></i>
                    <span class="menu-text">Quản trị viên</span>
                    <i class="fa fa-angle-right menu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{URL::to('/add-admin')}}" class="submenu-link">
                        <i class="fa fa-plus"></i> Thêm quản trị viên
                    </a></li>
                    <li><a href="{{URL::to('/all-admin')}}" class="submenu-link">
                        <i class="fa fa-list"></i> Danh sách quản trị viên
                    </a></li>
                </ul>
            </li>
            <!-- Customer -->
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link" onclick="toggleSubmenu(this)">
                    <i class="fa fa-user menu-icon"></i>
                    <span class="menu-text">Khách hàng</span>
                    <i class="fa fa-angle-right menu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{URL::to('/add-customer')}}" class="submenu-link">
                        <i class="fa fa-plus"></i> Thêm khách hàng
                    </a></li>
                    <li><a href="{{URL::to('/all-customer')}}" class="submenu-link">
                        <i class="fa fa-list"></i> Danh sách khách hàng
                    </a></li>
                </ul>
            </li>
        </ul> <!-- end sidebar-menu -->
    </aside> <!-- end sidebar -->

            
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="main-header">
            <div class="mobile-toggle" onclick="toggleMobileSidebar()">
                <i class="fa fa-bars"></i>
            </div>
            
            <div class="search-box">
                <input type="text" placeholder="Tìm kiếm sản phẩm, đơn hàng...">
                <i class="fa fa-search"></i>
            </div>

            <div class="header-right">
                <div class="header-icon">
                    <i class="fa fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>

                <div class="header-icon">
                    <i class="fa fa-envelope"></i>
                    <span class="notification-badge">5</span>
                </div>

                <div class="user-dropdown">
                    <div class="user-avatar">
                        
                    </div>
                    <div class="user-info">
                        <div class="user-name"></div>
                        <div class="user-role">
                            Quản trị viên
                        </div>
                    </div>
                    <i class="fa fa-angle-down"></i>

                    <div class="dropdown-menu-custom">
                        <a href="#" class="dropdown-item-custom">
                            <i class="fa fa-user"></i> Hồ sơ
                        </a>
                        <a href="#" class="dropdown-item-custom">
                            <i class="fa fa-cog"></i> Cài đặt
                        </a>
                        <a href="{{URL::to('/logout-admin')}}" class="dropdown-item-custom">
                            <i class="fa fa-sign-out"></i> Đăng xuất
                        </a>
                    </div>
                    
                </div>
                
            </div>
        </header>

        <!-- Content Area -->
        <section class="content-wrapper">
            @yield('admin_content')
        </section>

        <!-- Footer -->
        <footer class="footer">
            <p>© 2025 E-Commerce Admin Panel. Thiết kế bởi <strong>Your Team</strong></p>
        </footer>
    </div>

    <script src="{{asset('backend/js/bootstrap.js')}}"></script>
    <script src="{{asset('backend/js/jquery.dcjqaccordion.2.7.js')}}"></script>
    <script src="{{asset('backend/js/scripts.js')}}"></script>
    <script src="{{asset('backend/js/jquery.slimscroll.js')}}"></script>
    <script src="{{asset('backend/js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('backend/js/jquery.scrollTo.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    </body>
    </html>