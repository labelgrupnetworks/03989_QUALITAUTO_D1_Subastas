@php
	$user = session('user');
	$firtsLetter = substr($user['name'], 0, 1);
	$email = $user['usrw'];
	$pageName = Route::currentRouteName();
@endphp

<div class="card">
	<div class="card-body">
		<div class="d-flex gap-2 flex-wrap">
			<div class="user-logo img-thumbnail rounded-circle">
				<p class="h2 m-0">{{ $firtsLetter }}</p>
			</div>
			<div class="user-detail">
				<h6 class="card-title">{{ ($user['name']) }}</h6>
				<p class="card-text">{{ $email }}</p>
			</div>
		</div>
	</div>
	<div class="list-group list-group-flush">
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

		<a href="{{ Routing::slug('user/panel/info') }}" @class(['list-group-item list-group-item-action', 'active' => $pageName == 'panel.account_info'])
			aria-current="{{ $pageName == 'panel.account_info' }}">
			{{ trans("$theme-app.user_panel.info") }}
		</a>

		<a href="{{ Routing::slug('logout') }}" class="list-group-item list-group-item-action">
			{{ trans("$theme-app.user_panel.exit") }}
		</a>
	</div>
</div>
