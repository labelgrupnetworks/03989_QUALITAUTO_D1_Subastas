{{--   --}}
@if($item->oferta_asigl0 == 2)
    <div class="retired " style="background:#CA1111">
       DURÁN LIVE
    </div>
@elseif($subasta_venta == "V")
    <div class="retired estilo-1">
        {{ trans($theme.'-app.foot.direct_sale') }}
    </div>
@elseif($subasta_web)
    <div class="retired estilo-2">
		{{ trans($theme.'-app.lot_list.presencial') }}
	</div>
@elseif($subasta_online)
    <div class="retired estilo-4">
		{{ trans($theme.'-app.lot_list.online') }}
    </div>

@endif


@if( $retirado)
	<div class="RightlabelGrid " style="background:#CA1111">
		{{ trans($theme.'-app.lot.retired') }}
	</div>
@elseif($devuelto)
	<div class="RightlabelGrid" style="font-size: 10px">
		{{ trans($theme.'-app.subastas.dont_available') }}
	</div>
{{-- El vendido no aparece
@elseif($awarded && $cerrado &&  (!empty($precio_venta)) || ($sub_historica && !empty($item->impadj_asigl0)) )
	<div class="RightlabelGrid">
		{{ trans($theme.'-app.subastas.buy') }}
	</div>
	--}}
@endif

{{--
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
--}}




