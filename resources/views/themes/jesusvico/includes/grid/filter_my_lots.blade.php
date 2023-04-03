<div class="auction__filters-my-lots">
    <div class="auction__filters-collapse filter-parent-collapse with-caret text-center bg-lb-primary-150"
        data-bs-toggle="collapse" href="#estado_lotes" role="button" aria-expanded="true" aria-controls="estado_lotes">

        <div class="filter-title">{{ trans("$theme-app.lot_list.my_lots") }}</div>
    </div>

    <div class="auction__filters-type-list show filter-child-collapse p-0" id="myLots">
        <div class="input-category bg-lb-primary-50">
            <div class="radio form-check">
                <input class="js-check-my-lots form-check-input" id="myLotsProperty" name="myLotsProperty"
                    type="radio" value="1" @checked(!empty(request('myLotsProperty'))) />

                <label class="radio-label form-check-label" for="myLotsProperty">
                    {{ trans("$theme-app.lot_list.my_lots_property") }}
                </label>
            </div>
        </div>
        <div class="input-category bg-lb-primary-50">

            <div class="radio form-check">
                <input class="js-check-my-lots form-check-input" id="myLotsClient" name="myLotsClient" type="radio"
                    value="1" @checked(!empty(request('myLotsClient'))) />

                <label class="radio-label form-check-label" for="myLotsClient">
                    {{ trans("$theme-app.lot_list.my_lots_clients") }}
                </label>
            </div>
        </div>

    </div>
</div>
