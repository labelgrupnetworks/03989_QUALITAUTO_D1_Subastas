
<div id="reload_inf_lot" class="col-xs-12 info-ficha-buy-info no-padding">

	{{-- precio de salida --}}
	@if ($lote_actual->ocultarps_asigl0 != 'S')
		<div class="mt-1 mb-1 starting-price-wrapper @if($hay_pujas) hidden @endif">
			<p class="pre-title"><b>{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</b></p>
			<p class="pre-price starting_price">{{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
				@if(\Config::get("app.exchange"))
					| <span id="startPriceExchange_JS" class="exchange"> </span>
				@endif
			</p>
		</div>
	@endif

	{{-- puja actual --}}
	<div class="acual_bid_wrapper d-flex justify-content-space-between">
		<div id="text_actual_max_bid" class="mt-1 @if(!$hay_pujas) hidden @endif">
			<p class="pre-title"><b>{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</b></p>
				<strong>
				{{-- aparecera en rojo(clase other) si no eres el ganador y en verde si loeres (clase mine) , si no estas logeado no se modifica el color --}}
				@php
					$class = "";
					$highest = false;
					if(Session::has('user') && !empty($lote_actual->max_puja) && $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit']){

						$class = $lote_actual->max_puja->imp_asigl1 >= $lote_actual->impres_asigl0 ? "mine" : "gold";
						$highest = true;
					}
					elseif (Session::has('user') && !empty($lote_actual->max_puja) && $lote_actual->max_puja->cod_licit != $data['js_item']['user']['cod_licit']){

						$class = $lote_actual->max_puja->imp_asigl1 >= $lote_actual->impres_asigl0 ? "other" : "gold";
					}
				@endphp
				<span id="actual_max_bid" class="{{$class}}">{{ trans(\Config::get('app.theme').'-app.subastas.euros') }} {{ $lote_actual->formatted_actual_bid }} </span>
					@if(\Config::get("app.exchange"))
						| <span id="actualBidExchange_JS" class="exchange"> </span>
					@endif
				</strong>
		</div>

		<div id="text_highest_bidder" class="mt-1 @if(!$highest) hidden @endif">
			<p class="pre-title"><b>{{ trans("$theme-app.lot.highest_bidder") }}</b></p>
		</div>
	</div>

	{{-- orden máxima --}}
	@if(!empty($data['js_item']['user']))
	<div class="info_single_title hist_new @if(empty($data['js_item']['user']['ordenMaxima']) && !$data['js_item']['user']['pujaMaxima']) hidden @endif">
		<small>{{trans(\Config::get('app.theme').'-app.lot.max_puja')}} <b>

			{{trans(\Config::get('app.theme').'-app.subastas.euros')}}
			<span id="tuorden">

					@if($data['js_item']['user']['pujaMaxima'] && intval($data['js_item']['user']['ordenMaxima']) <= intval($data['js_item']['user']['pujaMaxima']->imp_asigl1))
						{{ $data['js_item']['user']['pujaMaxima']->formatted_imp_asigl1 }}
					@else
						{{ $data['js_item']['user']['ordenMaxima']}}
					@endif

			</span>

			@if(\Config::get("app.exchange"))
			 |	<span  id="yourOrderExchange_JS" class="exchange"> </span>
			@endif
		</b></small>
	</div>
	@endif

	<div class="row ficha-separator" style=""></div>

	{{-- precio minimo alcanzado / no alcanzado --}}
	@if (Session::has('user') && !empty($lote_actual->impres_asigl0))
	<div class="row precio_minimo precio_minimo_alcanzado hidden" >
		<div class="pre_min col-xs-12">
			<p class='pre-title' style="text-transform: capitalize"><b>{{ trans(\Config::get('app.theme').'-app.subastas.price_minim') }}: </b>
				<span>{{ trans(\Config::get('app.theme').'-app.subastas.reached') }}</span>
				<i class="fa fa-question-circle" style="cursor: pointer" aria-hidden="true" data-container="body"
					data-toggle="popover" data-placement="bottom" data-title="{{ trans("$theme-app.subastas.price_minim") }}" data-html="true" data-content='{!! trans("$theme-app.subastas.price_min_tooltip") !!}'>
				</i>
			</p>
		</div>
	</div>

	<div class="row precio_minimo precio_minimo_no_alcanzado hidden">
		<div class="pre_min col-xs-12">
			<p class='pre-title' style="text-transform: capitalize"><b>{{ trans(\Config::get('app.theme').'-app.subastas.price_minim') }}: </b>
				<span>{{ trans(\Config::get('app.theme').'-app.subastas.no_reached') }}</span>
				<i class="fa fa-question-circle" style="cursor: pointer" aria-hidden="true" data-container="body"
					data-toggle="popover" data-placement="bottom" data-title="{{ trans("$theme-app.subastas.price_minim") }}" data-html="true" data-content='{!! trans("$theme-app.subastas.price_min_tooltip") !!}'>
				</i>
			</p>
		</div>
	</div>
	@endif

	{{-- siguiente puja --}}
    <div class="mt-1 mb-1 @if(!$hay_pujas) hidden @endif">
		<p class='explanation_bid t_insert pre-title'>

			<span>{{ trans(\Config::get('app.theme').'-app.lot.next_min_bid') }}</span>

			<span class="next-bid">{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span> <span class="siguiente_puja"></span>
				@if(\Config::get("app.exchange"))
					| <span id="nextBidExchange_JS" class="exchange"></span>
				@endif
		</p>
	</div>

	<div class="row ficha-separator @if(!$hay_pujas) hidden @endif"></div>


	{{-- bloque puja --}}
	@if($start_session || $subasta_abierta_P)
	<div class="mt-2 mb-2 insert-bid-input d-flex justify-content-center flex-column">
		@if (Session::has('user') &&  Session::get('user.admin'))
		<div class="d-block w-100 mb-1">
			<input id="ges_cod_licit" name="ges_cod_licit" class="form-control" type="text" value="" type="text" style="border: 1px solid red;" placeholder="Código de licitador">
			@if ($subasta_abierta_P)
				<input type="hidden" id="tipo_puja_gestor" value="abiertaP" >
			@endif
		</div>
		@else
			<input type="hidden" id="tipo_puja_gestor" value="">
		@endif

		<div class="input-group d-block group-pujar-custom mb-2">
			<p class="insert-bid insert-max-bid"><b>{{ trans(\Config::get('app.theme').'-app.lot.insert_firm_bid') }}</b></p>
			<div class="d-flex">
				<input id="bid_amount_firm" placeholder="{{ $data['precio_salida'] }}" class="form-control control-number"
						type="text" value="{{ $data['precio_salida'] }}">

				<div class="input-group-btn">
					<button
						type="button"
						data-from="modal"
						data-tipoPuja="firme"
						class="lot-action_pujar_on_line ficha-btn-bid ficha-btn-bid-height button-principal <?= Session::has('user')?'add_favs':''; ?>"
						ref="{{ $lote_actual->ref_asigl0 }}"
						codsub="{{ $lote_actual->cod_sub }}" >{{ trans(\Config::get('app.theme').'-app.lot.firm_bid') }}
					</button>
				</div>
			</div>
		</div>

		<div class="input-group d-block group-pujar-custom ">
			<p class="insert-bid insert-max-bid"><b>{{ trans(\Config::get('app.theme').'-app.lot.insert_max_puja') }}</b><i class="fa fa-question-circle" style="cursor: pointer; margin-left: 5px" aria-hidden="true" data-container="body"
				data-toggle="popover" data-placement="bottom" data-title="{{ trans("$theme-app.lot.insert_max_puja") }}" data-html="true" data-content='{!! trans("$theme-app.lot.popover_max-bid") !!}'>
			</i></p>
			<div class="d-flex">
				<input id="bid_amount" placeholder="{{ $data['precio_salida'] }}" class="form-control control-number" type="text" value="{{ $data['precio_salida'] }}">
				<div class="input-group-btn">
					<button type="button" data-from="modal" class="lot-action_pujar_on_line ficha-btn-bid ficha-btn-bid-height button-principal <?= Session::has('user')?'add_favs':''; ?>" type="button" ref="{{ $lote_actual->ref_asigl0 }}" ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}" >{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</button>
				</div>
			</div>
		</div>

		<small class="mt-1">
			{{ trans("$theme-app.lot.add_buyer") }}
			<i class="fa fa-question-circle" style="cursor: pointer" aria-hidden="true" data-container="body"
				data-toggle="popover" data-placement="bottom" data-title="{{ trans("$theme-app.lot.add_buyer_title") }}" data-html="true" data-content='{!! trans("$theme-app.lot.add_buyer_content") !!}'>
			</i>
		</small>
	</div>
	<div class="row ficha-separator"></div>
	@endif

	@if(!empty($lote_actual->imptash_asigl0))
	<div class="mt-1 mb-1 estimate-wrapper">
		<div class="pre">
			<p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.estimate') }}</p>
			<p class="pre-price">{{ trans(\Config::get('app.theme').'-app.subastas.euros') }} {{ $lote_actual->formatted_imptas_asigl0 }}-{{ $lote_actual->formatted_imptash_asigl0 }}</p>
		</div>
	</div>
	@endif


<?php //solo se debe recargar la fecha en las subatsas tipo Online, ne las abiertas tipo P no se debe ejecutar ?>
@if($subasta_online)
    <script>
        $(document).ready(function() {

            $("#actual_max_bid").bind('DOMNodeInserted', function(event) {
                if (event.type == 'DOMNodeInserted') {

                   $.ajax({
                        type: "GET",
                        url:  "/lot/getfechafin",
                        data: { cod: cod_sub, ref: ref},
                        success: function( data ) {

                            if (data.status == 'success'){
                               $(".timer").data('ini', new Date().getTime());
                               $(".timer").data('countdownficha',data.countdown);
                               //var close_date = new Date(data.close_at * 1000);
                              // $("#cierre_lote").html(close_date.toLocaleDateString('es-ES') + " " + close_date.toLocaleTimeString('es-ES'));
                               $("#cierre_lote").html(format_date_large(new Date(data.close_at * 1000),''));
                            }


                        }
                    });
                }
            });
        });
    </script>
@endif
</div>


