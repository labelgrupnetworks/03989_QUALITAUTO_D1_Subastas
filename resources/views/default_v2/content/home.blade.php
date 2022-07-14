<div class="home-slider" data-text="SOFTWARE DE SUBASTAS">
	<div class="banner-content py-2 py-sm-5">
		<h1 class="text-center">SUBASTAS <span class="lb-text-primary">LABELGRUP</span></h1>
		<a class="btn btn-primary-custom text-wrap">MOSTRAR SUBASTA</a>
	</div>
	{!! \BannerLib::bannersPorKey('new_home', 'home-top-banner') !!}
</div>


<!-- Inicio lotes destacados -->
<section class="section-destacados mt-3 mb-5">
	<div class="container">
		<h1>{{ trans(\Config::get('app.theme').'-app.lot_list.lotes_destacados') }}</h1>

		<div class="lotes_destacados">
			<div class="loader"></div>
			<div class="carrousel-wrapper" id="lotes_destacados"></div>
		</div>

	</div>
</section>

@php
	$replace = array('lang' => \Tools::getLanguageComplete(Config::get('app.locale')) ,'emp' => Config::get('app.emp'));
@endphp


<script>
	var replace = @json($replace);
    $( document ).ready(function() {
        ajax_newcarousel("lotes_destacados", replace, null, {autoplay: false, arrows: true, dots: false});
    });
</script>
