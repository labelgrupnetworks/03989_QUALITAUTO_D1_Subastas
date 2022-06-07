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
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
{{-- No deben mostarse subastas historicas --}}
@php
	if (strtoupper($data['subasta_info']->lote_actual->subc_sub) == 'H') {
		header("Location: " . \URL::to(\Routing::is_home()), true, 302);
        exit();
	}
@endphp

<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.css') }}" />

<script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/node_modules/socket.io-client/socket.io.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.min.js') }}"></script>
@if(strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'O' || strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'P' || $data['subasta_info']->lote_actual->subabierta_sub == 'P')
@if(strtotime("now") < strtotime($data['subasta_info']->lote_actual->start_session))
	<script src="{{ Tools::urlAssetsCache('vendor/tiempo-real/tr_main.js') }}"></script>
    <script src="{{ URL::asset('js/hmac-sha256.js') }}"></script>
@endif

@endif
<script src="{{ URL::asset('js/openseadragon.min.js') }}"></script>

<script src="{{ URL::asset('vendor/jquery-print/jQuery.print.js') }}"></script>

<script>
var auction_info = $.parseJSON('<?php  echo str_replace("\u0022","\\\\\"",json_encode($data["js_item"],JSON_HEX_QUOT)); ?>');


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

<?php
$lote_actual = $data['subasta_info']->lote_actual;
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
    use App\libs\Currency;
    $currency = new Currency();
    $divisas = $currency->getAllCurrencies()
?>

var currency = $.parseJSON('<?php  echo str_replace("\u0022","\\\\\"",json_encode($divisas,JSON_HEX_QUOT)); ?>');

$(document).ready(function() {
	$('.add_bid').removeClass('loading');


        /* Mostrar divisas */
        $("#actual_currency").change(function(){

            changeCurrency({{$lote_actual->impsalhces_asigl0}},$(this).val(),"impsalexchange");
            changeCurrency(auction_info.lote_actual.actual_bid,$(this).val(),"impsalexchange-actual");
            changeCurrency({{$lote_actual->imptas_asigl0}},$(this).val(),"impsalexchange-tas");

            changeCurrency({{$lote_actual->impsalhces_asigl0}},$(this).val(),"impsalexchange2");
            changeCurrency(auction_info.lote_actual.actual_bid,$(this).val(),"impsalexchange-actual2");
            changeCurrency({{$lote_actual->imptas_asigl0}},$(this).val(),"impsalexchange-tas2");

            $.ajax({
                type: "POST",
                url:  "/api-ajax/updateDivisa",
                data: { divisa: $(this).val() },
                success: function( data ) {
                    if($('#impsalexchange').length > 0  && $('#impsalexchange-actual').length > 0){
            if(!$('#impsalexchange-actual').hasClass('hidden') ){


				let sizeText = document.getElementById('impsalexchange-actual').innerText.length;
                $('#impsalexchange').css('width', `${sizeText}ch`);
            }


        }
                }
            });
             //calculamos los tamaños de los campo, y los igualamos en caso de que sea mayor el precio actual

        })

        changeCurrency({{ $lote_actual->impsalhces_asigl0 }},$("#actual_currency").val(),"impsalexchange");
        changeCurrency({{ $lote_actual->actual_bid }},$("#actual_currency").val(),"impsalexchange-actual");
        changeCurrency({{$lote_actual->imptas_asigl0}},$("#actual_currency").val(),"impsalexchange-tas");

        changeCurrency({{ $lote_actual->impsalhces_asigl0 }},$("#actual_currency").val(),"impsalexchange2");
        changeCurrency({{ $lote_actual->actual_bid }},$("#actual_currency").val(),"impsalexchange-actual2");
        changeCurrency({{$lote_actual->imptas_asigl0}},$("#actual_currency").val(),"impsalexchange-tas2");


        if($('#impsalexchange').length > 0  && $('#impsalexchange-actual').length > 0){
            if(!$('#impsalexchange-actual').hasClass('hidden') ){
				let sizeText = document.getElementById('impsalexchange-actual').innerText.length;
                $('#impsalexchange').css('width', `${sizeText}ch`);
            }


        }

        /* fin divisas */


});






</script>
<?php



    $bread = array(
        "url" => '',
        "name" => ''
    );
    $in_category=false;
    if(!empty($lote_actual)){

		//$bread["url"] =  \Routing::translateSeo('subasta').$lote_actual->cod_sub."-".str_slug($lote_actual->name)."-".str_slug($lote_actual->id_auc_sessions);

		$bread["url"] = $lote_actual->url_subasta;
        $bread["name"] = ($lote_actual->tipo_sub == 'V')?trans(\Config::get('app.theme').'-app.global.go_shop'): trans(\Config::get('app.theme').'-app.global.go_auction');
        if(stripos(\Config::get('app.auction_in_categories'),$lote_actual->tipo_sub) !== false){
            $in_category=true;
        }
    }
?>

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
									<a class="nextLeft" title="{{ trans(\Config::get('app.theme').'-app.subastas.last') }}"
										href="{{$data['previous']}}">
										<span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
										<span>{{ trans(\Config::get('app.theme').'-app.subastas.last') }}</span>
									</a>
									@endif
							</p>
							</div>
                            <p class="hidden-xs hidden-sm"><a title="Home" href="/">{{ trans(\Config::get('app.theme').'-app.subastas.breadcrumb') }}</a></p>
                            <span class="divVertical hidden-xs hidden-sm"></span>
                            <p><a href="{{$bread['url']}}">{{$bread['name']}}</a></p>
                           <?php //oculto  ?>
                            @if(1 != 1 && $in_category)
								<span class="divVertical hidden-xs"></span>
                                <p class="cat-in-ficha hidden-xs"><a href="{{$lote_actual->url_subasta}}">{{ trans(\Config::get('app.theme').'-app.global.go_to')." ".$lote_actual->title_url_subasta}}</a></p>
                            @endif
                            <span class="divVertical"></span>
							<p><a href="javascript:history.back()"> {{ trans(\Config::get('app.theme').'-app.global.go_home') }}</a></p>

							<div class="next-lot hidden-md hidden-lg">
								<p class="next-lot">
									@if(!empty($data['next']))
									<a class="nextRight" title="{{ trans(\Config::get('app.theme').'-app.subastas.next') }}"
										href="{{$data['next']}}">
										<span>{{ trans(\Config::get('app.theme').'-app.subastas.next') }}</span>
										<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
									</a>
									@endif
								</p>
							</div>

						</div>
						<div class="w-100">
                            <form class="form-single-lot samsung-visual" method="get" action="{{$bread['url']}}">
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
                                    <label>{{ trans(\Config::get('app.theme').'-app.lot.go_to_lot') }}</label>
                                    <input class="single-lot" type="number" name="reference" url-redirect="{{Config::get('app.url')}}{{$bread['url']}}">
                                    <button id="single-lot" class="btn btn-color btn-bar-filters" type="submit">{{ trans(\Config::get('app.theme').'-app.lot.ver') }}</button>
                                </div>

                            </div>


						</form>
						</div>
                    </div>
                    </div>
                </div>
            </div>
        </section>
        <?php //@include('includes.breadcrumb_before_after') ?>
    </div>
    @include('content.ficha')
@stop
