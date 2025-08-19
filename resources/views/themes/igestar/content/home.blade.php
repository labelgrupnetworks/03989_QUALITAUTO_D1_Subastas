<div class="home-slider">
	{!! BannerLib::bannersPorKey('new_home', 'home-top-banner', ['dots' => false, 'autoplay' => true, 'autoplaySpeed' => 5000, 'slidesToScroll' => 1, 'arrows' => false]) !!}
</div>


<!-- Inicio lotes destacados -->
<section id="destacados_section" class="section-destacados my-5">
	<div class="container">
		<h2 class="h1">{{ trans('web.lot_list.lotes_destacados') }}</h2>

		<div class="lotes_destacados">
			<div class="loader"></div>
			<div class="carrousel-wrapper" id="lotes_destacados" data-container="destacados_section"></div>
		</div>

	</div>
</section>

<div class="about-us-banner my-5">
	{!! BannerLib::bannersPorKey('about_us', 'about_banner', '{dots: false}') !!}
</div>

@php
	$replace = ['lang' => Tools::getLanguageComplete(Config::get('app.locale')), 'emp' => Config::get('app.emp')];
@endphp

<script>
	var replace = @json($replace);
    $( document ).ready(function() {
        ajax_newcarousel("lotes_destacados", replace, null, {autoplay: false, arrows: true, dots: false});
    });
</script>
