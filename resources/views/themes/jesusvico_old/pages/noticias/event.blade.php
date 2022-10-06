@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<style>
	@import url('https://fonts.googleapis.com/css?family=Noto+Serif+KR:400,500,700');
</style>

<!-- titlte & breadcrumb -->
<section>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 text-center color-letter titlepage-contenidoweb" style="margin-bottom: 0">
				<h1 class="titlePage">{{ $banner->descripcion }}</h1>
			</div>
		</div>
	</div>
</section>

<!-- Posts -->
<section class="post_content">
	<div class="container">

		<div class="row event-container">

				<div class="popup-gallery">
				@foreach ($banner->images as $id => $text)
					<div class="col-xs-4 mb-5">
						<a href="/img/banner/{{\Config::get('app.theme')}}/{{\Config::get('app.emp')}}/{{$banner->id}}/{{$id}}/ES.jpg" title="{{$text}}">
							<img class="event-popup-img" src="/img/banner/{{\Config::get('app.theme')}}/{{\Config::get('app.emp')}}/{{$banner->id}}/{{$id}}/ES.jpg" alt="{{$text}}">
						</a>
					</div>
				@endforeach
				</div>

		</div>

	</div>
</section>

{{--
IMPORTANTE!
Para cambiar la velocidad de las animaciones en las transiciones, solo es posible hacerlo por css
ej.
.event-container .owl-carousel .owl-item {
    -webkit-animation-duration: 2s !important;
    animation-duration: 2s !important;
}
--}}

{{-- ------------ Carrousel antiguo de fotos ------------ --}}
{{-- <section class="post_content">
	<div class="container">

		<div class="row event-container">
			<div class="col-xs-8 col-xs-offset-2">

				<div class="owl-carousel owl-theme">
				@foreach ($banner->images as $id => $text)

					<div>
						<img src="/img/banner/{{\Config::get('app.theme')}}/{{\Config::get('app.emp')}}/{{$banner->id}}/{{$id}}/ES.jpg" alt="">
						<p>{{$text}}</p>
					</div>

				@endforeach
				</div>

			</div>
		</div>

	</div>
</section> --}}

<script>

$(document).ready(function() {
	$('.popup-gallery').magnificPopup({
		delegate: 'a',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
			titleSrc: function(item) {
				return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';
			}
		}
	});
});

/* let timeToChange = 4000;
$(document).ready(function(){


	owl = $(".owl-carousel").owlCarousel(getNewProperties());

	owl.on('translated.owl.carousel', function(e) {

		var itemsCount = e.item.count + 1;
		var index = e.item.index;

		if(itemsCount == index){
			setTimeout(reinitializeOwl, timeToChange);
		}
  	});

});

function reinitializeOwl(){
	$(".owl-carousel").data('owl.carousel').destroy();
	owl = $(".owl-carousel").owlCarousel(getNewProperties());
}


function getNewProperties(){

	let animationsIn = ['bounceIn', 'bounceInDown', 'bounceInLeft', 'bounceInRight', 'bounceInUp', 'fadeIn', 'fadeInDown', 'fadeInDownBig', 'fadeInLeft', 'fadeInLeftBig', 'fadeInUp'];
	let animationsOut = ['bounceOut', 'bounceOutDown', 'bounceOutLeft', 'bounceOutRight', 'bounceOutUp', 'fadeOut', 'fadeOutDown', 'fadeOutDownBig', 'fadeOutLeft', 'fadeOutLeftBig', 'fadeOutUp'];
	let properties = {
		animateOut: animationsOut[Math.floor(Math.random() * animationsOut.length)],
    	animateIn: animationsIn[Math.floor(Math.random() * animationsIn.length)],
    	items:1,
		smartSpeed:450,
		autoplay:true,
		autoplayTimeout: timeToChange,
		loop: true,
		autoHeight:true
	  };
	return properties;
} */
</script>

@stop
