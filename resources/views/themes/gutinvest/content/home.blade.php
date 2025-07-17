@php
	use App\Services\Content\BannerService;
	$bannerService = new BannerService();
@endphp
<div class="register-alert">
	<div class="register-alert-close">X</div>
	<div class="register-alert-register">
		<div class="register-alert-register-title">{{ trans(\Config::get('app.theme') . '-app.emails.not_register') }}</div>
		<div class="register-alert-register-btn"><a
				href="{{ \Routing::translateSeo('login') }}">{{ trans(\Config::get('app.theme') . '-app.login_register.register') }}</a>
		</div>
	</div>
	<div class="register-alert-suscribe">
		<div class="register-alert-suscribe-title" style="text-transform: uppercase">
			{{ trans(\Config::get('app.theme') . '-app.foot.newsletter_title') }}</div>
		<div class="register-alert-suscribe-btn"><a
				href="#newsletter_secction">{{ trans(\Config::get('app.theme') . '-app.emails.suscribe') }}</a></div>

	</div>
</div>


<section class="carousel-gutinvest">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-9 carousel-fijo">
				<div class="owl-carousel owl-theme" data-slider-id="1" id="owl-carousel">
					{!! BannerLib::bannerWithView('home', 'slide') !!}
				</div>
			</div>
			<div class="col-md-3  hidden-xs hidden-sm controls-carousel" style="padding-left:  0">

				<div class="controls-carousel-container">
					{!! BannerLib::bannerWithView('home', 'slide_control') !!}
				</div>
			</div>
		</div>
	</div>
</section>

<section class="subastas-home">
	<div class="container">
		<div class="titulos-home mb-0">
			<p style="width:100%">{{ trans(\Config::get('app.theme') . '-app.subastas.next_sell') }} (<span
					id="total_lots_title"></span>)</p>
		</div>
		<div class="search-input pstatic mb-2 mb-2">
			<form id="formsearch-responsive" role="search" action="{{ \Routing::slug('busqueda') }}">
				<div class="form-group" style="padding-right: 0;">
					<input class="form-control input-custom"
						placeholder="{{ trans(\Config::get('app.theme') . '-app.head.search_label') }}" type="text" name="texto"
						id="textSearch">
					<button type="submit" class="btn btn-custom-search" style="right:3px;">
						<i class="fa fa-search"></i>
					</button>
				</div>
			</form>
		</div>
	</div>

	<div class="subastas-home-content">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<h1>{{ trans(\Config::get('app.theme') . '-app.home.home_seo_title') }}</h1>
					<h2>{{ trans(\Config::get('app.theme') . '-app.home.home_seo_subtitle') }}</h2>
				</div>

				<?php $home_page = true; ?>

				@include('includes.blocs')

			</div>
		</div>
</section>



<div class="owl-carousel owl-theme hide" id="owl-carouse">
	<?php
	$key = 'slider_home_' . strtoupper(Config::get('app.locale'));

	$html = "<div>

	            <a href=\"{url}\" {target}>
	                <img class=\"img-responsive\" src=\"{img}\">
	                <div class='slider-text'>

	                    <h1>{html}</h1>
	                </div>

	            </a>
	        </div>";

	$content = $bannerService->getOldBannerWithSliderBlade($key, $html);

	?>
	<?= $content ?>
</div>

<!-- Fin slider -->
<!-- Inicio lotes destacados -->
<div class="lotes_destacados">
	<div class="container">
		<div class="title_lotes_destacados">
			<p>{{ trans(\Config::get('app.theme') . '-app.lot_list.lotes_destacados') }}
			<p>
		</div>
		<div class="loader"></div>
		<div class="owl-theme owl-carousel" id="lotes_destacados"></div>
	</div>
</div>

<section class="contacto-home">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 contact-home-content">

				<?php
				$contact = strtoupper(Config::get('app.locale')) == 'ES' ? 'contacto' : 'contact';
				$key = 'contacto_home_' . strtoupper(Config::get('app.locale'));
				$html = "<div class='col-xs-12 col-sm-6 slogan-big no-padding'>

				                                    {html}
				                            </div>

				<div class='col-xs-12 col-sm-6 col-md-5 col-md-offset-1 img-contacto-home no-padding'>

				<img class='hidden-xs' src='{img}'  alt=''>

				                    <div class='contacto-home-title'>
				                       <a href='{url}'>$contact</a>
				                    </div>
				                </div>

				";
				$content = $bannerService->getOldBannerWithSliderBlade($key, $html);
				?>
				<?= $content ?>

			</div>
		</div>
	</div>
