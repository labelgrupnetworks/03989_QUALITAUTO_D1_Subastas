<div class="auction__filters-type">

    <div class="auction__filters-collapse filter-parent-collapse d-flex align-items-center justify-content-between"
        data-bs-toggle="collapse" href="#auction_type" role="button" aria-expanded="false" aria-controls="auction_type">

        <div class="filter-title">{{ trans($theme . '-app.lot_list.auction_type') }}</div>

        <svg class="bi" width="16" height="16" fill="currentColor">
            <use xlink:href="/bootstrap-icons.svg#caret-down-fill" />
        </svg>
    </div>

    <div class="input-category d-flex align-items-center d-none">
        <input id="all_typesSub" name="typeSub" type="radio" value="" @checked(empty(request('typeSub'))) />
    </div>

    <div class="auction__filters-type-list collapse filter-child-collapse mt-1" id="auction_type">

        @foreach ($tipos_sub as $typeSub => $desType)
            @php
                $numLots = Tools::showNumLots($numActiveFilters, $filters, 'typeSub', $typeSub);
            @endphp

            <div @class(['input-type-auction', 'input-type-auction--disabled' => $numLots == 0])>
                <div class="radio">
                    <?php //si no ha lotes no podrán marcar la opcion
                    ?>

                    <input class="filter_lot_list_js" id="typeSub_{{ $typeSub }}" name="typeSub" type="radio"
                        value="{{ $typeSub }}" @checked($typeSub == $filters['typeSub']) @disabled($numLots == 0) />
                    <label for="typeSub_{{ $typeSub }}" @class(['radio-label', 'disabled' => $numLots == 0])>{{ $desType }}

                        <span class="grid-count">
                            ({{ Tools::numberformat($numLots) }})
                        </span>
                    </label>

                </div>
            </div>
        @endforeach

    </div>
</div>
