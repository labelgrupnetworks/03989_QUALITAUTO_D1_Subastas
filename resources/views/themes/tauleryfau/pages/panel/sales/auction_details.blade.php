<div class="tab-pane" id="auction-details-{{ $id }}" role="tabpanel">
	<h4 class="auction-details_title">{{ $title }}</h4>

	<div class="panel-lots">
		<div class="panel-lots_header-wrapper">
			<div class="panel-lots_header">
				<p></p>
				<p>Lote</p>
				<p>Descripción</p>
				<p>Precio Salida</p>
				<p>Precio Actual</p>
				<p>Incremento</p>
				<p>Pujas / Pujadores</p>
			</div>
		</div>

		@foreach ($lots as $lot)
			@include('pages.panel.sales.lot', [
				'lot' => $lot,
			])
		@endforeach
	</div>
</div>
