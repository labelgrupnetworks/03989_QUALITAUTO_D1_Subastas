<ul class="list-group list-group-flush list-group-filters-category">
    <li class="d-none">
        <input id="all_sections" name="section" type="radio" value="" @checked(empty($filters['section'])) />
        <label for="">
            {{ trans("$theme-app.lot_list.all_subcategories") }}
        </label>
    </li>

    @foreach ($sections as $sec)
        @php
            $numSectionLots = Tools::showNumLots($numActiveFilters, $filters, 'section', $sec['cod_sec']);
            $isSelected = $sec['cod_sec'] == $filters['section'];
        @endphp
        @if ($numSectionLots > 0)
            <li class="list-group-item">
                <input class="filter_lot_list_js d-none" id="section_{{ $sec['cod_sec'] }}" name="section" type="radio"
                    value="{{ $sec['cod_sec'] }}" @checked($isSelected)>
                <label class="radio-label" for="section_{{ $sec['cod_sec'] }}">
                    {{ ucfirst(mb_strtolower($sec['des_sec'])) }}
                </label>
            </li>

			{{-- No tienen, crear la vista solamente en caso de necesitarla --}}
            {{-- @if ($isSelected)
                @include('includes.grid.subsections_list')
            @endif --}}
        @endif
    @endforeach
</ul>
