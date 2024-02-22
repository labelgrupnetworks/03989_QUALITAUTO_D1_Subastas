
<div class="tabs-custom nav-user">

    <ul>
        <li class="<?php if($tab == 'datos-personales'){ echo(' tab-active'); } ?>" role="presentation"><a href="{{ \Routing::slug('user/panel/info') }}"><i class="fas fa-2x fa-user-circle"></i><p>{{ trans($theme.'-app.user_panel.info') }}</p></a></li>
        <li class="<?php if($tab == 'orders'){ echo(' tab-active'); } ?>" role="presentation"><a data-title="{{ trans($theme.'-app.panel.my_orders') }}" href="{{ \Routing::slug('user/panel/orders') }}"><img src="/themes/tauleryfau/assets/img/hammer.png"><p>{{ trans($theme.'-app.user_panel.orders') }}</p></a></li>
        <li class="<?php if($tab == 'allotments'){ echo(' tab-active'); } ?>" role="presentation"><a data-title="{{ trans($theme.'-app.panel.my_adj') }}" href="{{ \Routing::slug('user/panel/allotments/outstanding') }}" ><i class="fas fa-2x fa-hand-paper"></i><p>{{ trans($theme.'-app.user_panel.allotments') }}</p></a></li>
        <li class="<?php if($tab == 'favorites'){ echo(' tab-active'); } ?>" role="presentation" ><a href="{{ \Routing::slug('user/panel/orders') }}?favorites=true" data-title="{{ trans($theme.'-app.panel.my_adj') }}" ><i class="fas  fa-2x fa-star"></i><p>{{ trans($theme.'-app.user_panel.favorites') }}</p></a></li>
        <li class=""><a href="{{ \Routing::slug('logout') }}"><i class="fas fa-2x fa-times-circle"></i><p>{{ trans($theme.'-app.user_panel.exit') }}</p></a></li>

    </ul>
</div>

