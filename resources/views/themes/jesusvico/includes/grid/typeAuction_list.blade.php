<div class="auction__filters-type">

	<div class="auction__filters-collapse filter-parent-collapse with-caret text-center bg-lb-primary-150" data-bs-toggle="collapse"
		href="#auction_type" role="button" aria-expanded="true" aria-controls="auction_type">

		<div class="filter-title">{{ trans($theme.'-app.lot_list.auction_type') }}</div>
	</div>

	<div class="input-category d-flex align-items-center d-none">
		<input type="radio" name="typeSub" id="all_typesSub" value="" @checked(empty(request('typeSub')))/>
	</div>

	<div class="auction__filters-type-list collapse filter-child-collapse bg-lb-primary-50" id="auction_type">

		@foreach($tipos_sub as $typeSub =>$desType)
		<?php
			$numLots = Tools::showNumLots($numActiveFilters, $filters, "typeSub", $typeSub);
		?>

		<div @class(['input-type-auction', 'd-none' => $numLots == 0])>
			<div class="form-check">
				<input type="radio" name="typeSub" id="typeSub_{{$typeSub}}" value="{{$typeSub}}"
					class="filter_lot_list_js form-check-input"
					@checked($typeSub == $filters['typeSub'])
					@disabled($numLots == 0)
				/>
				<label for="typeSub_{{$typeSub}}"
					@class([
						'radio-label form-check-label',
						'disabled' => $numLots == 0,
					])>{{$desType}} ({{Tools::numberformat($numLots) }})
				</label>
			</div>
		</div>
		@endforeach

	</div>
</div>
