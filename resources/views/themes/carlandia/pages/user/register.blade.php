@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

@php
$esCedente = request()->has('transferor');
$jobs = array(
	'CONCESIONARIO' =>	'Concesionario',
	'COMPRAVENTA' =>	'Compraventa',
	'OTRA' =>	'Otra'
	);
@endphp

<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>

@if($esCedente)
	@include('front::pages.user.register_owner')
@else
	@include('front::pages.user.register_buyer')
@endif

@stop
