<div class="{{$class_square}} square" {!! $codeScrollBack !!}>
	<a title="{{ $titulo }}" class="lote-destacado-link secondary-color-text" <?= $url?>>

		{{-- las etiquetas van a parta para simplificar el código --}}
		@include('includes.grid.labelLots')
		<div class="item_lot">

			<div class="item_img">
				<img class="img-responsive " src="{{$img}}" alt="{{$titulo}}">
			</div>

			<div class="data-container">
				<div class="title_item">
					<h4 class="seo_h4">{{ $titulo}}</h4>
				</div>

				<div class="desc_lot">
					{!! $descripcion !!}
				</div>

				<div class="data-price">
					@if( !$retirado && !$devuelto)
						<p>
							@if($subasta_venta && $item->cod_sub == 'VDJ')
								<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.subastas.net_price') }}</span>
							@elseif($subasta_venta)
								<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</span>
							@else
								<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</span>
							@endif

							<span class="salida-price">{{$precio_salida}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
						</p>


						@if( ($subasta_online || ($subasta_web && $subasta_abierta_P) ) && !$cerrado && $hay_pujas)
							<p>
								<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
								<span class="salida-price {{$winner}}">{{ $maxPuja }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
							</p>
						@elseif ($subasta_online || ($subasta_web && $subasta_abierta_P && !$cerrado) )
							<p>
								<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }} </span>
							</p>
						@endif



						@if($awarded)
							<p>
								@if($devuelto)
									<span class="salida-title notAvailable">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</span>

								@elseif($cerrado && $remate && (!empty($precio_venta) ) || ($sub_historica && !empty($item->impadj_asigl0)) )

										@if($sub_historica && !empty($item->impadj_asigl0))
											@php($precio_venta = $item->impadj_asigl0)@endphp
										@endif
											<span class="salida-title soldGrid">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}</span>
											<span class="salida-price  soldGrid">{{ $precio_venta }}
												{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
											</span>

								@elseif($cerrado &&  (!empty($precio_venta)  || $desadjudicado))
									<span class="salida-title soldGrid2">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</span>

								@elseif($cerrado && empty($precio_venta))
									<span class="salida-title notSold">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</span>
								@endif
							</p>
						@endif


						@if($subasta_online && !$cerrado)
							<p class="salida-time">
							<i class="fa fa-clock-o"></i>
							<span data-countdown="{{strtotime($item->close_at) - getdate()[0] }}"
								data-format="{!! \Tools::down_timer($item->close_at); !!}" class="timer"></span>
							</p>
						@elseif($subasta_online)
						<p class="salida-time"></p>
						@endif

					@endif
				</div>

				{{--
				@if (!$devuelto && !$retirado && !$sub_historica)
					@if($cerrado &&  empty($precio_venta) && $compra)
						<p class="btn-bid-lotlist">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</p>
				@elseif($subasta_venta && !$cerrado )
				<p class="btn-bid-lotlist">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</p>
				@elseif(!$cerrado )
				<p class="btn-bid-lotlist">{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</p>
				@else
				<p class="btn-bid-lotlist"></p>
				@endif
				@endif
				--}}



			</div>

		</div>
	</a>
</div>
