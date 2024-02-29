<div class="filters-auction-texts">
	<div class="auction__filters-collapse filter-parent-collapse d-flex align-items-center justify-content-between"
		role="button" href="#auction_search" aria-expanded="true" aria-controls="auction_search"
		data-bs-toggle="collapse">

		<div class="filter-title">{{ trans("$theme-app.head.search_button") }}</div>

		<svg class="bi" width="16" height="16" fill="currentColor">
			<use xlink:href="/bootstrap-icons.svg#caret-down"/>
		</svg>
	</div>

	<div id="auction_search" class="mt-3 collapse show filter-child-collapse">
		<label class="filters-auction-label w-100 mb-2">
			<p>{{ trans($theme.'-app.lot_list.search') }}</p>
			<input id="description" placeholder="{{ trans($theme.'-app.lot_list.search_placeholder') }}"
				name="description" type="text" class="form-control form-control-sm filter-auction-input search-input_js" value="{{ app('request')->input('description') }}">
		</label>

		@if(!empty($codSub) && !empty($refSession) || Config::get('app.search_by_reference_in_grid', false))
		<label class="filters-auction-label w-100 mb-2">
			<p>{{ trans($theme.'-app.lot_list.reference') }}</p>
			<input id="reference" placeholder="{{ trans($theme.'-app.lot_list.reference') }}"
				name="reference" type="text" class="form-control form-control-sm filter-auction-input search-input_js" value="{{ app('request')->input('reference') }}">
		</label>
		@endif

		<button class="btn btn-sm btn-lb-primary w-100" type="submit">{{ trans($theme.'-app.lot_list.filter') }}</button>
	</div>
</div>
