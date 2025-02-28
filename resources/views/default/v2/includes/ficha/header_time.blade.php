<div class="ficha-info pb-3">

	<div class="d-flex justify-content-between flex-wrap">

		<h4 class="info-type-auction">
			@if($subasta_online)
			{{ trans('web.subastas.lot_subasta_online') }}
			@elseif($subasta_inversa)
			{{ trans('web.subastas.lot_subasta_inversa') }}
			@elseif($subasta_web)
			{{ trans('web.subastas.lot_subasta_presencial') }}
			@elseif($subasta_venta)
			{{ trans('web.subastas.lot_subasta_venta') }}
			@elseif($subasta_make_offer)
			{{ trans('web.subastas.lot_subasta_make_offer') }}
			@elseif($subasta_inversa)
			{{ trans('web.subastas.lot_subasta_inversa') }}
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
		<p class="ficha-info-clock">{{ trans('web.subastas.finalized') }}</p>
		@endif
	</div>

	@if(!$subasta_make_offer)
	<div class="ficha-date-closing">
		{{ trans('web.lot.closing_date') }} <span id="cierre_lote"></span>
	</div>
	@endif

</div>


