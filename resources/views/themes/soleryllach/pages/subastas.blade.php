@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
	<main class="subastas-page">
    	@include('content.subastas')
	</main>
@stop
