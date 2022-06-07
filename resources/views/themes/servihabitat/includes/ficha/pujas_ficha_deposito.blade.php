<div class="col-lg-12 col-md-12 info-ficha-buy-info no-padding">
    <div class=" col-xs-12 no-padding info-ficha-buy-info-price d-flex">

			@if($lote_actual->ocultarps_asigl0 != 'S')
            <div class="pre">
                <p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
                <p class="pre-price">{{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
					@if(\Config::get("app.exchange"))
					| <span id="startPriceExchange_JS" class="exchange"> </span>
					@endif
				</p>
			@endif

			</div>
			@if(!empty($lote_actual->imptas_asigl0))
				<div class="pre">
					<p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.estimate') }}</p>
					<p class="pre-price">{{ \Tools::moneyFormat($lote_actual->imptas_asigl0)}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
						@if(\Config::get("app.exchange"))
						| <span id="estimateExchange_JS" class="exchange"> </span>
						@endif
					</p>

				</div>
			@endif
    </div>
</div>

<div  class="col-lg-12 col-md-12 info-ficha-buy-info no-padding">
	@include('front::includes.ficha.ficha_conditions')
</div>

<div  class="col-lg-12 col-md-12 info-ficha-buy-info no-padding">
	<div class="info_single_content info_single_button ficha-button-buy">
		@if (!$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
			<button data-from="modal" class="button-principal lot-action_info_lot" type="button" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.subastas.ask_information') }}</button>
		@endif
	</div>
</div>
