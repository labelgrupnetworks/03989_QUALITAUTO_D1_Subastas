{{-- Baners de home --}}
<div class="banner-container">
	{!! \BannerLib::bannersPorUbicacionKeyAsClass('HOME', [
	    'home-large' => ['dots' => false, 'autoplay' => true, 'arrows' => false, 'autoplaySpeed' => 1500],
	]) !!}
</div>

{{-- INICIO CONTENIDO IRIS DEL MUNDO --}}

<style>
	.btn-white {
		background-color: #fff;
		color: #000;
		padding: 10px 20px;
		text-decoration: none;
		display: inline-block;
		font-size: 16px;
		margin: 4px 2px;
		cursor: pointer;
		border-radius: 15px;
		transition: background-color 0.2s ease-in-out;
	}

	.btn-white:hover {
		background-color: rgb(100, 100, 100);
		color: #fff;
		text-decoration: none;
	}

	.irises-content,
	.irises-home-container .btn-container {
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: column;
		text-align: center;
	}

	.irises-home-container h4 {
		font-weight: 400;
		line-height: 30px;
		width: 74ch;
	}

	/* Hacer una media query de que si la pantalla es más pequeña de 512 px cambiar el ancho del .irises-home-container h4 por auto*/
	@media (max-width: 512px) {
		.irises-home-container h4 {
			width: auto;
		}
	}

	.font-weight-200 {
		font-weight: 200;
	}
</style>

<script>
	window.onload = function() {
		if (window.innerWidth < 512) {
			document.querySelector('.irises-home-container img').src = '/themes/durannft/img/bardem-mobile.png';
		}
	}
</script>

{{-- Obtener el enlace y comprobar si en el enlace hay 'es' --}}

@if (\Config::get('app.locale') == 'es')
	<div class="container irises-home-container mt-3 mb-3">
		<div class="row">
			<div class="col-xs-12">
				<h2 class="text-center"><span class="font-weight-200">Empezamos el 29 de septiembre a las 12h <br>con el iris de
					</span><strong>Javier Bardem</strong></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<img class="img-responsive mt-5 mb-5" src="/themes/durannft/img/bardem-desktop.png">
			</div>
		</div>
		<div class="irises-content">
			<h2><strong>OJOS QUE ABREN OTROS OJOS</strong></h2>
			<h4>Ahora tú puedes conseguir la foto única de su iris y, al mismo tiempo, conseguir que muchas personas vulnerables
				recuperen la visión.</h4>
		</div>
		<div class="btn-container mt-4">
			<a class="btn btn-white" href="/es/lote/Iris-2741-2741/2-138-bardems-iris">INSCRÍBETE A LA SUBASTA</a>
		</div>
	</div>
@else
	<div class="container irises-home-container mt-3 mb-3">
		<div class="row">
			<div class="col-xs-12">
				<h2 class="text-center"><span class="font-weight-200">We start on September 29 at 12:00 p.m. <br>with the iris of
					</span><strong>Javier Bardem</strong></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<img class="img-responsive mt-5 mb-5" src="/themes/durannft/img/bardem-desktop.png">
			</div>
		</div>
		<div class="irises-content">
			<h2><strong>EYES THAT OPEN OTHER EYES</strong></h2>
			<h4>Now you can get the unique photo of your iris and, at the same time, get many vulnerable people
				regain vision.</h4>
		</div>
		<div class="btn-container mt-4">
			<a class="btn btn-white" href="/en/lote/Iris-2741-2741/2-138-bardems-iris">GO TO AUCTION</a>
		</div>
	</div>
@endif



{{-- FIN INICIO CONTENIDO IRIS DEL MUNDO --}}

{{-- ultimas obras
<div class="container mt-3 mb-3">
	<div class="row">
		<div id="ultimas-obras-container" class="col-xs-12 ultimas-obras-container">
			<div class="row">

				<div class="col-md-12 mb-2">

					<div class="ultimas-obras-texts ultimas-obras-text-flex">
						<div>
							<p class="title-30">ÚLTIMAS</p>
							<p class="title-40">OBRAS</p>
						</div>
						<div class="ultimas-obras-buttons">
							<div class="mb-1">
								<a href="{{ route('artists') }}" class="custom-button">VER TODAS</a>
							</div>
							<div class="custom-arrows-wrapper">
								<button class="custom-arrow prev" aria-label="Previous" type="button"><i class="fa fa-long-arrow-left"></i></button>
								<button class="custom-arrow next" aria-label="Next" type="button"><i class="fa fa-long-arrow-right"></i></button>
							</div>
						</div>

					</div>

				</div>

				<div class="col-md-12">
					<div class="text-center">
						<div class="lds-ellipsis loader">
							<div></div>
							<div></div>
							<div></div>
							<div></div>
						</div>
						<div class="owl-theme owl-carousel text-left" id="ultimos_lotes"></div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div> --}}

@php
$replace = ['lang' => \Tools::getLanguageComplete(Config::get('app.locale')), 'emp' => Config::get('app.emp')];
$lang = config('app.locale');
@endphp

<script>
	var replace = @json($replace);
	var key = "ultimos_lotes";
	var lang = @json($lang);

	$(document).ready(function() {
		ajax_newcarousel(key, replace, lang);
	});
</script>


{{-- mas banners ? --}}


{{-- Blog --}}
{{-- <section class="container blog-section mt-2">
	<h1 class="title-30 text-center text-uppercase">{{ trans("$theme-app.home.blog") }}</h1>

	<h2 class="title-40 text-center mt-1">{{ trans("$theme-app.blog.last_post") }}</h2>

	<div class="blog-wrapper row mt-3">
		@foreach (collect(\Tools::getWorpressRss('https://noticias.durangallery.com/feed/'))->take(4) as $post)
		<div class="col-xs-12 col-md-6 p-3">

			<div class="blog-content">
				<div class="line-post-item"></div>

				<div class="post-title post-title-custom">
					<h3>
						<a class="post-title-link" href="{{$post[ 'link'] }}" target="_blank">
							{{ $post['title'] }}
						</a>
					</h3>
				</div>

				<div class="post-content">
					{!! strip_tags($post['description']) !!}
				</div>

				<div class="read-more-button">
					<a href="{{$post[ 'link'] }}" title="Leer más" class="custom-button text-muted">
						<span>{{ trans("$theme-app.blog.more") }}</span>
					</a>
				</div>
			</div>

		</div>
		@endforeach
	</div>
</section> --}}



{{-- Instagram feed --}}
{{-- Es necesaria una conexion a la nueva api para poder mostrar esto --}}
{{-- <section class="instagram-section mt-2 mb-2 d-flex align-items-center justify-content-center flex-direction-column" style="height: 500px; background-color: #e2e2e2; font-size: 34px;">
		<a href="" class="color-black"><i class="fa fa-instagram"></i></a>
		<a href="" class="color-black"><p>duranonlinegallery</p></a>
		<p>(*pendiente*)</p>
</section> --}}
