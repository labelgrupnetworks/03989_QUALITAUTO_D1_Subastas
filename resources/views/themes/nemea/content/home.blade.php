<div class="home-slider">
	{!! BannerLib::bannersPorKey('home-principal', 'home-top-banner', ['dots' => false, 'autoplay' => false, 'arrows' => false], null, false) !!}
</div>


<!-- Inicio lotes destacados -->
<section class="section-destacados my-5" id="lotes_destacados_section">
	<div class="container">
		<h2 class="h1">{{ trans($theme.'-app.lot_list.lotes_destacados') }}</h2>

		<div class="lotes_destacados">
			<div class="loader"></div>
			<div class="carrousel-wrapper" id="lotes_destacados" data-container="lotes_destacados_section"></div>
		</div>

	</div>
</section>

@php
$page = App\Models\V5\Web_Page::getPageByKey('nosotros-home');
@endphp

@if ($page)
<section class="static-page home-static-page mb-5 pb-5">
	<div class="container">
		{!! $page->content_web_page !!}
	</div>
</section>
@endif


@php
	$replace = ['lang' => Tools::getLanguageComplete(Config::get('app.locale')), 'emp' => Config::get('app.emp')];
@endphp

<script>
	var replace = @json($replace);
    $( document ).ready(function() {
        ajax_newcarousel("lotes_destacados", replace, null, {autoplay: false, arrows: true, dots: false});
    });
</script>