</section>
<section class="sobre-nosotros-home">
	<div class="container">
		<div class="titulos-home">
			<p><?= trans(\Config::get('app.theme') . '-app.home.about_us_title') ?></p>
		</div>
	</div>
	<div class="sobre-nosotros-home-content">

		<div class="container" style="position: relative">
			<div class="sobre-nosotros-home-bg hidden-xs"></div>
			<div class="row">


				<?php
				$key = 'nosotros_home_' . strtoupper(Config::get('app.locale'));
				$html = "<div class='col-xs-12 col-sm-6 col-lg-6 col-md-6 serivicios-content'>

				                                    {html}
				                            </div>
				                            <div class='hidden-xs col-xs-12  col-sm-3 col-xs-offset-0 col-md-offset-1 sobre-nosotros-home-img' style='position: relative'>
				<img class='ico-service' style='max-width: 340px;' src='{img}'  alt=''>
				</div>
				                        </div>";
				$content = $bannerService->getOldBannerWithSliderBlade($key, $html);
				?>
				<?= $content ?>


			</div>
		</div>
	</div>
</section>

<section class="vendemos-home">
	<div class="container">
		<div class="titulos-home">
			<p><?= trans(\Config::get('app.theme') . '-app.home.sell_title') ?></p>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 vendemos-home-content">
				<?php
				$key = 'tasacion_home_' . strtoupper(Config::get('app.locale'));
				$html = "<div class='col-xs-6  no-padding'>
				                                <img class='ico-service' style='max-width: 90%;' src='{img}'  alt=''>

				                            </div>
				                            <div class='col-xs-6 vendemos-home-desc'>
				                                {html}
				                            </div>
				                        </div>";
				$content = $bannerService->getOldBannerWithSliderBlade($key, $html);
				?>
				<?= $content ?>
			</div>
		</div>
	</div>
</section>


<section class="services-home">
	<div class="container">
		<div class="titulos-home">
			<p><?= trans(\Config::get('app.theme') . '-app.home.service_title') ?></p>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 services-content">
				<?php
				$key = 'services_home_' . strtoupper(Config::get('app.locale'));
				$html = "<div class='col-xs-12 col-sm-4'>
				                <div class='circle-services'>
				                <img class='ico-service' style='max-width: 90%;' src='{img}'  alt=''>
				<p>{html}</p>
				</div>
				</div>";
				$content = $bannerService->getOldBannerWithSliderBlade($key, $html);
				?>
				<?= $content ?>

			</div>
			<div class="col-xs-12 text-center" style="margin: 30px 0">
				<a class="btn-link-home" title="{{ trans(\Config::get('app.theme') . '-app.foot.contact') }}"
					href="<?= \Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.services') ?>">{{ trans(\Config::get('app.theme') . '-app.foot.services') }}</a>

			</div>
		</div>
	</div>
</section>



















<script>
	<?php
	$key = 'lotes_destacados';
	$replace = [
	    'lang' => Config::get('app.language_complete')[Config::get('app.locale')],
	    'emp' => Config::get('app.emp'),
	];

	?>

	var replace = <?= json_encode($replace) ?>;
	var key = "<?= $key ?>";
	$(document).ready(function() {
		var totalLots = $('#total_lots_home').text()
		$('#total_lots_title').text(totalLots)

		if (localStorage.getItem("register") !== "true") {
			$('.register-alert').css('transform', 'translateY(0%)')
			localStorage.setItem("register", "true");

		}

		$('.register-alert-close').click(function() {
			$('.register-alert').css('transform', 'translateY(100%)')
		})




		$('.controls_slider').first().find('img').addClass('full-op')
		ajax_carousel(key, replace);
		var owl = $('#owl-carousel');
		owl.owlCarousel();
		// Listen to owl events:
		owl.on('changed.owl.carousel', function(event) {

			var page = event.page.index;
			var totalpage = event.page.count;
			if (totalpage > 3) {
				if (page > 2) {
					if ($(window).width() < 1200) {
						//var trans = (page - 4) * + 117.75
						var trans = (page - 4) * +86.5
					} else {
						//var trans = (page - 4) * + 142.5
						var trans = (page - 4) * +105.5
					}

					$('.controls-carousel-container').css({
						"-webkit-transform": "translateY( " + trans + "px)"
					});
				}
				if (page < 2) {
					$('.controls-carousel-container').css({
						"-webkit-transform": "translateY(0px)"
					});
				}


			}
			/*$('.controls-carousel-container').css({"-webkit-transform":"translateY( " + trans + "px)"});*/
			$('.controls_slider img').removeClass('full-op')
			$('.controls_slider[data-index=' + page + '] img').addClass('full-op')
		})

		$('.controls_slider').click(function(e) {
			console.log($(this).attr('data-index'))
			var index = $(this).attr('data-index')
			var owl = $('#owl-carousel');
			owl.owlCarousel();
			// Listen to owl events:
			owl.trigger('to.owl.carousel', [index])
		})
	});
</script>
