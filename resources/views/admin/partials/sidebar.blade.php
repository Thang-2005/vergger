<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ route('admin.dashboard') }}" class="site_title"><i class="fa fa-paw"></i> <span>Veggis!</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{ Auth::guard('admin')->user()->avatar ?? asset('asset/admin/build/images/user.png') }}" alt="..." width="50" height="50" class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>{{ __('messages.hello') }},</span>
                <h2>{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>{{ __('messages.overview') }}</h3>
                @php
                    $adminUser = Auth::guard('admin')->user();
                    $isAdmin = optional($adminUser?->roles)->contains('name', 'Admin');
                    $canManageUsers = $isAdmin || ($adminUser?->hasPermission('users.view') ?? false);
                    $canManageCategories = $isAdmin || ($adminUser?->hasPermission('categories.view') ?? false);
                    $canManageProducts = $isAdmin || ($adminUser?->hasPermission('products.view') ?? false);
                    $canManageOrders = $isAdmin || ($adminUser?->hasPermission('orders.view') ?? false);
                    $canManageContacts = $isAdmin || ($adminUser?->hasPermission('manage_contacts') ?? false);
                    $canManageCoupons = $isAdmin || ($adminUser?->hasPermission('manage_coupons') ?? false);
                    $canManagePermissions = $isAdmin || ($adminUser?->hasPermission('permissions.view') ?? false);
                   
                @endphp

                <ul class="nav side-menu">
                    <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i> {{ __('messages.dashboard') }}</a>
                    </li>

                    @if($canManageUsers)
                    <li>
                        <a><i class="fa fa-user"></i> {{ __('messages.user_management') }} <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('admin.users') }}">{{ __('messages.account_list') }}</a></li>
                            <li><a href="#">{{ __('messages.staff_permissions') }}</a></li>
                        </ul>
                    </li>
                    @endif

                    @if($canManageCategories)
                    <li>
                        <a><i class="fa fa-table"></i> {{ __('messages.category_management') }} <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('admin.categories.index') }}">{{ __('messages.manage_category') }}</a></li>
                        </ul>
                    </li>
                    @endif

                    @if($canManageProducts)
                    <li>
                        <a><i class="fa fa-cube"></i> {{ __('messages.product_management') }} <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="#">{{ __('messages.add_product') }}</a></li>
                            <li><a href="{{ route('admin.products.list') }}">{{ __('messages.product_list') }}</a></li>
                        </ul>
                    </li>
                    @endif

                    @if($canManageCoupons)
                    <li>
                        <a><i class="fa fa-tags"></i> {{ __('messages.coupon_management') }} <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('admin.coupons.index') }}">{{ __('messages.coupon_list') }}</a></li>
                        </ul>
                    </li>
                    @endif

                    @if($canManageOrders)
                    <li>
                        <a><i class="fa fa-shopping-cart"></i> {{ __('messages.order_management') }} <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('admin.orders.list') }}">{{ __('messages.order_list') }}</a></li>
                        </ul>
                    </li>
                    @endif

                    @if($canManageContacts)
                    <li>
                        <a><i class="fa fa-envelope"></i> {{ __('messages.contact_management') }} <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('admin.contacts.index') }}">{{ __('messages.contact_inbox') }}</a></li>
                        </ul>
                    </li>
                    @endif

                    <li class="{{ request()->routeIs('admin.notifications.index') ? 'active' : '' }}">
                        <a><i class="fa fa-bell"></i> Thông báo <span class="badge badge-danger pull-right">{{ $unreadNotificationsCount ?? 0 }}</span></a>
                        <ul class="nav child_menu">
                            <li><a href="#">Xem tất cả</a></li>
                            <li><a href="#">Chưa đọc</a></li>
                        </ul>
                    </li>
                    
                    @if($canManagePermissions)
                    <li>
                        <a><i class="fa fa-key"></i> {{ __('messages.permission_management') }} <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('admin.roles.index') }}">{{ __('messages.role_management') }}</a></li>
                            <li><a href="{{ route('admin.permissions') }}">{{ __('messages.permission_by_role') }}</a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->

        <div class="sidebar-footer hidden-small">
            @php $confirmMsg = __('messages.confirm_logout'); @endphp
            <a data-toggle="tooltip" data-placement="top" title="{{ __('messages.logout') }}" href="{{ route('admin.logout') }}" onclick="return confirm('{{ $confirmMsg }}');">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
    </div>
</div>
