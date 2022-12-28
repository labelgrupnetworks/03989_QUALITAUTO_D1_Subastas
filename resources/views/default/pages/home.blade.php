

@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')

@include('includes.tiempo_real_btn')

@include('content.home')

@stop
