@extends('layouts.default')

@push('stylesheets')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.css" />
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
@endpush

@push('javascripts')
<script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
@endpush

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop


@section('content')
{{-- No deben mostarse subastas historicas --}}
@php
	if (strtoupper($data['subasta_info']->lote_actual->subc_sub) == 'H') {
		header("Location: " . \URL::to(\Routing::is_home()), true, 302);
        exit();
	}

	$lote_actual = $data['subasta_info']->lote_actual;
	$cod_licit = $data['js_item']['user']['cod_licit'] ?? null;

	$lote_actual->total_pujas = 0;
	$lote_actual->total_postores = 0;

	if (isset($lote_actual->pujas)) {
		$lote_actual->total_pujas = sizeof($lote_actual->pujas);
		$aux_postores = array();

		foreach ($lote_actual->pujas as $key => $value) {
			$aux_postores[$value->cod_licit] = $value->cod_licit;
		}

		$lote_actual->total_postores = sizeof($aux_postores);
	}

	/* Diferentes tipos e monedas */
    $divisas = (new App\libs\Currency)->getAllCurrencies();

	$searchUrl = Tools::url_lot_to_js($lote_actual->cod_sub, $lote_actual->id_auc_sessions, $lote_actual->ref_asigl0, $lote_actual->num_hces1);

    $bread = array(
        "url" => '',
        "name" => ''
    );

    if(!empty($lote_actual)){
		$bread["url"] = $lote_actual->url_subasta;
        $bread["name"] = ($lote_actual->tipo_sub == 'V')?trans($theme.'-app.global.go_shop'): trans($theme.'-app.global.go_auction');
    }
@endphp

<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.css') }}" />

<script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/node_modules/socket.io-client/socket.io.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.min.js') }}"></script>

@if(strtoupper($lote_actual->tipo_sub) == 'O' || strtoupper($lote_actual->tipo_sub) == 'P' || $lote_actual->subabierta_sub == 'P')
	<script src="{{ Tools::urlAssetsCache('vendor/tiempo-real/tr_main.js') }}"></script>
    <script src="{{ URL::asset('js/hmac-sha256.js') }}"></script>
@endif

<script src="{{ URL::asset('js/openseadragon.min.js') }}"></script>
<script src="{{ URL::asset('vendor/jquery-print/jQuery.print.js') }}"></script>

<script>
var auction_info = $.parseJSON('<?php  echo str_replace("\u0022","\\\\\"",json_encode($data["js_item"],JSON_HEX_QUOT)); ?>');

var cod_sub = '{{$lote_actual->cod_sub}}';
var ref = '{{$lote_actual->ref_asigl0}}';
var imp = '{{$lote_actual->impsalhces_asigl0}}';
var cod_licit = '{{ $cod_licit }}';
cod_licit = cod_licit.length ? cod_licit : null;

routing.node_url 	 = '{{ Config::get("app.node_url") }}';
routing.comprar		 = '{{ $data["node"]["comprar"] }}';
routing.ol		 = '{{ $data["node"]["ol"] }}';
routing.favorites	 = '{{ Config::get('app.url')."/api-ajax/favorites" }}';

var currency = $.parseJSON('<?php  echo str_replace("\u0022","\\\\\"",json_encode($divisas,JSON_HEX_QUOT)); ?>');

