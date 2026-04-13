<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ route('admin.dashboard') }}" class="site_title"><i class="fa fa-paw"></i> <span>Veggis!</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{ asset('asset/admin/build/images/img.jpg') }}" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Xin chào,</span>
                <h2>{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>Tổng quan</h3>
                @php
                    $adminUser = Auth::guard('admin')->user();
                    $isAdmin = optional($adminUser?->roles)->contains('name', 'Admin');
                    $canManageUsers = $isAdmin || ($adminUser?->hasPermission('users.view') ?? false);
                    $canManageCategories = $isAdmin || ($adminUser?->hasPermission('categories.view') ?? false);
                    $canManageProducts = $isAdmin || ($adminUser?->hasPermission('products.view') ?? false);
                    $canManageOrders = $isAdmin || ($adminUser?->hasPermission('orders.view') ?? false);
                    $canManageContacts = $isAdmin || ($adminUser?->hasPermission('contacts.view') ?? false);
                    $canManagePermissions = $isAdmin || ($adminUser?->hasPermission('permissions.view') ?? false);
                   
                @endphp

                <ul class="nav side-menu">
                    <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i> Dashboard</a>
                    </li>

                    @if($canManageUsers)
                    <li>
                        <a><i class="fa fa-user"></i> Quản lý người dùng <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('admin.users') }}">Danh sách tài khoản</a></li>
                            <li><a href="#">Phân quyền nhân viên</a></li>
                        </ul>
                    </li>
                    @endif

                    @if($canManageCategories)
                    <li>
                        <a><i class="fa fa-table"></i> Quản lý danh mục <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('admin.categories.index') }}">Quản lý danh mục</a></li>
                        </ul>
                    </li>
                    @endif

                    @if($canManageProducts)
                    <li>
                        <a><i class="fa fa-cube"></i> Quản lý sản phẩm <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="#">Thêm sản phẩm</a></li>
                            <li><a href="{{ route('admin.products.list') }}">Danh sách sản phẩm</a></li>
                        </ul>
                    </li>
                    @endif

                    @if($canManageOrders)
                    <li>
                        <a><i class="fa fa-shopping-cart"></i> Quản lý đơn hàng <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('admin.orders.list') }}">Danh sách đơn hàng</a></li>
                        </ul>
                    </li>
                    @endif

                    @if($canManageContacts)
                    <li>
                            <a><i class="fa fa-envelope"></i> Liên hệ <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="#">Hộp thư liên hệ</a></li>
                                <li><a href="#">Lịch sử phản hồi</a></li>
                            </ul>
                    </li>
                    @endif
                    
                    @if($canManagePermissions)
                    <li>
                        <a><i class="fa fa-key"></i> Quản lý quyền <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('admin.roles.index') }}">Quản lý vai trò</a></li>
                            <li><a href="{{ route('admin.permissions') }}">Phân quyền theo vai trò</a></li>
                        </ul>

                    </li>
                    @endif
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->

        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Đăng xuất" href="{{ route('admin.logout') }}" onclick="return confirm('Bạn có muốn đăng xuất không?');">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
    </div>
</div>
