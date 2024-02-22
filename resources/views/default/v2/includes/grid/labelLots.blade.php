{{-- ofertas --}}
@if(!$cerrado)
	@if($oferta)
		<div class="label-grid">
			<span>{{ trans($theme.'-app.lot_list.hot_sale') }}</span>
		</div>
	@elseif(!empty($descuento))
		<div class="label-grid">
			<span>
			{{$descuento}} %
			{{ trans($theme.'-app.lot_list.discount') }}
			</span>
		</div>
	@endif
@endif



{{-- Estado lote --}}
@if($retirado)
	<div class="label-grid">
		<span>{{ trans($theme.'-app.lot.retired') }}</span>
	</div>
@elseif($devuelto)
	<div class="label-grid" style="font-size: 10px">
		<span>{{ trans($theme.'-app.subastas.dont_available') }}</span>
	</div>
@elseif($awarded && $cerrado &&  (!empty($precio_venta)) || ($sub_historica && !empty($item->impadj_asigl0)) )
	<div class="label-grid">
		<span>{{ trans($theme.'-app.subastas.buy') }}</span>
	</div>
@endif
