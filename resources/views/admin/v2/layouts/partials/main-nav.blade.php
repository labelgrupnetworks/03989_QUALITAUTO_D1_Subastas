<?php
$superadmin = $superadmin ?? true;
$config_menu_admin = Config::get('app.config_menu_admin');
$isB2bWihtLabel = in_array('b2b', $config_menu_admin) && $superadmin;
?>
<div class="main-nav">
    <div class="logo-box">
        <a class="logo-dark" href="/">
            <img class="img-fluid w-50 logo-lg" src="/themes/{{ $theme }}/assets/img/logo-dark.png"
                alt="{{ trans('web.menu.logo_alt') }}">
            <img class="img-fluid w-50 logo-sm" src="/themes/{{ $theme }}/assets/img/logo-sm.png"
                alt="{{ trans('web.menu.logo_alt') }}">
        </a>

        <a class="logo-light" href="/">
            <img class="img-fluid w-50 logo-lg" src="/themes/{{ $theme }}/assets/img/logo-white.png"
                alt="{{ trans('web.menu.logo_alt') }}">
            <img class="img-fluid w-50 logo-sm" src="/themes/{{ $theme }}/assets/img/logo-sm.png"
                alt="{{ trans('web.menu.logo_alt') }}">
        </a>
    </div>

    <button class="button-sm-hover" type="button" aria-label="{{ trans('web.menu.show_full_sidebar') }}">
        <i class="ri-menu-2-line fs-24 button-sm-hover-icon"></i>
    </button>

    <div class="scrollbar" data-simplebar>

        <ul class="navbar-nav" id="navbar-nav">

            <li class="menu-title">{{ trans('web.menu.title') }}</li>

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span class="nav-icon">
                        <i class="ri-dashboard-2-line"></i>
                    </span>
                    <span class="nav-text">{{ trans('web.menu.dashboard') }}</span>
                </a>
            </li>


            @if (!$superadmin)
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-icon">
                            <i class="ri-auction-line"></i>
                        </span>
                        <span class="nav-text">{{ trans('web.menu.available_lots') }}</span>
                    </a>
                </li>
            @endif

            @if ($superadmin)
                <li class="menu-title">
                    {{ trans('web.admin.title') }}
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-icon">
                            <i class="ri-building-2-line"></i>
                        </span>
                        <span class="nav-text">{{ trans('web.menu.companies') }}</span>
                    </a>
                </li>
            @endif

            @if (session('user.admin') && !$superadmin)
                <li class="menu-title">{{ trans('web.menu.company_section') }}</li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-icon">
                            <i class="ri-user-2-line"></i>
                        </span>
                        <span class="nav-text">{{ trans('web.menu.clients') }}</span>
                    </a>
                </li>
            @endif

            <li class="menu-title">{{ trans('web.menu.user_section') }}</li>

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span class="nav-icon">
                        <i class="ri-account-circle-fill"></i>
                    </span>
                    <span class="nav-text">{{ trans('web.menu.my_account') }}</span>
                </a>
            </li>


        </ul>
    </div>
</div>
