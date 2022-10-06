<div class="auction__filters-estado-lote">
    <div id="auction_filters_top_my_lots" class="auction__filters-collapse-title" role="button" data-toggle=""
        href="#my_lots" aria-expanded="true" aria-controls="my_lots">
        <div class="d-flex align-items-center">
            <p class="m-0" style="flex: 1">{{ trans(\Config::get('app.theme') . '-app.lot_list.my_lots') }}
            </p>
            <i style="float: right; font-size: 14px" class="fas fa-plus"></i>
        </div>
    </div>

    <div class="auction__filters-type-list mt-1 " id="my_lots" style="display: none">
        <div class="filters-padding">
            <div class="category_level_01 d-flex align-items-center justify-content-space-between">
                <div class="radio">
                    <input type="radio" class="js-check-my-lots" name="myLotsProperty" id="myLotsProperty" value="1"
                        <?= !empty(request('myLotsProperty')) ? 'checked="checked"' : '' ?> />
                    <label for="myLotsProperty" class="radio-label">
                        {{ trans(\Config::get('app.theme') . '-app.lot_list.my_lots_property') }}
                    </label>
                </div>
            </div>
            <div class="category_level_01 d-flex align-items-center justify-content-space-between">
                <div class="radio">
                    <input type="radio" class="js-check-my-lots" name="myLotsClient" id="myLotsClient" value="1"
                        <?= !empty(request('myLotsClient')) ? 'checked="checked"' : '' ?> />
                    <label for="myLotsClient" class="radio-label">
                        {{ trans(\Config::get('app.theme') . '-app.lot_list.my_lots_clients') }}
                    </label>
                </div>
            </div>

        </div>
    </div>
</div>
