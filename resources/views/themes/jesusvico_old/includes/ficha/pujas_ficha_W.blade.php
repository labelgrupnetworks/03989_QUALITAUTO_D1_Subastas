<div class="row hidden-xs">

	<div class="col-xs-12 col-sm-6 col-md-7 col-lg-7"></div>
	<div class="col-xs-12 col-sm-6 col-md-5 col-lg-5">
		<?php //if true botón de pujar en vivo ?>
		@if(!($cerrado_N && $fact_N && $start_session && !$end_session ))

			<?php //las subastas abiertas tipo P se veran como W cuando empiece la subasta, pero controlamos que no sep uedan hacer ordenes (!$subasta_abierta_P) ?>
			@if($cerrado_N && $fact_N && $start_orders && !$end_orders && !$subasta_abierta_P)

				<div class="insert-max-bid mb-1"><?=trans(\Config::get('app.theme').'-app.lot.insert_max_puja_start')?></div>

			@endif

		@endif
	</div>

</div>


{{-- Precio salida e input/boton live --}}
<div class="row d-flex align-items-center flex-wrap">
	@if ($lote_actual->ocultarps_asigl0 != 'S')
		<div class="col-xs-12 col-sm-6 col-md-7 col-lg-7 info-ficha-buy-info mt-1">

			<div class="info-ficha-buy-info-price d-flex border-bottom">

				<div class="pre">
					<p class="pre-price">
						{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}
						<span>
						{{$lote_actual->formatted_impsalhces_asigl0}}
						{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
						</span>
					</p>

				</div>

			</div>

		</div>
	@endif


	<div class="col-xs-12 col-sm-5 col-lg-5 info-ficha-buy-info">

		<?php //if true botón de pujar en vivo ?>
		@if($cerrado_N && $fact_N && $start_session && !$end_session)

			<div class="col-xs-12 no-padding">
				<div class="ficha-live-btn-content">

					<a class="ficha-live-btn-link secondary-button" href='{{\Tools::url_real_time_auction($data['subasta_info']->lote_actual->cod_sub,$data['subasta_info']->lote_actual->name,$data['subasta_info']->lote_actual->id_auc_sessions)}}'>
						<div class="bid-online"></div>
						<div class="bid-online animationPulseRed"></div>
						<?=trans(\Config::get('app.theme').'-app.lot.bid_live')?>
					</a>

				</div>
			</div>


		<?php //muestra los botones de pujar ?>
		@else
			<div class="col-xs-12 col-sm-12 hidden-sm hidden-md hidden-lg">
				<?php //if true botón de pujar en vivo ?>
				@if(!($cerrado_N && $fact_N && $start_session && !$end_session ))

					<?php //las subastas abiertas tipo P se veran como W cuando empiece la subasta, pero controlamos que no sep uedan hacer ordenes (!$subasta_abierta_P) ?>
					@if( $cerrado_N && $fact_N && $start_orders && !$end_orders && !$subasta_abierta_P)

						<div class="insert-max-bid mb-1"><?=trans(\Config::get('app.theme').'-app.lot.insert_max_puja_start')?></div>

					@endif

				@endif
			</div>


			<div class="col-xs-12 no-padding ficha-info-item-for-pay">

				<div class="info_single_content d-flex justify-content-space-between flex-direction-column">

					<?php //las subastas abiertas tipo P se veran como W cuando empiece la subasta, pero controlamos que no sep uedan hacer ordenes (!$subasta_abierta_P) ?>
					@if( $cerrado_N && $fact_N && $start_orders && !$end_orders && !$subasta_abierta_P)

						<div class="group-pujar-custom puj-btn-container">

							<input id="bid_modal_pujar" placeholder="{{ $data['precio_salida'] }}" class="form-control control-number" value="{{ $data['precio_salida'] }}" type="text">
							<span>{{trans(\Config::get('app.theme').'-app.subastas.euros')}}</span>

						</div>

					@endif

				</div>

			</div>

		@endif


	</div>

</div>

<div class="row">

	<div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">

		<?php //if true muestra texto de las pujas, false nada. ?>
		@if ($subasta_web && $subasta_abierta_O && $cerrado_N )
			<div class="no-padding info-ficha-buy-info-price d-flex">
				<div class="pre">
					<div id="text_actual_max_bid" class="price-title-principal pre <?=  $lote_actual->open_price >0? '':'hidden' ?>">

						<p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
						<div id="max_bid_color" class="pre-price <?= (count($data['ordenes']) > 0 && !empty($data['js_item']['user']) && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'])? 'winner':'no_winner' ?>">

							<span id="actual_max_bid">{{\Tools::moneyFormat($lote_actual->open_price) }}</span> {{trans(\Config::get('app.theme').'-app.subastas.euros')}}

						</div>

					</div>

					<div id="text_actual_no_bid" class="pre <?=  $lote_actual->open_price >0? 'hidden':'' ?>">
						<p class="pre-title-principal">{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }}</p>
					</div>

				</div>
			</div>

		@elseif ($subasta_web && $subasta_abierta_P && $cerrado_N )

			<div class="no-padding info-ficha-buy-info-price d-flex">
				<div class="pre">

					<div id="text_actual_max_bid" class="pre-price price-title-principal <?=  count($lote_actual->pujas) >0? '':'hidden' ?>">

						<p class="pre-title pre-price">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}
							<strong>
							{{-- aparecera en rojo(clase other) si no eres el ganador y en verde si no lo eres (clase mine) , si no estas logeado no se modifica el color --}}
								@if(Session::has('user'))

									@php($class = (!empty($lote_actual->max_puja) && $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'])? 'mine':'other')

								@else

									@php($class = '')

								@endif

								<span id="actual_max_bid" class="{{$class}}">{{ $lote_actual->formatted_actual_bid }} €</span>

							</strong>
						</p>

					</div>

				</div>
			</div>

		@endif

	</div>

	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">

		<?php //if true botón de pujar para pujar ?>
		@if(!($cerrado_N && $fact_N && $start_session && !$end_session))

			<div class="ficha-info-item-for-pay">

				<div class="info_single_content d-flex justify-content-space-between flex-direction-column">

					<?php //las subastas abiertas tipo P se veran como W cuando empiece la subasta, pero controlamos que no sep uedan hacer ordenes (!$subasta_abierta_P) ?>
					@if( $cerrado_N && $fact_N && $start_orders && !$end_orders && !$subasta_abierta_P)

						<div class="group-pujar-custom puj-btn-container">

							<div class="input-group-btn">
								<button id="pujar_ordenes_w" data-from="modal" type="button" class="ficha-btn-bid button-principal" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">
									{{ trans(\Config::get('app.theme').'-app.lot.place_bid') }}
								</button>
							</div>

						</div>

					@endif

				</div>

			</div>

		@endif

	</div>

</div>

<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
		<?php //if true orden maxima. ?>
			@if(!$fact_devuelta)

				<div class="info_single col-xs-12 ficha-puja no-padding">

					<div class="col-lg-12 no-padding">

						<div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">

							{{trans(\Config::get('app.theme').'-app.lot.max_puja')}}

							<strong>
								<span id="tuorden">
									@if ( !empty($data['js_item']['user']['ordenMaxima']))
										{{ $data['js_item']['user']['ordenMaxima']}}
									@endif
								</span>
								{{trans(\Config::get('app.theme').'-app.subastas.euros')}}
							</strong>

						</div>

					</div>

				</div>

			@endif
	</div>
</div>
