@php
	$lotsOwner = \App\Models\V5\FgHces1::where('PROP_HCES1', Session::get('user.cod'))->first();
@endphp

<div class="tabs-custom filters-auction-content">

	<div id="button-open-user-menu" class="tabs-custom-responsive visible-xs visible-sm">
		<i class="fas fa-align-left" style="color: black"></i>
	</div>
	<div style="postion: relative"></div>

	<div id="user-account-ul">
	<p class="user-acount-menu-title" style="text-transform: uppercase;">{{ trans(\Config::get('app.theme').'-app.user_panel.auction') }}</p>
	<ul class="ul-format color-letter">

		<li class="text-uppercase<?php if($tab == 'orders'){ echo(' tab-active'); } ?>" role="presentation">

			<a class="" data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_orders') }}"
				href="{{ \Routing::slug('user/panel/orders') }}">
				{{ trans(\Config::get('app.theme').'-app.user_panel.orders') }}
			</a>
		</li>
		<li class="text-uppercase<?php if($tab == 'favorites'){ echo(' tab-active'); } ?>" role="presentation">
			<a class="" href="{{ \Routing::slug('user/panel/favorites') }}"
				data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_adj') }}">
				{{ trans(\Config::get('app.theme').'-app.user_panel.favorites') }}
			</a>
		</li>

		<li class="text-uppercase<?php if($tab == 'allotments' || $tab == 'bills'){ echo(' tab-active'); } ?>"
			role="presentation">
			<a class="" role="button" data-toggle="collapse" href="#collapse_adj" aria-expanded="false"
				aria-controls="collapse_adj" data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_adj') }}"
				href="{{ \Routing::slug('user/panel/allotments/outstanding') }}">
				{{ trans(\Config::get('app.theme').'-app.user_panel.allotments') }}

			</a>
		</li>
		<div class="<?php if($tab != 'allotments' && $tab !='bills'){echo('collapse'); } ?>" id="collapse_adj">
			<ul class="sub_menu_adj">
				<a class="@if($tab == 'allotments') active @endif"
					data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_adj') }}"
					href="{{ \Routing::slug('user/panel/allotments') }}">{{ trans(\Config::get('app.theme').'-app.user_panel.lots') }}</a>
				<a class="@if($tab == 'bills') active @endif"
					data-title="{{ trans(\Config::get('app.theme').'-app.panel.pending_bills') }}"
					href="{{ \Routing::slug('user/panel/bills') }}">{{ trans(\Config::get('app.theme').'-app.user_panel.pending_bills') }}</a>
			</ul>
		</div>

		@if($lotsOwner)
		<li class="text-uppercase<?php if($tab == 'sales'){ echo(' tab-active'); } ?>" role="presentation">
			<a class="" href="{{ \Routing::slug('user/panel/sales') }}"
				data-title="{{ trans(\Config::get('app.theme').'-app.panel.lots_owner') }}">
				{{ trans(\Config::get('app.theme').'-app.user_panel.lots_owner') }}
			</a>
		</li>
		<li class="text-uppercase<?php if($tab == 'sales_totals'){ echo(' tab-active'); } ?>" role="presentation">
			<a class="" href="{{ \Routing::slug('user/panel/sales') }}?totals"
				data-title="{{ trans(\Config::get('app.theme').'-app.panel.lots_owner') }}">
				{{ trans(\Config::get('app.theme').'-app.user_panel.totals') }}
			</a>
		</li>
	@endif
	</ul>

	<p class="user-acount-menu-title mb-4" style="text-transform: uppercase;">
		<a class="user-acount-menu-link {{ $tab == 'directsale' ? 'active' : ''}}" href="{{ route('panel.allotment.diectsale', ['lang' => config('app.locale')]) }}">
			{{ trans("$theme-app.foot.direct_sale") }}
		</a>
	</p>

	<p class="user-acount-menu-title" style="text-transform: uppercase">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</p>
	<ul class="ul-format color-letter">

		<li class="text-uppercase<?php if($tab == 'datos-personales'){ echo(' tab-active'); } ?>" role="presentation">

			<a class="" href="{{ \Routing::slug('user/panel/info') }}">
				{{ trans(\Config::get('app.theme').'-app.user_panel.info') }}</a>
		</li>
		<li class="text-uppercase">
			<a href="{{ \Routing::slug('logout') }}">
				{{ trans(\Config::get('app.theme').'-app.user_panel.exit') }}
			</a>
		</li>
	</ul>

	</div>

</div>
