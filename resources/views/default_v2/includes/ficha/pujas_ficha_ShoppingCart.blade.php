@php
    $importe = \Tools::moneyFormat($lote_actual->actual_bid, '', 2);
    if (!empty($lote_actual->impres_asigl0) && $lote_actual->impres_asigl0 > $lote_actual->impsalhces_asigl0) {
        $importe = \Tools::moneyFormat($lote_actual->impres_asigl0, '', 2);
    }
@endphp

<div class="ficha-pujas ficha-shopping">

    {{-- Precio venta --}}
    <h4 class="price sold-price mb-4">
        <span>{{ trans("$theme-app.subastas.price_sale") }}</span>
        <span>
            {{ $importe }} {{ trans("$theme-app.subastas.euros") }}

            @if (Config::get('app.exchange'))
                | <span class="exchange" id="directSaleExchange_JS"> </span>
                <input id="startPriceDirectSale" type="hidden" value="{{ $importeExchange }}">
            @endif
        </span>
    </h4>

    @if (!$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
        {{-- Si el lote es NFT y el usuario está logeado pero no tiene wallet --}}
        @if ($lote_actual->es_nft_asigl0 == 'S' && !empty($data['usuario']) && empty($data['usuario']->wallet_cli))
            <p class="require-wallet mb-4">{!! trans("$theme-app.lot.require_wallet") !!}</p>
        @else
            <button class="btn btn-lb-primary w-100 mt-auto addShippingCart_JS" data-from="modal" type="button"
                ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}"
                codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">
                {{ trans("$theme-app.subastas.buy_lot") }}
            </button>
            {{-- código de token --}}
            @csrf
        @endif
    @endif

</div>
