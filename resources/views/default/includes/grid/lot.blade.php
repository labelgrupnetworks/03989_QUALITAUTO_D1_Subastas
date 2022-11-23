

<div class="{{$class_square}} square" {!! $codeScrollBack !!}>
	<a title="{{ $titulo }}" class="lote-destacado-link secondary-color-text" <?= $url?> >

		{{-- las etiquetas van a parta para simplificar el código --}}
		@include('includes.grid.labelLots')
        <div class="item_lot">
			@if($subasta_online && !$cerrado)
				<p class="salida-time">
					<i class="fa fa-clock-o"></i>
					<span data-countdown="{{strtotime($item->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($item->close_at); ?>" class="timer"></span>
				</p>
			@endif
            <div class="item_img">
                <img class="img-responsive " src="{{$img}}" alt="{{ $titulo }}">
            </div>

            <div class="data-container">
                    <div class="title_item">
                        <span class="seo_h4" style="text-align: center;">{!! strip_tags($titulo) !!}</span>
                    </div>

					<div class="data-price text-center">
						@if( !$retirado && !$devuelto)
							<p style="visibility: {{ $item->ocultarps_asigl0 != 'S' ? 'visible' : 'hidden'}}">
								@if(!$subasta_make_offer)
									@if($subasta_venta)
										<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</span>
									@else
										<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</span>
									@endif
									@if($precio_salida ==0)
										{{ trans(\Config::get('app.theme').'-app.lot.free') }}
									@else
										<span class="salida-price">{{$precio_salida}}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
									@endif
								@endif
							</p>
							@if(($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado && $hay_pujas)

							<p>
								<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
								<span class="salida-price {{$winner}}">{{ $maxPuja }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
							</p>


							@endif
							@if( $awarded && !$devuelto && !$retirado)
								@if($cerrado && $remate &&  (!empty($precio_venta) ) || ($sub_historica && !empty($item->impadj_asigl0)) )
									@if($sub_historica && !empty($item->impadj_asigl0))
										@php($precio_venta = $item->impadj_asigl0)@endphp
									@endif
									<p>
										<span class="salida-title soldGrid">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}</span>
										<span class="salida-price  soldGrid">{{ $precio_venta }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
									</p>

								@elseif($cerrado &&  empty($precio_venta) && !$compra)
									<p> <span class="salida-title notSold">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</span></p>
								@endif
							@endif
					@endif
				</div>

				@if (!$devuelto && !$retirado && !$sub_historica)
					@if($cerrado &&  empty($precio_venta) && $compra)
						<p class="btn-bid-lotlist">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</p>
					@elseif($subasta_venta  && !$cerrado)
						@if(!$end_session)
						<p class="btn-bid-lotlist">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</p>
						@endif
					@elseif(!$cerrado && (!$subasta_online || $inicio_pujas_online))
						@php /* Si no está cerrado saldrá el botón o si la subasta online está abierta y es superior a la fecha de inicio también aparecerá */ @endphp
						<p class="btn-bid-lotlist">{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</p>
					@endif
				@endif

            </div>

        </div>
    </a>
</div>
