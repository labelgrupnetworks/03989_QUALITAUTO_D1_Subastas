<div id="reload_inf_lot" class="col-xs-12 info-ficha-buy-info no-padding grid-block">

	{{-- admin --}}
	<div class="grid-admin">
		<div class="w-100">

			@if (Session::has('user') && Session::get('user.admin'))
					<div class="d-block w-100">

						<input id="ges_cod_licit" name="ges_cod_licit" class="form-control" type="text" value="" type="text" style="border: 1px solid red;" placeholder="Código de licitador">

						@if ($subasta_abierta_P)

							<input type="hidden" id="tipo_puja_gestor" value="abiertaP">

						@endif

					</div>
				@endif

		</div>
	</div>

	{{-- mensaje puja maxima --}}
	<div class="grid-insert-string">
		<div class="w-100">

			<div class="insert-bid insert-max-bid">
				{{ trans(\Config::get('app.theme').'-app.lot.insert_max_puja') }}
			</div>

		</div>
	</div>



	{{-- Precio salida --}}
	@if ($lote_actual->ocultarps_asigl0 != 'S')
		<div class="d-flex align-items-center flex-wrap grid-precio-salida">
			<div class="w-100 info-ficha-buy-info-price border-bottom">
				<div class="pre">

					<p class="pre-price">
						{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}
						<span class="text-right">
							{{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
						</span>
					</p>

				</div>
			</div>
		</div>
	@endif

	{{-- input --}}
	<div class="d-flex align-items-center flex-wrap grid-input">

		<div class="w-100">
			<div class="input-group d-block group-pujar-custom ">

				<div class="puj-btn-container">

					<input id="bid_amount" placeholder="{{ $lote_actual->siguientes_escalados[0] }}" class="form-control control-number" type="text" value="{{ $lote_actual->siguientes_escalados[0] }}">
					<span>{{trans(\Config::get('app.theme').'-app.subastas.euros')}}</span>

				</div>

			</div>
		</div>

	</div>


	{{-- Puja actual --}}
	<div class="d-flex align-items-center flex-wrap grid-puja-actual">

		<div class="w-100 info-ficha-buy-info-price ficha-price-actual">

			<div id="text_actual_max_bid" class="pre-price price-title-principal <?=  count($lote_actual->pujas) >0? '':'hidden' ?>">

				<div class="pre">

					<p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}

						<strong class="text-right">
							{{-- aparecera en rojo(clase other) si no eres el ganador y en verde si loeres (clase mine) , si no estas logeado no se modifica el color --}}
							@if(Session::has('user'))
								@php($class = (!empty($lote_actual->max_puja) && $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'])? 'mine':'other')
							@else
								@php($class = '')
							@endif

							<span id="actual_max_bid" class="{{$class}}">{{ $lote_actual->formatted_actual_bid }} €</span>

						</strong>

					</p>

				</div>

				<div class="pre">
					@if (isset($lote_actual->impres_asigl0) && $lote_actual->impres_asigl0 > 0 && Session::has('user'))

						<div class="pre_min">

							<p class='pre-title'> {{ trans(\Config::get('app.theme').'-app.subastas.price_minim') }}:</p>

								<strong class="right">
									<span class="precio_minimo_alcanzado mine hidden">{{ trans(\Config::get('app.theme').'-app.subastas.reached') }}</span>
									<span class="precio_minimo_no_alcanzado other hidden">{{ trans(\Config::get('app.theme').'-app.subastas.no_reached') }}</span>
								</strong>

						</div>

					@endif
				</div>

			</div>

		</div>

	</div>


	{{-- boton pujar --}}
	<div class="d-flex align-items-center flex-wrap grid-btn-pujar">

		<div class="w-100">

			<div class="input-group d-block group-pujar-custom ">

				<div class="puj-btn-container">

					<div>

						<button type="button" data-from="modal"
							class="lot-action_pujar_on_line ficha-btn-bid ficha-btn-bid-height button-principal <?= Session::has('user')?'add_favs':''; ?>"
							type="button" ref="{{ $lote_actual->ref_asigl0 }}" ref="{{ $lote_actual->ref_asigl0 }}"
							codsub="{{ $lote_actual->cod_sub }}">

							{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}

						</button>

					</div>
				</div>

			</div>

		</div>

	</div>

	{{-- mensajes puja minima, siguiente etc. (estan ocultos por decision del cliente) --}}
	<div class="d-flex align-items-center flex-wrap grid-puja-maxima">

		{{-- msg de su puja máxima --}}
		<div class="w-100 info-ficha-buy-info-price siguiente-puja">

			<div class="pre">

				<div id="text_actual_no_bid" class="price-title-principal pre col-xs-12 col-sm-3 no-padding <?=  count($lote_actual->pujas) >0? 'hidden':'' ?>">
					{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }}
				</div>

				<div class="">
					@if ($hay_pujas)

						<p class='explanation_bid t_insert pre-title'>
							{{ trans(\Config::get('app.theme').'-app.lot.next_min_bid') }}
							<strong>
								<span class="siguiente_puja"></span>&nbsp;{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
							</strong>
						</p>

					@else

						<p class='explanation_bid t_insert pre-title'>
							{{ trans(\Config::get('app.theme').'-app.lot.min_puja') }}
							<strong>
								<span class="siguiente_puja"></span>&nbsp;{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
							</strong>
						</p>

					@endif
				</div>

			</div>
		</div>

		<div class="w-100">

			<div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">

				<p>{{trans(\Config::get('app.theme').'-app.lot.max_puja')}}
				<strong>

					<span id="tuorden">

						@if ( !empty($data['js_item']['user']['ordenMaxima']))

							{{ \Tools::moneyFormat($data['js_item']['user']['ordenMaxima']) }}

						@endif

					</span>

					{{trans(\Config::get('app.theme').'-app.subastas.euros')}}
				</strong>
			</p>
			</div>

		</div>

	</div>


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
