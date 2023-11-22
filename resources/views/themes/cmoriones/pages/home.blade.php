

@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

@php

	header("Location: " . URL::to(route('allCategories'), true, 302));
	exit();

@endphp

@include('content.home')

@stop
