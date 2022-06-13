@extends('layouts.default')


@php
 $lote_actual = $data['subasta_info']->lote_actual;

	if($lote_actual->tipo_sub == 'O'){
		$data['seo']->h1_seo = "Coche <strong>". 	$lote_actual->descweb_hces1. "</strong> en Subasta Online" ;
		$data['seo']->meta_title = "Coche ". 	$lote_actual->descweb_hces1 ." en Subasta | Carlandia";
		$data['seo']->meta_description = "Hazte con un increíble ". 	$lote_actual->descweb_hces1 ." de ocasión al mejor precio a través de nuestro innovador sistema de pujas para particulares.";
	}
	elseif($lote_actual->tipo_sub == 'V'){
		$data['seo']->h1_seo = "Coche <strong>".  	$lote_actual->descweb_hces1.  "</strong> en Venta Directa" ;
		$data['seo']->meta_title = "Coche ". 	$lote_actual->descweb_hces1 ." en oferta| Carlandia";
		$data['seo']->meta_description = "Hazte con un increíble ". 	$lote_actual->descweb_hces1. " de ocasión al mejor precio, somos especialistas en venta directa a particulares. ¡No esperes más!";
	}

@endphp

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.css') }}" />

@if (config('app.socket_v4', 0))
<script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/client-dist/socket.io.js') }}"></script>
@else
<script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/node_modules/socket.io-client/socket.io.js') }}"></script>
@endif

<script src="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.min.js') }}"></script>
@if(strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'O' || strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'P' || $data['subasta_info']->lote_actual->subabierta_sub == 'P')

<script src="{{ Tools::urlAssetsCache('/vendor/tiempo-real/tr_main.js') }}"></script>
<script src="{{ URL::asset('js/hmac-sha256.js') }}"></script>

@endif
<script src="{{ URL::asset('js/openseadragon.min.js') }}"></script>

<script src="{{ URL::asset('vendor/jquery-print/jQuery.print.js') }}"></script>

@if(\Config::get("app.exchange"))
	<script src="{{ URL::asset('js/default/divisas.js') }}"></script>
@endif

<script>
var auction_info = $.parseJSON('<?php  echo str_replace("\u0022","\\\\\"",json_encode($data["js_item"],JSON_HEX_QUOT)); ?>');

@if(\Config::get("app.exchange"))
	var currency = $.parseJSON('<?php  echo str_replace("\u0022","\\\\\"",json_encode($data["divisas"],JSON_HEX_QUOT)); ?>');
@endif

var cod_sub = '{{$data['subasta_info']->lote_actual->cod_sub}}';
var ref = '{{$data['subasta_info']->lote_actual->ref_asigl0}}';
var imp = '{{$data['subasta_info']->lote_actual->impsalhces_asigl0}}';
<?php
//gardamos el código de licitador para saber is esta logeado o no y mostrar mensaje de que debe logearse
 if (!empty($data['js_item']) && !empty($data['js_item']['user']) && !empty($data['js_item']['user']['cod_licit'])   )
 {
     $cod_licit ="'". $data['js_item']['user']['cod_licit']."'";
 }else{
     $cod_licit ="null";
 }
?>
var cod_licit = <?=$cod_licit?>;
routing.node_url 	 = '{{ Config::get("app.node_url") }}';
routing.comprar		 = '{{ $data["node"]["comprar"] }}';
routing.ol		 = '{{ $data["node"]["ol"] }}';
routing.favorites	 = '{{ Config::get('app.url')."/api-ajax/favorites" }}';

$(document).ready(function() {
	$('.add_bid').removeClass('loading');
});

</script>
<?php

	$caracteristicas = App\Models\V5\FgCaracteristicas_Hces1::getByLot($lote_actual->num_hces1, $lote_actual->lin_hces1);
    if(!empty($lote_actual)){


        $bread = array();
        $bread[] = array("url" => $lote_actual->url_subasta, "name" =>$lote_actual->title_url_subasta  );
        if(!empty($data['seo']->meta_title)){
            $bread[] = array( "name" => $data['seo']->meta_title );
        }else{
            $bread[] = array( "name" => $lote_actual->titulo_hces1 );
        }
    }


?>

	<div class="container next-last-ficha">
            <div class="row">
                <div class="col-xs-12 col-sm-12 color-letter">
                    @include('includes.breadcrumb_before_after')
                </div>
            </div>
        </div>
    @include('content.ficha')
@stop
