<?php
//Precio Financiado
/* $importeFinanciado = 0;
$importeQuota = 0;
if(!empty($caracteristicas[44])){
	$importeFinanciado = \Tools::moneyFormat($caracteristicas[44]->value_caracteristicas_hces1, trans("$theme-app.subastas.euros"));
}
if(!empty($caracteristicas[52])){
	$importeQuota = \Tools::moneyFormat($caracteristicas[52]->value_caracteristicas_hces1, trans("$theme-app.subastas.euros"), 2);
} */

$importeFinanziadoMax = $caracteristicas[57]->value_caracteristicas_hces1 ?? 0;
$importeFinanziadoMin = $caracteristicas[58]->value_caracteristicas_hces1 ?? 0;
$importeCuotaMax = $caracteristicas[59]->value_caracteristicas_hces1 ?? 0;
$importeCuotaMin = $caracteristicas[60]->value_caracteristicas_hces1 ?? 0;
$importeMax = $caracteristicas[61]->value_caracteristicas_hces1 ?? 0;
$importeMin = $caracteristicas[62]->value_caracteristicas_hces1 ?? 0;
?>

<div class="col-xs-12 no-padding ">

	<div class="ficha_V col-xs-12 header-info-ficha-prices info-ficha-buy-info-price d-flex">

		<div class="ficha_v_title text-center">
			<p>{{ trans("$theme-app.lot.price_range") }}
				<i class="fa fa-info-circle ml-1 js-modal"
					data-content="{{ trans("$theme-app.lot.price_range_info") }}" data-title="{{ trans("$theme-app.lot.price_range") }}" aria-hidden="true"></i>
			</p>
		</div>

		<div class="ficha_v_price_contado text-center">
			<div class="row">
				<div class="col-xs-12 col-md-6">
					<p>{{ trans("$theme-app.lot.cash") }}</p>
				</div>
				<div class="col-xs-12 col-md-6 prices_block">
					<p>
						@if($importeMax || $importeMin)
						{{ $importeMax ? \Tools::moneyFormat($importeMax, trans("$theme-app.subastas.euros"), 0) : trans("$theme-app.lot.n_a") }}
						<span>-</span>
						{{ $importeMin ? \Tools::moneyFormat($importeMin, trans("$theme-app.subastas.euros"), 0) : trans("$theme-app.lot.n_a") }}
						@else
						{{ trans("$theme-app.lot.cash_na") }}
						@endif
					</p>
				</div>
			</div>
		</div>

		<div class="ficha_v_price_financiado text-center">
			<div class="row">
				<div class="col-xs-12 col-md-6">
					<p class="financiado_title">
						{{ trans("$theme-app.lot.financed") }}
						<i class="fa fa-info-circle ml-1 js-modal"
						data-content="{{ trans("$theme-app.lot.financed_monthly_fee_info") }}" data-title="{{ trans("$theme-app.lot.financed_monthly_fee") }}" aria-hidden="true"></i>
					</p>
				</div>
				<div class="col-xs-12 col-md-6 prices_block">
					<p>
						@if($importeFinanziadoMax || $importeFinanziadoMin)
						{{ $importeFinanziadoMax ? \Tools::moneyFormat($importeFinanziadoMax, trans("$theme-app.subastas.euros"), 0) : trans("$theme-app.lot.n_a") }}
						<span>-</span>
						{{ $importeFinanziadoMin ? \Tools::moneyFormat($importeFinanziadoMin, trans("$theme-app.subastas.euros"), 0) : trans("$theme-app.lot.n_a") }}
						@else
						{{ trans("$theme-app.lot.financed_na") }}
						@endif
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-md-6">
					<p class="monthly-fee-title">
						{{ trans("$theme-app.lot.monthly_fee") }}
						<i class="fa fa-info-circle ml-1 js-modal"
						data-content="{{ trans("$theme-app.lot.financed_monthly_fee_info") }}" data-title="{{ trans("$theme-app.lot.financed_monthly_fee") }}" aria-hidden="true"></i>
					</p>
				</div>
				<div class="col-xs-12 col-md-6 prices_block">
					<p>
						@if($importeCuotaMax || $importeCuotaMin)
						{{ $importeCuotaMax ? \Tools::moneyFormat($importeCuotaMax, trans("$theme-app.subastas.euros"), 0) : trans("$theme-app.lot.n_a") }}
						<span>-</span>
						{{ $importeCuotaMin ? \Tools::moneyFormat($importeCuotaMin, trans("$theme-app.subastas.euros"), 0) : trans("$theme-app.lot.n_a") }}
						@else
						{{ trans("$theme-app.lot.monthly_fee_na") }}
						@endif
					</p>
				</div>
			</div>
		</div>


		{{-- precio de financiado --}}
		{{-- @if(!empty($importeFinanciado))
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
		@endif --}}

		{{-- precio de venta --}}
		{{-- <div class="pre pre-impventa d-flex flex-column justify-content-space-between">
			<p class="pre-title">{{ trans("$theme-app.lot.price_cash") }}
				<i  class="fa fa-info-circle ml-1 js-modal" aria-hidden="true"
				data-content="{{ trans("$theme-app.lot.price_cash_info") }}" data-title="{{ trans("$theme-app.lot.price_cash") }}" >
				</i>
			</p>
			<p class="pre-price">{{ $lote_actual->formatted_impsalhces_asigl0 }}
				{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
			</p>
		</div> --}}

	</div>



	<div class="col-xs-12 info-ficha-buy-info-price info-ficha-buy-inside info-next-bid">

		<p class="mb-2">
			<small style="font-size: 15px">
				Los Precios al Contado del rango se basan en datos reales.<br>
				Las Cuotas Mensuales y Precios Financiados son orientativos.
			</small>
		</p>

		{{-- Comprar ya --}}
		@if(!empty($lote_actual->impsalhces_asigl0) && !$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
		{{-- <div class="mt-1 hidden">
			<div class="mb-1">{{ trans("$theme-app.lot.buy_now") }}
				<i class="fa fa-info-circle ml-1 js-modal" aria-hidden="true"
					data-title="{{ trans("$theme-app.lot.buy_now") }}" data-content="{{ trans("$theme-app.lot.buy_now_info") }}">
				</i>
			</div>
		</div> --}}
		<div class="d-flex mb-2 hidden">
			{{-- <input class="form-control control-number input-buy-online" type="text"
				value="{{ $lote_actual->impsalhces_asigl0 }}" readonly="readonly"> --}}
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
		@if(!$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
		<div class="contraoferta-block">
			<div class="mb-1">
				{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}
				<i class="fa fa-info-circle ml-1 js-modal" aria-hidden="true"
					data-title="{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}" data-content="{{ trans(\Config::get('app.theme').'-app.lot.buy_lot_info') }}">
				</i>
				<p>(Al Contado, aunque luego financies la compra a un precio inferior).</p>
			</div>

		</div>
		<div class="d-flex mb-2">
			<input
				id="counteroffer-input"
				class="form-control control-number input-buy-online" type="text" value="" autocomplete="off">

			<input type="hidden" name="counteroffer-without-user-route" value="">

			<div class="input-group-btn">
				<button id="contraofertarEvent_JS" type="button" data-from="modal" class="ficha-btn-bid ficha-btn-bid-height button-principal btn-contraofertar lot-action_contraofertar"
					ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}" type="button">
					ENVIAR
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
