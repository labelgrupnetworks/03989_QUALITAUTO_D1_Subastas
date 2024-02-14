@extends('layouts.default')

@section('title')
{{ trans($theme.'-app.artist.artists') }}
@stop

@php
	$artistsIds = $artists->pluck('id_artist');
	$lotsByArtist = \App\Models\V5\FgAsigl0::select('IDVALUE_CARACTERISTICAS_HCES1, count(REF_ASIGL0) as lots')->activeLotAsigl0()->joinFgCaracteristicasAsigl0()->joinFgCaracteristicasHces1Asigl0()
		->whereIn("IDVALUE_CARACTERISTICAS_HCES1", $artistsIds)->groupBy('idvalue_caracteristicas_hces1')->pluck('lots', 'idvalue_caracteristicas_hces1');
@endphp

@section('content')
<div class="container mb-3">
	<div class="row">
		<div class="col-xs-12 text-center">
			<h1 class="titlePage title-80 bold my-4">{{trans($theme.'-app.artist.artists')}}</h1>
		</div>
	</div>

	<div class="row d-flex flex-wrap artists-container">

		@foreach($artists as $artist)
		@include("includes.artists.artist")
		@endforeach

	</div>

	<div class="row">
		<div class="col-xs-12 d-flex justify-content-center artists-container">
			{{ $artists->links() }}
		</div>
	</div>

</div>
@stop
