<?php
#Galeria
if (\Config::get('app.emp') == '003' || \Config::get('app.emp') == '004') {
    $subObj = new App\Models\V5\FgSub();
    #Cojemos la exposicion/subasta tipo E  activa que empiece antes, si no quieren que aparezca esa que la pongan en histórico
    $actual = $subObj
        ->select('DES_SUB, COD_SUB')
        ->where('SUBC_SUB', 'S')
        ->where('TIPO_SUB', 'E')
        ->where('OPCIONCAR_SUB', 'N')
        ->orderby('DFEC_SUB')
        ->first();
    if (!empty($actual)) {
        $url_web = \Tools::url_exposicion($actual->des_sub, $actual->cod_sub);
    } else {
        $url_web = Route('exposiciones');
    }
}
?>
@if (\Config::get('app.emp') == '003' || \Config::get('app.emp') == '004')
    <script>
        window.location.href = '{{ $url_web }}';
    </script>
@endif

@php
	$gridAnsorenaSomos = "{
 		'dots': false,
 		'arrows': false,
 		'rows': 2,
 		'slidesPerRow': 3,
 		 	'responsive': [
 		  		{
 		   			'breakpoint': 768,
 		   			'settings': {
 		    			'rows': 3,
 		    			'slidesPerRow': 2,
 		   			}
 		  		}
 		 	]
 		}";
@endphp

@if (\Config::get('app.emp') == '001' || \Config::get('app.emp') == '002')

{{--
<div style="position: relative;">
	<video autoplay="" playsinline="" loop="" muted="" poster="" width="100%" height="100%"  title="home">
		<source src="/themes/ansorena/assets/home.mp4" type="video/mp4">
	</video>
	<div class="buttonVideo">
	<a class="btn-video " href="https://vimeo.com/user158798428" >
		<span>{{ trans("$theme-app.home.ver-video") }} <i class="fas fa-play"></i></span>
	</a>
	</div>
</div>
--}}
    {{-- CÓDIGO DE LA HOME DE ANSORENA --}}

	{{-- Banner superior de la home --}}

    <div class="home-banner">
        {!! \BannerLib::bannersPorKey('home-top-banner', 'home-top', ['arrows' => false, 'dots' => true, 'infinite' => true, 'autoplay' => true, 'autoplaySpeed' => 3000 ]) !!}
    </div>

	{{-- Banners de imagen de imagen y texto --}}

    <div class="home-banner">
        {!! \BannerLib::bannersPorKey('VIDEO_VIMEO', 'home-img-and-text-1', ['arrows' => false, 'dots' => false]) !!}
    </div>

	<div class="home-banner">
        {!! \BannerLib::bannersPorKey('text-and-img-home-1', 'home-img-and-text-1', ['arrows' => false, 'dots' => false]) !!}
    </div>



    <div class="home-banner">
        <div class="banner-img-and-text-2">
            {!! \BannerLib::bannersPorKey('text-and-img-home-2', 'home-img-and-text-2', ['arrows' => false, 'dots' => false]) !!}
        </div>
    </div>

	{{-- Sección de Ansorena compromiso --}}

	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="home-compromiso mt-3 mb-5">
					<h3 class="title-home">{{ trans("$theme-app.home.ansorena-engagement") }}</h3>
					<p class="my-0 text-home">{{ trans("$theme-app.home.engagement-ring-text-1") }}</p>
					<p class="text-home">{{ trans("$theme-app.home.engagement-ring-text-2") }}</p>
					<div class="container-img">
						<img class="img-config" src="themes/{{ $theme }}/img/compromiso.jpg" alt="Imágen de Ansorena compromiso">
					</div>
					<a class="mini-button"
					 	href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.ansorena-engagement') }}">{{ trans("$theme-app.home.visit-catalog") }}</a>

				</div>
			</div>
		</div>
	</div>

	{{-- Newsletter --}}

	<div class="container-fluid" style="background-color: #7D9395">
		<div class="home-bottom mt-5 mb-5">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<h3 class="title-home">
							<a style="color: white;"  href="{{ Routing::translateSeo('pagina')."newsletter"}}">{{ trans("$theme-app.home.unete_newsletter") }}</a>
						</h3>
					</div>
				</div>
			</div>

		</div>
	</div>


	{{-- Sección Trabajar en ansorena --}}

	<div class="container-fluid" style="background-color: #DEDEDE">
		<div class="home-trabaja mt-5 mb-5">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-4">
						<img src="themes/{{ $theme }}/img/fachada.jpg" alt="" class="img-trabaja">
					</div>
					<div class="col-xs-12 col-sm-8">
						<h3 class="title-home">{{ trans("$theme-app.home.careers") }}</h3>
						<p class="text-home">{{ trans("$theme-app.home.careers-text") }}</p>
						<a class="mini-button" href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.careers') }}">{{ trans("$theme-app.home.read-more") }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	{{-- Banner Ansorena Somos --}}

	<div class="container-fluid" style="background-color: #7D9395">
		<div class="home-bottom mt-5 mb-5">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<h3 class="title-home">{{ trans("$theme-app.home.ansorena-is") }}</h3>
					</div>
				</div>
			</div>
			{{-- Banner --}}
			<div class="home-banner">
				{!! \BannerLib::bannersPorKey('home-bottom-banner', 'banner-home-bottom', $gridAnsorenaSomos) !!}
			</div>
		</div>
	</div>


@endif


























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
