<div class="ficha-info pb-3">
	<div class="d-flex ficha-info-justify-content flex-wrap">

		<p>{{ trans("$theme-app.lot.auction_date") }}</p>
		<div>
			<p>{{ Tools::getDateFormat($lote_actual->start_session, 'Y-m-d H:i:s', 'd-m-Y H:i') }}
				{{ trans("$theme-app.lot_list.time_zone") }}</p>
			@if (!$cerrado)
				<p class="auc-timer" class="timer fw-normal"
					data-countdown="{{ strtotime($lote_actual->start_session) - getdate()[0] }}"
					data-format="{{ Tools::down_timer($lote_actual->start_session) }}" data-closed="0"></p>
			@else
				<div class="px-3 py-2 bg-lb-color-backgorund-light d-flex align-items-center gap-3 justify-content-center float-start"
					style="margin-right: -1rem">
					<img class="mb-1" src="/themes/{{ $theme }}/assets/icons/hammer.svg" alt="hammer">
					<p class="ficha-info-clock">{{ trans($theme . '-app.subastas.finalized') }}</p>
				</div>
			@endif
		</div>
	</div>
</div>
