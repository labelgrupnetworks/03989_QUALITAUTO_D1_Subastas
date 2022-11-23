

@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')

@include('includes.pujar_lote_btn')

@include('content.home')

@stop
