@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@push('stylesheets')
<link href="{{ Tools::urlAssetsCache('/css/default/grid.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/grid.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')

<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 text-center mb-3">
			<?php //Si quieren mostrar nombre de la subasta o que se vea texto Lotes ?>

				<h1 class="titlePage-custom color-letter text-center">{{$seo_data->h1_seo}}</h1>


		</div>
	</div>
</div>

    @include('content.grid')
@stop

