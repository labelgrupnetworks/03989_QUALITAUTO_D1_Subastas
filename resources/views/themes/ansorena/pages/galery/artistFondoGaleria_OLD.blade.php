@extends('layouts.default')

@section('title')
{{ trans($theme.'-app.galery.exhibitions') }}
@stop


@section('content')
<link href="{{ Tools::urlAssetsCache('/css/default/galery.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/galery.css') }}" rel="stylesheet" type="text/css">
<div class="container">
	<div class="row">

			<div class="col-xs-12 galTitle">
				<h1 class="titlePage-custom color-letter text-center">{{ $artist->name_artist }}</h1>


			</div>
	</div>
</div>

@php

@endphp



<div class="container ">
	<div class="row ">
		<div class="sliderMainFondoGaleria owl-theme owl-carousel">
			@foreach($lots as $lot)
			<div   >
				<div><img src="{{\Tools::url_img("square_large", $lot->num_hces1, $lot->lin_hces1, null, true)}}" ></div>
				<div class="infoLotFondoGaleria text-right">
					<strong>{{ $lot->descweb_hces1 }} </strong>
					<br>
					{{-- mostramos la tecnica --}}
					{!! $features[$lot->num_hces1."_".$lot->lin_hces1][2]?? "" !!}
					 |
					{{-- mostramos las medidas --}}
					{!! $features[$lot->num_hces1."_".$lot->lin_hces1][3]?? "" !!}
				</div>
			</div>

			@endforeach
		</div>
		<div  class="sliderthumbGaleria owl-theme owl-carousel">
			@foreach($lots as $lot)
			<div  class="sliderObraGaleria"  >
				<img src="{{\Tools::url_img("square_medium", $lot->num_hces1, $lot->lin_hces1, null, true)}}" >
			</div>

			@endforeach
		</div>


	</div>
</div>

<script>
	$(".sliderMainFondoGaleria").slick({
		slidesToShow: 1,
  		slidesToScroll: 1,
 		asNavFor: '.sliderthumbGaleria'
	});

$(".sliderthumbGaleria").slick({
	slidesToShow: 6,
 	slidesToScroll: 6,
	arrows: true,
	focusOnSelect: true,
	asNavFor: '.sliderMainFondoGaleria',
	responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 4,
					slidesToScroll: 4,

				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,


				}
			}

		]
});


function carrouselFondoGaleria(carrousel) {
	if (carrousel.data('hasSlick')) {
		carrousel.slick('unslick');
	}

	var rows = 1;


	carrousel.slick({
		slidesToScroll: 1,
		rows: rows,
		/*slidesPerRow: 4,*/
		slidesToShow: 1,
		arrows: true,
		swipeToSlide: true,
	/*	prevArrow: $('.owl-prev'),
		nextArrow: $('.owl-next'),
		*/
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 4,
					slidesToScroll: 4,
					infinite: true,
					dots: true,
					rows: 1,
					slidesPerRow: 4,
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
					rows: 1,
					slidesPerRow: 2,

				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					rows: 1,
					slidesPerRow: 1,
				}
			}

		]
	});

	carrousel.data('hasSlick', true);
}
</script>

@stop
