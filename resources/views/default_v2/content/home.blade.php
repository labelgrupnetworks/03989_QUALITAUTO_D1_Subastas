<div class="home-slider" data-text="SOFTWARE DE SUBASTAS">
	<div class="banner-content py-2 py-sm-5">
		<h1 class="text-center">SUBASTAS <span class="lb-text-primary">LABELGRUP</span></h1>
		<a class="btn btn-primary-custom text-wrap">MOSTRAR SUBASTA</a>
	</div>
	{!! \BannerLib::bannersPorKey('new_home', 'home-top-banner') !!}
</div>


<!-- Inicio lotes destacados -->
<section class="section-destacados my-5">
	<div class="container">
		<h1>{{ trans(\Config::get('app.theme').'-app.lot_list.lotes_destacados') }}</h1>

		<div class="lotes_destacados">
			<div class="loader"></div>
			<div class="carrousel-wrapper" id="lotes_destacados"></div>
		</div>

	</div>
</section>

<div class="about-us-banner my-5">
	{!! \BannerLib::bannersPorKey('about_us', 'about_banner', '{dots: false}') !!}
</div>

<section class="section-categoires py-5">
	<div class="container">
		<h1 class="mb-4">Categorias</h1>
		<div class="row">
			@include('components.category_1', ['category' => 'ARTE', 'image' => 'category_art.jpg', 'url' => '/es/subastas-diseno', 'size' => 'col-6 col-md-4'])
			@include('components.category_1', ['category' => 'JOYAS', 'image' => 'category_jewel.jpg', 'url' => '/es/subastas-diseno', 'size' => 'col-6 col-md-4'])
			@include('components.category_1', ['category' => 'NUMIMÁTICA', 'image' => 'category-numismatica.jpg', 'url' => '/es/subastas-diseno', 'size' => 'col-6 col-md-4'])
			@include('components.category_1', ['category' => 'ARTE', 'image' => 'category_art.jpg', 'url' => '/es/subastas-diseno', 'size' => 'col-6 col-md-4'])
		</div>
	</div>
</section>

<section class="section-categoires py-5">
	<div class="container">
		<h1 class="mb-4">Categorias</h1>
		<div class="row">
			@include('components.category_2', ['category' => 'ARTE', 'image' => 'category_art.jpg', 'url' => '/es/subastas-diseno', 'size' => 'col-6 col-md-4'])
			@include('components.category_2', ['category' => 'JOYAS', 'image' => 'category_jewel.jpg', 'url' => '/es/subastas-diseno', 'size' => 'col-6 col-md-4'])
			@include('components.category_2', ['category' => 'NUMIMÁTICA', 'image' => 'category-numismatica.jpg', 'url' => '/es/subastas-diseno', 'size' => 'col-6 col-md-4'])
			@include('components.category_2', ['category' => 'ARTE', 'image' => 'category_art.jpg', 'url' => '/es/subastas-diseno', 'size' => 'col-6 col-md-4'])
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
