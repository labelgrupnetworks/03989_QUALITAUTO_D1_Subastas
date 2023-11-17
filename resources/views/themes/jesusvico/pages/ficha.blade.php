@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@push('stylesheets')
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.css') }}" />
<link rel="stylesheet" type="text/css" href="/css/hint.css" >
@endpush

@push('scripts')
<script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/node_modules/socket.io-client/socket.io.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.min.js') }}"></script>

@if(strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'M' || strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'I' || strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'O' || strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'P' || $data['subasta_info']->lote_actual->subabierta_sub == 'P')
	@if(strtotime("now") < strtotime($data['subasta_info']->lote_actual->start_session))
	<script src="{{ Tools::urlAssetsCache('/vendor/tiempo-real/tr_main.js') }}"></script>
	<script src="{{ URL::asset('js/hmac-sha256.js') }}"></script>
	@endif
@endif

<script defer src="{{ Tools::urlAssetsCache('/vendor/openseadragon/openseadragon.js') }}"></script>

@if(\Config::get("app.exchange"))
	<script src="{{ URL::asset('js/default/divisas.js') }}"></script>
@endif

@endpush


@section('content')

<script>
var auction_info = @json($data["js_item"]);

@if(\Config::get("app.exchange"))
	var currency = @json($data["divisas"]);
@endif

var cod_sub = '{{$data['subasta_info']->lote_actual->cod_sub}}';
var ref = '{{$data['subasta_info']->lote_actual->ref_asigl0}}';
var imp = '{{$data['subasta_info']->lote_actual->impsalhces_asigl0}}';

@php
//gardamos el código de licitador para saber is esta logeado o no y mostrar mensaje de que debe logearse
 if (!empty($data['js_item']) && !empty($data['js_item']['user']) && !empty($data['js_item']['user']['cod_licit'])   )
 {
     $cod_licit = $data['js_item']['user']['cod_licit'];
 }else{
     $cod_licit = 'null';
 }
@endphp

var cod_licit = {{$cod_licit}};
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

	$titleName = match (true /* $auction->subc_sub */) {
		$lote_actual->tipo_sub === App\Models\V5\FgSub::TIPO_SUB_VENTA_DIRECTA => trans("$theme-app.subastas.store"),
		$lote_actual->subc_sub === App\Models\V5\FgSub::SUBC_SUB_HISTORICO => trans("$theme-app.subastas.previous_auctions"),
		$lote_actual->subc_sub === App\Models\V5\FgSub::SUBC_SUB_ACTIVO => trans("$theme-app.subastas.current_auction"),
		default => trans("$theme-app.subastas.inf_subasta_subasta"),
	};
@endphp
<main class="page-ficha">
	<div class="container grid-header">
		<div class="row">
			<div class="col-12">
				{{-- <p class="h1">{{ $titleName }} | <b><a href="{{ $lote_actual->url_subasta }}">{{ $lote_actual->title_url_subasta }}</a></b></p> --}}
				<p class="h1"><b><a href="{{ $lote_actual->url_subasta }}">{{ $lote_actual->title_url_subasta }}</a></b> | <a class="back-link" href="javascript:navigation.back();">{{ trans("$theme-app.global.back") }}</a>  </p>
			</div>
		</div>
	</div>

	@include('content.ficha')
</main>

@stop
