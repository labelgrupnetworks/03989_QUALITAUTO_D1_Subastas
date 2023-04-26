@php
$importe = \Tools::moneyFormat($lote_actual->actual_bid);
$importeExchange = $lote_actual->actual_bid;
if(!empty($lote_actual->impres_asigl0) && $lote_actual->impres_asigl0 >  $lote_actual->impsalhces_asigl0){
	$importe =  \Tools::moneyFormat($lote_actual->impres_asigl0);
	$importeExchange = $lote_actual->impres_asigl0;
}
@endphp

<div class="ficha-pujas ficha-venta">

	{{-- Precio venta --}}
	<h4 class="price sold-price mb-4">
		<span>{{ trans("$theme-app.subastas.price_sale") }}</span>
		<span>
			{{ $importe }} {{ trans("$theme-app.subastas.euros") }}
			@if(\Config::get("app.exchange"))
				|   <span id="directSaleExchange_JS" class="exchange"> </span>
				<input id="startPriceDirectSale" type="hidden" value="{{$importeExchange}}">
			@endif
		</span>
	</h4>


	@if (!$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
		{{-- Si el lote es NFT y el usuario está logeado pero no tiene wallet --}}
		@if ($lote_actual->es_nft_asigl0 == "S" &&  !empty($data["usuario"])  && empty($data["usuario"]->wallet_cli) )
			<p class="require-wallet mb-4">{!! trans("$theme-app.lot.require_wallet") !!}</p>
		@else
			<button class="btn btn-lb-primary w-100 mb-4 lot-action_comprar_lot" type="button" data-from="modal"
				ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">
				{{ trans("$theme-app.subastas.buy_lot") }}
			</button>
		@endif
	@endif

	{{-- Packengers --}}
	@if (config('app.urlToPackengers'))
	@php
	$lotFotURL = $lote_actual->cod_sub . '-' . $lote_actual->ref_asigl0;
	$urlCompletePackengers = \Config::get('app.urlToPackengers') . $lotFotURL;
	@endphp

	<div>
		<a class="d-block btn btn-outline-lb-secondary" href="{{ $urlCompletePackengers }}" target="_blank">
			<svg class="bi" width="16" height="16" fill="currentColor">
				<use xlink:href="/bootstrap-icons.svg#truck"></use>
			</svg>
			{{ trans("$theme-app.lot.packengers_ficha") }}
		</a>
	</div>
	@endif

</div>
