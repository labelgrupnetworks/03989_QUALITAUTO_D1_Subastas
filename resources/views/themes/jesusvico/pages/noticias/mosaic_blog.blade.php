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
				<h1 class="titlePage">{{trans(\Config::get('app.theme').'-app.blog.museum-pieces')}}</h1>
			</div>
		</div>
	</div>
</section>

<!-- Posts -->
<section class="post_content">
	<div class="container">

		<div class="row mosaic-container">

			@foreach ($banners as $banner)

			<div class="post-container">

				<div class="title-mosaic text-center">
					<p>{{$banner->descripcion}}</p>
				</div>

				<div class="img-mosaic">
					<img alt="{{$banner->descripcion}}" class="img-responsive img-blog"
							src="{{$banner->url_image}}" class="img-mosaic">
				</div>

				<div class="description-mosaic text-center mt-1">
					{!! strip_tags($banner->texto) !!}
				</div>

			</div>

			@endforeach

		</div>

	</div>
</section>


<!-- The Modal -->
<div id="mosaic-modal" class="mosaic-modal">
	<span class="mosaic-close">&times;</span>
	<img class="modal-mosaic-content img-responsive" id="img-modal">
	<div id="mosaic-caption"></div>
</div>

<script>

    $(document).ready(function(){
        $('.resumen').each(function (){
            var str = $(this).text();
            var res = str.replace("[*CITA*]","");
            var str = $(this).text(str);
        });

    });

</script>


@stop
