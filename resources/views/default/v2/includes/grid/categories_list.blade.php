<div class="auction__filters-categories">

    <div class="auction__filters-collapse filter-parent-collapse d-flex align-items-center justify-content-between" data-bs-toggle="collapse"
        href="#auction_categories" role="button" aria-expanded="true" aria-controls="auction_categories">

		<div class="filter-title">{{ trans(\Config::get('app.theme') . '-app.lot_list.categories') }}</div>

        <svg class="bi" width="16" height="16" fill="currentColor">
            <use xlink:href="/bootstrap-icons.svg#caret-down-fill" />
        </svg>
    </div>

    <div class="auction__filters-type-list mt-2 collapse filter-child-collapse" id="auction_categories">

        <div class="input-category d-flex align-items-center d-none">
            <input id="all_categories" name="category" type="radio" value=""
                <?= empty($filters['category']) ? 'checked="checked"' : '' ?> />
        </div>


        @foreach ($categories as $category)
            <?php
            $linOrtsec0 = $category['lin_ortsec0'];
            $numCategoryLots = Tools::showNumLots($numActiveFilters, $filters, 'category', $linOrtsec0);
            ?>

            <?php # si una categoria no tiene lotes no puede estar marcada ni buscar las subcategorias
            ?>
            @if ($linOrtsec0 == $filters['category'] && $numCategoryLots > 0)

				<div class="input-category auction__filters-collapse d-flex align-items-center justify-content-space-between mb-1"
					data-bs-toggle="collapse" href="#sections_{{ $category['key_ortsec0'] }}" role="button"
                    aria-expanded="true" aria-controls="sections_{{ $category['key_ortsec0'] }}">

					<div class="category_level_01">
                        <div class="radio">
                            <input class="filter_lot_list_js" id="category_{{ $linOrtsec0 }}" name="category"
                                type="radio" value="{{ $linOrtsec0 }}" checked="checked" />
                            <label class="radio-label" for="category_{{ $linOrtsec0 }}">
								{{ $category['des_ortsec0'] }}
                                <span class="grid-count">({{ Tools::numberformat($numCategoryLots) }})</span>
							</label>
                        </div>
                    </div>

					@include('components.boostrap_icon', ['icon' => 'caret-down-fill', 'size' => '16'])

                </div>

                @include('includes.grid.sections_list')
            @elseif($numCategoryLots > 0)
                <div class="input-category d-flex align-items-center mb-1">

                    <div class="radio">
                        <input class="filter_lot_list_js" id="category_{{ $linOrtsec0 }}" name="category"
                            type="radio" value="{{ $linOrtsec0 }}"
                            <?= $numCategoryLots> 0 ? '' : 'disabled=disabled' ?> />
                        <label class="radio-label <?= $numCategoryLots > 0 ? '' : 'disabled-label' ?>"
                            for="category_{{ $linOrtsec0 }}">{{ $category['des_ortsec0'] }}
							<span class="grid-count">({{ Tools::numberformat($numCategoryLots) }})</span>
						</label>
                    </div>

                </div>
            @endif
        @endforeach

    </div>
</div>
