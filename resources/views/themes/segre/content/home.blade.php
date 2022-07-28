@php
$pajOptions = "{
		'dots': false,
	'arrows': false,
	'rows': 3,
		'slidesPerRow': 3,
		'responsive': [
				{
						'breakpoint': 768,
						'settings': {
								'slidesPerRow': 1,
						}
				}
		]
	}";

@endphp

{!! \BannerLib::bannersPorKey('home-top-banner', 'home-superior', [
    'arrows' => true,
    'dots' => false,
    'autoplay' => true,
    'autoplaySpeed' => '3000',
]) !!}

<div class="bg-primary-color">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="d-flex align-items-center flex-column home-text-container">
					<div class="text-center title-color-yellow">
						<h2>Bienvenido a Subastas Segre<br> Próxima Subasta 13, 14 y 15 Septiembre</h2>
					</div>
					<div class="text-center paragraph-color-gray">
						<h3>Exposición abierta del 2 al 12 de Septiembre</h3>
						<p>Casa de subastas líder en España.<br> Le ayudamos a descubrir y adquirir tesoros del arte, el coleccionismo y
							la
							decoración. Un sólido equipo de especialistas que le asesorarán en las diferentes disciplinas del arte: Pintura,
							Artes
							decorativas y Joyas.<br> Para cualquier consulta contacte en el 915 159 584 o en info@subastassegre.es<br>
						</p>
					</div>
					<div>
						<a class="btn btn-home" target="_self" href="{{-- https://www.subastassegre.es/recibir-catalogos/ --}}"><span>SUSCRÍBASE A NUESTROS
								CATÁLOGOS</span></a>
					</div>
					<div>
						<a class="btn btn-home" target="_self" href="{{-- https://www.subastassegre.es/vender-en-segre-valoracion/ --}}"><span>SOLICITAR UNA
								VALORACIÓN</span></a>
					</div>
					<div>
						<a class="btn btn-home" target="_self" href="{{-- https://www.subastassegre.com/default/customer/account/create/ --}}"><span>ACCEDA A SU CUENTA -
								REGISTRARSE</span></a>
					</div>

					<div class="social-link-container">
						@if (\Config::get('app.facebook'))
							<a class="facebook-social-link social-link" href="{{ \Config::get('app.facebook') }}" target="_blank">
								<i class="fa fa-facebook-square social-link-icon" aria-hidden="true"></i>
							</a>
						@endif
						@if (\Config::get('app.twitter'))
							<a class="twitter-social-link social-link" href="{{ \Config::get('app.twitter') }}" target="_blank">
								<i class="fa fa-twitter-square social-link-icon" aria-hidden="true"></i>
							</a>
						@endif
						@if (\Config::get('app.youtube'))
							<a class="youtube-social-link social-link" href="{{ \Config::get('app.youtube') }}" target="_blank">
								<i class="fa fa-youtube-square social-link-icon" aria-hidden="true"></i>
							</a>
						@endif
						@if (\Config::get('app.instagram'))
							<a class="instagram-social-link social-link" href="{{ \Config::get('app.instagram') }}" target="_blank">
								<i class="fa fa-instagram social-link-icon" aria-hidden="true"></i>
							</a>
						@endif
						@if (\Config::get('app.linkedin'))
							<a class="linkedin-social-link social-link" href="{{ \Config::get('app.linkedin') }}" target="_blank">
								<i class="fa fa-linkedin-square social-link-icon" aria-hidden="true"></i>
							</a>
						@endif
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<h2 class="title-color-dark-gray mt-4 mb-4 text-center">Subasta Julio 2022. Lotes no vendidos</h2>
		</div>
	</div>
</div>
<div class="bg-primary-color pt-5 pb-5">
	{!! \BannerLib::bannersPorKey('home-category-banner', 'home-p-a-j', $pajOptions) !!}
</div>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<h2 class="title-color-dark-gray mt-4 mb-4 text-center">Catálogos de la Subasta Julio 2022</h2>
		</div>
	</div>
</div>



<div class="home-slider">
	<div class="container">
		<div class="row flex-display row-custom">
			@if (!Session::has('user'))
				<div class="col-xs-3 home-slider-control hidden-xs hidden-sm hidden-md">
					<div class="banner-register">
						<div class="banner-register-title">{{ trans(\Config::get('app.theme') . '-app.home.not_account') }}
						</div>
						<div class="banner-register-sub-title hidden">
							{{ trans(\Config::get('app.theme') . '-app.login_register.crear_cuenta') }}</div>
						<div class="banner-register-btn text-center">
							<a class="button-principal" title="{{ trans(\Config::get('app.theme') . '-app.login_register.register') }}"
								href="https://www.subastassegre.com/default/customer/account/create/">{{ trans(\Config::get('app.theme') . '-app.login_register.register') }}</a>
						</div>
						<div class="banner-register-hr">
							<hr>
						</div>
						<div class="banner-register-title">{{ trans(\Config::get('app.theme') . '-app.home.account') }}</div>
						<div class="banner-register-btn text-center">
							<a class="secondary-button user-account btn_login"
								href="javascript:;">{{ trans(\Config::get('app.theme') . '-app.login_register.generic_name') }}</a>
						</div>
					</div>
				</div>
			@endif

			<div
				class="slider-new-banner col-xs-12 p-0 @if (!Session::has('user')) col-md-9 col-lg-9  @else col-xs-12 @endif">
				{!! \BannerLib::bannersPorKey('home', 'home-top-banner') !!}
			</div>


		</div>
	</div>
</div>

<div class="clearfix"></div>
<br><br>

<!-- Inicio lotes destacados -->
{{-- <div id="lotes_destacados-content" class="lotes_destacados secundary-color-text">
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
</div> --}}




















































</div>

















































<script>
 <?php
	$key = 'lotes_destacados';

	$replace = [
	    'lang' => \Tools::getLanguageComplete(Config::get('app.locale')),
	    'emp' => Config::get('app.emp'),
	];
	?>
 var replace = <?= json_encode($replace) ?>;
 var key = "<?= $key ?>";

 $(document).ready(function() {
  ajax_carousel(key, replace);


 });
</script>
