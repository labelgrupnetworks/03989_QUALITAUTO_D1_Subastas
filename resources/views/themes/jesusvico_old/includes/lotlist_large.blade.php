<div class="{{$class_square}} large_square">
	<a title="{{ $titulo }}" class="lote-destacado-link secondary-color-text" <?= $url?>>
		<div class="col-xs-12 no-padding item_lot_large" style="position: relative">
			@if( $retirado)
			<div class="retired">
				{{ trans(\Config::get('app.theme').'-app.lot.retired') }}
			</div>
			@elseif($fact_devuelta)
			<div class="retired" style="font-size: 10px">
				{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
			</div>
			@elseif($awarded && $cerrado && (!empty($precio_venta)) || ($sub_historica && !empty($item->impadj_asigl0))
			)
			<div class="retired">
				{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
			</div>
			@endif
			<div class="col-xs-12 col-sm-5 col-lg-4 no-padding">
				<div class="border_img_lot">
					<div class="item_img">
						<div data-loader="loaderDetacados" class='text-input__loading--line'></div>
						<img class="img-responsive lazy" style="display: none" data-src="{{$img}}" alt="{{$titulo}}">
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-7 col-lg-8">
				<div class="data-container">

					<div class="title_item">
						<span class="seo_h4">{{ $titulo}}</span>
					</div>
					<div class="desc_lot">
						<?= $item->desc_hces1 ?>
					</div>

					@if( !$retirado && !$fact_devuelta)

					<div class="row">

						<div class="col-xs-6">

							<div class="data-price">



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

								@if( ($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado &&
								$hay_pujas)

								<p class="{{$winner}}">
									<span class="salida-title puja_actual">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
									<span class="salida-price puja_actual">{{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
								</p>


								@elseif ($subasta_web && $subasta_abierta_O && !empty($item->open_price) && !$cerrado)
								<p class="{{$winner}}">{{ \Tools::moneyFormat($item->open_price) }}
									<span class="salida-title puja_actual">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
									<span class="salida-price puja_actual">{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
								</p>

								@endif

								@if( $awarded || $devuelto)

								<p class="salida">

									@if($devuelto)

									<p class="salida-title extra-color-one">
										{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>

									@elseif($cerrado && $remate && (!empty($precio_venta) ) || ($sub_historica &&
									!empty($item->impadj_asigl0)) )

									@if($sub_historica && !empty($item->impadj_asigl0))

									@php($precio_venta = $item->impadj_asigl0)

									@endif

									<p class="salida-title extra-color-one">
										{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}: <span
											class="pill">{{ \Tools::moneyFormat($precio_venta) }}
											{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>

									@elseif($cerrado && !empty($precio_venta) && !$remate)

									<p class="salida-title extra-color-one">
										{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</p>

									@elseif($cerrado && empty($precio_venta))

									<p class="salida-title extra-color-one">
										{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</p>
									@endif

								</p>
								@endif





							</div>

						</div>

						<div class="col-xs-6">

							@if ($sub_cerrada)

							<p class="btn btn-filter d-flex justify-content-center align-items-center color-letter"></p>

							@elseif($subasta_venta && !$cerrado && !$end_session)

							<p class="btn btn-filter d-flex justify-content-center align-items-center color-letter">
								<span>{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</span>
							</p>

							<?php //si un lote cerrado no se ha vendido se podra comprar ?>
							@elseif( ($subasta_web || $subasta_online) && $cerrado && empty($lote_actual->himp_csub) &&
							$compra && !$fact_devuelta)

							<p class="btn btn-filter d-flex justify-content-center align-items-center color-letter">
								<span>{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</span>
							</p>

							<?php //si una subasta es abierta p solo entraremso a la tipo online si no esta iniciada la subasta ?>
							@elseif( ($subasta_online || ($subasta_web && $subasta_abierta_P && !$start_session)) &&
							!$cerrado)

							<p class="btn btn-filter d-flex justify-content-center align-items-center color-letter">
								<span>{{ trans(\Config::get('app.theme').'-app.lot.place_bid') }}</span>
							</p>

							@elseif( $subasta_web && !$cerrado)

							<p class="btn btn-filter d-flex justify-content-center align-items-center color-letter">
								<span>{{ trans(\Config::get('app.theme').'-app.lot_list.bid_live') }}</span>
							</p>
							@endif

						</div>

					</div>

					@endif


				</div>
			</div>
		</div>
	</a>
</div>
