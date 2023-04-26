<div class="auction__filters-estado-lote">

    <div class="auction__filters-collapse filter-parent-collapse d-flex align-items-center justify-content-space-between"
        data-toggle="collapse" href="#estado_lotes" role="button" aria-expanded="true" aria-controls="estado_lotes">

        <div class="filter-title">{{ trans("$theme-app.lot_list.lots_status") }}</div>

        @include('components.boostrap_icon', ['icon' => 'caret-down-fill'])
    </div>

    <div class="auction__filters-type-list mt-1 collapse filter-child-collapse" id="estado_lotes">
        <div class="input-category auction__filters-collapse d-flex align-items-center justify-content-space-between collapse in"
            id="">
            <div class="category_level_01 d-flex align-items-center justify-content-space-between">
                <div class="radio">
                    <input class="js-check-award" id="liveLots" name="liveLots" type="radio" value="1"
                        @checked(!empty(request('liveLots'))) />

                    <label class="radio-label" for="liveLots">
                        {{ trans("$theme-app.lot_list.live_lots_filter") }}
                    </label>
                </div>
            </div>
            <div class="category_level_01 d-flex align-items-center justify-content-space-between">
                <div class="radio">
                    <input class="js-check-award" id="no-award" name="noAward" type="radio" value="1"
                        @checked(!empty(request('noAward'))) />

                    <label class="radio-label" for="no-award">
                        {{ trans("$theme-app.lot_list.no_award_filter") }}
                    </label>
                </div>
            </div>
            <div class="category_level_01 d-flex align-items-center justify-content-space-between">
                <div class="radio">
                    <input class="js-check-award" id="award" name="award" type="radio" value="1"
                        @checked(!empty(request('award'))) />

                    <label class="radio-label" for="award">
                        {{ trans("$theme-app.lot_list.award_filter") }}
                    </label>
                </div>
            </div>
        </div>
    </div>

</div>
