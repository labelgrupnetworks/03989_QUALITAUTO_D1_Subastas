
<div class="tabs-custom filters-auction-content">
    <div id="button-open-user-menu" class="tabs-custom-responsive visible-xs visible-sm"><i class="fa fa-align-left"></i></div>
    <div style="postion: relative"></div>
    <ul id="user-account-ul" class="ul-format color-letter">
        <li class="text-uppercase<?php if($tab == 'orders'){ echo(' tab-active'); } ?>" role="presentation">

            <a class="" data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_orders') }}" href="{{ \Routing::slug('user/panel/orders') }}">
                @if ($tab== 'orders')
                <img width="20px" src="/themes/{{\Config::get('app.theme')}}/assets/img/hammer-grey.png"  alt="{{ trans(\Config::get('app.theme').'-app.panel.my_orders') }}">
                @else
                <img width="20px" src="/themes/{{\Config::get('app.theme')}}/assets/img/hammer.png"  alt="{{ trans(\Config::get('app.theme').'-app.panel.my_orders') }}">
                @endif
                {{ trans(\Config::get('app.theme').'-app.user_panel.orders') }}
            </a>
        </li>
		@if (\Config::get('app.userPanelMySales'))
			@php
				$hasSales = (new App\Models\User())->hasSales(session('user.cod'));
			@endphp
			@if ($hasSales)
				<li class="text-uppercase<?php if($tab == 'sales'){ echo(' tab-active'); } ?>" role="presentation">
					<a data-title="{{ trans("$theme-app.user_panel.my_sale_title") }}" href="{{ route('panel.sales', ['lang' => config('app.locale')]) }}"><i class="fa fa-money" aria-hidden="true"></i>
						{{ trans("$theme-app.user_panel.my_sale_title") }}
					</a>
				</li>
			@endif
		@endif
        <li class="text-uppercase<?php if($tab == 'favorites'){ echo(' tab-active'); } ?>" role="presentation" >
            <a class="" href="{{ \Routing::slug('user/panel/' . (empty(\Config('app.new_favorites_panel')) ? 'favorites' : \Config('app.new_favorites_panel'))) }}" data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_adj') }}" >
                <i class="fa fa-star"></i>
                {{ trans(\Config::get('app.theme').'-app.user_panel.favorites') }}
            </a>
        </li>

        <li class="text-uppercase <?php if($tab == 'allotments' || $tab == 'bills'){ echo('tab-active'); } ?>" role="presentation">
            <a href="{{ \Routing::slug('user/panel/allotments') }}" data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_adj') }}">
                <i class="fa fa-trophy"></i>
                {{ trans(\Config::get('app.theme').'-app.user_panel.allotments') }}
            </a>
        </li>

			@if( \Config::get("app.shoppingCart") )
				<li class="text-uppercase<?php if($tab == 'showShoppingCart'){ echo(' tab-active'); } ?>" role="presentation">
					<a class="" href="{{ route('showShoppingCart', ['lang' => config('app.locale')]) }}">
						{{ trans("$theme-app.foot.direct_sale") }}
					</a>
				</li>
			@endif

			@if (\Config::get('app.makePreferences'))
				<li class="text-uppercase<?php if($tab == 'form-preferencias'){ echo(' tab-active'); } ?>" role="presentation">
					<a class="" href="{{ \Routing::slug('user/panel/preferences') }}">
						<i class="fa fa-bell-o" aria-hidden="true"></i> {{ trans("$theme-app.user_panel.preferences") }}
					</a>
				</li>
			@endif

        <li class="text-uppercase<?php if($tab == 'datos-personales'){ echo(' tab-active'); } ?>" role="presentation">

            <a class="" href="{{ \Routing::slug('user/panel/info') }}">
                <i class="fa fa-user-circle"></i>
                {{ trans(\Config::get('app.theme').'-app.user_panel.info') }}</a></li>

 <?php /*<li role="presentation" <?php if($tab == 'datos-personales'){ echo('class="active"'); } ?>><a href="{{ \Routing::slug('user/panel/info') }}">{{ trans(\Config::get('app.theme').'-app.user_panel.info') }}</a></li> */?>
        <li class="text-uppercase"><a href="{{ \Routing::slug('logout') }}"><i class="fa fa-sign-out"></i>{{ trans(\Config::get('app.theme').'-app.user_panel.exit') }}</a></li>
    </ul>
</div>
