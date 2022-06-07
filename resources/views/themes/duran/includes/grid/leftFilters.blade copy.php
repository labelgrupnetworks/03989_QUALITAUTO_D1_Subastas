
<div class="filters-auction-content">

	<div class="form-group">
		<form id="form_lotlist" class="color-text" method="get" action="{{ $url }}">
			<input type="hidden" name="order" id="hidden_order" value="{{request('order')}}"   />

			<div class="filters-auction-title d-flex align-items-center justify-content-space-between">
					<span>{{ trans(\Config::get('app.theme').'-app.lot_list.filters') }}</span>
			</div>
			<div class="filters-auction-texts">
				<label class="filters-auction-label" for="description"><span>{{ trans(\Config::get('app.theme').'-app.lot_list.search') }}</span></label>
				<input id="description" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.search_placeholder') }}" name="description" type="text" class="form-control input-sm filter-auction-input" value="{{ app('request')->input('description') }}">
				<div class="filters-auction-divider-medium"></div>
				@if(!empty($codSub) && !empty($refSession))
					<label class="filters-auction-label" for="reference">{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}</label>
					<input id="reference" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}" name="reference" type="text" class="form-control input-sm filter-auction-input" value="{{ app('request')->input('reference') }}">

				@endif


			<div class="filters-auction-divider-medium"></div>
			<button class="btn btn-filter color-letter" type="submit">{{ trans(\Config::get('app.theme').'-app.lot_list.filter') }}</button>

			</div>


			<div class="filters-auction-divider-medium"></div>
			@include('includes.grid.categories_list')
			<div class="filters-auction-divider-medium"></div>
			@include('includes.grid.features_list')

			@if(!empty($auction))
                @include('includes.grid.filter_sold')
            @else
				@include('includes.grid.typeAuction_list')
            @endif


		</form>
	</div>

</div>


<script>
    if (screen.width>768) {
        $("#estado_lotes").addClass("in");
        $("#auction_type").addClass("in");
        $("#auction_categories").addClass("in");
		@foreach($features as $idFeature => $feature)
			@if(!empty($featuresCount[$idFeature]))
				$("#feature_{{$idFeature}}").addClass("in");
			@endif
		@endforeach
    }
</script>







