<div id="reload_inf_lot" class="col-xs-12 info-ficha-buy-info no-padding">
	<div class="">
		<?php
		$userOrdenMax = $data['js_item']['user']['ordenMaxima'] ?? 0;
		$userPujaMax = $data['js_item']['user']['pujaMaxima']->imp_asigl1 ?? 0;
		?>
		<div class="info_single_title hist_new {{ empty($userOrdenMax) && empty($userPujaMax) ? 'hidden' : '' }}">
			{{trans(\Config::get('app.theme').'-app.lot.max_puja')}}
			<strong>
				<span id="tuorden">
					@if ($userOrdenMax >= $userPujaMax)
					{{ \Tools::moneyFormat( $userOrdenMax, false, 0) }}
					@else
					{{ \Tools::moneyFormat( $userPujaMax, false, 0) }}
					@endif
				</span>
				{{trans(\Config::get('app.theme').'-app.subastas.euros')}}</strong>
		</div>
	</div>
	<div class=" col-xs-12 no-padding info-ficha-buy-info-price d-flex">

		<div class="pre">
			<p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
			<p class="pre-price"> {{\Tools::moneyFormat( $lote_actual->imptas_asigl0, false, 2) }}
				{{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </p>

		</div>

	</div>

	{{-- 11/01/2021 si la sesion de la subasta online no ha empezado, no se podrá pujar --}}
	@if($start_session)
	<div
		class="col-xs-12 no-padding info-ficha-buy-info-price border-top-bottom d-flex flex-wrap alig-items-center justify-content-between pt-1 pb-1">
		<div class="no-padding col-xs-6 mt-1 mb-1 d-flex flex-column justify-content-center">
			<div class="pre">
				<div id="text_actual_max_bid"
					class="pre-price price-title-principal <?=  count($lote_actual->pujas) >0? '':'hidden' ?>">
					<p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
					<strong>
						{{-- aparecera en rojo(clase other) si no eres el ganador y en verde si loeres (clase mine) , si no estas logeado no se modifica el color --}}
						@php
						if(Session::has('user')){
						$class = (!empty($lote_actual->max_puja) && $lote_actual->max_puja->cod_licit ==
						$data['js_item']['user']['cod_licit'])? 'mine':'other';
						}
						else{
						$class = '';
						}
						@endphp
						<span id="actual_max_bid" class="{{$class}}">{{ $lote_actual->formatted_actual_bid }} €</span>

					</strong>
				</div>
				<div id="text_actual_no_bid"
					class="price-title-principal pre no-padding <?=  count($lote_actual->pujas) >0? 'hidden':'' ?>">
					{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }} </div>

			</div>
		</div>
		<div class="pre d-flex  col-xs-6 p-0 mt-1 mb-1">
			<div class=" no-padding puja-minima">
				@if ($hay_pujas)
				<p class='explanation_bid t_insert'>{{ trans(\Config::get('app.theme').'-app.lot.next_min_bid') }}</p>
				<strong class="euro-style"><span
						class="siguiente_puja">{{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </span><span
						style="font-size: 20px;"> €</span></strong>
				@else
				<p class='explanation_bid t_insert'>{{ trans(\Config::get('app.theme').'-app.lot.min_puja') }} </p>
				<strong class="euro-style"><span
						class="siguiente_puja">{{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </span><span
						style="font-size: 20px;"> €</span></strong>
				@endif
			</div>
		</div>
	</div>


	<div class="insert-bid-input col-lg-10 col-lg-offset-1 d-flex justify-content-center flex-column">

		@if (Session::has('user') && Session::get('user.admin'))
		<div class="d-block w-100">
			<input id="ges_cod_licit" name="ges_cod_licit" class="form-control" type="text" value="" type="text"
				style="border: 1px solid red;" placeholder="Código de licitador">
			@if ($subasta_abierta_P || $subasta_online)
			<input type="hidden" id="tipo_puja_gestor" value="abiertaP">
			@endif
		</div>
		@else
		<input type="hidden" id="tipo_puja_gestor" value="">
		@endif

		{{-- Puja directa --}}
		<div class="input-group d-block group-pujar-custom ">
			<div>
				<div class="insert-bid insert-max-bid">{{ trans(\Config::get('app.theme').'-app.lot.insert_firm_bid') }}
				</div>
			</div>
			<div class="d-flex mb-2">
				<input id="bid_amount_firm" style="font-size: 20px;" placeholder="{{ $data['precio_salida'] }}"
					class="form-control control-number" type="text" value="{{ $data['precio_salida'] }}">
				<div class="input-group-btn">

					@if(!Session::has('user'))
					<button type="button" data-from="modal"
						class="btn-blue lot-action_pujar_no_user ficha-btn-bid ficha-btn-bid-height button-principal"
						type="button">{{ trans(\Config::get('app.theme').'-app.lot.firm_bid') }}</button>
					@elseif(!$deposito)
					<button type="button" data-from="modal" data-codcli="{{Session::get('user')['cod'] }}"
						data-ref="{{ $lote_actual->ref_asigl0 }}" data-codsub="{{ $lote_actual->cod_sub }}"
						data-lang="{{\App::getLocale()}}"
						class="btn-blue lot-action_pujar_no_licit ficha-btn-bid ficha-btn-bid-height button-principal <?= Session::has('user')?'add_favs':''; ?>"
						type="button">
						{{ trans(\Config::get('app.theme').'-app.lot.firm_bid') }}
					</button>
					@elseif(!$auctionConditionsAccepted)
					<button type="button" data-from="modal" data-codcli="{{Session::get('user')['cod'] }}"
						data-lang="{{\App::getLocale()}}"
						ref="{{ $lote_actual->ref_asigl0 }}"
						codsub="{{ $lote_actual->cod_sub }}"
						data-tipoPuja="firme"
						class="btn-blue lot-action_pujar_no_bases ficha-btn-bid ficha-btn-bid-height button-principal {{ Session::has('user') ? 'add_favs' : '' }}"
						type="button">
					{{ trans(\Config::get('app.theme').'-app.lot.firm_bid') }}
					</button>
					@else
					<button type="button" data-tipoPuja="firme" data-from="modal"
						class="btn-blue lot-action_pujar_on_line ficha-btn-bid ficha-btn-bid-height button-principal <?= Session::has('user')?'add_favs':''; ?>"
						type="button" ref="{{ $lote_actual->ref_asigl0 }}"
						codsub="{{ $lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.lot.firm_bid') }}</button>
					@endif
				</div>
			</div>
		</div>

		{{-- Puja automatica --}}
		{{-- Comentada por orden de Inbusa --}}

		<div class="input-group d-block group-pujar-custom " style="display: none">
			<div>
				<div class="insert-bid insert-max-bid">
					{{ trans(\Config::get('app.theme').'-app.lot.insert_max_puja') }}
					<!-- HTML to write -->
					<a href="#" class="d-inline-flex align-items-center" data-toggle="tooltip" data-placement="top"
						title="{{ trans(\Config::get('app.theme').'-app.lot.info_auto_content') }}"><i
							class="fa fa-info-circle" aria-hidden="true"></i>
						<small>{{ trans(\Config::get('app.theme').'-app.lot.info_auto') }}</small></a>

				</div>
				<script>
					$(function () {
						  $('[data-toggle="tooltip"]').tooltip()
						});
				</script>
			</div>
			<div class="d-flex mb-2">
				<input id="bid_amount" style="font-size: 20px;" placeholder="{{ $data['precio_salida'] }}"
					class="form-control control-number" type="text" value="{{ $data['precio_salida'] }}">
				<div class="input-group-btn">
					@if(!$deposito)
					<button type="button" data-from="modal"
						class="btn-red lot-action_pujar_no_licit ficha-btn-bid ficha-btn-bid-height button-principal <?= Session::has('user')?'add_favs':''; ?>"
						type="button">{{ trans(\Config::get('app.theme').'-app.lot.automatic_bid') }}</button>
					@else
					<button type="button" data-from="modal"
						class="btn-red lot-action_pujar_on_line ficha-btn-bid ficha-btn-bid-height button-principal <?= Session::has('user')?'add_favs':''; ?>"
						type="button" ref="{{ $lote_actual->ref_asigl0 }}" ref="{{ $lote_actual->ref_asigl0 }}"
						codsub="{{ $lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.lot.automatic_bid') }}</button>
					@endif
				</div>
			</div>
		</div>


	</div>
	@else
	<div class="col-xs-12 no-padding">
        <div class="col-xs-12 no-padding ficha-info-items-buy">
            <div class="info_single_content info_single_button ficha-button-buy">
                <button class="button-principal button-prox" type="button">{{ trans(\Config::get('app.theme').'-app.subastas.proximamente') }}</button>
			</div>
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
