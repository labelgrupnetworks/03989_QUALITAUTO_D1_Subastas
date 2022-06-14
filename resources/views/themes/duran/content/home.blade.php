<?php /*
<div class="container-fluid categories-carousel-home">
	<div class="row">
		<div class="owl-theme" id="owl-carousel-responsive-home" style="visibility: hidden;">
			<?php
			$fgortsec0 = new App\Models\V5\FgOrtsec0();
			$categories = $fgortsec0->GetAllFgOrtsec0()->get()->toarray();
			?>
@foreach ($categories as $k => $category)
<div class="item">
	<div class="card">
		<div class="card-body">
			<a href="{{ route("category", ["category" => $category["key_ortsec0"]]) }}">
				<h4 class="card-title"><img class="img-responsive"
						src="{{ asset('themes/duran/assets/img/categorias/cat-' .$category["lin_ortsec0"]. '.png')}}">
				</h4>
				<p class="card-text teko-font">{{$category["des_ortsec0"]}}</p>
			</a>
		</div>
	</div>
</div>
@endforeach


</div>
</div>
</div>
*/
?>
{!! \BannerLib::bannersPorKey('CATEGORIAS', 'banner-categorias', "{dots: false, arrows: true, infinite: true, speed:
	300, slidesToShow: 9, slidesToScroll: 1, autoplay: true, autoplaySpeed: 2000, responsive: [ { breakpoint: 1024,
	settings: { slidesToShow: 6, slidesToScroll: 1, infinite: true, dots: true } }, { breakpoint: 600, settings: {
	slidesToShow: 3, arrows: false } }, { breakpoint: 480, settings: { slidesToShow: 2 } } ]}") !!}
<?php /*




 {!! \BannerLib::bannersPorKey('HOME-1', '', ['dots' => true, 'autoplay' => true, 'arrows' => false]) !!}
 {!! \BannerLib::bannersPorKey('HOME-2', 'banner-home-2', ['dots' => false]) !!}
 {!! \BannerLib::bannersPorKey('HOME-3', 'banner-home-3', ['dots' => false]) !!}
 <div class="banner-4-custom">
	{!! \BannerLib::bannersPorKey('HOME-4', 'banner-home-4', ['dots' => false]) !!}
</div>

{!! \BannerLib::bannersPorKey('HOME-5', 'banner-home-5', ['dots' => false]) !!}
<br>

 {!! \BannerLib::bannersPorKey('TESTLABEL', 'banner_video_home', ['dots' => false]) !!}
<div class="container mt-3 mb-3">
	<h1 class="titlePage lotes-destacados-tittle">{{ trans(\Config::get('app.theme').'-app.home.stories') }}</h1>
	<div class="row d-flex flex-wrap">
		<div class="col-xs-12 col-md-5 col-md-offset-2">
			{!! \BannerLib::bannersPorKey('HISTORIAS-IZQ', 'banner_home_historias') !!}
		</div>
		<div class="col-xs-12 col-md-4 mini-sliders">

				<div class="slider-home slider-two col-xs-6 col-md-12">
					{!! \BannerLib::bannersPorKey('HISTORIAS_DRCH_1', 'banner_home_historias') !!}
				</div>

				<div class="slider-home slider-three col-xs-6 col-md-12">
					{!! \BannerLib::bannersPorKey('HISTORIAS_DRCH_2', 'banner_home_historias') !!}
				</div>

		</div>
	</div>
</div>






*/
?>
{!! \BannerLib::bannersPorUbicacionKeyAsClass('HOME', ["IMG_COMPLETA_HOME" => ['dots' => true, 'autoplay' => true, 'arrows' => false]]) !!}














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

/*
	var owlHome = $("#owl-carousel-responsive-home");

	// para que no se vea en vertical mientras carga
	owlHome.on('initialized.owl.carousel', function(event){
    	$("#owl-carousel-responsive-home").css('visibility', 'visible');
	});

	owlHome.owlCarousel({
		items: 9,
		autoplay: true,
		margin: 20,
		dots: false,
		nav: true,
		loop: true,
		checkVisible: false,
		responsiveClass: true,
		navText: ['<img src="themes/duran/img/back.svg">', '<img src="themes/duran/img/next.svg">'],
		responsive: {
			0: {
				items: 3
			},
			400:{
				items: 4
			},
			600: {
				items: 5
			},
			1000: {
				items: 9
			},
			1200: {
				items: 9
			},
		}
	});
*/
</script>
