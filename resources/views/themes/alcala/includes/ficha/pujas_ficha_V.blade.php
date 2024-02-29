<div class="col-xs-12 no-padding ">


    <div class="info_single ficha_V col-xs-12 no-padding">

        <div class="col-xs-12 no-padding ficha-info-items-buy">
            <div class="pre">
                    <p class="pre-title-principal">{{ trans($theme.'-app.subastas.price_sale') }}</p>
                    <p class="pre-price">{{$lote_actual->formatted_actual_bid}} {{ trans($theme.'-app.subastas.euros') }}</p>
                </div>
                <div class="info_single_content info_single_button ficha-button-buy">
                    @if (!$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
                        <button data-from="modal" class="button-principal lot-action_comprar_lot" type="button" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans($theme.'-app.subastas.buy_lot') }}</button>
                    @endif
                </div>
            </div>

    </div>
</div>
