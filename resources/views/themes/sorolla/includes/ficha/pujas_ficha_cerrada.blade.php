<?php
    $precio_venta=NULL;
        if (!empty($lote_actual->himp_csub)){
            $precio_venta=$lote_actual->himp_csub;
        }
//si es un histórico y la subasta del asigl0 = a la del hces1 es que no está en otra subasta y podemso coger su valor de compra de implic_hces1
elseif($lote_actual->subc_sub == 'H' && $lote_actual->cod_sub == $lote_actual->sub_hces1 && $lote_actual->lic_hces1 == 'S' and $lote_actual->implic_hces1 >0){
    $precio_venta = $lote_actual->implic_hces1;
}

//Si hay precio de venta y impsalweb_asigl0 contiene valor, mostramos este como precio de venta
$precio_venta = (!empty($precio_venta) && $lote_actual->impsalweb_asigl0 != 0) ? $lote_actual->impsalweb_asigl0 : $precio_venta;
?>

<div class="info_single lot-sold col-xs-12 no-padding">

            <div class="col-xs-8 col-sm-12 no-padding ">

				<div class="pre">
					@if($lote_actual->subc_sub == 'H')
					<p style="cursor: pointer" class="btn-bid-lotlist btn-consult" data-lot="{{ $lote_actual->ref_asigl0 }}"
						data-toggle="modal" data-target="#modalConsulta">{{ trans("$theme-app.lot.consult") }}</p>

					@elseif($cerrado && !empty($precio_venta) && $remate )
					<p class="pre-title-principal adj-text">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}</p>
					<p class="pre-price">{{ \Tools::moneyFormat($precio_venta) }}
						{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>


					@elseif($cerrado && !empty($precio_venta) && !$remate)
					<p style="cursor: pointer" class="btn-bid-lotlist btn-consult" data-lot="{{ $lote_actual->ref_asigl0 }}"
						data-toggle="modal" data-target="#modalConsulta">{{ trans("$theme-app.lot.consult") }}</p>

					@elseif($subasta_venta && !$cerrado && $lote_actual->end_session > time())
					<p style="cursor: pointer" class="btn-bid-lotlist btn-consult" data-lot="{{ $lote_actual->ref_asigl0 }}"
						data-toggle="modal" data-target="#modalConsulta">{{ trans("$theme-app.lot.consult") }}</p>


					@elseif($cerrado && empty($precio_venta))
					<p style="cursor: pointer" class="btn-bid-lotlist btn-consult" data-lot="{{ $lote_actual->ref_asigl0 }}"
						data-toggle="modal" data-target="#modalConsulta">{{ trans("$theme-app.lot.consult") }}</p>


					@elseif($devuelto)

					<p class="pre-title-principal adj-text">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
					@endif
				</div>

            </div>

</div>

@include('includes.modal_consulta')
