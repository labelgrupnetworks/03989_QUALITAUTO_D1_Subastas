@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

@php
	$openLogin = request()->has('openLogin') ? ['openLogin' => 'S'] : [];
	header("Location: " . "https://www.tauleryfau.com" , true, 302);
	exit();
@endphp

@stop
