@php
	$isCedente = false;
	if(Session::has('user')) {
		$isCedente = \App\Models\V5\FxCli::isCedente(Session::get('user.cod'));
	}
@endphp

<div class="tabs-custom filters-auction-content">
    <div id="button-open-user-menu" class="tabs-custom-responsive visible-xs visible-sm"><i class="fas fa-align-left"></i></div>
    <div style="postion: relative"></div>
    <ul id="user-account-ul" class="ul-format color-letter">

		@if(!$isCedente)
		<li class="text-uppercase<?php if($tab == 'orders'){ echo(' tab-active'); } ?>" role="presentation" >
            <a class="" href="{{ \Routing::slug('user/panel/orders') }}" data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_bidds') }}" >
                <img width="20px" src="/themes/{{\Config::get('app.theme')}}/assets/img/hammer.png"  alt="{{ trans(\Config::get('app.theme').'-app.panel.my_bidds') }}">
                {{ trans(\Config::get('app.theme').'-app.user_panel.my_bidds') }}
            </a>
        </li>

		<li class="text-uppercase @if(Route::currentRouteName() == 'panel.counteroffers') {{ 'tab-active' }} @endif" role="presentation" >
            <a class="" href="{{ route('panel.counteroffers', ['lang' => Config::get('app.locale')]) }}" data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_counter_offers') }}" >
				<i class="fa fa-flag"></i>
                {{ trans(\Config::get('app.theme').'-app.user_panel.my_counter_offers') }}
            </a>
        </li>

		<li class="text-uppercase @if(Route::currentRouteName() == 'panel.pre_awards') {{ 'tab-active' }} @endif" role="presentation" >
            <a class="" href="{{ route('panel.pre_awards', ['lang' => Config::get('app.locale')]) }}" data-title="{{ trans(\Config::get('app.theme').'-app.panel.pre_awards') }}" >
				<i class="fa fa-handshake-o"></i>
                {{ trans(\Config::get('app.theme').'-app.user_panel.pre_awards') }}
            </a>
        </li>

		<li class="text-uppercase<?php if($tab == 'allotments'){ echo(' tab-active'); } ?>" role="presentation">
            <a href="{{ \Routing::slug('user/panel/allotments') }}" data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_adj') }}">
                <i class="fas fa-trophy"></i>
                {{ trans(\Config::get('app.theme').'-app.user_panel.allotments') }}
            </a>
        </li>

        <li class="text-uppercase<?php if($tab == 'favorites'){ echo(' tab-active'); } ?>" role="presentation" >
            <a class="" href="{{ \Routing::slug('user/panel/favorites') }}" data-title="{{ trans(\Config::get('app.theme').'-app.panel.my_adj') }}" >
                <i class="fas fa-star"></i>
                {{ trans(\Config::get('app.theme').'-app.user_panel.favorites') }}
            </a>
        </li>
		@endif

        <li class="text-uppercase<?php if($tab == 'datos-personales'){ echo(' tab-active'); } ?>" role="presentation">

            <a class="" href="{{ \Routing::slug('user/panel/info') }}">
                <i class="fas fa-user-circle"></i>
                {{ trans(\Config::get('app.theme').'-app.user_panel.info') }}</a></li>

 <?php /*<li role="presentation" <?php if($tab == 'datos-personales'){ echo('class="active"'); } ?>><a href="{{ \Routing::slug('user/panel/info') }}">{{ trans(\Config::get('app.theme').'-app.user_panel.info') }}</a></li> */?>
        <li class="text-uppercase"><a href="{{ \Routing::slug('logout') }}"><i class="fas fa-sign-out-alt"></i>{{ trans(\Config::get('app.theme').'-app.user_panel.exit') }}</a></li>
    </ul>
</div>
