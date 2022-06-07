<div class="col-xs-12 no-padding mb-1"
	style="gap: 5px">

	<div class="info_single_title info-type-auction-title no-padding d-flex justify-content-space-between">

		@if($subasta_online)
		<i class="fa fa-info-circle mr-1 js-modal" aria-hidden="true"
			data-title="{{ trans("$theme-app.subastas.lot_subasta_online") }}" data-content="{{ trans("$theme-app.subastas.lot_subasta_online_info") }}"></i>
		<div class="info-type-auction online">
			{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_online') }}
		</div>
		@elseif($subasta_web)
		<div class="info-type-auction web">
			{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_presencial') }}
		</div>
		@elseif($subasta_venta)
		<i class="fa fa-info-circle mr-1 js-modal" data-modal="info-subasta-v" aria-hidden="true"
		data-title="{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_venta') }}" data-content="{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_venta_info') }}">
		</i>
		<div class="info-type-auction venta">
			{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_venta') }}
		</div>
		@endif


			<div class="col-xs-12 ficha-info-close-lot d-flex justify-content-space-between flex-wrap">
				@if(!$subasta_venta)

				<div class="date_top_side_small date-color-black">
					<span>{{ trans("$theme-app.lot.ending_date") }}</span>
					@if($subasta_venta)
					<span id="cierre_lote">{{ Tools::getDateFormat($timeCountdown, 'Y-m-d H:i:s', 'd/m/Y H:i') }}
						h</span>
					@else
					<span id="cierre_lote">{{ Tools::getDateFormat($timeCountdown, 'Y/m/d H:i:s', 'd/m/Y H:i') }}
						h</span>
					@endif
				</div>

				@if($cerrado_N && !empty($timeCountdown) && strtotime($timeCountdown) > getdate()[0])

				<div class=" ficha-info-clock">
					<span class="clock">
						<i class="fas fa-clock"></i>
						<span data-{{$nameCountdown}}="{{ strtotime($timeCountdown) - getdate()[0] }}"
							data-format="<?= \Tools::down_timer($timeCountdown); ?>" class="timer">
						</span>
					</span>
				</div>

				@elseif($cerrado)

				<div class="info-type-auction">{{ trans(\Config::get('app.theme').'-app.subastas.finalized') }}</div>

				@endif
				@endif
				<div class="col-xs-12 text-right p-0">
					<p class="m-0 titleficha">{{ trans("$theme-app.lot.lot-name") }} {{ $lote_actual->ref_asigl0 }} </p>
				</div>
			</div>

	</div>



</div>