$(document).ready(function() {
	$('.add_bid').removeClass('loading');

	/* Mostrar divisas */
	$("#actual_currency").change(function(){
		updateCurrecy(auction_info.lote_actual);
		adjustImpsalexchangeWidth();
	})

	updateCurrecy(auction_info.lote_actual);
	adjustImpsalexchangeWidth();
	/* fin divisas */

	function updateCurrecy({impsalhces_asigl0, actual_bid, imptas_asigl0 }) {
		let actualCurrency = $("#actual_currency").val();

		changeCurrency(impsalhces_asigl0, actualCurrency, "impsalexchange");
		changeCurrency(actual_bid, actualCurrency, "impsalexchange-actual");
		changeCurrency(imptas_asigl0, actualCurrency, "impsalexchange-tas");

		changeCurrency(impsalhces_asigl0, actualCurrency, "impsalexchange2");
		changeCurrency(actual_bid, actualCurrency, "impsalexchange-actual2");
		changeCurrency(imptas_asigl0, actualCurrency,"impsalexchange-tas2");
	}

	/**
	* Aunque se ejecute, el display content del elemento evita que se modifique el width.
	* En el caso de realmente querer ajustar los dos elemento al mismo tamaño, se debe
	* modificar el display del elemento y es mejor obtener el offsetWidth del elemento
	* de referencia para modificar el width del elemento a modificar.
	*/
	function adjustImpsalexchangeWidth() {
		if($('#impsalexchange').length > 0  && $('#impsalexchange-actual').length > 0){
			if(!$('#impsalexchange-actual').hasClass('hidden') ){
				let sizeText = document.getElementById('impsalexchange-actual').innerText.length;
				$('#impsalexchange').css('width', `${sizeText}ch`);
			}
		}
	}
	});
</script>

<style>
@media (max-width: 370px){
    .bar-filters .input-lot {
        text-align: center;
        margin-top: 20px;
        display: block;
        -webkit-box-pack: center;-ms-flex-pack: center;justify-content: center;
    }
}
</style>
    <div id="ficha">
                <section class="principal-bar">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="principal-bar-wrapper flex valign">
						<div class="principal-bar-breadcumbs w-100">
							<div class="prev-lot hidden-md hidden-lg">
								<p class="prev-lot">
									@if(!empty($data['previous']))
									<a class="nextLeft" title="{{ trans($theme.'-app.subastas.last') }}"
										href="{{$data['previous']}}">
										<span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
										<span>{{ trans($theme.'-app.subastas.last') }}</span>
									</a>
									@endif
							</p>
							</div>
                            <p class="hidden-xs hidden-sm"><a title="Home" href="/">{{ trans($theme.'-app.subastas.breadcrumb') }}</a></p>
                            <span class="divVertical hidden-xs hidden-sm"></span>
                            <p><a href="{{$bread['url']}}">{{$bread['name']}}</a></p>

                            <span class="divVertical"></span>
							<p><a href="javascript:history.back()"> {{ trans($theme.'-app.global.go_home') }}</a></p>

							<div class="next-lot hidden-md hidden-lg">
								<p class="next-lot">
									@if(!empty($data['next']))
									<a class="nextRight" title="{{ trans($theme.'-app.subastas.next') }}"
										href="{{$data['next']}}">
										<span>{{ trans($theme.'-app.subastas.next') }}</span>
										<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
									</a>
									@endif
								</p>
							</div>

						</div>
						<div class="w-100">
                            <form id="searchLot" class="form-single-lot samsung-visual" method="get" action="{{ $searchUrl }}">
                            <div class="bar-filters into-lot flex">

                                  <div class="select-currency d-felx align-items-center">
                                      <select id="actual_currency">
                                          @foreach($divisas as $divisa)
                                              @if($divisa->cod_div != 'EUR')
                                                  <?php //quieren que salgan los dolares por defecto (sin no hay nada o hay euros  ?>
                                                  <option value='{{ $divisa->cod_div }}' <?= ($data['js_item']['subasta']['cod_div_cli'] == $divisa->cod_div || ($divisa->cod_div == 'USD' &&  ($data['js_item']['subasta']['cod_div_cli'] == 'EUR'  || $data['js_item']['subasta']['cod_div_cli'] == '' )))? 'selected="selected"' : '' ?>>
                                                       {{ $divisa->cod_div }}
                                                  </option>
                                              @endif
                                          @endforeach
                                      </select>
                                  </div>

                                <div class="input-lot">
                                    <label>{{ trans($theme.'-app.lot.go_to_lot') }}</label>
                                    <input class="single-lot" type="number" name="reference" url-redirect="{{Config::get('app.url')}}{{$bread['url']}}">
                                    <button id="single-lot" class="btn btn-color btn-bar-filters" type="submit">{{ trans($theme.'-app.lot.ver') }}</button>
                                </div>

                            </div>


						</form>
						</div>
                    </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('content.ficha')
@stop
