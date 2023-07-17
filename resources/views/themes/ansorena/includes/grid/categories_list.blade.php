<div class="auction__filters-categories">

    <fieldset>
        <legend class="ff-highlight">{{ trans("$theme-app.lot_list.categories") }}</legend>

        <div class="form-check">
            <input type="radio" name="category" id="all_categories" class="filter_lot_list_js form-check-input"
                value="" {{ empty($filters['category']) ? 'checked="checked"' : '' }} />

            <label for="all_categories" class="form-check-label">
                {{ trans("$theme-app.global.all") }}
            </label>
        </div>

        @foreach ($categories as $category)
            @php
                $linOrtsec0 = $category['lin_ortsec0'];
                $numCategoryLots = Tools::showNumLots($numActiveFilters, $filters, 'category', $linOrtsec0);
            @endphp
            @if ($linOrtsec0 == $filters['category'] && $numCategoryLots > 0)
                <div class="input-category auction__filters-collapse" role="button" data-bs-toggle="collapse"
                    href="#sections_{{ $category['key_ortsec0'] }}" aria-expanded="true"
                    aria-controls="sections_{{ $category['key_ortsec0'] }}">

                    <div class="category_level_01">
                        <div class="form-check">
                            <input type="radio" name="category" id="category_{{ $linOrtsec0 }}"
                                value="{{ $linOrtsec0 }}" class="form-check-input" checked="checked" />
                            <label for="category_{{ $linOrtsec0 }}"
                                class="form-check-label">{{ $category['des_ortsec0'] }}
                                ({{ Tools::numberformat($numCategoryLots) }})
                            </label>
                        </div>
                    </div>
                    <i role="button" data-bs-toggle="collapse" href="#sections_{{ $category['key_ortsec0'] }}"
                        aria-expanded="true" aria-controls="sections_{{ $category['key_ortsec0'] }}"
                        class="fa fa-sort-down"></i>
                </div>

                @include('includes.grid.sections_list')
            @elseif($numCategoryLots > 0)
                <div class="input-category">

                    <div class="form-check">
                        <input type="radio" name="category" id="category_{{ $linOrtsec0 }}"
                            value="{{ $linOrtsec0 }}" class="filter_lot_list_js form-check-input" />

                        <label for="category_{{ $linOrtsec0 }}"
                            class="form-check-label">{{ $category['des_ortsec0'] }}
                            ({{ Tools::numberformat($numCategoryLots) }})</label>
                    </div>

                </div>
            @endif
        @endforeach

    </fieldset>
</div>
