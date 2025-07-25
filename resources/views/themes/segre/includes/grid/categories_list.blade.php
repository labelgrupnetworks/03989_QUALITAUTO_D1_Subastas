<div class="auction__filters-categories">

    <p>
        {{ trans("$theme-app.lot_list.categories") }}
    </p>

    <ul class="list-group list-group-flush list-group-filters-category">
        <li class="d-none">
            <input id="all_categories" name="category" type="radio" value="" @checked(empty($filters['category'])) />
        </li>

        @foreach ($categories as $category)
            @php
                $linOrtsec0 = $category['lin_ortsec0'];
                $numCategoryLots = Tools::showNumLots($numActiveFilters, $filters, 'category', $linOrtsec0);
				$isChecked = $linOrtsec0 == $filters['category'];
            @endphp

			@if($numCategoryLots > 0)
            <li @class([
				'list-group-item',
				//'active' => $linOrtsec0 == $filters['category']
			])>
                <input class="filter_lot_list_js d-none" id="category_{{ $linOrtsec0 }}" name="category" type="radio"
                    value="{{ $linOrtsec0 }}" @checked($isChecked) />

                <label class="radio-label" for="category_{{ $linOrtsec0 }}">
                    {{ $category['des_ortsec0'] }}
                </label>

				@if($isChecked)
                	@include('includes.grid.sections_list')
				@endif
            </li>
			@endif
        @endforeach
    </ul>

</div>
