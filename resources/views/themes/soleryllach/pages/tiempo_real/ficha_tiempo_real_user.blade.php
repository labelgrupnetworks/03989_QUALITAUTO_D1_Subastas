@extends('layouts.tiempo_real')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@push('stylesheets')
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
@endpush

@section('content')

@include('includes.tr.tiempo_real_user.header')

@if(\Config::get("app.exchange"))
	<script src="{{ URL::asset('js/default/divisas.js') }}"></script>
@endif


@php
# Fecha hasta
$horah       = $data['subasta_info']->lote_actual->end_session;
$hastah      = substr($data['subasta_info']->lote_actual->end_session,0,10);
$hastah      = str_replace('-', '/', $hastah);
$fecha_finh  = $hastah.$horah;
$ministeryLicit = config('app.ministeryLicit', false);
$currency = null;
$divisas = null;

$withExchange = config('app.exchange', false);
if($withExchange) {
	$currency = new App\libs\Currency();
	$divisas = $currency->getAllCurrencies($data['js_item']['subasta']['currency']->name);
}

$auctionStatus = $data['subasta_info']->status;
$tiempo = $data['subasta_info']->lote_actual->start_session;

if($auctionStatus == 'stopped' || $auctionStatus == 'reload'){
	$tiempo =  $data['subasta_info']->reanudacion;
}

$isITP = Tools::isITPLot($data['subasta_info']->lote_actual->cod_sub, $data['subasta_info']->lote_actual->ref_asigl0);

@endphp

<script>
var ministeryLicit = @json($ministeryLicit);
const withExchange = '{{$withExchange}}';
var currency = (Boolean(withExchange)) ? @json($divisas) : null;

//solamente contiene el estado en la primera carga, no se actualiza
const initialAuctionStatus = '{{$auctionStatus}}';

$(function() {

	if(initialAuctionStatus == 'stopped' || initialAuctionStatus == 'reload'){
		$('body').addClass('tr_stop');
        $('.tiempo_real')[0].style.position = "fixed";
	}
	else if(initialAuctionStatus != 'in_progress') {
		$('.tiempo_real')[0].style.position = "fixed";
		$('body').addClass('tr_finished');
	}
	else {
		$('#clock, button.start').hide();
        $(".logo").show();
        $(".subasta h3").show();
		$('body').addClass('tr_progress');

        $('.started').removeClass('hidden');

        // si aun no esta iniciada se verá la imagen en grande
        $('.colimagen').addClass('col-lg-6');
        $('.colimagen').removeClass('col-lg-12');
	}

    $(document).ready(function() {
    	$(".tiempo").data('ini', new Date().getTime());
    	countdown_timer($(".tiempo"));
	});

	if(initialAuctionStatus == 'ended') {
		$('.tiempo').countdown('stop');
        $('.tiempo').html(messages.neutral.auction_end);
        $('button.start').hide();
	}
});
</script>

<div id="ficha" class="ficha_tr">

        @include('includes.tr.tiempo_real_user.clock')

        @include('includes.tr.tiempo_real_user.product')
        @include('includes.tr.tiempo_real_user.info')
        @include('includes.tr.tiempo_real_user.info_auction')
        @include('includes.tr.tiempo_real_user.streaming')

</div>

@stop
