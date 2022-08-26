@if(config('app.countdown_ingrid', 0) && !empty($auction))
	<div class="filters-auction-content mb-1">
		<b><p data-countdown="{{ strtotime($auction->session_start) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($auction->session_start); ?>" data-closed="{{ 0 }}" class="timer mt-1"></p></b>
		<p>{{ \Tools::getDateFormat($auction->session_start, 'Y-m-d H:i:s', 'd/m/Y H:i') }} {{ trans(\Config::get('app.theme').'-app.lot_list.time_zone') }}</p>
	</div>
@endif

<div class="filters-auction-content">

	<div class="filters-auction-title d-flex align-items-end justify-content-between border-bottom pb-1 mt-1">
		<p>{{ trans(\Config::get('app.theme').'-app.lot_list.filters') }}</p>

		<button class="btn btn-sm btn-outline-border-lb-primary d-flex align-items-center" onclick="hideFilters(event)">
			<svg class="bi" width="16" height="16" fill="currentColor">
				<use xlink:href="/bootstrap-icons.svg#arrow-bar-left"/>
			</svg>
		</button>
	</div>

	<div class="form-group">
		<form id="form_lotlist" class="color-text" method="get" action="{{ $url }}">
			{{-- oldpage es la página en la que estabamos antes de ir a la ficha, al volver debemos ir a ella --}}
			<input type="hidden" name="oldpage" id="oldpage" value="{{request('oldpage')}}"   />
			<input type="hidden" name="oldlot" id="oldlot" value="{{request('oldlot')}}"   />
			<input type="hidden" name="order" id="hidden_order" value="{{request('order')}}"   />
			<input type="hidden" name="total" id="hidden_total" value="{{request('total')}}"   />
			<input type="hidden" name="historic" id="hidden_historic" value="{{request('historic')}}"   />

			<div class="filters-types mb-1">
				@include('includes.grid.badges_section')

				@include('includes.grid.search_list')

				@include('includes.grid.categories_list')

				@include('includes.grid.features_list')

				@if(!empty($auction))
					@if (strtotime($auction->session_start) < time() && ($auction->tipo_sub=='W'))
						@include('includes.grid.filter_sold')
					@endif
					@else
						@include('includes.grid.typeAuction_list')
				@endif
			</div>


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
