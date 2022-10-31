<div class="filters-auction-texts bg-lb-primary-50">
	<div class="auction__filters-collapse filter-parent-colapse with-caret text-center bg-lb-primary-150"
		role="button" href="#auction_search" aria-expanded="true" aria-controls="auction_search"
		data-bs-toggle="collapse">

		<div class="filter-title">{{ trans("$theme-app.head.search_button") }}</div>
	</div>

	<div id="auction_search" class="collapse show filter-child-collapse">
		<div class="input-group mb-2">
			<input id="description" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.search_placeholder') }}"
				name="description" type="text" class="form-control filter-auction-input search-input_js" value="{{ app('request')->input('description') }}">
			<button class="btn btn-sm btn-lb-primary d-flex align-items-center" type="submit">
				@include('components.boostrap_icon', ['icon' => 'search'])
			</button>
		</div>


		@if(!empty($codSub) && !empty($refSession))
		<div class="input-group">
			<input id="reference" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}"
				name="reference" type="text" class="form-control filter-auction-input search-input_js" value="{{ app('request')->input('reference') }}">
			<button class="btn btn-sm btn-lb-primary d-flex align-items-center" type="submit">
				@include('components.boostrap_icon', ['icon' => 'search'])
			</button>
		</div>
		@endif
	</div>
</div>
