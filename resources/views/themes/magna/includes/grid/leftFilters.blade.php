@if(config('app.countdown_ingrid', 0) && !empty($auction))
	<div class="filters-auction-content mb-1">
		<b><p data-countdown="{{ strtotime($auction->session_start) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($auction->session_start); ?>" data-closed="{{ 0 }}" class="timer mt-1"></p></b>
		<p>{{ \Tools::getDateFormat($auction->session_start, 'Y-m-d H:i:s', 'd/m/Y H:i') }} {{ trans($theme.'-app.lot_list.time_zone') }}</p>
	</div>
@endif

<div class="filters-auction-content">

	<div class="filters-auction-title d-flex align-items-end justify-content-between border-bottom py-1 mt-1">
		<p>{{ trans($theme.'-app.lot_list.filters') }}</p>

		<button class="btn btn-sm btn-link btn-icon d-lg-none" onclick="hideFilters(event)">
			<x-icon.boostrap icon="caret-down-fill" size="16" />
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
				@include('includes.grid.search_list')

				@include('includes.grid.categories_list')

				@if(!empty($features))
					@include('includes.grid.features_list')
				@endif
			</div>


		</form>
	</div>

</div>
