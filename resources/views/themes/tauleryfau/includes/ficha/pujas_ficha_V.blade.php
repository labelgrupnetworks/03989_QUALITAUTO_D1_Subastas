<div>
    <div class="single-lot-desc-title desc">
        <h3>{{ trans($theme.'-app.lot.description') }}</h3>
        <p class="sub-o">
            <?php // entraran tanto lotes de subastas V como cerrados de otra con posibilidad de compra  ?>
            @if($lote_actual->tipo_sub == 'W')
                {{ trans($theme.'-app.subastas.lot_subasta_presencial') }}
            @elseif($lote_actual->tipo_sub == 'O')
                {{ trans($theme.'-app.subastas.lot_subasta_online') }}
            @endif
        </p>
    </div>
    <div>
        <div class="single-lot-desc-content" id="box">
            @if( \Config::get('app.descweb_hces1'))
                <?= $lote_actual->descweb_hces1 ?>
            @elseif ( \Config::get('app.desc_hces1' ))
                <?= $lote_actual->desc_hces1 ?>
            @endif
        </div>
    </div>
</div>

<div class="info_single ficha_V">
    <div class="info_single_title">
        <div class="sub-o hidden">
            @if($lote_actual->cerrado_asigl0 == 'N')
                <span class="clock timer">
                <span class="clock"><i class="fa fa-clock-o"></i><span data-countdown="{{ strtotime($lote_actual->end_session) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($lote_actual->end_session); ?>" data-closed="{{ $lote_actual->cerrado_asigl0 }}" class="timer"></span>
                    </span>
                </span>
            @endif
        </div>
    </div>

    <div class="exit-price prices">
        <?php /* PRECIO ESTIMADO Comentado por cambio de diseño por del cliente 01/12/2019 Eloy
            <div class="price">
                @if(!empty($lote_actual->oferta_asigl0) && ($lote_actual->oferta_asigl0 == 1 || $lote_actual->oferta_asigl0 == 2))
                    <span class="pre title">{{ trans($theme.'-app.subastas.price_salida_venta') }}</span>
                @else
                    <span class="pre title">{{ trans($theme.'-app.subastas.precio_estimado') }} </span>
                @endif
                <div class="text-right">
                <span class="pre <?=!empty( $lote_actual->oferta_asigl0) && $lote_actual->oferta_asigl0 == 1?'tachado':'';?>">{{ $lote_actual->formatted_imptas_asigl0 }}  {{ trans($theme.'-app.subastas.euros') }}</span>
                <div class="vertical-bar" >|</div>
                <span id="impsalexchange-tas" class="currency-trnas <?=!empty( $lote_actual->oferta_asigl0) && $lote_actual->oferta_asigl0 == 1?'tachado':'';?>"></span>


            </div>
            </div>
         * <div class="divider-prices"></div>
     */?>

            <style>
                .exit-price.prices{
                    margin-bottom: 30px;
                }
            </style>


                <div class="price precio-venta">
                    @if(!empty($lote_actual->oferta_asigl0) && ($lote_actual->oferta_asigl0 == 1 || $lote_actual->oferta_asigl0 == 2))
                        <span class="pre title">{{ trans($theme.'-app.subastas.nuestro_precio') }}</span>
                    @else
                        <span class="pre title">{{ trans($theme.'-app.subastas.price_sale') }}</span>
                    @endif
                    <div class="text-right">
                    <span class="">{{$lote_actual->formatted_actual_bid}}  {{ trans($theme.'-app.subastas.euros') }} </span>
                            <div class="vertical-bar" >|</div>
                            <span id="impsalexchange-actual" class="currency-trnas"></span>

                    </div>
                </div>
            <?php /* no ponemos CET   <span id="cet_o"> {{ trans($theme.'-app.lot.cet') }}</span> */ ?>
        </div>
    <?php
    //   capturando la conversion de la moneda
         $moneda=\Tools::conservationCurrency($lote_actual->num_hces1, $lote_actual->lin_hces1, array("conservation_1","conservation_2"));

    ?>
	@if ($lote_actual->retirado_asigl0 == 'N' && empty($lote_actual->himp_csub) && ($lote_actual->subc_sub == 'S' || $lote_actual->subc_sub == 'A'))

    <div class="botones-puja-vetadirecta">
            <div class="input-group direct-puja">
                <button data-from="modal" class="lot-action_comprar_lot btn-color" type="button" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}"><i class="fa fa-shopping-cart" aria-hidden="true"></i> {{ trans($theme.'-app.subastas.buy_lot') }}</button>
            </div>

            </div>
        @endif
    </div>






