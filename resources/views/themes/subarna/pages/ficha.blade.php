@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.css') }}" />

<script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/node_modules/socket.io-client/socket.io.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.min.js') }}"></script>

@if(strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'O' || strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'P' || $data['subasta_info']->lote_actual->subabierta_sub == 'P')
<script src="{{ Tools::urlAssetsCache('/vendor/tiempo-real/tr_main.js') }}"></script>
<script src="{{ URL::asset('js/hmac-sha256.js') }}"></script>
@endif

<script src="{{ URL::asset('js/openseadragon.min.js') }}"></script>
<script src="{{ URL::asset('vendor/jquery-print/jQuery.print.js') }}"></script>

<script>
var auction_info = $.parseJSON('<?php  echo str_replace("\u0022","\\\\\"",json_encode($data["js_item"],JSON_HEX_QUOT)); ?>');
var cod_sub = '{{$data['subasta_info']->lote_actual->cod_sub}}';
var ref = '{{$data['subasta_info']->lote_actual->ref_asigl0}}';
var imp = '{{$data['subasta_info']->lote_actual->impsalhces_asigl0}}';

@php
//gardamos el código de licitador para saber is esta logeado o no y mostrar mensaje de que debe logearse
 if (!empty($data['js_item']) && !empty($data['js_item']['user']) && !empty($data['js_item']['user']['cod_licit'])   )
 {
     $cod_licit ="'". $data['js_item']['user']['cod_licit']."'";
 }else{
     $cod_licit ="null";
 }
@endphp

var cod_licit = <?=$cod_licit?>;
routing.node_url 	 = '{{ Config::get("app.node_url") }}';
routing.comprar		 = '{{ $data["node"]["comprar"] }}';
routing.ol		 = '{{ $data["node"]["ol"] }}';
routing.favorites	 = '{{ Config::get('app.url')."/api-ajax/favorites" }}';

$(document).ready(function() {
	$('.add_bid').removeClass('loading');
});
</script>

@php
	$lote_actual = $data['subasta_info']->lote_actual;
@endphp

<main class="ficha-page">
	@include('content.ficha')
</main>
@stop
