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
                <span>Xin chao,</span>
                <h2>{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>Tong quan</h3>
                @php
                    $adminUser = Auth::guard('admin')->user();
                    $isAdmin = optional($adminUser?->roles)->contains('name', 'Admin');
                    $canManageUsers = $isAdmin || ($adminUser?->hasPermission('manage_users') ?? false);
                    $canManageCategories = $isAdmin || ($adminUser?->hasPermission('manage_categories') ?? false);
                    $canManageProducts = $isAdmin || ($adminUser?->hasPermission('manage_products') ?? false);
                    $canManageOrders = $isAdmin || ($adminUser?->hasPermission('manage_orders') ?? false);
                    $canManageContacts = $isAdmin || ($adminUser?->hasPermission('manage_contacts') ?? false);
                    $canManageContacts = $isAdmin || ($adminUser?->hasPermission('manage_contacts') ?? false);
                    $canManagePermissions = $isAdmin || ($adminUser?->hasPermission('manage_permissions') ?? false);
                   
                @endphp

                <ul class="nav side-menu">
                    <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i> Dashboard</a>
                    </li>

                    @if($canManageUsers)
                    <li>
                        <a><i class="fa fa-user"></i> Quan ly nguoi dung <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('admin.users') }}">Danh sach tai khoan</a></li>
                            <li><a href="#">Phan quyen nhan vien</a></li>
                        </ul>
                    </li>
                    @endif

                    @if($canManageCategories)
                    <li>
                        <a><i class="fa fa-table"></i> Quan ly danh muc <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="#">Them danh muc</a></li>
                            <li><a href="#">Danh sach danh muc</a></li>
                        </ul>
                    </li>
                    @endif

                    @if($canManageProducts)
                    <li>
                        <a><i class="fa fa-cube"></i> Quan ly san pham <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="#">Them san pham</a></li>
                            <li><a href="#">Danh sach san pham</a></li>
                        </ul>
                    </li>
                    @endif

                    @if($canManageOrders)
                    <li>
                        <a><i class="fa fa-shopping-cart"></i> Quan ly don hang <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="#">Tao don hang</a></li>
                            <li><a href="#">Danh sach don hang</a></li>
                        </ul>
                    </li>
                    @endif

                    @if($canManageContacts)
                    <li>
                            <a><i class="fa fa-envelope"></i> Lien he <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="#">Hop thu lien he</a></li>
                                <li><a href="#">Lich su phan hoi</a></li>
                            </ul>
                    </li>
                    @endif
                    
                    @if($canManagePermissions)
                    <li>
                        <a><i class="fa fa-key"></i> Quan ly quyen <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="#">Danh sach quyen</a></li>
                            <li><a href="#">Phan quyen nguoi dung</a></li>
                        </ul>

                    </li>
                    @endif
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->

        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Dang xuat" href="{{ route('admin.logout') }}" onclick="return confirm('Ban co muon dang xuat khong?');">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
    </div>
</div>
