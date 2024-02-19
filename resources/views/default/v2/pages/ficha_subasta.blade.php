@extends('layouts.default')

@section('title')
{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')
@php
$bread = array();
$bread[] = array("url" => $data["url_bread"], "name" => $data["name_bread"] );
$bread[] = array( "name" =>$data['auction']->des_sub );
@endphp
<main>
	<div class="container auction-detail-header">
		<div class="row">
			<div class="col-12">
				@include('includes.breadcrumb')
			</div>
			<div class="col-12">
				<h1 class="titleSingle">{{ $data["auction"]->des_sub}}</h1>
			</div>
		</div>
	</div>

	@include('content.ficha_subasta')
</main>
@stop
