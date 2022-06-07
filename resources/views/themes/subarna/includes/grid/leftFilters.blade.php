@if($auction && $codSub && $codSub != 'VDJ')
<div class="expo-container">
		<h4><b>{{ trans(\Config::get('app.theme').'-app.subastas.see_subasta') }}</b></h4>
		<p>{{ trans(\Config::get('app.theme').'-app.subastas.auction_day') }} {{ $auction->sesfechas_sub }} - {{ $auction->seshorario_sub }}</p>
		<p>{{ trans(\Config::get('app.theme').'-app.calendar.expo') }} {{$auction->expofechas_sub}}</p>
		<p>{{ trans(\Config::get('app.theme').'-app.lot.location') }}: {{$auction->seslocal_sub}}</p>
</div>
@elseif($auction && $codSub && $codSub == 'VDJ')
<div class="expo-container">
<p>
	{{ trans("$theme-app.lot_list.jewel_auction") }}

</p>
</div>
@endif


<div class="filters-auction-content">

	<div class="form-group">
		<form id="form_lotlist" class="color-text" method="get" action="{{ $url }}">
			{{-- oldpage es la página en la que estabamos antes de ir a la ficha, al volver debemos ir a ella --}}
		<input type="hidden" name="oldpage" id="oldpage" value="{{request('oldpage')}}"   />
		<input type="hidden" name="oldlot" id="oldlot" value="{{request('oldlot')}}"   />
		<input type="hidden" name="order" id="hidden_order" value="{{request('order')}}"   />
		<input type="hidden" name="total" id="hidden_total" value="{{request('total')}}"   />

			<div class="filters-auction-title d-flex align-items-center justify-content-space-between">
					<span>{{ trans(\Config::get('app.theme').'-app.lot_list.filters') }}</span>
			</div>
			<div class="filters-auction-texts">
				<label class="filters-auction-label" for="description"><span>{{ trans(\Config::get('app.theme').'-app.lot_list.search') }}</span></label>
				<input id="description" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.search_placeholder') }}" name="description" type="text" class="form-control input-sm filter-auction-input search-input_js" value="{{ app('request')->input('description') }}">
				<div class="filters-auction-divider-medium"></div>

				@if( (!empty($codSub) && !empty($refSession)) || request()->typeSub == 'P')
					<label class="filters-auction-label" for="reference">{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}</label>
					<input id="reference" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}" name="reference" type="text" class="form-control input-sm filter-auction-input search-input_js" value="{{ app('request')->input('reference') }}">

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
				<div style="display: none">
					@include('includes.grid.typeAuction_list')
				</div>
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







