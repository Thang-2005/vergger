<!-- Language Switcher -->
<div class="language-switcher">
    <div class="dropdown" style="display: inline-block;">
        <a href="javascript:void(0);" class="dropdown-toggle language-toggle" id="languageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Chuyển đổi ngôn ngữ">
            <i class="fa fa-globe"></i>
            <span class="language-text">
                @if(app()->getLocale() == 'vi')
                    Tiếng Việt
                @else
                    English
                @endif
            </span>
        </a>
        <div class="dropdown-menu language-menu" aria-labelledby="languageDropdown">
            @php
                $isAdminRoute = strpos(request()->path(), 'admin') === 0;
                $changeRoute = $isAdminRoute ? 'admin.locale.change' : 'locale.change';
            @endphp
            <a class="dropdown-item language-option {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route($changeRoute, 'en') }}" data-locale="en">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 60 30'%3E%3Crect width='60' height='30' fill='%234C7BA7'/%3E%3Ctext x='30' y='20' font-size='16' fill='white' text-anchor='middle'%3EUS%3C/text%3E%3C/svg%3E" alt="English" style="width: 20px; height: 15px; margin-right: 8px;">
                English
            </a>
            <a class="dropdown-item language-option {{ app()->getLocale() == 'vi' ? 'active' : '' }}" href="{{ route($changeRoute, 'vi') }}" data-locale="vi">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 60 30'%3E%3Crect width='60' height='30' fill='%23DC143C'/%3E%3Cpolygon points='30,10 35,20 26,20' fill='%23FFDE00'/%3E%3C/svg%3E" alt="Tiếng Việt" style="width: 20px; height: 15px; margin-right: 8px;">
                Tiếng Việt
            </a>
        </div>
    </div>
</div>

<style>
.language-switcher {
    display: inline-block;
}

.language-switcher .dropdown {
    position: relative !important;
}

.language-switcher .dropdown-toggle {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    color: #333;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.3s ease;
    border: none;
    background: transparent;
    cursor: pointer;
}

.language-switcher .dropdown-toggle:hover {
    background-color: #f5f5f5;
    color: #007bff;
}

.language-switcher .language-text {
    margin-left: 6px;
    font-size: 14px;
}

.language-switcher .dropdown-menu {
    min-width: 180px;
    border: 1px solid #e3e3e3;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    position: absolute !important;
    z-index: 9999 !important;
    display: none !important;
    left: 0;
    top: 100%;
}

.language-switcher .dropdown-menu.show,
.language-switcher .dropdown-menu.open {
    display: block !important;
}

.language-switcher .dropdown.open .dropdown-menu {
    display: block !important;
}

.language-switcher .language-option {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    color: #333;
    text-decoration: none;
    transition: all 0.2s ease;
}

.language-switcher .language-option:hover {
    background-color: #f5f5f5;
    color: #007bff;
}

.language-switcher .language-option.active {
    background-color: #007bff;
    color: white;
    font-weight: 500;
}

.language-switcher .language-option.active:hover {
    background-color: #0056b3;
}

/* Admin specific styles */
.top_nav .language-switcher .dropdown-toggle {
    color: #2c3e50;
    padding: 10px 12px;
}

.top_nav .language-switcher .dropdown-toggle:hover {
    background-color: transparent;
    color: #007bff;
}

/* Client specific styles */
.ltn__header-options .language-switcher .dropdown-toggle {
    font-size: 13px;
    padding: 5px 8px;
}

.ltn__header-options .language-switcher .dropdown-toggle:hover {
    color: #007bff;
}
</style>
