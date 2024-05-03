<div class="tab-pane" id="auction-details-{{ $id }}" role="tabpanel">
	<h4 class="auction-details_title">{{ $title }}</h4>

	<div class="panel-lots">
		<div class="panel-lots_header-wrapper">
			<div class="table-grid_header panel-lots_header">
				<p></p>
				<p>{{ trans("$theme-app.user_panel.lot") }}</p>
				<p>{{ trans("$theme-app.user_panel.description") }}</p>
				<p>{{ trans("$theme-app.user_panel.starting_price") }}</p>
				<p>{{ trans("$theme-app.user_panel.actual_price") }}</p>
				<p>{{ trans("$theme-app.user_panel.increase") }}</p>
				<p>{{ trans("$theme-app.user_panel.bids") }} / {{ trans("$theme-app.user_panel.bidders") }}</p>
			</div>
		</div>

		@foreach ($lots as $lot)
			@include('pages.panel.sales.lot', [
				'lot' => $lot,
			])
		@endforeach
	</div>
</div>
