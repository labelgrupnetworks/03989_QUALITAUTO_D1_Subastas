@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
// login es la págian antigua y si cierran sesion estando en una página que requiere estar logeado redirige a esta página  ?>
	header("Location: " . URL::to(route('register', ['lang' => Config::get('app.locale')])), true, 301);
	exit();
@endphp
