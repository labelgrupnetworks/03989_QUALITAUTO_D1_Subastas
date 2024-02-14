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

<div class="info_single lot-sold col-xs-12 no-padding">

            <div class="col-xs-8 col-sm-12 no-padding ">
                @if($cerrado && !empty($precio_venta) && $remate )
                    <div class="pre">
                        <p class="pre-title-principal adj-text">{{ trans($theme.'-app.subastas.buy_to') }}</p>
                        <p class="pre-price">{{ \Tools::moneyFormat($precio_venta) }} {{ trans($theme.'-app.subastas.euros') }}</p>
                    </div>

                @elseif($cerrado && !empty($precio_venta) &&  !$remate)

                <div class="pre">
                        <p class="pre-title-principal adj-text">{{ trans($theme.'-app.subastas.buy') }}</p>
                </div>
                @elseif($subasta_venta && !$cerrado && $lote_actual->end_session > time())
                    <div class="pre">
                            <p class="pre-title-principal adj-text">{{ trans($theme.'-app.subastas.dont_buy') }}</p>
                    </div>

                @elseif($cerrado && empty($precio_venta))
                    <div class="pre">
                            <p class="pre-title-principal adj-text ">{{ trans($theme.'-app.subastas.dont_buy') }}</p>
                    </div>

                @elseif($devuelto)
                    <div class="pre">
                            <p class="pre-title-principal adj-text">{{ trans($theme.'-app.subastas.dont_available') }}</p>
                    </div>
                @endif

                </div>

</div>
