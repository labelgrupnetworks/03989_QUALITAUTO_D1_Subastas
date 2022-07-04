
<?php
$precio_venta=NULL;
if (!empty($lote_actual->himp_csub)){
    $precio_venta=$lote_actual->himp_csub;
}
//si es un histórico y la subasta del asigl0 = a la del hces1 es que no está en otra subasta y podemso coger su valor de compra de implic_hces1
elseif($lote_actual->subc_sub == 'H' && $lote_actual->cod_sub == $lote_actual->sub_hces1 && $lote_actual->lic_hces1 == 'S' and $lote_actual->implic_hces1 >0){
    $precio_venta = $lote_actual->implic_hces1;
}

//Si hay precio de venta y impsalweb_asigl0 impsalweb_asigl0 contiene valor, mostramos este como precio de venta
$precio_venta = (!empty($precio_venta) && $lote_actual->impsalweb_asigl0 != 0) ? $lote_actual->impsalweb_asigl0 : $precio_venta;
?>
<div class="info_single col-xs-12">
        <div class="info_single_title col-xs-12">
                {{ trans(\Config::get('app.theme').'-app.subastas.finalized') }}
        </div>
        <div class="row">
                <div class="col-xs-12 col-sm-6">

                        @if($lote_actual->cerrado_asigl0 == 'S' && $lote_actual->remate_asigl0 =='S' && (!empty($precio_venta)) || ($lote_actual->subc_sub == 'H' && !empty($lote_actual->impadj_asigl0)) )



                        <p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}:</p> <div class="pre">{{ \Tools::moneyFormat( ($lote_actual->subc_sub == 'H' && !empty($lote_actual->impadj_asigl0))? $lote_actual->impadj_asigl0 : $precio_venta ) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
                        @elseif($lote_actual->cerrado_asigl0 == 'S' && (!empty($precio_venta) ||$lote_actual->desadju_asigl0 =='S' ))
                        <p class="pre"> {{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</p>
                        @elseif(strtoupper($lote_actual->tipo_sub) == 'V' && $lote_actual->cerrado_asigl0 == 'N' && $lote_actual->end_session > date("now"))
                            <p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</p>
                        @elseif($lote_actual->cerrado_asigl0 == 'S' && empty($precio_venta))
                        <p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</p>
                        @elseif($lote_actual->cerrado_asigl0 == 'D')
                        <p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
                        @endif
                        <p class="pre"></p>
                </div>
                {{-- <div class="col-xs-12 col-sm-6">
					<p class="cat">{{ trans(\Config::get('app.theme').'-app.lot.categories') }}</p>
					@php
					$categorys = new \App\Models\Category();
					$tipo_sec = $categorys->getSecciones($data['js_item']['lote_actual']->sec_hces1);
					@endphp

					<p>
					@foreach($tipo_sec as $sec)
						{{$sec->des_tsec}}
					@endforeach
					</p>
				</div> --}}
        </div>
</div>
