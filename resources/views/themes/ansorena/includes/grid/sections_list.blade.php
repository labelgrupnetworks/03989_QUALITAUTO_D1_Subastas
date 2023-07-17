{{-- cargamos las secciones que dependen de este Tsec --}}
<div class="category_level__02 collapse show" style="padding-left: 2rem;" id="sections_{{ $category['key_ortsec0'] }}">
    <div class="input-category d-flex align-items-center">
        <div class="form-check">
            <input type="radio" class="form-check-input" name="section" id="all_sections" value=""
                <?= empty($filters['section']) ? 'checked="checked"' : '' ?> />
            <label for="all_sections" class="form-check-label">
                {{ trans("$theme-app.global.all") }} ({{ $numCategoryLots }})
            </label>
        </div>
    </div>
    @foreach ($sections as $sec)
        <?php $numSectionLots = Tools::showNumLots($numActiveFilters, $filters, 'section', $sec['cod_sec']); ?>
        @if ($numSectionLots > 0)
            <div class="input-category d-flex align-items-center">
                <div class="form-check">
                    <input type="radio" name="section" id="section_{{ $sec['cod_sec'] }}"
                        value="{{ $sec['cod_sec'] }}" class="filter_lot_list_js form-check-input"
                        <?= $sec['cod_sec'] == $filters['section'] ? 'checked="checked"' : '' ?> />
                    <label for="section_{{ $sec['cod_sec'] }}" class="form-check-label">
                        {{ $sec['des_sec'] }} ({{ Tools::numberformat($numSectionLots) }})
                    </label>
                </div>
            </div>

            @if ($sec['cod_sec'] == $filters['section'])
                @include('includes.grid.subsections_list')
            @endif
        @endif
    @endforeach

</div>
