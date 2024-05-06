@php
    use App\Models\User;
    $userMenu = Session::get('user');
    $userAvatar = (new User())->getAvatar($userMenu['cod']);
    $userName = mb_convert_case($userMenu['name'], MB_CASE_TITLE);
@endphp

<div class="submenu-header-panel">
    <div class="panel_user">
        <img src="{{ $userAvatar }}" alt="avatar del usuario" style="aspect-ratio: 1" width="60">
        <div class="panel_user-data">
            <p>{{ $userName }}</p>
            <small>Cliente {{ $userMenu['cod'] }}</small>
        </div>
    </div>

    <div class="submenu-links hidden-md hidden-lg">
        <a href="{{ wpLink('wp_home') }}" title="{{ trans($theme . '-app.home.home') }}" style="margin-bottom: 2px">
            <i class="icon_house fa-3x"></i>
        </a>

        {{-- hamburger-menu --}}
        <a data-toggle="collapse" href="#collapseSubMenu" role="button" aria-expanded="false"
            aria-controls="collapseSubMenu">
            <i class="fa fa-bars fa-3x" aria-hidden="true"></i>
        </a>
    </div>
</div>

<ul class="collapse" id="collapseSubMenu">
    <li @class(['active' => Route::currentRouteName() === 'panel.summary'])>
        <a href="{{ route('panel.summary', ['lang' => config('app.locale')]) }}">
            Resumen
        </a>
    </li>
    <li @class([
        'active' =>
            Route::currentRouteName() === 'panel.orders' &&
            !request()->has('favorites'),
    ])>
        <a href="{{ route('panel.orders', ['lang' => config('app.locale')]) }}">
            {{ trans("$theme-app.user_panel.orders") }}
        </a>
    </li>
    <li @class([
        'active' =>
            Route::currentRouteName() === 'panel.orders' &&
            request()->has('favorites'),
    ])>
        <a href="{{ route('panel.orders', ['lang' => config('app.locale'), 'favorites' => true]) }}">
            {{ trans("$theme-app.user_panel.favorites") }}
        </a>
    </li>
    <li @class([
        'active' => Route::currentRouteName() === 'panel.allotment-bills',
    ])>
        <a href="{{ route('panel.allotment-bills', ['lang' => config('app.locale')]) }}">
            {{ trans("$theme-app.user_panel.my_pending_bills") }}
        </a>
    </li>
    <li @class(['active' => Route::currentRouteName() === 'panel.sales'])>
        <a href="{{ route('panel.sales', ['lang' => config('app.locale')]) }}">
            {{ trans("$theme-app.user_panel.my_assignments") }}
        </a>
    </li>
    <li @class([
        'active' => Route::currentRouteName() === 'panel.account_info',
    ])>
        <a href="{{ route('panel.account_info', ['lang' => config('app.locale')]) }}">
            {{ trans("$theme-app.user_panel.info") }}
        </a>
    </li>
</ul>

<div class="panel_banner">
	{!! \BannerLib::bannersPorKey('user_panel', 'user_panel', ['arrows' => false, 'dots' => false, 'autoplay' => true, 'autoplaySpeed' => 4000]) !!}
</div>
