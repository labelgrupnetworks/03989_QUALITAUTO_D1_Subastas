@php
    $currencySimbol = trans("$theme-app.subastas.euros");
    $canPurchasable =
        $lote_actual->retirado_asigl0 == 'N' &&
        empty($lote_actual->himp_csub) &&
        ($lote_actual->subc_sub == 'S' || $lote_actual->subc_sub == 'A');
@endphp
<div class="ficha-puja-v">

    <div class="ficha-info-block">
        <span>{{ trans("$theme-app.lot.total_pagar") }}:</span>
        <span>
            @if ($lote_actual->sub_hces1 == 'VDJ')
                {{ Tools::moneyFormat($lote_actual->impsalhces_asigl0, $currencySimbol, 2) }}
            @else
                {{ Tools::moneyFormat($lote_actual->impsalhces_asigl0 + $lote_actual->impsalhces_asigl0 * ($lote_actual->comlhces_asigl0 / 100) * 1.21, $currencySimbol, 2) }}
            @endif
    </div>


    @if ($canPurchasable)
        <div class="ficha-info-block">
            <div class="">
                <button class="lot-action_comprar_lot btn btn-block btn-lg" data-from="modal" type="button"
                    ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    {{ trans("$theme-app.subastas.buy_lot") }}
                </button>
            </div>

            <div class="ficha-commision">
                @if ($lote_actual->sub_hces1 == 'VDJ')
                    {!! trans("$theme-app.lot.price_commission_vdj") !!}
                @else
                    {!! trans($theme . '-app.lot.price_commission', [
                        'precio_salida' => \Tools::moneyFormat($lote_actual->impsalhces_asigl0),
                        'comision' => \Tools::moneyFormat($lote_actual->impsalhces_asigl0 * ($lote_actual->comlhces_asigl0 / 100)),
                        'iva_comision' => \Tools::moneyFormat(
                            $lote_actual->impsalhces_asigl0 * ($lote_actual->comlhces_asigl0 / 100) * 0.21,
                            '',
                            2,
                        ),
                    ]) !!}
                @endif
            </div>
        </div>
    @endif

</div>

{{-- @if (Session::has('user') && $lote_actual->retirado_asigl0 == 'N')
<a class="btn {{ $lote_actual->favorito ? 'hidden' : '' }}" id="add_fav"
	href="javascript:action_fav_modal('add')">
	<i class="fa fa-heart-o" aria-hidden="true"></i>
</a>
<a class="btn {{ $lote_actual->favorito ? '' : 'hidden' }}" id="del_fav"
	href="javascript:action_fav_modal('remove')">
	<i class="fa fa-heart" aria-hidden="true"></i>
</a>
@endif --}}
