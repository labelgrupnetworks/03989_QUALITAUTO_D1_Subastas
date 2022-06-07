<div class="col-xs-12 no-padding ">


	<div class="info_single ficha_V col-xs-12 mt-2">

		<div class="row">
			<div class="col-xs-12 ficha-info-items-buy border-top-bottom">

				<div class="pre d-flex justify-content-space-between">
					<p class="pre-price mt-1 mb-1">
						{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}
					</p>
					<p class="pre-price mt-1 mb-1">
						{{$lote_actual->formatted_actual_bid}}
						{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
					</p>
				</div>
			</div>

			<div class="col-xs-12 mt-1 p-0">
				<div class="info_single_content info_single_button ficha-button-buy">
					@if (!$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
					<button data-from="modal" class="button-principal lot-action_comprar_lot w-100" type="button"
						ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}"
						codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</button>
					@endif
				</div>
			</div>
		</div>

	</div>

</div>
