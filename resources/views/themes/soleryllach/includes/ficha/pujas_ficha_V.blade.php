<div class="info_single ficha_V col-xs-12">
    <div class="info_single_title col-xs-12">
        <div class="sub-o">

           <?php // entraran tanto lotes de subastas V como cerrados de otra con posibilidad de compra  ?>

            <p>
                @if ($lote_actual->tipo_sub == 'V')
                    {{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_venta') }}
                @elseif($lote_actual->tipo_sub == 'W')
                    {{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_presencial') }}
                @elseif($lote_actual->tipo_sub == 'O')
                    {{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_online') }}
                @endif
            </p>

            @if($lote_actual->cerrado_asigl0 == 'N')
                <span class="clock"><i class="fa fa-clock-o"></i><span data-countdown="{{ strtotime($lote_actual->end_session) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($lote_actual->end_session); ?>" data-closed="{{ $lote_actual->cerrado_asigl0 }}" class="timer"></span>
            @endif
        </div>
        <div class="date_top_side_small">

            @if($lote_actual->cerrado_asigl0 == 'N')
                <span class="cierre_lote"></span>
            @endif
          <?php /* no ponemos CET   <span id="cet_o"> {{ trans(\Config::get('app.theme').'-app.lot.cet') }}</span> */ ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6">
        <p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</p>
        <div class="pre">{{$lote_actual->formatted_actual_bid}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
    </div>
    <div class="col-xs-12">
        <div class="info_single_content info_single_button">
            @if ($lote_actual->retirado_asigl0 == 'N' && empty($lote_actual->himp_csub) && ($lote_actual->subc_sub == 'S' || $lote_actual->subc_sub == 'A'))
            <br>   <button data-from="modal" class="lot-action_comprar_lot btn btn-lg btn-custom btn-color" type="button" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}"><i class="fa fa-shopping-cart" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</button>
           <br> <br>
           @endif
        </div>
    </div>

</div>

<script>
   $(document).ready(function() {
        //calculamos la fecha de cierre
     //   $("#cierre_lote").html(format_date_large(new Date("{{$lote_actual->end_session}}".replace(/-/g, "/"))),"");
        $(".cierre_lote").html(format_date_large(new Date("{{$lote_actual->end_session}}".replace(/-/g, "/")),''));

    });
</script>




