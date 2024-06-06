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
            <small>{{ trans("$theme-app.user_panel.client") }} {{ $userMenu['cod'] }}</small>
        </div>
    </div>

    <div class="submenu-links hidden-md hidden-lg">
        <a href="{{ wpLink('wp_home') }}" title="{{ trans($theme . '-app.home.home') }}">
            <i class="icon_house fa-2x"></i>
        </a>

        {{-- hamburger-menu --}}
        <a data-toggle="collapse" href="#collapseSubMenu" role="button" aria-expanded="false" class="toggle-icon"
            aria-controls="collapseSubMenu" >
            <i class="fa fa-bars fa-2x" aria-hidden="true"></i>
			<i class="fa fa-times fa-2x" aria-hidden="true"></i>
        </a>
    </div>
</div>

<ul class="collapse" id="collapseSubMenu">
    <li @class(['active' => Route::currentRouteName() === 'panel.summary'])>
        <a href="{{ route('panel.summary', ['lang' => config('app.locale')]) }}">
            {{ trans("$theme-app.user_panel.summary") }}
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
            {{ trans("$theme-app.user_panel.my_favourites") }}
        </a>
    </li>
    <li @class([
        'active' => Route::currentRouteName() === 'panel.allotment-bills',
    ])>
        <a href="{{ route('panel.allotment-bills', ['lang' => config('app.locale')]) }}">
            {{ trans("$theme-app.user_panel.my_pending_bills") }}
        </a>
    </li>
    <li @class(['active' => in_array(Route::currentRouteName(), ['panel.sales.active', 'panel.sales.finish', 'panel.sales.pending-assign'])])>
        <a href="{{ route('panel.sales.active', ['lang' => config('app.locale')]) }}">
            {{ trans("$theme-app.user_panel.my_assignments") }}
        </a>
    </li>
    <li @class([
        'active' => Route::currentRouteName() === 'panel.account_info',
    ])>
        <a href="{{ route('panel.account_info', ['lang' => config('app.locale')]) }}">
            {{ trans("$theme-app.user_panel.my_profile") }}
        </a>
    </li>
</ul>

<div class="panel_banner">
	{!! \BannerLib::bannersPorKey('user_panel', 'user_panel', ['arrows' => false, 'dots' => false, 'autoplay' => true, 'autoplaySpeed' => 4000]) !!}
</div>
