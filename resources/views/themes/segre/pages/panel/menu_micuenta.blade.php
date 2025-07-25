@php
	$pageName = Route::currentRouteName();
@endphp

<div class="card menu-panel-card">

	<div class="h6 mb-2">{{ trans("$theme-app.user_panel.buyer") }}</div>
	<div class="list-group list-group-flush mb-3">
		<a href="{{ Routing::slug('user/panel/orders') }}" @class(['list-group-item list-group-item-action', 'active' => $pageName == 'panel.orders'])
			aria-current="{{ $pageName == 'panel.orders' }}">
			{{ trans("$theme-app.user_panel.orders") }}
		</a>

		<a href="{{ Routing::slug('user/panel/favorites') }}" @class(['list-group-item list-group-item-action', 'active' => $pageName == 'panel.favorites'])
			aria-current="{{ $pageName == 'panel.favorites' }}">
			{{ trans("$theme-app.user_panel.favorites") }}
		</a>

		<a href="{{ Routing::slug('user/panel/allotments') }}" @class(['list-group-item list-group-item-action', 'active' => $pageName == 'panel.allotments'])
			aria-current="{{ $pageName == 'panel.allotments' }}">
			{{ trans("$theme-app.user_panel.allotments") }} {{ trans("$theme-app.user_panel.lots") }}
		</a>

		<a href="{{ Routing::slug('user/panel/bills') }}" @class(['list-group-item list-group-item-action', 'active' => $pageName == 'panel.bills'])
			aria-current="{{ $pageName == 'panel.bills' }}">
			{{ trans("$theme-app.user_panel.allotments") }} {{ trans("$theme-app.user_panel.pending_bills") }}
		</a>

	</div>


	<div class="h6 mb-2">{{ trans("$theme-app.user_panel.setting") }}</div>
	<div class="list-group list-group-flush mb-3">
		<a href="{{ Routing::slug('user/panel/info') }}" @class(['list-group-item list-group-item-action', 'active' => $pageName == 'panel.account_info'])
			aria-current="{{ $pageName == 'panel.account_info' }}">
			{{ trans("$theme-app.user_panel.info") }}
		</a>

		<a href="{{ Routing::slug('logout') }}" class="list-group-item list-group-item-action">
			{{ trans("$theme-app.user_panel.exit") }}
		</a>
	</div>

</div>
