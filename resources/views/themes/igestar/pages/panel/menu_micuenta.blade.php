@php
    /* $user = session('user');
	$firtsLetter = substr($user['name'], 0, 1);
	$email = $user['usrw']; */
    $pageName = Route::currentRouteName();
@endphp

<div class="card menu-panel-card">

    <div class="h6 mb-2">{{ trans('web.user_panel.buyer') }}</div>
    <div class="list-group list-group-flush mb-3">
        <a href="{{ Routing::slug('user/panel/orders') }}" aria-current="{{ $pageName == 'panel.orders' }}"
            @class([
                'list-group-item list-group-item-action',
                'active' => $pageName == 'panel.orders',
            ])>
            {{ trans('web.user_panel.orders') }}
        </a>

        <a href="{{ Routing::slug('user/panel/favorites') }}" aria-current="{{ $pageName == 'panel.favorites' }}"
            @class([
                'list-group-item list-group-item-action',
                'active' => $pageName == 'panel.favorites',
            ])>
            {{ trans('web.user_panel.favorites') }}
        </a>

        <a href="{{ Routing::slug('user/panel/allotments') }}" aria-current="{{ $pageName == 'panel.allotments' }}"
            @class([
                'list-group-item list-group-item-action',
                'active' => $pageName == 'panel.allotments',
            ])>
            {{ trans('web.user_panel.allotments') }} {{ trans('web.user_panel.lots') }}
        </a>
    </div>


    <div class="h6 mb-2">{{ trans('web.user_panel.setting') }}</div>
    <div class="list-group list-group-flush mb-3">
        <a href="{{ Routing::slug('user/panel/info') }}" aria-current="{{ $pageName == 'panel.account_info' }}"
            @class([
                'list-group-item list-group-item-action',
                'active' => $pageName == 'panel.account_info',
            ])>
            {{ trans('web.user_panel.info') }}
        </a>

		@if(Config::get('app.withRepresented', false))
        <a href="{{ route('panel.represented.list', ['lang' => config('app.locale')]) }}"
            aria-current="{{ $pageName == 'panel.represented.list' }}" @class([
                'list-group-item list-group-item-action',
                'active' => $pageName == 'panel.represented.list',
            ])>
            {{ trans('web.user_panel.represented_link') }}
        </a>
		@endif

        <a class="list-group-item list-group-item-action" href="{{ Routing::slug('logout') }}">
            {{ trans('web.user_panel.exit') }}
        </a>
    </div>



</div>
