@php
    use App\Models\User;
	use App\Models\V5\FxCli;
    $userMenu = Session::get('user');
    $userAvatar = (new User())->getAvatar($userMenu['cod']);
    $userName = mb_convert_case($userMenu['name'], MB_CASE_TITLE);
	$ries = FxCli::where('cod_cli', $userMenu['cod'])->value('ries_cli');
@endphp

<div class="submenu-header-panel">
    <div class="panel_user">
        <img src="{{ $userAvatar }}" alt="avatar del usuario" style="aspect-ratio: 1" width="60">
        <div class="panel_user-data">
            <p>{{ $userName }}</p>
            <small>{{ trans("$theme-app.user_panel.client") }} {{ $userMenu['cod'] }}</small>
			@if($ries)
				<p style="font-size: .9em">{{ trans("web.user_panel.bid_limit") }} {{ Tools::moneyFormat($ries, '€') }}</p>
			@endif
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
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M288 32C128.9 32 0 160.9 0 320c0 52.8 14.3 102.3 39.1 144.8 5.6 9.6 16.3 15.2 27.4 15.2h443c11.1 0 21.8-5.6 27.4-15.2C561.8 422.3 576 372.8 576 320c0-159.1-128.9-288-288-288zm0 64c14.7 0 26.6 10.1 30.3 23.7-1.1 2.3-2.6 4.2-3.5 6.7l-9.2 27.7c-5.1 3.5-11 6-17.6 6-17.7 0-32-14.3-32-32S270.3 96 288 96zM96 384c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm48-160c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm246.8-72.4l-61.3 184C343.1 347.3 352 364.5 352 384c0 11.7-3.4 22.6-8.9 32H232.9c-5.5-9.5-8.9-20.3-8.9-32 0-33.9 26.5-61.4 59.9-63.6l61.3-184c4.2-12.6 17.7-19.5 30.4-15.2 12.6 4.2 19.4 17.8 15.2 30.4zm14.7 57.2l15.5-46.6c3.5-1.3 7.1-2.2 11.1-2.2 17.7 0 32 14.3 32 32s-14.3 32-32 32c-11.4 0-20.9-6.3-26.6-15.2zM480 384c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32z"/></svg>
            {{ trans("$theme-app.user_panel.summary") }}
        </a>
    </li>
    <li @class([
        'active' =>
            Route::currentRouteName() === 'panel.orders' &&
            !request()->has('favorites'),
    ])>
        <a href="{{ route('panel.orders', ['lang' => config('app.locale')]) }}">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="currentColor" d="M505 199.4l-22.6-22.6c-9.4-9.4-24.6-9.4-33.9 0l-5.7 5.7L329.6 69.3l5.7-5.7c9.4-9.4 9.4-24.6 0-33.9L312.6 7c-9.4-9.4-24.6-9.4-33.9 0L154.2 131.5c-9.4 9.4-9.4 24.6 0 33.9l22.6 22.6c9.4 9.4 24.6 9.4 33.9 0l5.7-5.7 39.6 39.6-81 81-5.7-5.7c-12.5-12.5-32.8-12.5-45.3 0L9.4 412.1c-12.5 12.5-12.5 32.8 0 45.3l45.3 45.3c12.5 12.5 32.8 12.5 45.3 0l114.7-114.7c12.5-12.5 12.5-32.8 0-45.3l-5.7-5.7 81-81 39.6 39.6-5.7 5.7c-9.4 9.4-9.4 24.6 0 33.9l22.6 22.6c9.4 9.4 24.6 9.4 33.9 0l124.5-124.5c9.4-9.4 9.4-24.6 0-33.9z"/></svg>
            {{ trans("$theme-app.user_panel.orders") }}
        </a>
    </li>
    <li @class([
        'active' =>
            Route::currentRouteName() === 'panel.orders' &&
            request()->has('favorites'),
    ])>
        <a href="{{ route('panel.orders', ['lang' => config('app.locale'), 'favorites' => true]) }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"/></svg>
			{{ trans("$theme-app.user_panel.my_favourites") }}
        </a>
    </li>
    <li @class([
        'active' => Route::currentRouteName() === 'panel.allotment-bills',
    ])>
        <a href="{{ route('panel.allotment-bills', ['lang' => config('app.locale')]) }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="currentColor" d="M528.1 301.3l47.3-208C578.8 78.3 567.4 64 552 64H159.2l-9.2-44.8C147.8 8 137.9 0 126.5 0H24C10.7 0 0 10.7 0 24v16c0 13.3 10.7 24 24 24h69.9l70.2 343.4C147.3 417.1 136 435.2 136 456c0 30.9 25.1 56 56 56s56-25.1 56-56c0-15.7-6.4-29.8-16.8-40h209.6C430.4 426.2 424 440.3 424 456c0 30.9 25.1 56 56 56s56-25.1 56-56c0-22.2-12.9-41.3-31.6-50.4l5.5-24.3c3.4-15-8-29.3-23.4-29.3H218.1l-6.5-32h293.1c11.2 0 20.9-7.8 23.4-18.7z"/></svg>
            {{ trans("$theme-app.user_panel.my_pending_bills") }}
        </a>
    </li>
    <li @class(['active' => in_array(Route::currentRouteName(), ['panel.sales.active', 'panel.sales.finish', 'panel.sales.pending-assign'])])>
        <a href="{{ route('panel.sales.active', ['lang' => config('app.locale')]) }}">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 616 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="currentColor" d="M602 118.6L537.1 15C531.3 5.7 521 0 510 0H106C95 0 84.7 5.7 78.9 15L14 118.6c-33.5 53.5-3.8 127.9 58.8 136.4 4.5 .6 9.1 .9 13.7 .9 29.6 0 55.8-13 73.8-33.1 18 20.1 44.3 33.1 73.8 33.1 29.6 0 55.8-13 73.8-33.1 18 20.1 44.3 33.1 73.8 33.1 29.6 0 55.8-13 73.8-33.1 18.1 20.1 44.3 33.1 73.8 33.1 4.7 0 9.2-.3 13.7-.9 62.8-8.4 92.6-82.8 59-136.4zM529.5 288c-10 0-19.9-1.5-29.5-3.8V384H116v-99.8c-9.6 2.2-19.5 3.8-29.5 3.8-6 0-12.1-.4-18-1.2-5.6-.8-11.1-2.1-16.4-3.6V480c0 17.7 14.3 32 32 32h448c17.7 0 32-14.3 32-32V283.2c-5.4 1.6-10.8 2.9-16.4 3.6-6.1 .8-12.1 1.2-18.2 1.2z"/></svg>
			{{ trans("$theme-app.user_panel.my_assignments") }}
        </a>
    </li>
    <li @class([
        'active' => Route::currentRouteName() === 'panel.account_info',
    ])>
        <a href="{{ route('panel.account_info', ['lang' => config('app.locale')]) }}">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="currentColor" d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z"/></svg>
            {{ trans("$theme-app.user_panel.my_profile") }}
        </a>
    </li>

	<li>
		<a href="{{ \Routing::slug('logout') }}">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512" fill="currentColor"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M242.7 256l100.1-100.1c12.3-12.3 12.3-32.2 0-44.5l-22.2-22.2c-12.3-12.3-32.2-12.3-44.5 0L176 189.3 75.9 89.2c-12.3-12.3-32.2-12.3-44.5 0L9.2 111.5c-12.3 12.3-12.3 32.2 0 44.5L109.3 256 9.2 356.1c-12.3 12.3-12.3 32.2 0 44.5l22.2 22.2c12.3 12.3 32.2 12.3 44.5 0L176 322.7l100.1 100.1c12.3 12.3 32.2 12.3 44.5 0l22.2-22.2c12.3-12.3 12.3-32.2 0-44.5L242.7 256z"/></svg>
			{{ trans("$theme-app.user_panel.exit") }}
		</a>
	</li>

</ul>

<div class="panel_banner">
	{!! \BannerLib::bannersPorKey('user_panel', 'user_panel', ['arrows' => false, 'dots' => false, 'autoplay' => true, 'autoplaySpeed' => 4000]) !!}
</div>
