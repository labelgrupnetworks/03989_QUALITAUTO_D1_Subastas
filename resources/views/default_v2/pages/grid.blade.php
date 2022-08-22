@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')

<div class="container grid-header">
	<div class="row">
		<div class="col-12">
			@include('includes.breadcrumb')
		</div>
		<div class="col-12">
			<h1>{{ $seo_data->h1_seo }}</h1>
		</div>
	</div>
</div>

    @include('content.grid')
@stop

