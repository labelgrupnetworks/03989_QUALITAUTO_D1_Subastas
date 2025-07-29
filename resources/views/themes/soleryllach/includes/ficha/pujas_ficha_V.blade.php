@php
	use App\Support\Date;

    $showBuyButton =
        $lote_actual->retirado_asigl0 == 'N' &&
        empty($lote_actual->himp_csub) &&
        ($lote_actual->subc_sub == 'S' || $lote_actual->subc_sub == 'A');

	$endSession = Date::toISOFormat($lote_actual->end_session, 'DD MMMM YYYY | HH\h mm');
@endphp

<div class="ficha_V">
    <div class="ficha_prices">
        <p class="price_label">{{ trans('web.subastas.price_sale') }}</p>
        <p class="price_value">{{ $lote_actual->formatted_actual_bid }} {{ trans('web.subastas.euros') }}</p>
    </div>

    @if ($showBuyButton)
        <div class="ficha_actions">
            <button class="lot-action_comprar_lot btn btn-bold btn-color" data-from="modal" type="button"
                ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}"
                codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">
                {{ trans('web.subastas.buy_lot') }}
            </button>
        </div>
    @endif

    <div class="ficha_shares">
        <p>{{ $endSession }}</p>
        @include('includes.ficha.share')
    </div>

</div>
