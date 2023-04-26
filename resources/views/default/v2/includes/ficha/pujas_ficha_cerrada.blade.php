@php
$precio_venta = $lote_actual->himp_csub ?? null;

//si es un histórico y la subasta del asigl0 = a la del hces1 es que no está en otra subasta y podemso coger su valor de compra de implic_hces1
if(!$precio_venta && $lote_actual->subc_sub == 'H' && $lote_actual->cod_sub == $lote_actual->sub_hces1 && $lote_actual->lic_hces1 == 'S' and $lote_actual->implic_hces1 > 0) {
	$precio_venta = $lote_actual->implic_hces1;
}

//Si hay precio de venta y impsalweb_asigl0 contiene valor, mostramos este como precio de venta
$precio_venta = (!empty($precio_venta) && $lote_actual->impsalweb_asigl0 != 0) ? $lote_actual->impsalweb_asigl0 : $precio_venta;
@endphp

<div class="ficha-pujas ficha-cerrada">

	{{-- Precio salida --}}
	<h4 class="price salida-price">
		<span>{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</span>
		<span class="value">
			{{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
		</span>
	</h4>

	{{-- Precio venta --}}
	@if($cerrado && !empty($precio_venta) && $remate )
		<h4 class="price sold-price">
			<span>{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}</span>
			<span class="value">
				{{ Tools::moneyFormat($precio_venta, trans("$theme-app.subastas.euros"), 0) }}
			</span>
		</h4>

	{{-- Vendido x no mostramos precio --}}
	@elseif($cerrado && !empty($precio_venta) && !$remate)
		<h4 class="ficha-is-awarded">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</h4>

	{{-- No Vendido - sesion finalizada --}}
	@elseif($subasta_venta && !$cerrado && $lote_actual->end_session > time())
		<h4 class="ficha-is-awarded">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</h4>

	{{-- No Vendido - cerrado  --}}
	@elseif($cerrado && empty($precio_venta))
		<h4 class="ficha-is-awarded">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</h4>

	 @elseif($devuelto)
		<h4 class="ficha-is-awarded">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</h4>

	@endif
</div>
