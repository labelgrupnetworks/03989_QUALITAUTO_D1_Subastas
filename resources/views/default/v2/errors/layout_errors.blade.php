@yield('http_error')

@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')

<div class="container py-5 my-5 text-center">
	<p class="fs-64 mb-3">
		@yield('error_code')
	</p>
	<h1>
		@yield('error_message')
	</h1>
	<a href="/{{\App::getLocale()}}">{{ trans($theme.'-app.global.go_home') }}</a>
</div>

@stop
