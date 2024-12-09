@extends('layouts.default')

@section('title')
	{{ $data['data']->name_web_page }}
@stop

@section('content')
@php
	$bread[] = array("name" =>$data['data']->name_web_page);
@endphp

<main>
	<div class="container">
		@include('includes.breadcrumb')
		<h1>{{ $data['data']->name_web_page }}</h1>
	</div>

	<div class="container mt-4">
		<div id="pagina-{{ $data['data']->id_web_page }}" class="contenido contenido-web static-page">
			{!! $data['data']->content_web_page !!}
		</div>
	</div>
</main>

@stop

