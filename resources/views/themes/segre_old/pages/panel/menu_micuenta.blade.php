
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
        <li class="text-uppercase<?php if($tab == 'allotments' || $tab == 'bills'){ echo(' tab-active'); } ?>" role="presentation">
            <a class="" role="button" data-toggle="collapse" href="#collapse_adj" aria-expanded="false" aria-controls="collapse_adj" data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_adj') }}" href="{{ \Routing::slug('user/panel/allotments/outstanding') }}" >
                <i class="fas fa-trophy"></i>
                {{ trans(\Config::get('app.theme').'-app.user_panel.allotments') }}

            </a>

            </li>
            <div class="<?php if($tab != 'allotments' && $tab !='bills'){echo('collapse'); } ?>" id="collapse_adj">
                <ul class="sub_menu_adj">
                    <a class="@if($tab == 'allotments') active @endif" style="text-transform: uppercase" data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_adj') }}" href="{{ \Routing::slug('user/panel/allotments') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.lots') }}</a>
                    <a class="@if($tab == 'bills') active @endif" style="text-transform: uppercase" data-title="{{ trans(\Config::get('app.theme').'-app.panel.pending_bills') }}" href="{{ \Routing::slug('user/panel/bills') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.pending_bills') }}</a>
                </ul>
            </div>

 <?php /*<li role="presentation" <?php if($tab == 'datos-personales'){ echo('class="active"'); } ?>><a href="{{ \Routing::slug('user/panel/info') }}">{{ trans(\Config::get('app.theme').'-app.user_panel.info') }}</a></li> */?>
        <li class="text-uppercase"><a href="{{ \Routing::slug('logout') }}"><i class="fas fa-sign-out-alt"></i>{{ trans(\Config::get('app.theme').'-app.user_panel.exit') }}</a></li>
    </ul>
</div>
