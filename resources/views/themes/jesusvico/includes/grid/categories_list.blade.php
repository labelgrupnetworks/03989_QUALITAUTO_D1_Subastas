<div class="auction__filters-categories">

    <div class="auction__filters-collapse filter-parent-collapse with-caret text-center bg-lb-primary-150" data-bs-toggle="collapse"
        href="#auction_categories" role="button" aria-expanded="true" aria-controls="auction_categories">

		<div class="filter-title">{{ trans(\Config::get('app.theme') . '-app.lot_list.categories') }}</div>
    </div>

    <div class="auction__filters-type-list collapse filter-child-collapse p-0" id="auction_categories">

        <div class="input-category bg-lb-primary-50">
			<div class="form-check">
				<input type="radio" name="category" id="all_categories" value="" class="filter_lot_list_js form-check-input" @checked(empty($filters["category"]))/>
				<label for="all_categories" class="form-check-label">
					{{trans(\Config::get('app.theme').'-app.lot_list.all_categories')}}
				</label>
			</div>
        </div>


        @foreach ($categories as $category)
            <?php
            $linOrtsec0 = $category['lin_ortsec0'];
            $numCategoryLots = Tools::showNumLots($numActiveFilters, $filters, 'category', $linOrtsec0);
            ?>

            <?php # si una categoria no tiene lotes no puede estar marcada ni buscar las subcategorias
            ?>
            @if ($linOrtsec0 == $filters['category'] && $numCategoryLots > 0)
                <div class="input-category auction__filters-collapse d-flex align-items-center justify-content-space-between bg-lb-primary-50"
                    data-toggle="collapse" href="#sections_{{ $category['key_ortsec0'] }}" role="button"
                    aria-expanded="true" aria-controls="sections_{{ $category['key_ortsec0'] }}">

					<div class="category_level_01">
                        <div class="radio form-check">
                            <input class="filter_lot_list_js form-check-input" id="category_{{ $linOrtsec0 }}" name="category"
                                type="radio" value="{{ $linOrtsec0 }}" checked="checked" />
                            <label class="radio-label form-check-label" for="category_{{ $linOrtsec0 }}">{{ $category['des_ortsec0'] }}
                                ({{ Tools::numberformat($numCategoryLots) }})</label>
                        </div>
                    </div>
                </div>

                @include('includes.grid.sections_list')

            @elseif($numCategoryLots > 0)
                <div class="input-category bg-lb-primary-50">

                    <div class="radio form-check">
                        <input class="filter_lot_list_js form-check-input" id="category_{{ $linOrtsec0 }}" name="category"
                            type="radio" value="{{ $linOrtsec0 }}" />

							<label class="radio-label form-check-label"
                            for="category_{{ $linOrtsec0 }}">{{ $category['des_ortsec0'] }}
                            ({{ Tools::numberformat($numCategoryLots) }})</label>
                    </div>

                </div>
            @endif
        @endforeach

    </div>
</div>
