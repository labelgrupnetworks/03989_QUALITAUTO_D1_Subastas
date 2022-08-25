<div class="ficha-info pb-3">

	<div class="d-flex justify-content-between flex-wrap">

		<h4 class="info-type-auction">
			@if($subasta_online)
			{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_online') }}
			@elseif($subasta_web)
			{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_presencial') }}
			@elseif($subasta_venta)
			{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_venta') }}
			@elseif($subasta_make_offer)
			{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_make_offer') }}
			@elseif($subasta_inversa)
			{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_inversa') }}
			@endif
		</h4>

		@if($cerrado_N && !empty($timeCountdown) && strtotime($timeCountdown) > getdate()[0])
		<p class="ficha-info-clock">
			<span class="timer"
				data-{{$nameCountdown}}="{{ strtotime($timeCountdown) - getdate()[0] }}"
				data-format="<?= \Tools::down_timer($timeCountdown); ?>">
			</span>
		</p>
		@elseif($cerrado)
		<p class="ficha-info-clock">{{ trans(\Config::get('app.theme').'-app.subastas.finalized') }}</p>
		@endif
	</div>

	@if(!$subasta_make_offer)
	<div class="ficha-date-closing">
		{{ trans(\Config::get('app.theme').'-app.lot.closing_date') }} <span id="cierre_lote"></span>
	</div>
	@endif

</div>


