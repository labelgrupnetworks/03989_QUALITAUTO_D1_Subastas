
<div class="tabs-custom">
    <ul>
		<li class="text-uppercase<?php if($tab == 'sales'){ echo(' tab-active'); } ?>" role="presentation"><a data-title="{{ trans("$theme-app.user_panel.my_sale_title") }}" href="{{ route('panel.sales', ['lang' => config('app.locale')]) }}">{{ trans("$theme-app.user_panel.my_sale_title") }}</a></li>
        <li class="text-uppercase<?php if($tab == 'orders'){ echo(' tab-active'); } ?>" role="presentation"><a data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_orders') }}" href="{{ \Routing::slug('user/panel/orders') }}">{{ trans(\Config::get('app.theme').'-app.user_panel.orders') }}</a></li>
        <li class="text-uppercase<?php if($tab == 'favorites'){ echo(' tab-active'); } ?>" role="presentation" ><a href="{{ \Routing::slug('user/panel/favorites') }}" data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_adj') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.favorites') }}</a></li>
        <li class="text-uppercase<?php if($tab == 'allotments'){ echo(' tab-active'); } ?>" role="presentation"><a data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_adj') }}" href="{{ \Routing::slug('user/panel/allotments/outstanding') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.allotments') }}</a></li>

        <li class="text-uppercase<?php if($tab == 'pending_bills'){ echo(' tab-active'); } ?>" role="presentation"><a data-title="{{ trans(\Config::get('app.theme').'-app.panel.pending_bills') }}" href="{{ \Routing::slug('user/panel/pending_bills') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.pending_bills') }}</a></li>

        <li class="text-uppercase<?php if($tab == 'datos-personales'){ echo(' tab-active'); } ?>" role="presentation"><a href="{{ \Routing::slug('user/panel/info') }}">{{ trans(\Config::get('app.theme').'-app.user_panel.info') }}</a></li>
        <li class="hidden-lg text-uppercase"><a style="text-align:center !important;" href="{{ \Routing::slug('logout') }}">{{ trans(\Config::get('app.theme').'-app.user_panel.exit') }}</a></li>
    </ul>
</div>

