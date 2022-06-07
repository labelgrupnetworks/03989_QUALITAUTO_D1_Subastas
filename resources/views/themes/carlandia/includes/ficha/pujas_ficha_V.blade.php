<?php
//Precio Financiado
$importeFinanciado = 0;
$importeQuota = 0;
if(!empty($caracteristicas[44])){
	$importeFinanciado = \Tools::moneyFormat($caracteristicas[44]->value_caracteristicas_hces1, trans("$theme-app.subastas.euros"));
}
if(!empty($caracteristicas[52])){
	$importeQuota = \Tools::moneyFormat($caracteristicas[52]->value_caracteristicas_hces1, trans("$theme-app.subastas.euros"), 2);
}
?>

<div class="col-xs-12 no-padding ">

	<div class="ficha_V col-xs-12 header-info-ficha-prices info-ficha-buy-info-price d-flex">

		{{-- precio de financiado --}}
		@if(!empty($importeFinanciado))
		<div class="pre pre-impsal">
			<p class="pre-title">{{ trans("$theme-app.lot.price_financed") }} <i
					class="fa fa-info-circle ml-1 js-modal"
					data-content="{{ trans("$theme-app.lot.price_financed_info") }}" data-title="{{ trans("$theme-app.lot.price_financed") }}" aria-hidden="true"></i>
			</p>
			<div class="d-flex justify-content-space-around flex-wrap">
				<div class="info-ficha-price-wrapper">
					<p class="m-0">{{ trans("$theme-app.user_panel.amount") }}</p>
					<p class="pre-price m-0">{{$importeFinanciado}}</p>
				</div>

				@if(!empty($importeQuota))
				<div class="info-ficha-price-wrapper">
					<p class="m-0">{{ trans("$theme-app.lot.quota") }}</p>
					<p class="pre-price m-0">{{$importeQuota}}</p>
				</div>
				@endif
			</div>

		</div>
		@endif

		{{-- precio de venta --}}
		<div class="pre pre-impventa d-flex flex-column justify-content-space-between">
			<p class="pre-title">{{ trans("$theme-app.lot.price_cash") }}
				<i  class="fa fa-info-circle ml-1 js-modal" aria-hidden="true"
				data-content="{{ trans("$theme-app.lot.price_cash_info") }}" data-title="{{ trans("$theme-app.lot.price_cash") }}" >
				</i>
			</p>
			<p class="pre-price">{{ $lote_actual->formatted_impsalhces_asigl0 }}
				{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
			</p>
		</div>

	</div>



	<div class="col-xs-12 info-ficha-buy-info-price info-ficha-buy-inside info-next-bid">

		<p class="mb-2"><small>{{ trans("$theme-app.lot.info_prices_cash") }}</small></p>

		{{-- Comprar ya --}}
		@if(!empty($lote_actual->impsalhces_asigl0) && !$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
		<div class="mt-1">
			<div class="mb-1">{{ trans("$theme-app.lot.buy_now") }}
				<i class="fa fa-info-circle ml-1 js-modal" aria-hidden="true"
					data-title="{{ trans("$theme-app.lot.buy_now") }}" data-content="{{ trans("$theme-app.lot.buy_now_info") }}">
				</i>
			</div>
		</div>
		<div class="d-flex mb-2">
			<input class="form-control control-number input-buy-online" type="text"
				value="{{ $lote_actual->impsalhces_asigl0 }}" readonly="readonly">
			{{-- precio que se pone para los eventos de ga --}}
			<input id="price_compra_ya_JS" type="hidden" value ="{{ $lote_actual->impsalhces_asigl0 }}">
			<div class="input-group-btn">

				<button id="comprarYaEvent_JS" type="button" data-from="modal" class="ficha-btn-bid ficha-btn-bid-height button-principal lot-action_comprar_lot"
					ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}" data-type-puja="{{ \App\Models\V5\FgAsigl1_Aux::PUJREP_ASIGL1_COMPRAR_VD }}">
					{{ trans("$theme-app.lot.accept") }}
				</button>
			</div>
		</div>
		@endif

		{{-- Contraofertar --}}
		@if( !empty($lote_actual->imptas_asigl0) && $lote_actual->imptas_asigl0 >= 0 && $lote_actual->imptas_asigl0 < $lote_actual->impsalhces_asigl0 && !$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
		<div class="mt-1">
			<div class="mb-1">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}
				<i class="fa fa-info-circle ml-1 js-modal" aria-hidden="true"
				data-title="{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}" data-content="{{ trans(\Config::get('app.theme').'-app.lot.buy_lot_info') }}">
			</i>
			</div>
		</div>
		<div class="d-flex mb-2">
			<input
				id="counteroffer-input"
				class="form-control control-number input-buy-online" type="text" value="">

			<div class="input-group-btn">
				<button id="contraofertarEvent_JS" type="button" data-from="modal" class="ficha-btn-bid ficha-btn-bid-height button-principal btn-contraofertar lot-action_contraofertar"
					ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}" type="button">
					{{ trans(\Config::get('app.theme').'-app.lot.counteroffer_btn') }}
				</button>
			</div>
		</div>
		@elseif(!$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
			<div class="mt-1">
				<div class="mb-1">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}
					<i class="fa fa-info-circle ml-1 js-modal" aria-hidden="true"
					data-title="{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}" data-content="{{ trans(\Config::get('app.theme').'-app.lot.buy_lot_info') }}">
				</i>
				</div>
			</div>
			<div class="d-flex mb-2 counteroffer-input-wrapper">
				<input class="form-control control-number input-buy-online" placeholder="{{ trans("$theme-app.lot.counteroffers_not_accepted") }}" type="text" disabled>
				<div class="input-group-btn">
					<button type="button" class="ficha-btn-bid ficha-btn-bid-height button-principal btn-contraofertar lot-action_contraofertar" disabled>
						{{ trans(\Config::get('app.theme').'-app.lot.counteroffer_btn') }}
					</button>
				</div>
			</div>
		@endif

	</div>


</div>
