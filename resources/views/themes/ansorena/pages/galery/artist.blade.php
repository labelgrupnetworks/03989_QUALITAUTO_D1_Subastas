@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.galery.exhibitions') }}
@stop


@section('content')
<link href="{{ Tools::urlAssetsCache('/css/default/galery.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/galery.css') }}" rel="stylesheet" type="text/css">
<div class="container">
	<div class="row">

			<div class="col-xs-12 galTitle">
				<h1 class="titlePage-custom color-letter text-center">{{ $artist->name_artist }}</h1>


			</div>
	</div>
</div>

   @php
$auctions=[];
#cojer todos los primeros lotes para poder sacar las iamgenes.
foreach($exhibitions as $exhibition){
		#código de subasta para buscar el lote
		$auctions[]=$exhibition->cod_sub;
	}
$fgasigl0 = new  App\Models\V5\FgAsigl0 ;
	$lots = $fgasigl0->select("cod_sub, numhces_asigl0, linhces_asigl0")->JoinSubastaAsigl0()->where("ref_asigl0",1)->wherein("cod_sub",$auctions)->get();
	$imgSubastas = [];
	foreach ($lots as $lot){
		$imgSubastas[$lot->cod_sub] = \Tools::url_img("square_medium", $lot->numhces_asigl0, $lot->linhces_asigl0, null, true);
	}

@endphp



<div class="container ">
	<div class="row ">
		<div class="gridExhibitions">
			@foreach($exhibitions as $exhibition)
				@include('includes.galery.exhibition')
			@endforeach
		</div>
	</div>
</div>


@stop
