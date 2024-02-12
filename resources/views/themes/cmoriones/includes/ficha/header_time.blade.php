<div class="ficha-info pb-3">

	<div class="d-flex justify-content-between flex-wrap align-items-end">

		<h4 class="info-type-auction mb-0">
			{{ $lote_actual->name }}
		</h4>

		@if($cerrado)
		<p class="ficha-info-clock">{{ trans(\Config::get('app.theme').'-app.subastas.finalized') }}</p>
		@endif
	</div>

	@if(!$subasta_make_offer)
	<div class="ficha-date-closing">
		{{ trans(\Config::get('app.theme').'-app.lot.closing_date') }} <span id="cierre_lote"></span>
	</div>
	@endif

</div>


