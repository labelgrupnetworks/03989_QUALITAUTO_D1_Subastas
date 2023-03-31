{{-- cargamos las secciones que dependen de este Tsec --}}
<div class="category_level__02 collapse show bg-lb-primary-50" id="sections_{{ $category['key_ortsec0'] }}"
    data-bs-toggle="collapse" style="font-size: 1rem; padding-left: 1rem;">

    <div class="input-category d-flex align-items-center hidden">
        <div class="radio">
            <input id="all_sections" name="section" type="radio" value=""
                <?= empty($filters['section']) ? 'checked="checked"' : '' ?> />
            <label class="ratio-label" for="all_sections">
                {{ trans(\Config::get('app.theme') . '-app.lot_list.all_subcategories') }} ({{ $numCategoryLots }})
            </label>
        </div>
    </div>

    @foreach ($sections as $sec)
        <?php $numSectionLots = Tools::showNumLots($numActiveFilters, $filters, 'section', $sec['cod_sec']); ?>
        @if ($numSectionLots > 0)
            <div class="input-category input-subcategory d-flex align-items-center">
                <div class="radio">
                    <input class="filter_lot_list_js" id="section_{{ $sec['cod_sec'] }}" name="section" type="radio"
                        value="{{ $sec['cod_sec'] }}"
                        <?= $sec['cod_sec'] == $filters['section'] ? 'checked="checked"' : '' ?> />
                    <label class="radio-label"
                        for="section_{{ $sec['cod_sec'] }}">{{ ucfirst(mb_strtolower($sec['des_sec'])) }}
                        ({{ Tools::numberformat($numSectionLots) }})</label>
                </div>
            </div>

            @if ($sec['cod_sec'] == $filters['section'])
                @include('includes.grid.subsections_list')
            @endif
        @endif
    @endforeach

</div>
