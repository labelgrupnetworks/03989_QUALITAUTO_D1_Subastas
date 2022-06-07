
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
                        @if($lote_actual->tipo_sub != 'V')
                            @if( \Config::get('app.estimacion'))
                                <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.estimate') }} <span> {{$lote_actual->formatted_imptas_asigl0}} -  {{$item->formatted_imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>
                            @elseif( \Config::get('app.impsalhces_asigl0'))
                                <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}  <span> {{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span> </p>
                            @endif
                        @endif
                        @if($lote_actual->cerrado_asigl0 == 'S' && $lote_actual->remate_asigl0 =='S' && (!empty($precio_venta)  || $lote_actual->subc_sub == 'H' && !empty($lote_actual->impadj_asigl0)) )
                            @if($lote_actual->subc_sub == 'H' && !empty($lote_actual->impadj_asigl0))
                                @php($precio_venta = $lote_actual->impadj_asigl0)
                            @endif
                            <p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}:</p> <div class="pre">{{ \Tools::moneyFormat($precio_venta) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
                        @elseif($lote_actual->cerrado_asigl0 == 'S' && (!empty($precio_venta) || $lote_actual->desadju_asigl0 =='S') )
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


				<div class="col-xs-12 col-sm-6">
					@if(Session::has('user') && $lote_actual->retirado_asigl0 =='N')
						<a class="btn {{ $lote_actual->favorito ? 'hidden' : '' }}" id="add_fav" href="javascript:action_fav_modal('add')">
							<i class="fa fa-heart-o" aria-hidden="true"></i>
						</a>
						<a class="btn {{ $lote_actual->favorito ? '' : 'hidden' }}" id="del_fav" href="javascript:action_fav_modal('remove')">
							<i class="fa fa-heart" aria-hidden="true"></i>
						</a>
					@endif
				</div>
        </div>
</div>
