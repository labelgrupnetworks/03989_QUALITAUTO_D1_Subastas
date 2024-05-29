<div class="home-slider">
	<div class="container">
		<div class="row flex-display row-custom">
			@if(!Session::has('user'))
			<div class="col-xs-3 home-slider-control hidden-xs hidden-sm hidden-md">
				<div class="banner-register text-center">
					<div class="banner-register-title">{{ trans(\Config::get('app.theme').'-app.home.not_account') }}
					</div>
					<div class="banner-register-sub-title hidden">
						{{ trans(\Config::get('app.theme').'-app.login_register.crear_cuenta') }}</div>
					<div class="banner-register-btn text-center">
						<a class="button-principal"
							title="{{ trans(\Config::get('app.theme').'-app.login_register.register') }}"
							href="{{ \Routing::slug('register') }}">{{ trans(\Config::get('app.theme').'-app.login_register.register') }}</a>
					</div>
					<div class="banner-register-hr">
						<hr>
					</div>
					<div class="banner-register-title">{{ trans(\Config::get('app.theme').'-app.home.account') }}</div>
					<div class="banner-register-btn text-center">
						<a class="secondary-button user-account btn_login"
							href="javascript:;">{{ trans(\Config::get('app.theme').'-app.login_register.generic_name') }}</a>
					</div>
				</div>
			</div>
			@endif

			<div
				class="slider-new-banner col-xs-12 @if(!Session::has('user'))col-md-9 col-lg-9 @endif">
				{!! \BannerLib::bannersPorKey('new_home', 'home-top-banner') !!}
			</div>

		</div>

		{{-- <div class="row mt-2">
			{!! \BannerLib::bannersPorKey('new_home_2', '', ['dots' => false]) !!}
		</div> --}}

	</div>
</div>

<div class="clearfix"></div>
<br><br>

<!-- Inicio grid lotes destacados -->
<div id="lotes_destacados-content" class="lotes_destacados secundary-color-text">
	<div class="container">
		<div class="row flex-display flex-wrap">
			<div class="col-xs-12 col-sm-12 col-md-12 lotes-destacados-principal-title">
				<div class="lotes-destacados-tittle color-letter">
					{{ trans(\Config::get('app.theme').'-app.lot_list.lotes_destacados') }}
				</div>
			</div>

			<div class="col-xs-12 col-sm-10 col-md-12 text-center">
				<div class="lds-ellipsis loader">
					<div></div>
					<div></div>
					<div></div>
					<div></div>
				</div>
				<div id="lotes_destacados"></div>
			</div>
		</div>
	</div>
</div>


@php
	$replace = array('lang' => \Tools::getLanguageComplete(Config::get('app.locale')) ,'emp' => Config::get('app.emp'));
@endphp

{{-- JS de lotes destacados --}}
{{-- <script>
	var replace = @json($replace);

    $( document ).ready(function() {
        ajax_newcarousel("lotes_destacados", replace);
    });
</script> --}}

<script>
	var replace = @json($replace);

    $( document ).ready(function() {
        ajax_lotes_destacados_grid("lotes_destacados", replace);
    });
</script>
