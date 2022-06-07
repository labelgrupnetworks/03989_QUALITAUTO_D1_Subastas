@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('assets_components')
<link href="{{ Tools::urlAssetsCache('/css/default/grid.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/grid.css') }}" rel="stylesheet" type="text/css">
@endsection


@section('content')

<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12">


			<?php
			$bread = array();
			if($filters['typeSub'] == 'P'){
				$urlAllCategories =  route("allCategories", ['typeSub'=>'P']);
				$bread[] = array("url" =>$urlAllCategories, "name" => trans(\Config::get('app.theme').'-app.foot.online_auction') );
			}
			?>

			@include('includes.breadcrumb')

			<?php //Si quieren mostrar nombre de la subasta o que se vea texto Lotes ?>

				<h1 class="titlePage-custom color-letter">{{$seo_data->h1_seo}}</h1>


		</div>
	</div>
</div>

    @include('content.grid')
@stop

