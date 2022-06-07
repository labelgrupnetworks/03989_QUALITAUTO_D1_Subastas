@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')

<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 text-center">
			<?php //Si quieren mostrar nombre de la subasta o que se vea texto Lotes ?>

				{{-- <h1 class="titlePage-custom color-letter text-center">{{$seo_data->h1_seo}}</h1> --}}

			{{-- @include('includes.breadcrumb') --}}
		</div>
	</div>
</div>

    @include('content.grid')
@stop

