<div class="home-slider">
	{!! BannerLib::bannersPorKey('home-principal', 'home-top-banner', ['dots' => false, 'autoplay' => false, 'arrows' => false], null, false) !!}
</div>


<!-- Inicio lotes destacados -->
<section class="section-destacados my-5">
	<div class="container">
		<h1>{{ trans($theme.'-app.lot_list.lotes_destacados') }}</h1>

		<div class="lotes_destacados">
			<div class="loader"></div>
			<div class="carrousel-wrapper" id="lotes_destacados"></div>
		</div>

	</div>
</section>


@php
	$replace = ['lang' => Tools::getLanguageComplete(Config::get('app.locale')), 'emp' => Config::get('app.emp')];
@endphp

<script>
	var replace = @json($replace);
    $( document ).ready(function() {
        ajax_newcarousel("lotes_destacados", replace, null, {autoplay: false, arrows: true, dots: false});
    });
</script>
