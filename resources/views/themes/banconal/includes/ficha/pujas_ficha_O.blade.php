

	<div id="reload_inf_lot" class="col-xs-12 info-ficha-buy-info no-padding">

		<div class="col-xs-12 no-padding">
			<div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">
				{{trans(\Config::get('app.theme').'-app.lot.max_puja')}}
				<strong>
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

					</strong><br><br>



			</div>
		</div>
		<div class="col-xs-6 no-padding info-ficha-buy-info-price d-flex">

				<div class="pre">
					@if ($lote_actual->ocultarps_asigl0 != 'S')
						<p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
						<p class="pre-price">{{ \Tools::moneyFormat($lote_actual->impsalweb_asigl0, $currency, 0, 'L',".",",")}}
						@if(\Config::get("app.exchange"))
							| <span id="startPriceExchange_JS" class="exchange"> </span>
						@endif
						</p>
					@endif
				</div>

		</div>
		<div class=" col-xs-6 no-padding info-ficha-buy-info-price">

				<div id="text_actual_max_bid" class="d-flex pre-price price-title-principal <?=  count($lote_actual->pujas) >0? '':'hidden' ?>">
					<div class="pre pre-actual_max_bid">
						<p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
						<strong>
							{{-- aparecera en rojo(clase other) si no eres el ganador y en verde si loeres (clase mine) , si no estas logeado no se modifica el color --}}
							@if(Session::has('user'))
								@php($class = (!empty($lote_actual->max_puja) &&   $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'])? 'mine':'other')
							@else
								@php($class = '')
							@endif
							<span id="actual_max_bid" class="{{$class}}">{{ \Tools::moneyFormat($lote_actual->actual_bid, $currency, 0, 'L',".",",") }} </span>
							@if(\Config::get("app.exchange"))
							| <span id="actualBidExchange_JS" class="exchange"> </span>
							@endif


						</strong>
					</div>

				</div>

		</div>
		<div class="col-xs-12 no-padding info-ficha-buy-info-price border-top-bottom">
			<div class="pre d-flex mt-2 mb-2 ">
				<div  id="text_actual_no_bid" class="price-title-principal pre col-xs-12 col-sm-3 no-padding <?=  count($lote_actual->pujas) >0? 'hidden':'' ?>">
					{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }}
				</div>

				<div class="col-xs-12 col-sm-9 no-padding">
					@if ($hay_pujas)
						<p class='explanation_bid t_insert pre-title' >{{ trans(\Config::get('app.theme').'-app.lot.next_min_bid') }}  </p>
					@else
						<p class='explanation_bid t_insert pre-title'>{{ trans(\Config::get('app.theme').'-app.lot.min_puja') }}  </p>
					@endif
					<strong>{{ $currency }} <span class="siguiente_puja"> </span>
						@if(\Config::get("app.exchange"))
							| <span id="nextBidExchange_JS" class="exchange"> </span>
						@endif
					</strong>
				</div>

			</div>
		</div>

		@if($inicio_pujas || $subasta_abierta_P)
			<div class="insert-bid-input col-lg-10 col-lg-offset-1 d-flex justify-content-center flex-column">

				<div class="input-group d-block group-pujar-custom ">
					<div>
						<div class="insert-bid insert-max-bid mb-1">{{ trans(\Config::get('app.theme').'-app.lot.insert_max_puja') }}
							<a href="javascript:;" data-toggle="modal" data-target="#modalAjax" class="info-ficha-lot c_bordered" data-ref="/es/pagina/puja-maxima?modal=1" data-title="Información de puja máxima">
								<i class="fas fa-info-circle"></i>
							</a>
						</div>
					</div>
					<div class="d-flex mb-2">
						@if (count($lote_actual->pujas)== 0)
							<input id="bid_amount" placeholder="{{ $lote_actual->impsalweb_asigl0 }}" class="form-control control-number" type="text" value="{{ $lote_actual->impsalweb_asigl0 }}">
						@else
							<input id="bid_amount" placeholder="{{ $data['precio_salida'] }}" class="form-control control-number" type="text" value="{{ $data['precio_salida'] }}">
						@endif
						<div class="input-group-btn">
							<button type="button" data-from="modal" ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}"
								class="lot-action_pujar_on_line_banco ficha-btn-bid ficha-btn-bid-height button-principal">
								{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}
							</button>
						</div>
					</div>



					<div class="d-flex mb-2">
						@if(Session::has('user') && !empty(\Config::get("app.DeleteOrdersAnyTime")))
							<button style="width:100%" id="cancelarOrdenUser"   class="ficha-btn-bid-height button-principal  @if(empty($data['js_item']['user']['ordenMaxima']))  hidden @endif" type="button" ref="{{$data['subasta_info']->lote_actual->ref_asigl0}}" sub="{{$data['subasta_info']->lote_actual->cod_sub}}" >  {{ trans(\Config::get('app.theme').'-app.user_panel.delete_orden') }}
							</button>
						@endif

					</div>

				</div>
			</div>

		@else
		<div class="">
			<p>{{ trans("$theme-app.lot.can_place_bids") }}{{ Tools::getDateFormat($lote_actual->fini_asigl0, 'Y-m-d H:i:s', 'd/m/Y H:i:s') }}</p>
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


