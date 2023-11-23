{{-- cargamos las secciones que dependen de este Tsec --}}
<div class="category_level__02 collapse show" id="sections_{{ $category['key_ortsec0'] }}" aria-expanded="true"
    style="padding-left: 2rem;">

	{{-- see all --}}
	<div class="input-category hidden">
        <div class="radio">
            <input id="all_sections" name="section" type="radio" value="" @checked(empty($filters['section']))>
            <label class="ratio-label" for="all_sections">
				{{ trans("$theme-app.lot_list.all_subcategories") }}
				<span class="grid-count">
                	({{ $numCategoryLots }})
				</span>
            </label>
        </div>
    </div>

    @foreach ($sections as $sec)
        @php
            $numSectionLots = Tools::showNumLots($numActiveFilters, $filters, 'section', $sec['cod_sec']);
			$isSelected = $sec['cod_sec'] == $filters['section'];
        @endphp
        @if ($numSectionLots > 0)
            <div class="input-category d-flex align-items-center">
                <div class="radio">
                    <input class="filter_lot_list_js" id="section_{{ $sec['cod_sec'] }}" name="section" type="radio"
                        value="{{ $sec['cod_sec'] }}" @checked($isSelected)>

					<label class="radio-label" for="section_{{ $sec['cod_sec'] }}">
                        {{ ucfirst(mb_strtolower($sec['des_sec'])) }}
                        <span class="grid-count">
                            ({{ Tools::numberformat($numSectionLots) }})
                        </span>
                    </label>
                </div>
            </div>

            @if ($isSelected)
                @include('includes.grid.subsections_list')
            @endif
        @endif
    @endforeach

</div>
