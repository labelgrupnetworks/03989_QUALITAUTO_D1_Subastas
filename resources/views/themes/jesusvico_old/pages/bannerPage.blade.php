@extends('layouts.default')

@section('seo')
@php
	$data['seo'] = new \stdClass();
	$data['seo']->meta_title = trans(\Config::get('app.theme').'-app.metas.title_'. $data['banner']);
	$data['seo']->meta_description = trans(\Config::get('app.theme').'-app.metas.description_' . $data['banner']);
@endphp
@endsection

@section('title')
{{ $data['name_web_page'] }}
@stop

@section('content')

<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 color-letter titlepage-contenidoweb">
			<h1 class="titlePage"> {{ $data['name_web_page'] }}</h1>
		</div>
	</div>
</div>

<div id="content-page-banner" class="content-page-banner mb-5">

	{!! \BannerLib::bannersPorKey($data['banner'], $data['banner'], ['dots' => false]) !!}

</div>


@stop
