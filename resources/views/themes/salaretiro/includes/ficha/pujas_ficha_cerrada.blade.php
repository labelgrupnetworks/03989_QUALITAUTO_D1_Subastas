
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
        <div class="info_single_title col-xs-12">
                {{ trans($theme.'-app.subastas.finalized') }}
        </div>
        <div class="row">
                <div class="col-xs-12 col-sm-6">
                        
                        @if($lote_actual->cerrado_asigl0 == 'S' && !empty($precio_venta) && $lote_actual->remate_asigl0 =='S' )    
                        <p class="pre">{{ trans($theme.'-app.subastas.buy_to') }}:</p> <div class="pre">{{ \Tools::moneyFormat($precio_venta) }} {{ trans($theme.'-app.subastas.euros') }}</div>
                        @elseif($lote_actual->cerrado_asigl0 == 'S' && !empty($precio_venta)  || $lote_actual->desadju_asigl0 =='S')
                        <p class="pre"> {{ trans($theme.'-app.subastas.dont_available') }}</p>
                        @elseif(strtoupper($lote_actual->tipo_sub) == 'V' && $lote_actual->cerrado_asigl0 == 'N' && $lote_actual->end_session > date("now"))
                            <p class="pre">{{ trans($theme.'-app.subastas.dont_buy') }}</p>
                        @elseif( $lote_actual->lic_hces1 != 'S' && $lote_actual->cerrado_asigl0 == 'S' && empty($precio_venta))
                        <p class="pre">{{ trans($theme.'-app.subastas.dont_buy') }}</p>
                        @elseif($lote_actual->cerrado_asigl0 == 'D')
                        <p class="pre">{{ trans($theme.'-app.subastas.dont_available') }}</p>
                        @else
                        <p class="pre">{{ trans($theme.'-app.subastas.dont_available') }}</p>
                        @endif
                        
                        @if(!empty($data['lot_other_sub']))
                        <p class="pre">
                            <?php
                                $webfriend = !empty($data['lot_other_sub']->webfriend_hces1)? $data['lot_other_sub']->webfriend_hces1 :  str_slug($data['lot_other_sub']->titulo_hces1);
                                $url_friendly = \Routing::translateSeo('lote').$data['lot_other_sub']->cod_sub."-".str_slug($data['lot_other_sub']->id_auc_sessions).'-'.$data['lot_other_sub']->id_auc_sessions."/".$data['lot_other_sub']->ref_asigl0.'-'.$data['lot_other_sub']->num_hces1.'-'.$webfriend;
                            ?>
                            <a class="active-auction"   href="{{$url_friendly}}">{{ trans($theme.'-app.subastas.actual_sub_active') }}</a>
                        </p>
                        @endif


                </div>
                <div class="col-xs-12 col-sm-6">
                        <p class="cat">{{ trans($theme.'-app.lot.categories') }}</p>
                         <?php
                            $categorys = new \App\Models\Category();
                            $tipo_sec = $categorys->getSecciones($data['js_item']['lote_actual']->sec_hces1);
                        ?>
                        <p>
                            @foreach($tipo_sec as $sec)
                                {{$sec->des_tsec}}
                            @endforeach
                       </p>
                       <p class="shared">{{ trans($theme.'-app.lot.share_lot') }}</p>
                        @include('includes.ficha.share')
                        </ul>
                </div>
        </div>
</div>
