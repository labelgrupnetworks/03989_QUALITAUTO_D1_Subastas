


<?php
$precio_venta=NULL;
if (!empty($lote_actual->himp_csub)){
    $precio_venta=$lote_actual->himp_csub;
}
//si es un histórico y la subasta del asigl0 = a la del hces1 es que no está en otra subasta y podemso coger su valor de compra de implic_hces1
elseif($lote_actual->subc_sub == 'H' && $lote_actual->cod_sub == $lote_actual->sub_hces1 && $lote_actual->lic_hces1 == 'S' and $lote_actual->implic_hces1 >0){
    $precio_venta = $lote_actual->implic_hces1;
}

?>

<div>
    <div class="single-lot-desc-title desc">
        <h3>{{ trans(\Config::get('app.theme').'-app.lot.description') }}</h3>
        @if($lote_actual->tipo_sub != 'V' )
        <p class="sub-o">{{ trans(\Config::get('app.theme').'-app.subastas.finalized') }}</p>
        @endif
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


<div class="info_single ">
        <div class="info_single_title">
            <div class="exit-price prices">

                    @if($lote_actual->tipo_sub == 'V' )
                    <div class="price  <?=!empty( $lote_actual->oferta_asigl0) && $lote_actual->oferta_asigl0 == 1?'tachado':'';?>">
                        @if(!empty( $lote_actual->oferta_asigl0) && $lote_actual->oferta_asigl0 == 1)
                            <span class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.price_salida') }}</span>
                        @elseif((empty( $lote_actual->oferta_asigl0)) || (!empty( $lote_actual->oferta_asigl0) && $lote_actual->oferta_asigl0 == 2))
                            <span class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.precio_estimado') }}</span>
                        @endif
                        <span class="pre">{{$lote_actual->formatted_imptas_asigl0}}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                    </div>
                    <div class="divider-prices"></div>
                    @endif
                    <div class="price starting-price" >
                        @if($lote_actual->tipo_sub == 'V' &&!empty($lote_actual->oferta_asigl0) && ($lote_actual->oferta_asigl0 == 1 || $lote_actual->oferta_asigl0 == 2))
                            <span class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.nuestro_precio') }}</span>
                        @elseif($lote_actual->tipo_sub == 'V' )
                            <span class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</span>
                        @else
                            <span class="pre">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</span>
                        @endif
                        <span id="impsalexchange" class="currency-trnas" style="float:right; margin-top:4px; margin-left: 5px;" >
                            {{ trans(\Config::get('app.theme').'-app.subastas.dolares') }} {{ $lote_actual->formatted_impsalhces_asigl0}}
                        </span>
                        <span class="pre" style="float:right;">
                            {{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}  |
                        </span>

                    </div>

                @if($lote_actual->cerrado_asigl0 == 'S' && $lote_actual->tipo_sub == 'V' )

                @elseif($lote_actual->cerrado_asigl0 == 'S' && ((!empty($precio_venta) &&  $lote_actual->remate_asigl0 !='S') || $lote_actual->desadju_asigl0 =='S' ))
                <div class="price price-awarded" style="text-align: center;">
                    <p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</p>
                </div>
                @elseif($lote_actual->cerrado_asigl0 == 'S' && $lote_actual->remate_asigl0 =='S' && (!empty($precio_venta))|| ($lote_actual->subc_sub == 'H' && !empty($lote_actual->impadj_asigl0)) )
                    @if($lote_actual->subc_sub == 'H' && !empty($lote_actual->impadj_asigl0))
                        @php($precio_venta = $lote_actual->impadj_asigl0)
                    @endif
                <div class="price price-awarded">
                    <p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}: {{\Tools::moneyFormat($precio_venta)}} €</p>
                </div>
                <div class="price-awarded-salechange">
                    <p id="impsalexchange-actual" class="" style="margin: 0px; font-size: 14px;"></p>
                </div>
                @elseif(strtoupper($lote_actual->tipo_sub) == 'V' && $lote_actual->cerrado_asigl0 == 'N' && $lote_actual->end_session > date("now"))

                <div class="price" style="text-align: center;">
                    <p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</p>
                </div>
                @elseif($lote_actual->cerrado_asigl0 == 'S' && empty($precio_venta) )

                <div class="price text-center">
                    <p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>

                </div>
                @elseif($lote_actual->cerrado_asigl0 == 'D')

                <div class="price text-center">
                    <p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>

                </div>
                @endif
        </div>

    </div>
    <?php
    //   capturando la conversion de la moneda

    /*
    $moneda=\Tools::conservationCurrency( $data['subasta_info']->lote_actual->num_hces1,  $data['subasta_info']->lote_actual->lin_hces1, array("conservation_1","conservation_2"));


    <div class="input-group direct-puja">
        <div class='explanation_bid text-right' >
            <a data-toggle="modal" data-target="#currency-types" href=""><span><i class="fa fa-info-circle fa-lg"></i></span>
            </a>
        </div>
        <div class="currency-input">
            @if ($lote_actual->tipo_sub != 'V' && !empty($moneda) && !empty($moneda->conservation_1))
                <button data-toggle="modal" data-target="#currency-types"  class="currency-show-button" style="width: 100%; margin-right: 0px;">
                    {{ !empty($moneda->conservation_2)?  $moneda->conservation_1.' / '.$moneda->conservation_2: $moneda->conservation_1}}
                </button>
            @endif
        </div>
    </div>
</div>
                    */?>

@if($lote_actual->cerrado_asigl0 == 'S' && $lote_actual->tipo_sub == 'V' )
<div class="exit-price prices finalized">
<div class="price text-center" style="text-align: center;">
            <p class="pre text-center">
                @if($lote_actual->cerrado_asigl0 == 'S' && $lote_actual->tipo_sub == 'V' )
                    {{ trans(\Config::get('app.theme').'-app.subastas.sold_lot') }}
                @else

                    <?php /*  {{ trans(\Config::get('app.theme').'-app.subastas.finalized') }}  */?>
                @endif
            </p>
        </div>
 </div>
@endif

<script>


   $(document).ready(function() {
        //calculamos la fecha de cierre

            $('.historial').append($('.finalized'))



    });


</script>
