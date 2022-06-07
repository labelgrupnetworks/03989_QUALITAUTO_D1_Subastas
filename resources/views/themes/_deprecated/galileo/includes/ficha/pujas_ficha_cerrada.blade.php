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
    <div class="col-xs-12 no-padding">

        <div class="col-xs-12 no-padding desc-lot-title d-flex justify-content-space-between">
                <p class="desc-lot-profile-title">{{ trans(\Config::get('app.theme').'-app.lot.description') }}</p>
                <div class="info_single_title info-type-auction-title no-padding">
                        <div class="info-type-auction">{{ trans(\Config::get('app.theme').'-app.subastas.finalized') }}</div>
                    </div>
        </div>
        <div class="col-xs-12 no-padding desc-lot-profile-content">
                <p><?= $lote_actual->desc_hces1 ?></p>
        </div>
            
    </div>

            <div class="col-xs-8 col-sm-12 no-padding ">   
                @if($lote_actual->cerrado_asigl0 == 'S' && !empty($precio_venta) && $lote_actual->remate_asigl0 =='S' )    
                    <div class="pre">
                        <p class="pre-title-principal adj-text text-right">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}</p>
                        <p class="pre-price text-right">{{ \Tools::moneyFormat($precio_venta) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                    </div>
                    
                @elseif($lote_actual->cerrado_asigl0 == 'S' && (!empty($precio_venta) || $lote_actual->desadju_asigl0 =='S'))

                <div class="pre">
                        <p class="pre-title">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</p>
                </div>
                @elseif(strtoupper($lote_actual->tipo_sub) == 'V' && $lote_actual->cerrado_asigl0 == 'N' && $lote_actual->end_session > date("now"))
<!-- El cliente no quiere mostrar "No Vendido”-->
                    <div class="pre">
                            <p class="pre-title"></p>
                    </div>
                   
                @elseif($lote_actual->cerrado_asigl0 == 'S' && empty($precio_venta))
                    <div class="pre">
                            <p class="pre-title "></p>
                    </div>

                @elseif($lote_actual->cerrado_asigl0 == 'D')
                    <div class="pre">
                            <p class="pre-title">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
                    </div>
                @endif

                </div>
                
                
                <?php
                $categorys = new \App\Models\Category();
                $tipo_sec = $categorys->getSecciones($data['js_item']['lote_actual']->sec_hces1);
            ?>
            
            @if(count($tipo_sec) !== 0)
                <div class="col-xs-12 no-padding fincha-info-cats">
                    <div class="cat">{{ trans(\Config::get('app.theme').'-app.lot.categories') }}</div>    
                    @foreach($tipo_sec as $sec)  
                       <span class="badge">{{$sec->des_tsec}}</span>
                    @endforeach
                </div>
            @endif
</div>
