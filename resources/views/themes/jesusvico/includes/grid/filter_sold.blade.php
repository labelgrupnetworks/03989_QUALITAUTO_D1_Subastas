<div class="auction__filters-estado-lote">

    <div class="auction__filters-collapse filter-parent-collapse with-caret text-center bg-lb-primary-150" data-bs-toggle="collapse"
         href="#estado_lotes" role="button" aria-expanded="true" aria-controls="estado_lotes">

        <div class="filter-title">{{ trans("$theme-app.lot_list.lots_status") }}</div>
    </div>

    <div class="auction__filters-type-list collapse filter-child-collapse bg-lb-primary-50" id="estado_lotes">
        <div class="form-check">
            <input class="js-check-award form-check-input" id="liveLots" name="liveLots" type="radio" value="1"
                @checked(!empty(request('liveLots'))) />

            <label class="radio-label form-check-label" for="liveLots">
                {{ trans("$theme-app.lot_list.live_lots_filter") }}
            </label>
        </div>
        <div class="form-check">
            <input class="js-check-award form-check-input" id="no-award" name="noAward" type="radio" value="1"
                @checked(!empty(request('noAward'))) />

            <label class="radio-label form-check-label" for="no-award">
                {{ trans("$theme-app.lot_list.no_award_filter") }}
            </label>
        </div>
        <div class="form-check">
            <input class="js-check-award form-check-input" id="award" name="award" type="radio" value="1"
                @checked(!empty(request('award'))) />

            <label class="radio-label form-check-label" for="award">
                {{ trans("$theme-app.lot_list.award_filter") }}
            </label>
        </div>
    </div>

</div>
