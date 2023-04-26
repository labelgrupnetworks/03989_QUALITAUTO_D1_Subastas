

@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
@if(\Config::get('app.linkTiempoRealHome'))
	@include('includes.tiempo_real_btn')
@endif
@include('content.home')

@stop
