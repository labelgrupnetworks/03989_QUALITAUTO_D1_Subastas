
{{-- Baners de home --}}
<div class="banner-container">
	{!! \BannerLib::bannersPorUbicacionKeyAsClass('HOME',["home-large" => ['dots' => false, 'autoplay' => true, 'arrows' => false]]) !!}
</div>



{{-- Slider de artistas --}}
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			@include('front::includes.home.artist_carrousel')
		</div>
	</div>
</div>


{{-- ultimas obras --}}
<div class="container mt-3 mb-3">
	<div class="row">
		<div id="ultimas-obras-container" class="col-xs-12 ultimas-obras-container">
			<div class="row">

				<div class="col-md-3 col-md-push-9 mb-2">

					<div class="ultimas-obras-texts text-right">
						<p class="title-30 color-pink">ÚLTIMAS</p>
						<p class="title-40 color-black">OBRAS</p>
						<div class="ultimas-obras-buttons">
							<div class="mb-1">
								<a href="{{ route('artists') }}" class="custom-button">VER TODOS</a>
							</div>
							<div class="custom-arrows-wrapper">
								<button class="custom-arrow prev" aria-label="Previous" type="button"><i class="fa fa-long-arrow-left"></i></button>
								<button class="custom-arrow next" aria-label="Next" type="button"><i class="fa fa-long-arrow-right"></i></button>
							</div>
						</div>

					</div>

				</div>

				<div class="col-md-9 col-md-pull-3">
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
</div>



@php
	$replace = array('lang' => \Tools::getLanguageComplete(Config::get('app.locale')) ,'emp' => Config::get('app.emp'));
	$lang = config('app.locale');
@endphp

<script>
	var replace = @json($replace);
    var key ="ultimos_lotes";
	var lang = @json($lang);

    $( document ).ready(function() {
        ajax_newcarousel(key, replace, lang);
     });
</script>


{{-- mas banners ? --}}


{{-- Blog --}}
<section class="container blog-section mt-2">
	<h1 class="title-30 color-pink text-center text-uppercase">{{ trans("$theme-app.home.blog") }}</h1>

	<h2 class="title-40 color-black text-center mt-1">{{ trans("$theme-app.blog.last_post") }}</h2>

	<div class="blog-wrapper row mt-3">
		@foreach ( collect(\Tools::getWorpressRss('https://noticias.durangallery.com/feed/'))->take(4) as $post)
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
</section>



{{-- Instagram feed --}}
{{-- Es necesaria una conexion a la nueva api para poder mostrar esto --}}
{{-- <section class="instagram-section mt-2 mb-2 d-flex align-items-center justify-content-center flex-direction-column" style="height: 500px; background-color: #e2e2e2; font-size: 34px;">
		<a href="" class="color-black"><i class="fa fa-instagram"></i></a>
		<a href="" class="color-black"><p>duranonlinegallery</p></a>
		<p>(*pendiente*)</p>
</section> --}}
