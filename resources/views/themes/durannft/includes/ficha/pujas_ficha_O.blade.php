<div id="reload_inf_lot" class="col-xs-12 info-ficha-buy-info no-padding">
	<div class="col-xs-12">
		<div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima']) ? '' : 'hidden' ?> ">
			{{ trans(\Config::get('app.theme') . '-app.lot.max_puja') }}
			<strong>
				<span id="tuorden">
					@if (!empty($data['js_item']['user']['ordenMaxima']))
						@if (!empty($lote_actual->max_puja) && $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'])
							{{ $lote_actual->formatted_actual_bid }}
						@else
							{{ $data['js_item']['user']['ordenMaxima'] }}
						@endif
					@endif
				</span>
				{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
				@if (\Config::get('app.exchange'))
					| <span id="yourOrderExchange_JS" class="exchange"> </span>
				@endif
			</strong><br><br>



		</div>
	</div>
	<div class="col-xs-12 no-padding info-ficha-buy-info-price d-flex">

		@if ($lote_actual->ocultarps_asigl0 != 'S')
			<div class="pre">
				<p class="pre-title">{{ trans(\Config::get('app.theme') . '-app.lot.lot-price') }}</p>
				<p class="pre-price">{{ $lote_actual->formatted_impsalhces_asigl0 }}
					{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
					@if (\Config::get('app.exchange'))
						| <span id="startPriceExchange_JS" class="exchange"> </span>
					@endif
				</p>
			</div>
		@endif

		@if (!empty($lote_actual->imptas_asigl0))
			<div class="pre">
				<p class="pre-title">{{ trans(\Config::get('app.theme') . '-app.lot.estimatelow') }}</p>
				<p class="pre-price pre-price-estimate">{{ \Tools::moneyFormat($lote_actual->imptas_asigl0) }}
					{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}


				</p>
			</div>
		@endif
		@if (!empty($lote_actual->imptash_asigl0))
			<div class="pre">
				<p class="pre-title">{{ trans(\Config::get('app.theme') . '-app.lot.estimatehigh') }}</p>
				<p class="pre-price pre-price-estimate">{{ \Tools::moneyFormat($lote_actual->imptash_asigl0) }}
					{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}


				</p>
			</div>
		@endif

	</div>
	<div class=" col-xs-12 no-padding info-ficha-buy-info-price">

		<div id="text_actual_max_bid"
			class="d-flex pre-price price-title-principal <?= count($lote_actual->pujas) > 0 ? '' : 'hidden' ?>">
			<div class="pre pre-actual_max_bid">
				<p class="pre-title">{{ trans(\Config::get('app.theme') . '-app.lot.puja_actual') }}</p>
				<strong>
					{{-- aparecera en rojo(clase other) si no eres el ganador y en verde si loeres (clase mine) , si no estas logeado no se modifica el color --}}
					@if (Session::has('user'))
						@php($class = !empty($lote_actual->max_puja) && $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'] ? 'mine' : 'other')
					@else
						@php($class = '')
					@endif
					<span id="actual_max_bid" class="{{ $class }}">{{ $lote_actual->formatted_actual_bid }}
						{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}</span>
					@if (\Config::get('app.exchange'))
						| <span id="actualBidExchange_JS" class="exchange"> </span>
					@endif


				</strong>
			</div>
			<div class="pre">
				@if (isset($lote_actual->impres_asigl0) && $lote_actual->impres_asigl0 > 0 && Session::has('user'))
					<div class="pre_min">

						<p class='pre-title'> {{ trans(\Config::get('app.theme') . '-app.subastas.price_minim') }}: </p>
						<strong>
							<span
								class="precio_minimo_alcanzado mine hidden">{{ trans(\Config::get('app.theme') . '-app.subastas.reached') }}</span>
							<span
								class="precio_minimo_no_alcanzado other hidden">{{ trans(\Config::get('app.theme') . '-app.subastas.no_reached') }}</span>
						</strong>

					</div>
				@endif
			</div>
		</div>

	</div>
	<div class="col-xs-12 no-padding info-ficha-buy-info-price border-top-bottom">
		<div class="pre d-flex mt-2 mb-2 ">
			<div id="text_actual_no_bid"
				class="price-title-principal pre col-xs-12 col-sm-3 no-padding <?= count($lote_actual->pujas) > 0 ? 'hidden' : '' ?>">
				{{ trans(\Config::get('app.theme') . '-app.lot_list.no_bids') }}
			</div>

			<div class="col-xs-12 col-sm-9 no-padding">
				@if ($hay_pujas)
					<p class='explanation_bid t_insert pre-title'>{{ trans(\Config::get('app.theme') . '-app.lot.next_min_bid') }}
					</p>
				@else
					<p class='explanation_bid t_insert pre-title'>{{ trans(\Config::get('app.theme') . '-app.lot.min_puja') }} </p>
				@endif
				<strong><span class="siguiente_puja"> </span>{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
					@if (\Config::get('app.exchange'))
						| <span id="nextBidExchange_JS" class="exchange"> </span>
					@endif
				</strong>
			</div>

		</div>
	</div>

	@if ($start_session || $subasta_abierta_P)
		<div class="insert-bid-input col-lg-10 col-lg-offset-1 d-flex justify-content-center flex-column">

			@if (Session::has('user') && Session::get('user.admin'))
				<div class="d-block w-100">
					<input id="ges_cod_licit" name="ges_cod_licit" class="form-control" type="text" value="" type="text"
						style="border: 1px solid red;" placeholder="Código de licitador">
					@if ($subasta_abierta_P)
						<input type="hidden" id="tipo_puja_gestor" value="abiertaP">
					@endif
				</div>
			@endif

				<div class="input-group d-block group-pujar-custom ">
					<div>
						<div class="insert-bid insert-max-bid mb-1">{{ trans(\Config::get('app.theme') . '-app.lot.insert_max_puja') }}
						</div>
					</div>
					<div class="d-flex mb-2">
						<input id="bid_amount" placeholder="{{ $data['precio_salida'] }}" class="form-control control-number"
							type="text" value="{{ $data['precio_salida'] }}">
						<div class="input-group-btn">
							@if (Session::has('user'))
							<button type="button" data-from="modal"
								class=" lot-action_pujar_on_line ficha-btn-bid ficha-btn-bid-height button-principal <?= Session::has('user') ? 'add_favs' : '' ?>"
								type="button" ref="{{ $lote_actual->ref_asigl0 }}" ref="{{ $lote_actual->ref_asigl0 }}"
								codsub="{{ $lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme') . '-app.lot.pujar') }}</button>
							@else
								<button type="button" data-from="modal" id="js-ficha-login"
									class="ficha-btn-bid ficha-btn-bid-height button-principal"
									type="button">{{ trans(\Config::get('app.theme') . '-app.lot.pujar') }}</button>
							@endif
						</div>
					</div>







			@if (\Config::get('app.urlToPackengers'))
				<?php
				$lotFotURL = $lote_actual->cod_sub . '-' . $lote_actual->ref_asigl0;
				$urlCompletePackengers = \Config::get('app.urlToPackengers') . $lotFotURL;
				?>
				<div class="mt-1 mb-1 text-center">
					<div class="packengers-container-button-ficha">
						<a class="packengers-button-ficha" href="{{ $urlCompletePackengers }}" target="_blank">
							<i class="fa fa-truck" aria-hidden="true"></i>
							{{ trans("$theme-app.lot.packengers_ficha") }}
						</a>
					</div>
				</div>
			@endif


		</div>
	@else

	<div class="mt-1 mb-2 text-center">

		<p> {{ trans("$theme-app.lot.bid_start_text") }} <span id="inicio_fecha"></span></p>

	</div>

	@endif

	@if (empty($data['usuario']))
	<div class="text-center mt-2 mb-2">
		<a class="btn btn-white" href="{{ config('app.custom_login_url') }}&context_url={{ url()->current() }}">{{ trans("$theme-app.lot.register_here") }}</a>
	</div>
	@endif


	<?php //solo se debe recargar la fecha en las subatsas tipo Online, ne las abiertas tipo P no se debe ejecutar
	?>
	@if ($subasta_online)
		<script>
			$(document).ready(function() {

				$("#inicio_fecha").html(format_date_large(new Date("{{$lote_actual->start_session}}".replace(/-/g, "/")),''));

				$("#actual_max_bid").bind('DOMNodeInserted', function(event) {
					if (event.type == 'DOMNodeInserted') {

						$.ajax({
							type: "GET",
							url: "/lot/getfechafin",
							data: {
								cod: cod_sub,
								ref: ref
							},
							success: function(data) {

								if (data.status == 'success') {
									$(".timer").data('ini', new Date().getTime());
									$(".timer").data('countdownficha', data.countdown);
									//var close_date = new Date(data.close_at * 1000);
									// $("#cierre_lote").html(close_date.toLocaleDateString('es-ES') + " " + close_date.toLocaleTimeString('es-ES'));
									$("#cierre_lote").html(format_date_large(new Date(data.close_at *
										1000), ''));
								}


							}
						});
					}
				});
			});
		</script>
	@endif
</div>
