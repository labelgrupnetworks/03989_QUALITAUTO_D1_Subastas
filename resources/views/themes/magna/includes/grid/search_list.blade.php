<div class="filters-auction-texts">

	<div id="auction_search" class="filters-auction-texts-form">
		<label class="filters-auction-label">
			<p>{{ trans($theme.'-app.lot_list.search') }}</p>

			<input id="description" placeholder="{{ trans($theme.'-app.lot_list.search_placeholder') }}"
				name="description" type="text" class="form-control form-control-sm filter-auction-input search-input_js" value="{{ app('request')->input('description') }}">
		</label>

		@if(!empty($codSub) && !empty($refSession) || Config::get('app.search_by_reference_in_grid', false))
		<label class="filters-auction-label mb-2">
			<p>{{ trans($theme.'-app.lot_list.reference') }}</p>
			<input id="reference" placeholder="{{ trans($theme.'-app.lot_list.reference') }}"
				name="reference" type="text" class="form-control form-control-sm filter-auction-input search-input_js" value="{{ app('request')->input('reference') }}">
		</label>
		@endif

		<button class="btn btn-lb-primary rounded-5" type="submit">{{ trans($theme.'-app.lot_list.filter') }}</button>
	</div>
</div>
