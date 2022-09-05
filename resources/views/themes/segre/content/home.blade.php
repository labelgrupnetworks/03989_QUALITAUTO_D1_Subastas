@php
$home3bannerOptions = "{
		'dots': false,
	'arrows': false,
	'rows': 1,
		'slidesPerRow': 1,
		'responsive': [
				{
						'breakpoint': 768,
						'settings': {
								'slidesPerRow': 1,
						}
				}
		]
	}";

$youtubeBannerOptions = "{
		'dots': false,
	'arrows': false,
	'rows': 5 ,
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
						<h2>{{ trans($theme . '-app.home.welcome_segre') }}<br>{{ trans($theme . '-app.home.next_auction') }}</h2>
					</div>
					<div class="text-center paragraph-color-gray">
						<h3>{{ trans($theme . '-app.home.open_exhibition') }}</h3>
						<p class="paragraph-96ch mt-2 mb-1">{!! trans($theme . '-app.home.segre_home_description') !!}<br></p>
					</div>
					<div class="mt-1 mb-1">
						<a class="btn btn-home" target="_self"
							href="{{ route('catalogos_newsletter') }}"><span>{{ trans($theme . '-app.home.subscribe_catalogs') }}</span></a>
					</div>
					<div class="mt-1 mb-1">
						<a class="btn btn-home" target="_self"
							href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.segre-enlaces.segre_valuation') }}">
							<span>{{ trans($theme . '-app.home.valuarion_request') }}</span></a>
					</div>
					<div class="mt-1 mb-1">
						<a class="btn btn-home" target="_self"
							href="{{ \Routing::slug('register') }}"><span>{{ trans($theme . '-app.home.register_account') }}</span></a>
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
			<h2 class="title-color-dark-gray mt-4 mb-4 text-center">{{ trans($theme . '-app.home.auction_title') }}</h2>
		</div>
	</div>
</div>
<div class="bg-primary-color pt-5 pb-5">
	{!! \BannerLib::bannersPorKey('home-category-banner', 'home-p-a-j', $home3bannerOptions) !!}
</div>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<h2 class="title-color-dark-gray mt-4 mb-4 text-center">{{ trans($theme . '-app.home.catalogs_title') }}</h2>
		</div>
	</div>
</div>

<div class="catalog-banner-container">
	{!! \BannerLib::bannersPorKey('catalog-home-banner', 'home-catalog', $home3bannerOptions) !!}
</div>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<h2 class="title-color-dark-gray mt-4 mb-4 text-center">{{ trans($theme . '-app.home.featured_lots_title') }}</h2>
		</div>
	</div>
</div>

<div class="bg-primary-color pt-5 pb-5">
	<div class="yt-banner-container pb-3">
		{!! \BannerLib::bannersPorKey('featured-lot-banner', 'featured-log-bnr', $youtubeBannerOptions) !!}
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-6">
				{!! \BannerLib::bannersPorKey('expo-img-banner', 'expo-img', "{'dots': false, 'arrows': false,'}") !!}
				{{ trans("$theme-app.key.code") }}
			</div>
			<div class="col-xs-12 col-md-6">
				<div>
					<h2 class="text-center virtual-visit-title">{{ trans("$theme-app.home.virtual_visit_title") }}<br>{{ trans("$theme-app.home.virtual_visit_subtitle") }}</h2>
					<div class="text-center mt-3">
						<a class="btn btn-visita-virtual" href="https://my.matterport.com/show/?m=nNAmVkeLrSz">{{ trans("$theme-app.home.start_visit") }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<h2 class="title-color-dark-gray mt-4 mb-4 text-center">{{ trans($theme . '-app.home.segre_register_title') }}</h2>
		</div>
	</div>
</div>

<div class="ventajas-segre-banner-container">
	{!! \BannerLib::bannersPorKey('ventajas-segre-bnr', 'ventajas-segre-imgs', "{'dots': false, 'arrows': false,'}") !!}
</div>

<div class="text-center mt-4 mb-2">
	<a class="btn btn-register-home" href="{{ \Routing::slug('register') }}">{{ trans("$theme-app.home.register_button") }}</a>
</div>


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

@include('includes.newsletter')

<div class="sell-segre-banner-container">
	{!! \BannerLib::bannersPorKey('banner-sell-segre', 'sell-segre-banner', "{'dots': false, 'arrows': false,'}") !!}
</div>

<div class="app-segre-banner-container">
	{!! \BannerLib::bannersPorKey('banner-app-segre', 'app-segre-banner', "{'dots': false, 'arrows': false,'}") !!}
</div>




















































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
