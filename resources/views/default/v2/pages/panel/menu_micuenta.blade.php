@php
	/* $user = session('user');
	$firtsLetter = substr($user['name'], 0, 1);
	$email = $user['usrw']; */
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

		<a href="{{ route('showShoppingCart', ['lang' => config('app.locale')]) }}" @class(['list-group-item list-group-item-action', 'active' => $pageName == 'showShoppingCart'])
			aria-current="{{ $pageName == 'showShoppingCart' }}">
			{{ trans("$theme-app.foot.direct_sale") }}
		</a>

	</div>

	<div class="h6 my-2">{{ trans("$theme-app.user_panel.seller") }}</div>
	<div class="list-group list-group-flush mb-3">

		<a href="{{ route('panel.sales', ['lang' => config('app.locale')]) }}" @class(['list-group-item list-group-item-action', 'active' => $pageName == 'panel.sales'])
			aria-current="{{ $pageName == 'panel.sales' }}">
			{{ trans("$theme-app.user_panel.my_sale_title") }}
		</a>

	</div>


	<div class="h6 mb-2">{{ trans("$theme-app.user_panel.setting") }}</div>
	<div class="list-group list-group-flush mb-3">
		<a href="{{ Routing::slug('user/panel/info') }}" @class(['list-group-item list-group-item-action', 'active' => $pageName == 'panel.account_info'])
			aria-current="{{ $pageName == 'panel.account_info' }}">
			{{ trans("$theme-app.user_panel.info") }}
		</a>

		<a href="{{ route('panel.addresses', ['lang' => config('app.locale')]) }}" @class(['list-group-item list-group-item-action', 'active' => $pageName == 'panel.addresses'])
			aria-current="{{ $pageName == 'panel.addresses' }}">
			{{ trans("$theme-app.user_panel.addresses") }}
		</a>

		<a href="{{ Routing::slug('logout') }}" class="list-group-item list-group-item-action">
			{{ trans("$theme-app.user_panel.exit") }}
		</a>
	</div>



</div>
