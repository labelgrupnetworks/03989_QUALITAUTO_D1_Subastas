<div class="user-panel-menu">
	<a class="d-flex align-items-center @if($tab == 'datos-personales') active @endif" href="">
		<div class="img-icon-container d-flex align-items-center justify-content-center">
			<img src="/themes/{{\Config::get('app.theme')}}/assets/img/user-icon.svg" alt="">
		</div>
		<p>{{ trans(\Config::get('app.theme').'-app.user_panel.info') }}</p>
	</a>
	<a class="d-flex align-items-center @if($tab == 'allotments') active @endif" href="">
		<div class="img-icon-container d-flex align-items-center justify-content-center">
			<img src="/themes/{{\Config::get('app.theme')}}/assets/img/user-icon.svg" alt="">
		</div>
		<p>Mis Facturas</p>
	</a>
	<a class="d-flex align-items-center @if($tab == 'orders') active @endif" href="">
		<div class="img-icon-container d-flex align-items-center justify-content-center">
			<img style="width: 100%" src="/themes/{{\Config::get('app.theme')}}/assets/img/auction-icon.svg" alt="">
		</div>
		<p>{{ trans(\Config::get('app.theme').'-app.user_panel.orders') }}</p>
	</a>
	<a class="d-flex align-items-center @if($tab == 'datos-personales') active @endif" href="">
		<div class="img-icon-container d-flex align-items-center justify-content-center">
			<img src="/themes/{{\Config::get('app.theme')}}/assets/img/position-icon.svg" alt="">
		</div>
		<p>Mis Direcciones</p>
	</a>
	<a class="d-flex align-items-center @if($tab == 'datos-personales') active @endif" href="">
		<div class="img-icon-container d-flex align-items-center justify-content-center">
			<img style="width: 110%; transform: rotate(0deg)" src="/themes/{{\Config::get('app.theme')}}/assets/img/hand-icon.svg" alt="">
		</div>
		<p>Mis Cesiones</p>
	</a>
	<a class="d-flex align-items-center @if($tab == 'favorites') active @endif" href="">
		<div class="img-icon-container d-flex align-items-center justify-content-center">
			<img style="width: 100%" src="/themes/{{\Config::get('app.theme')}}/assets/img/star-icon.svg" alt="">
		</div>
		<p>{{ trans(\Config::get('app.theme').'-app.user_panel.favorites') }}</p>
	</a>
</div>
