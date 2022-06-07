@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
<link href="{{ Tools::urlAssetsCache('/css/default/galery.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/galery.css') }}" rel="stylesheet" type="text/css">
<div class="container">
	<div class="row">
		{{--
			<div class="col-xs-12 galTitle">
				<h1 class="titlePage-custom color-letter text-center">{{$auction->des_sub}}</h1>

			</div>
			--}}
	</div>
</div>

    @include('content.galery.galery')
@stop

