{{-- ofertas --}}
@if(!$cerrado)
	@if( $oferta)
		<div class="RightlabelGrid">
			{{ trans($theme.'-app.lot_list.hot_sale') }}
		</div>
	@elseif(!empty($descuento))
		<div class="RightlabelGrid">
			{{$descuento}} %
			{{ trans($theme.'-app.lot_list.discount') }}
		</div>
	@endif
@endif



{{-- Estado lote --}}
@if( $retirado)
	<div class="RightlabelGrid ">
		{{ trans($theme.'-app.lot.retired') }}
	</div>
@elseif($devuelto)
	<div class="RightlabelGrid" style="font-size: 10px">
		{{ trans($theme.'-app.subastas.dont_available') }}
	</div>
@elseif($awarded && $cerrado &&  (!empty($precio_venta)) || ($sub_historica && !empty($item->impadj_asigl0)) )
	<div class="RightlabelGrid">
		{{ trans($theme.'-app.subastas.buy') }}
	</div>
@endif
