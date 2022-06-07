@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.galery.artists') }}
@stop


@section('content')
<link href="{{ Tools::urlAssetsCache('/css/default/galery.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/galery.css') }}" rel="stylesheet" type="text/css">
<div class="container">
	<div class="row">

			<div class="col-xs-12 galTitle">
				<h1 class="titlePage-custom color-letter text-center">{{ trans(\Config::get('app.theme').'-app.galery.gallery_collection') }}</h1>


			</div>
			<div class="col-xs-12 textPage-custom">
				{!!  nl2br(trans(\Config::get('app.theme').'-app.galery.texto_gallery_collection')) !!}
			</div>
			<div class=" col-xs-12 searchExhibitions">
				<form id="fromSearchExhibitions" >
					<input  type="text" id="searchExhibitions_JS"  name="search" value="{{request("search")}}" size="30" maxlength="128">
					<button type="submit">	<i class="fa fa-search" aria-hidden="true"></i></button>
				</form>

			</div>

	</div>
</div>


<div class="container ">
	<div class="row ">

			<div class="gridArtists">
					@foreach($artists as $artist)
					@php
						$url = Route("artistaFondoGaleria",["id_artist" => $artist->id_artist]);
						$img = \Tools::url_img("square_medium", $artist->num_hces1, $artist->lin_hces1, null, true);
					@endphp
						@include('includes.galery.artist')
					@endforeach
				</div>

	</div>
</div>
	<script>
		$("#searchExhibitions_JS").keydown(function(e){
			if (e.keyCode == 13) {
				$("#fromSearchExhibitions").submit();
			}
		})


	</script>
@stop
