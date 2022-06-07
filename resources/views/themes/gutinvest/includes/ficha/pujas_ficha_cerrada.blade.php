
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
<div class="info_single col-xs-12">
        <div class="sub-o">
            <p class="" style="font-weight: bold;">{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_online') }}</p>
            </div>

                <div class="col-xs-12 col-sm-12 no-padding exit-price">
                        
                        @if($lote_actual->cerrado_asigl0 == 'S' && $lote_actual->remate_asigl0 =='S' && (!empty($precio_venta))|| ($lote_actual->subc_sub == 'H' && !empty($lote_actual->impadj_asigl0)) )   
                        @if($lote_actual->subc_sub == 'H' && !empty($lote_actual->impadj_asigl0))
                            @php($precio_venta = $lote_actual->impadj_asigl0)
                        @endif  
                        <p class="pre text">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}:</p> <div class="pre">{{ \Tools::moneyFormat($precio_venta) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
                        @elseif($lote_actual->desadju_asigl0 =='S')
                         <p class="pre text">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</p>
                        @elseif($lote_actual->cerrado_asigl0 == 'S')
                        <p class="pre text">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</p>
                        @elseif(strtoupper($lote_actual->tipo_sub) == 'V' && $lote_actual->cerrado_asigl0 == 'N' && $lote_actual->end_session > date("now"))
                            <p class="pre text">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</p>
                        @elseif($lote_actual->cerrado_asigl0 == 'D')
                        <p class="pre text">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
                        @endif
                        <p class="pre"></p>
                </div>
                <div class="col-xs-12 col-sm-6 no-padding categories">
                        <p class="cat">{{ trans(\Config::get('app.theme').'-app.lot.categories') }}</p>
                         <?php
                            $categorys = new \App\Models\Category();
                            $tipo_sec = $categorys->getSecciones($data['js_item']['lote_actual']->sec_hces1);
                        ?>
                        <p>
                            @foreach($tipo_sec as $sec)
                                {{$sec->des_tsec}}
                            @endforeach
                       </p>
                </div>
    <div class="col-xs-12 col-sm-6 no-padding">
                       <p class="shared">{{ trans(\Config::get('app.theme').'-app.lot.share_lot') }}</p>
                        @include('includes.ficha.share')
                        </ul>
                </div>

</div>
