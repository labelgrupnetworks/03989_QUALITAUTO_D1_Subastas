@extends('layouts.default')

@section('seo')
@php
	$data['seo'] = new \stdClass();
	$data['seo']->meta_title = trans(\Config::get('app.theme').'-app.foot.direct_sale_art');
	$data['seo']->meta_description = trans(\Config::get('app.theme').'-app.foot.direct_sale_art');
@endphp
@endsection

@section('title')
{{ $data['name_web_page'] }}
@stop

@section('content')

<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 color-letter titlepage-contenidoweb">
			<h1 class="titlePage"> {{ trans(\Config::get('app.theme').'-app.foot.direct_sale_art') }}</h1>
		</div>
	</div>
</div>

<div id="content-page-banner" class="content-page-banner mb-3">
	{!! \BannerLib::bannersPorKey('adjudicacion-arte-1', $data['banner'], ['dots' => false]) !!}
</div>
<div id="content-page-banner" class="content-page-banner mb-3">
	{!! \BannerLib::bannersPorKey('adjudicacion-arte-2', $data['banner'], ['dots' => false]) !!}
</div>
<div id="content-page-banner" class="content-page-banner mb-3">
	{!! \BannerLib::bannersPorKey('adjudicacion-arte-3', $data['banner'], ['dots' => false]) !!}
</div>
<div id="content-page-banner" class="content-page-banner mb-3">
	{!! \BannerLib::bannersPorKey('adjudicacion-arte-4', $data['banner'], ['dots' => false]) !!}
</div>

@stop
