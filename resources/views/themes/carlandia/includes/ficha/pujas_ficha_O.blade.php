<div id="reload_inf_lot" class="col-xs-12 info-ficha-buy-info no-padding">

    <div class="col-xs-12 info-ficha-buy-info-price header-info-ficha-prices d-flex p-0">

			{{-- precio de salida --}}
            <div class="pre pre-impsal">
                <p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
                <p class="pre-price">{{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
				@if(\Config::get("app.exchange"))
					| <span id="startPriceExchange_JS" class="exchange"> </span>
				@endif
				</p>
			</div>

			{{-- precio estimado --}}
			{{-- @if(!empty($lote_actual->imptash_asigl0))
            <div class="pre pre-imptash">
                <p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.estimate') }}</p>
                <p class="pre-price">{{ \Tools::moneyFormat($lote_actual->imptash_asigl0)}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
			</div>
			@endif --}}

			{{-- aparecera en rojo(clase other) si no eres el ganador y en verde si loeres (clase mine) , si no estas logeado no se modifica el color --}}
			@php
				$class = "";
				if(Session::has('user') && !empty($lote_actual->max_puja) && $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit']){
					$class="mine";
				}
				elseif (Session::has('user') && !empty($lote_actual->max_puja)) {
					$class="other";
				}
			@endphp

			{{-- Puja actual --}}
			<div class="pre pre-actualbid status_bid {{$class}}">

				<p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}
					<i class="fa fa-info-circle ml-1 js-modal info-highest-bid" aria-hidden="true"
						data-title="{{ trans("$theme-app.lot.highest_bid") }}" data-content="{{ trans("$theme-app.lot.highest_bid_info") }}"></i>

					<i class="fa fa-info-circle ml-1 js-modal info-lost-bid" aria-hidden="true"
					data-title="{{ trans("$theme-app.lot.lost_bid") }}" data-content="{{ trans("$theme-app.lot.lost_bid_info") }}"></i>
				</p>

				<div id="text_actual_no_bid" class="price-title-principal pre @if($hay_pujas) hidden @endif">
					{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }}
				</div>

				<div id="text_actual_max_bid" class="d-flex pre-price price-title-principal justify-content-center text_actual_max_bid @if(!$hay_pujas) hidden @endif">
				<strong>

					<span id="actual_max_bid" class="{{$class}}">{{ $lote_actual->formatted_actual_bid }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
					@if(\Config::get("app.exchange"))
					| <span id="actualBidExchange_JS" class="exchange"> </span>
					@endif
				</strong>
				</div>

			</div>



    </div>

	@php
		$pujaMaxima = 0;
	@endphp


    <div class=" col-xs-12 info-ficha-buy-info-price info-ficha-buy-inside">

		<div class="row">
			<div class="col-xs-12">
				<p class="mb-1 mt-1"><small>{{ trans("$theme-app.lot.info_prices_cash") }}</small></p>
			</div>

			@if(!empty($data['js_item']['user']))
			<div id="text_actual_max_bid" class="col-xs-12 mt-2 pre-price price-title-principal text_actual_max_bid @if(!$hay_pujas && empty($data['js_item']['user']['ordenMaxima']) && !$data['js_item']['user']['pujaMaxima']) hidden @endif">

					{{-- orden máxima --}}
					<div class="d-flex justify-content-space-between info_single_title hist_new">

						<p class="m-0">{{trans(\Config::get('app.theme').'-app.lot.max_puja')}}</p>

						<strong>

							<span id="tuorden">
								@if($data['js_item']['user']['pujaMaxima'] && intval($data['js_item']['user']['ordenMaxima']) <= intval($data['js_item']['user']['pujaMaxima']->imp_asigl1))
									{{ $data['js_item']['user']['pujaMaxima']->formatted_imp_asigl1 }}
								@else
									{{ $data['js_item']['user']['ordenMaxima']}}
								@endif
							</span>

							{{trans(\Config::get('app.theme').'-app.subastas.euros')}}

							@if(\Config::get("app.exchange"))
							 |	<span  id="yourOrderExchange_JS" class="exchange"> </span>
							@endif

						</strong>
					</div>

            </div>
			@endif



		</div>

    </div>

	{{-- Siguiente puja --}}
	{{-- texto de puja inicial debe ser... --}}
    <div class="col-xs-12 info-ficha-buy-info-price info-next-bid info-ficha-buy-inside">
        <div class="pre d-flex">

            <div class="col-xs-12 no-padding d-flex justify-content-space-between">

				<p class='explanation_bid t_insert'>
					<span class="min_bid @if(!$hay_pujas) hidden @endif">{{ trans(\Config::get('app.theme').'-app.lot.next_min_bid') }} </span>
					<span class="no_bids @if($hay_pujas) hidden @endif">{{ trans(\Config::get('app.theme').'-app.lot.min_puja') }} </span>
					<i class="fa fa-info-circle ml-1 js-info-modal" data-modal="info-next-bid" aria-hidden="true"></i>
				</p>

				{{-- <p class='explanation_bid t_insert'>
				@if ($hay_pujas)
					{{ trans(\Config::get('app.theme').'-app.lot.next_min_bid') }}
				@else
					{{ trans(\Config::get('app.theme').'-app.lot.min_puja') }}
				@endif
				<i class="fa fa-info-circle ml-1 js-info-modal" data-modal="info-next-bid" aria-hidden="true"></i>
				</p> --}}

				<strong><span class="siguiente_puja"> </span> {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
					@if(\Config::get("app.exchange"))
						| <span id="nextBidExchange_JS" class="exchange"> </span>
					@endif
				</strong>
            </div>

        </div>
	</div>

	{{-- bloque input pujar --}}
	@if($start_session || $subasta_abierta_P)
        <div class="insert-bid-input col-lg-12 d-flex justify-content-center flex-column">

            {{-- @if (Session::has('user') &&  Session::get('user.admin'))
            <div class="d-block w-100 mb-1">
                <input id="ges_cod_licit" name="ges_cod_licit" class="form-control" type="text" value="" type="text" style="border: 1px solid red;" placeholder="Código de licitador">
                @if ($subasta_abierta_P || $subasta_online)
                    <input type="hidden" id="tipo_puja_gestor" value="abiertaP" >
                @endif
            </div>
			@else
			<input type="hidden" id="tipo_puja_gestor" value="">
            @endif --}}
			<input type="hidden" id="tipo_puja_gestor" value="">


            <div class="input-group d-block group-pujar-custom ">

				{{-- Puja en Firme --}}
				<div>
					<div class="insert-bid insert-max-bid mb-1">{{ trans(\Config::get('app.theme').'-app.lot.insert_firm_bid') }}
						<i class="fa fa-info-circle ml-1 js-modal" aria-hidden="true"
						data-title="{{ trans("$theme-app.lot.insert_firm_bid") }}" data-content="{{ trans("$theme-app.lot.insert_firm_bid_info") }}"></i>
					</div>
				</div>
				<div class="d-flex mb-2">
					<input id="bid_amount_firm" placeholder="{{ $data['precio_salida'] }}" class="form-control control-number"
						type="text" value="{{ $data['precio_salida'] }}">
					<div class="input-group-btn">
						<button  id="pujaEvent_JS"  type="button" data-from="modal" data-tipoPuja="firme"
							class="lot-action_pujar_on_line ficha-btn-bid ficha-btn-bid-height button-principal <?= Session::has('user')?'add_favs':''; ?>"
							type="button" ref="{{ $lote_actual->ref_asigl0 }}"
							codsub="{{ $lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.lot.firm_bid') }}</button>
					</div>
				</div>

				{{-- Puja en Automática --}}
				<div>
					<div class="insert-bid insert-max-bid mb-1">{{ trans(\Config::get('app.theme').'-app.lot.insert_max_puja') }}
						<i class="fa fa-info-circle ml-1 js-modal" aria-hidden="true"
						data-title="{{ trans("$theme-app.lot.insert_max_puja") }}" data-content="{{ trans("$theme-app.lot.insert_max_puja_info") }}"></i>
					</div>
				</div>
				<div class="d-flex mb-2">
					<input id="bid_amount" class="form-control control-number"
						type="text" value="">
					<div class="input-group-btn">
						<button id="pujaAutomaticaEvent_JS"  type="button" data-from="modal"
							class="lot-action_pujar_on_line ficha-btn-bid ficha-btn-bid-height button-principal <?= Session::has('user')?'add_favs':''; ?>"
							type="button" ref="{{ $lote_actual->ref_asigl0 }}"
							codsub="{{ $lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</button>
					</div>
				</div>

				{{-- Comprar --}}
				@if(!empty($lote_actual->imptash_asigl0) && $lote_actual->imptash_asigl0 > $lote_actual->actual_bid)
				<div id="js-buylot-block">
				<div>
					<div class="mb-1">{{ trans("$theme-app.lot.buy_now") }}
						<i class="fa fa-info-circle ml-1 js-modal" aria-hidden="true"
						data-title="{{ trans("$theme-app.lot.buy_now") }}" data-content="{{ trans("$theme-app.lot.buy_now_info") }}"></i>
					</div>
				</div>
				<div class="d-flex mb-2">
					<input class="form-control control-number input-buy-online" type="text"
						value="{{ $lote_actual->imptash_asigl0 }}" readonly="readonly">
						{{-- precio que se pone para los eventos de ga --}}
						<input id="price_compra_ya_JS" type="hidden" value ="{{ $lote_actual->imptash_asigl0 }}">
					<div class="input-group-btn">
						<button id="comprarYaEvent_JS" type="button" data-from="modal" class="ficha-btn-bid ficha-btn-bid-height button-principal lot-action_comprar_lot"
							ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}" data-type-puja="{{ \App\Models\V5\FgAsigl1_Aux::PUJREP_ASIGL1_COMPRAR_ONLINE }}">
							{{ trans("$theme-app.lot.accept") }}
						</button>
					</div>
				</div>

				</div>
				@endif

			</div>
        </div>
	@endif

	{{-- Comprar ya / primera version--}}
	{{-- @if(true)
	<div class="col-xs-12 info-ficha-buy-info-price info-ficha-buy-inside info-next-bid">
        <div class="pre d-flex">

            <div class="col-xs-12 no-padding d-flex justify-content-space-between">
				<p class='pre-title' >COMPRAR YA <i class="fa fa-info-circle ml-1 js-info-modal" data-modal="info-comprar" aria-hidden="true"></i></p>

				<strong><span>{{ $lote_actual->formatted_imptash_asigl0 }}</span> {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
					@if(\Config::get("app.exchange"))
						| <span id="buyNow_JS" class="exchange"> </span>
					@endif
				</strong>
            </div>


        </div>
	</div>

	<div class="btn-buy-ficha-o col-lg-12 d-flex justify-content-center">
		<button type="button" data-from="modal" class="button-principal" ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}">COMPRAR YA</button>
    </div>
	@endif --}}


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


