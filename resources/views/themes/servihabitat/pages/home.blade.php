

@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

@if(!Session::has('user'))
@php
	header("Location: " . \URL::to(route('register', ['lang' => \Config::get('app.locale')])), true, 302);
    exit();
@endphp
@endif

@include('content.home')

@stop
