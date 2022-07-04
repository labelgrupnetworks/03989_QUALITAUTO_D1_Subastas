@php
$precio_venta=NULL;

if (!empty($lote_actual->himp_csub)){
	$precio_venta=$lote_actual->himp_csub;
}
//si es un histórico y la subasta del asigl0 = a la del hces1 es que no está en otra subasta y podemso coger su valor de compra de implic_hces1
elseif($lote_actual->subc_sub == 'H' && $lote_actual->cod_sub == $lote_actual->sub_hces1 && $lote_actual->lic_hces1 == 'S' and $lote_actual->implic_hces1 >0){
	$precio_venta = $lote_actual->implic_hces1;
}

//Si hay precio de venta y impsalweb_asigl0 contiene valor, mostramos este como precio de venta
$precio_venta = (!empty($precio_venta) && $lote_actual->impsalweb_asigl0 != 0)
	? $lote_actual->impsalweb_asigl0
	: $precio_venta;
@endphp

<div  class="col-lg-12 col-md-12 info-ficha-buy-info no-padding">
	@include('front::includes.ficha.ficha_conditions')
</div>
