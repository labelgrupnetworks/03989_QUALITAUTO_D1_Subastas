@include('includes.ficha.header_time')

<div class="ficha-info-content">

	@if(!$retirado && !$devuelto && !$fact_devuelta)
		<div class="ficha-info-items">

				@if ($sub_cerrada)
					@include('includes.ficha.pujas_ficha_cerrada')

				@elseif($subasta_venta && !$cerrado && !$end_session)
					@if(\Config::get("app.shoppingCart") )
						@include('includes.ficha.pujas_ficha_ShoppingCart')
					@else
						@include('includes.ficha.pujas_ficha_V')
					@endif

				{{-- si un lote cerrado no se ha vendido se podra comprar --}}
				@elseif(($subasta_web || $subasta_online) && $cerrado && empty($lote_actual->himp_csub) && $compra && !$fact_devuelta)

					@include('includes.ficha.pujas_ficha_V')

					{{-- si una subasta es abierta p solo entraremso a la tipo online si no esta iniciada la subasta --}}
				@elseif( ($subasta_online || ($subasta_web && $subasta_abierta_P && !$start_session)) && !$cerrado)
					@include('includes.ficha.pujas_ficha_O')

				@elseif($subasta_web && !$cerrado)

					@include('includes.ficha.pujas_ficha_W')

				@elseif($subasta_make_offer && !$cerrado)
					@include('includes.ficha.pujas_ficha_M')
				@else
					@include('includes.ficha.pujas_ficha_cerrada')
				@endif

		</div>
	@endif
</div>

