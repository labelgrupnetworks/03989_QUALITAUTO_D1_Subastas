<div class="user-panel-menu">
	<a class="d-flex align-items-center @if($tab == 'datos-personales') active @endif" href="{{ \Routing::slug('user/panel/info') }}">
		<div class="img-icon-container d-flex align-items-center justify-content-center">
			<img style="width: 100%" src="/themes/{{\Config::get('app.theme')}}/assets/img/perfil.svg" alt="">
		</div>
		<p>{{ trans(\Config::get('app.theme').'-app.user_panel.info') }}</p>
	</a>
	<a class="d-flex align-items-center @if($tab == 'allotments') active @endif" href="{{ \Routing::slug('user/panel/allotments') }}">
		<div class="img-icon-container d-flex align-items-center justify-content-center">
			<img style="width: 100%" src="/themes/{{\Config::get('app.theme')}}/assets/img/facturas.svg" alt="">
		</div>
		<p>{{trans(\Config::get('app.theme').'-app.user_panel.my_pending_bills')}}</p>
	</a>
	<a class="d-flex align-items-center @if($tab == 'orders') active @endif" href="{{ \Routing::slug('user/panel/orders') }}">
		<div class="img-icon-container d-flex align-items-center justify-content-center">
			<img style="width: 100%" src="/themes/{{\Config::get('app.theme')}}/assets/img/pujas.svg" alt="">
		</div>
		<p>{{ trans(\Config::get('app.theme').'-app.user_panel.orders') }}</p>
	</a>
	<a class="d-flex align-items-center @if($tab == 'shipping-address') active @endif" href="{{ \Routing::slug('user/panel/addresses') }}">
		<div class="img-icon-container d-flex align-items-center justify-content-center">
			<img style="width: 100%" src="/themes/{{\Config::get('app.theme')}}/assets/img/direccion.svg" alt="">
		</div>
		<p>{{ trans(\Config::get('app.theme').'-app.user_panel.my_addresses') }}</p>
	</a>
	<a class="d-flex align-items-center @if($tab == 'cesiones') active @endif" href="{{ \Routing::slug('user/panel/sales') }}">
		<div class="img-icon-container d-flex align-items-center justify-content-center">
			<img style="width: 110%; transform: rotate(0deg)" src="/themes/{{\Config::get('app.theme')}}/assets/img/cesion.svg" alt="">
		</div>
		<p>{{ trans("$theme-app.user_panel.my_assignments") }}</p>
	</a>
	<a class="d-flex align-items-center @if($tab == 'favorites') active @endif" href="{{ \Routing::slug('user/panel/orders') }}?favorites=true">
		<div class="img-icon-container d-flex align-items-center justify-content-center">
			<img style="width: 100%" src="/themes/{{\Config::get('app.theme')}}/assets/img/favoritos.svg" alt="">
		</div>
		<p>{{ trans(\Config::get('app.theme').'-app.user_panel.favorites') }}</p>
	</a>
</div>
