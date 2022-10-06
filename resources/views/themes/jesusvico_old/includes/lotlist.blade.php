<div class="{{$class_square}} square">
	<a title="{{ $titulo }}" class="lote-destacado-link secondary-color-text" <?= $url?>>
		@if( $retirado)
		<div class="retired ">
			{{ trans(\Config::get('app.theme').'-app.lot.retired') }}
		</div>
		@elseif($fact_devuelta)
		<div class="retired" style="font-size: 10px">
			{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
		</div>
		@elseif($awarded && $cerrado && (!empty($precio_venta)) || ($sub_historica && !empty($item->impadj_asigl0)) )
		<div class="retired">
			{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
		</div>
		@endif
		<div class="item_lot">
			<div class="item_img">
				<div data-loader="loaderDetacados" class='text-input__loading--line'></div>
				<img class="img-responsive lazy" style="display: none;" data-src="{{$img}}" alt="{{$titulo}}">
			</div>

			<div class="data-container">
				<div class="title_item">
					<span class="seo_h4" style="text-align: center;">{{ $titulo}}</span>
				</div>

				<div class="desc_lot">
					<?= $item->desc_hces1 ?>
				</div>

				<div class="data-price text-center">
					@if( !$retirado && !$fact_devuelta)
					@if($subasta_venta)
					<p>
						<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</span>
						<span class="salida-price">{{$item->formatted_actual_bid}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
					</p>
					@else
					<p>
						<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</span>
						<span class="salida-price">{{$precio_salida}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
					</p>

					@endif
					@if(($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado && $hay_pujas)
					<p class="{{$winner}}">
						<span class="salida-title puja_actual">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
						<span class="salida-price puja_actual">{{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
					</p>

					@elseif ($subasta_web && $subasta_abierta_O && !empty($item->open_price) && !$cerrado )

					<p class="{{$winner}}">{{ \Tools::moneyFormat($item->open_price) }}
						<span class="salida-title puja_actual">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
						<span class="salida-price puja_actual">{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
					</p>
					@endif


					@if( $awarded || $devuelto)
						@if($devuelto)
							<p class="salida-title">
								{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
								<div class="salida-price"></div>
						@elseif($cerrado && $remate && (!empty($precio_venta) ) || ($sub_historica && !empty($item->impadj_asigl0)) )

							@if($sub_historica && !empty($item->impadj_asigl0))
								@php($precio_venta = $item->impadj_asigl0)
							@endif

							<p>
								<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}</span>
								<span class="salida-price">{{ \Tools::moneyFormat($precio_venta) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
							</p>

						@elseif($cerrado && !empty($precio_venta) && !$remate)
							<p class="salida-title">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</p>
							<div class="salida-price"></div>

						@elseif($cerrado && empty($precio_venta))
							<p class="salida-title">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</p>
							<div class="salida-price"></div>

						@else
							{{-- <p class="salida-title extra-color-one"></p>
                                <div class="salida-price"></div> --}}
						@endif

					@endif


					@endif

				</div>

				@if ($sub_cerrada)
					<p class="" style="height: 40px; margin: 0">
						<span></span>
					</p>

            	@elseif($subasta_venta && !$cerrado && !$end_session)
					<p class="btn btn-filter d-flex justify-content-center align-items-center color-letter">
						<span>{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</span>
					</p>

                <?php //si un lote cerrado no se ha vendido se podra comprar ?>
                @elseif( ($subasta_web || $subasta_online) && $cerrado && empty($lote_actual->himp_csub) && $compra && !$fact_devuelta)
					<p class="btn btn-filter d-flex justify-content-center align-items-center color-letter">
						<span>{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</span>
					</p>

				<?php //si una subasta es abierta p solo entraremso a la tipo online si no esta iniciada la subasta ?>
                @elseif( ($subasta_online || ($subasta_web && $subasta_abierta_P && !$start_session)) && !$cerrado)
					<p class="btn btn-filter d-flex justify-content-center align-items-center color-letter">
						<span>{{ trans(\Config::get('app.theme').'-app.lot.place_bid') }}</span>
					</p>

                @elseif( $subasta_web && !$cerrado)
					<p class="btn btn-filter d-flex justify-content-center align-items-center color-letter">
						<span>{{ trans(\Config::get('app.theme').'-app.lot_list.bid_live') }}</span>
					</p>

				@else

				<p class="" style="height: 40px; margin: 0">
					<span></span>
				</p>

                @endif

				@if( ($subasta_online || $subasta_web) && $cerrado && empty($item->himp_csub) && $compra && !$fact_devuelta)

					<?php
					/*<span>{{ trans(\Config::get('app.theme').'-app.lot.place_bid') }}</span>
					<p class="salida-time">
						<i class="fa fa-clock-o"></i>
						<span data-countdown="{{strtotime($item->close_at) - getdate()[0] }}"
							data-format="<?= \Tools::down_timer($item->close_at); ?>" class="timer"></span>
					</p>
					*/
					?>
				@endif

			</div>

		</div>
	</a>
</div>
