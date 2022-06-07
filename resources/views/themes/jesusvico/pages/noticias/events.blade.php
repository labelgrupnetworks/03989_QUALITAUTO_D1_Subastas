@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('seo')
@php
	$data['seo'] = new \stdClass();
	$data['seo']->meta_title = trans(\Config::get('app.theme').'-app.metas.title_events');
	$data['seo']->meta_description = trans(\Config::get('app.theme').'-app.metas.description_events');
@endphp
@endsection

@section('content')
<style>
	@import url('https://fonts.googleapis.com/css?family=Noto+Serif+KR:400,500,700');
</style>

<!-- titlte & breadcrumb -->
<section>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 text-center color-letter titlepage-contenidoweb" style="margin-bottom: 0">
				<h1 class="titlePage">{{trans(\Config::get('app.theme').'-app.blog.events')}}</h1>
			</div>
		</div>
	</div>
</section>

<!-- Posts -->
<section class="post_content">
	<div class="container">

		<div class="row events-container">

			@foreach ($banners as $banner)
			<a href="/{{Config::get('app.locale')}}/events/{{ $banner->id }}">
				<div class="post-container">

					<div class="title-event text-center">
						<p>{{$banner->descripcion}}</p>
					</div>

					<div class="img-event">
						<img alt="{{$banner->descripcion}}" class="img-responsive img-blog"
							src="{{$banner->url_image}}">
					</div>

					<div class="button-post">
						<p>{{ trans(\Config::get('app.theme').'-app.lot.see_more') }}</p>
					</div>

				</div>
			</a>
			@endforeach

		</div>

	</div>
</section>
@stop
