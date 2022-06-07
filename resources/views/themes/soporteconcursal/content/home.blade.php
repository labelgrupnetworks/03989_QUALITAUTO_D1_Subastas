<div class="home-slider">
	<div class="container">
		<div class="row flex-display row-custom">
			@if(!Session::has('user'))
			<div class="col-xs-3 home-slider-control hidden-xs hidden-sm hidden-md">
				<div class="banner-register">
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
				class="slider-new-banner col-xs-12 p-0 @if(!Session::has('user'))col-md-9 col-lg-9  @else col-xs-12 @endif">
				{!! \BannerLib::bannersPorKey('new_home', 'home-top-banner') !!}
			</div>


		</div>
	</div>
</div>

<div class="clearfix"></div>
<br><br>

<!-- Inicio lotes destacados -->
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
				<div class="owl-theme owl-carousel" id="lotes_destacados"></div>
				<div class="owl-theme owl-carousel owl-loaded owl-drag m-0 pl-10" id="navs-arrows">
					<div class="owl-nav">
						<div class="owl-prev"><i class="fas fa-chevron-left"></i></div>
						<div class="owl-next"><i class="fas fa-chevron-right"></i></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


@include('includes.categories')





















































</div>

















































<script>
	<?php
        $key = "lotes_destacados";

        $replace = array(
          'lang' => \Tools::getLanguageComplete(Config::get('app.locale')) ,'emp' => Config::get('app.emp') ,
        );
    ?>
    var replace = <?= json_encode($replace) ?>;
    var key ="<?= $key ?>";

    $( document ).ready(function() {
        ajax_carousel(key,replace);


     });








</script>
