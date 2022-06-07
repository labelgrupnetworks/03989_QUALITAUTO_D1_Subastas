
<div class="tabs-custom filters-auction-content">
    <div id="button-open-user-menu" class="tabs-custom-responsive visible-xs visible-sm"><i class="fas fa-align-left"></i></div>
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
        <li class="text-uppercase<?php if($tab == 'favorites'){ echo(' tab-active'); } ?>" role="presentation" >

            <a class="" href="{{ \Routing::slug('user/panel/favorites') }}" data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_adj') }}" >
                <i class="fas fa-star"></i>
                {{ trans(\Config::get('app.theme').'-app.user_panel.favorites') }}
            </a>
            </li>
        <li class="text-uppercase<?php if($tab == 'allotments'){ echo(' tab-active'); } ?>" role="presentation">
            <a class="" data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_adj') }}" href="{{ \Routing::slug('user/panel/allotments') }}" >
                <i class="fas fa-trophy"></i>
                {{ trans(\Config::get('app.theme').'-app.user_panel.allotments') }}
            </a>
        </li>

        <li class="text-uppercase<?php if($tab == 'datos-personales'){ echo(' tab-active'); } ?>" role="presentation">

            <a class="" href="{{ \Routing::slug('user/panel/info') }}">
                <i class="fas fa-user-circle"></i>
                {{ trans(\Config::get('app.theme').'-app.user_panel.info') }}</a></li>

 <?php /*<li role="presentation" <?php if($tab == 'datos-personales'){ echo('class="active"'); } ?>><a href="{{ \Routing::slug('user/panel/info') }}">{{ trans(\Config::get('app.theme').'-app.user_panel.info') }}</a></li> */?>
        <li class="text-uppercase"><a href="{{ \Routing::slug('logout') }}"><i class="fas fa-sign-out-alt"></i>{{ trans(\Config::get('app.theme').'-app.user_panel.exit') }}</a></li>
    </ul>
</div>
