@foreach ($tipos_sub as $typeSub => $desType)
    <?php
    $numLots = Tools::showNumLots($numActiveFilters, $filters, 'typeSub', $typeSub);
    ?>

    @if ($numLots)
        <div class="filters-auction-content num-lots-view">
            <div class="filters-auction-title d-flex align-items-center justify-content-space-between">

                <div class="input-type-auction d-flex align-items-center">
                    <div class="radio">
                        <?php //si no ha lotes no aparece la opcion
                        ?>

                        <input type="radio" name="typeSub" id="typeSub_{{ $typeSub }}" value="{{ $typeSub }}"
                            class="filter_lot_list_js" <?= $numLots > 0 ? '' : 'disabled=disabled' ?>
                            <?= $typeSub == $filters['typeSub'] ? 'checked=checked' : '' ?> />
                        <label for="typeSub_{{ $typeSub }}"
                            class="radio-label <?= $numLots > 0 ? '' : 'disabled-label' ?>">{{ trans("$theme-app.sheet_tr.view") }} {{ $desType }}
                            ({{ Tools::numberformat($numLots) }})
                        </label>

                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
<div class="input-category d-flex align-items-center hidden">
	<input type="radio" name="typeSub" id="all_typesSub" value="" {{ empty(request('typeSub'))? 'checked="checked"' : '' }}  />
</div>
