<div class="col-xs-12 no-padding ">


	<div class="info_single ficha_Cust col-xs-12 no-padding">

		<div class="col-xs-12 no-padding ficha-info-items-buy info-ficha-buy-info-price">


			<div class="pre">
				<p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
				<p class="pre-price"> {{\Tools::moneyFormat( $lote_actual->imptas_asigl0, false, 2) }}
					{{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </p>

			</div>


			<div class="info_single_content info_single_button ficha-button-buy">
				@if (!$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
				<button class="button-principal btn-ficha-custom"
					type="button">{{ trans(\Config::get('app.theme').'-app.lot.auction_closed_envelope') }}</button>
				@endif
			</div>
		</div>

	</div>
</div>
