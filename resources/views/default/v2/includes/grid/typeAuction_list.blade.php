<div class="auction__filters-type">

	<div class="auction__filters-collapse filter-parent-collapse d-flex align-items-center justify-content-between" role="button"
		data-bs-toggle="collapse" href="#auction_type" aria-expanded="false" aria-controls="auction_type">

		<div class="filter-title">{{ trans(\Config::get('app.theme').'-app.lot_list.auction_type') }}</div>

		<svg class="bi" width="16" height="16" fill="currentColor">
			<use xlink:href="/bootstrap-icons.svg#caret-down-fill" />
		</svg>
	</div>

	<div class="input-category d-flex align-items-center d-none">
		<input type="radio" name="typeSub" id="all_typesSub" value="" @checked(empty(request('typeSub')))/>
	</div>

	<div class="auction__filters-type-list collapse filter-child-collapse mt-1" id="auction_type">

		@foreach($tipos_sub as $typeSub =>$desType)
		<?php
			$numLots = Tools::showNumLots($numActiveFilters, $filters, "typeSub", $typeSub);
		?>

		<div class="input-type-auction d-flex align-items-center">
			<div class="radio">
				<?php //si no ha lotes no podrán marcar la opcion ?>

				<input type="radio" name="typeSub" id="typeSub_{{$typeSub}}" value="{{$typeSub}}" class="filter_lot_list_js"
					@checked($typeSub == $filters['typeSub'])
					@disabled($numLots == 0)
				/>
				<label for="typeSub_{{$typeSub}}"
					@class([
						'radio-label',
						'disabled' => $numLots == 0,
					])>{{$desType}} ({{Tools::numberformat($numLots) }})
				</label>

			</div>
		</div>
		@endforeach

	</div>
</div>
