
<div class="panel_user">
    <img src="" alt="">
    <div class="panel_user-data">
        <p>Nombre</p>
        <small>Cliente </small>
    </div>
</div>

<ul>
	<li @class(['active' => Route::currentRouteName() === 'panel.summary'])>
        <a href="{{ route('panel.summary', ['lang' => config('app.locale')]) }}">
			Resumen
        </a>
    </li>
	<li @class(['active' => Route::currentRouteName() === 'panel.orders' && !request()->has('favorites')])>
        <a href="{{ route('panel.orders', ['lang' => config('app.locale')]) }}">
			{{ trans("$theme-app.user_panel.orders") }}
        </a>
    </li>
	<li @class(['active' => Route::currentRouteName() === 'panel.orders' && request()->has('favorites')])>
        <a href="{{ route('panel.orders', ['lang' => config('app.locale'), 'favorites' => true]) }}">
			{{ trans("$theme-app.user_panel.favorites") }}
        </a>
    </li>
	<li @class(['active' => Route::currentRouteName() === 'panel.allotment-bills'])>
        <a href="{{ route('panel.allotment-bills', ['lang' => config('app.locale')]) }}">
			{{ trans("$theme-app.user_panel.my_pending_bills") }}
        </a>
    </li>
	<li @class(['active' => Route::currentRouteName() === 'panel.sales'])>
        <a href="{{ route('panel.sales', ['lang' => config('app.locale')]) }}">
			{{ trans("$theme-app.user_panel.my_assignments") }}
        </a>
    </li>
	<li @class(['active' => Route::currentRouteName() === 'panel.account_info'])>
        <a href="{{ route('panel.account_info', ['lang' => config('app.locale')]) }}">
			{{ trans("$theme-app.user_panel.info") }}
        </a>
    </li>
</ul>

<div class="panel_banner">

</div>
