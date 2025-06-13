@php
    $importe = Tools::moneyFormat($lote_actual->actual_bid);
    $importeExchange = $lote_actual->actual_bid;
    if (!empty($lote_actual->impres_asigl0) && $lote_actual->impres_asigl0 > $lote_actual->impsalhces_asigl0) {
        $importe = Tools::moneyFormat($lote_actual->impres_asigl0);
        $importeExchange = $lote_actual->impres_asigl0;
    }
@endphp

<div class="lot-sold ficha-v">
    <div class="w-100 d-flex align-items-center justify-content-between mb-5">
        <div>
            <p class="ff-highlight ficha-lot-price mb-3">
                {{ trans("$theme-app.global.precio") . ' ' . $importe . ' ' . trans("$theme-app.subastas.euros") }}
            </p>

            <p>{{ trans("$theme-app.lot.commission_vat_incl") }}</p>
        </div>

		<button class="lot-action_comprar_lot btn btn-lb-primary btn-medium" data-from="modal" type="button"
			ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}"
			codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">
			{{ trans("$theme-app.lot.buy") }}
		</button>

    </div>

    @if (Config::get('app.urlToPackengers'))
	<div class="mb-5">
        @php
            $lotReference = str_replace('.', '-', $lote_actual->ref_asigl0);
            $lotFotURL = "$lote_actual->cod_sub-$lotReference";
        @endphp
        <a class="btn btn-small btn-outline-lb-primary btn-packengers gap-2 w-100"
            href="{{ Config::get('app.urlToPackengers') . "/{$lotFotURL}?source=estimate" }}" target="_blank">
            <x-icon.boostrap icon="truck" />
            {{ trans("$theme-app.lot.packengers_ficha") }}
        </a>

		<p class="mt-3">
			{!! trans("$theme-app.lot.contact_package_options") !!}
		</p>
	</div>
    @endif

	<p class="mb-3">
		{{ trans("$theme-app.lot.purchase_confirmation") }}
	</p>

	<p>
		{!! trans("$theme-app.lot.terms_apply") !!}
	</p>

</div>
