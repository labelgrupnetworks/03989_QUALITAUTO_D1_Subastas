<div id="button-open-user-menu" class="tabs-custom-responsive visible-xs visible-sm"><i class="fa fa-align-left" aria-hidden="true"></i></div>
<div class="tabs-custom filters-auction-content">
	<div style="postion: relative"></div>
	<ul id="user-account-ul" class="ul-format color-letter">
		<li class="text-uppercase<?php if ($tab == 'orders') {
		    echo ' tab-active';
		} ?>" role="presentation">

			<a class="" data-title="{{ trans($theme . '-app.panel.my_orders') }}"
				href="{{ \Routing::slug('user/panel/orders') }}">
                <img width="20px" src="/themes/{{$theme}}/assets/img/hammer.png" class="hammer-icon hammer-icon-standard" alt="{{ trans($theme.'-app.panel.my_orders') }}">
                <img width="20px" src="/themes/{{$theme}}/assets/img/hammer-hover.png" class="hammer-icon-hover" alt="{{ trans($theme.'-app.panel.my_orders') }}">
				{{ trans($theme . '-app.user_panel.orders') }}
			</a>
		</li>
		@if (\Config::get('app.userPanelMySales'))
			@php
				$hasSales = (new App\Models\User())->hasSales(session('user.cod'));
			@endphp
			@if ($hasSales)
				<li class="text-uppercase<?php if ($tab == 'sales') {
				    echo ' tab-active';
				} ?>" role="presentation">
					<a data-title="{{ trans("$theme-app.user_panel.my_sale_title") }}"
						href="{{ route('panel.sales', ['lang' => config('app.locale')]) }}">
						<i class="fa fa-money" aria-hidden="true"></i>
						{{ trans("$theme-app.user_panel.my_sale_title") }}
					</a>
				</li>
			@endif
		@endif
		<li class="text-uppercase<?php if ($tab == 'favorites') {
		    echo ' tab-active';
		} ?>" role="presentation">

			<a class=""
				href="{{ \Routing::slug('user/panel/' . (empty(\Config('app.new_favorites_panel')) ? 'favorites' : \Config('app.new_favorites_panel'))) }}"
				data-title="{{ trans($theme . '-app.panel.my_adj') }}">
				<i class="fa fa-star"></i>
				<span class="menu-option-text">{{ trans($theme . '-app.user_panel.favorites') }}</span>
			</a>
		</li>
		<li class="text-uppercase<?php if ($tab == 'allotments' || $tab == 'bills') {
		    echo ' tab-active';
		} ?>" role="presentation">
			<a class="" role="button" data-toggle="collapse" href="#collapse_adj" aria-expanded="false"
				aria-controls="collapse_adj" data-title="{{ trans($theme . '-app.panel.my_adj') }}"
				href="{{ \Routing::slug('user/panel/allotments/outstanding') }}">
				<i class="fa fa-trophy"></i>
				<span class="menu-option-text">{{ trans($theme . '-app.user_panel.allotments') }}</span>

			</a>

		</li>
		<div class="<?php if ($tab != 'allotments' && $tab != 'bills') {
		    echo 'collapse';
		} ?>" id="collapse_adj">
			<ul class="sub_menu_adj">
				<a class="@if ($tab == 'allotments') active @endif" style="text-transform: uppercase"
					data-title="{{ trans($theme . '-app.panel.my_adj') }}"
					href="{{ \Routing::slug('user/panel/allotments') }}">{{ trans($theme . '-app.user_panel.lots') }}</a>
				<a class="@if ($tab == 'bills') active @endif" style="text-transform: uppercase"
					data-title="{{ trans($theme . '-app.panel.pending_bills') }}"
					href="{{ \Routing::slug('user/panel/bills') }}">{{ trans($theme . '-app.user_panel.pending_bills') }}</a>
			</ul>
		</div>

		@if (\Config::get('app.shoppingCart'))
			<li class="text-uppercase<?php if ($tab == 'showShoppingCart') {
			    echo ' tab-active';
			} ?>" role="presentation">
				<a class="" href="{{ route('showShoppingCart', ['lang' => config('app.locale')]) }}">
					<span class="menu-option-text">{{ trans("$theme-app.foot.direct_sale") }}</span>
				</a>
			</li>
		@endif

		@if (\Config::get('app.makePreferences'))
			<li class="text-uppercase<?php if ($tab == 'form-preferencias') {
			    echo ' tab-active';
			} ?>" role="presentation">
				<a class="" href="{{ \Routing::slug('user/panel/preferences') }}">
					<i class="fa fa-bell-o" aria-hidden="true"></i>
					<span class="menu-option-text">{{ trans("$theme-app.user_panel.preferences") }}</span>
				</a>
			</li>
		@endif

		<li class="text-uppercase<?php if ($tab == 'datos-personales') {
		    echo ' tab-active';
		} ?>" role="presentation">

			<a class="" href="{{ \Routing::slug('user/panel/info') }}">
				<i class="fa fa-user-circle"></i>
				<span class="menu-option-text">{{ trans($theme . '-app.user_panel.info') }}</span></a>
		</li>

		<?php /*<li role="presentation" <?php if($tab == 'datos-personales'){ echo('class="active"'); } ?> ?>><a
			href="{{ \Routing::slug('user/panel/info') }}">{{ trans($theme . '-app.user_panel.info') }}</a>
		</li> */?>
		<li class="text-uppercase"><a href="{{ \Routing::slug('logout') }}">
			<i class="fa fa-sign-out" aria-hidden="true"></i>
			<span class="menu-option-text">{{ trans($theme . '-app.user_panel.exit') }}</span></a></li>
	</ul>
</div>
