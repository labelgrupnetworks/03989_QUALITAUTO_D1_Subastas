@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

<section class="py-sm-5 my-5">
	<div class="container py-sm-5">
		<h1 class="not-logged-text fs-64 text-lg-center">{{$data}}</h1>
	</div>
</section>


@stop













