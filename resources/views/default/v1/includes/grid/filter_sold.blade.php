<div class="auction__filters-estado-lote">
        <div class="auction__filters-collapse d-flex align-items-center justify-content-space-between" role="button" data-toggle="collapse" href="#estado_lotes" aria-expanded="true" aria-controls="estado_lotes">
            <div>{{ trans($theme.'-app.lot_list.lots_status') }}</div>
            <i class="fa fa-sort-down"></i>
        </div>

        <div class="auction__filters-type-list mt-1 collapse" id="estado_lotes" >
            <div class="input-category auction__filters-collapse">
                <div class="category_level_01 d-flex align-items-center justify-content-space-between">
                    <div class="radio">
                        <input type="radio" class="js-check-award" name="liveLots" id="liveLots" value="1" <?=  !empty(request('liveLots'))? 'checked="checked"' : '' ?>  />
                        <label for="liveLots" class="radio-label">
                            {{trans($theme.'-app.lot_list.live_lots_filter')}}
                        </label>
                    </div>
                </div>
                <div class="category_level_01 d-flex align-items-center justify-content-space-between">
                      <div class="radio">
                        <input type="radio" class="js-check-award" name="noAward" id="no-award" value="1" <?=  !empty(request('noAward'))? 'checked="checked"' : '' ?>  />
                        <label for="no-award" class="radio-label">
                            {{trans($theme.'-app.lot_list.no_award_filter')}}
                        </label>
                    </div>
                </div>
                 <div class="category_level_01 d-flex align-items-center justify-content-space-between">
                    <div class="radio">
                        <input type="radio" class="js-check-award" name="award" id="award" value="1" <?=  !empty(request('award'))? 'checked="checked"' : '' ?>  />
                        <label for="award" class="radio-label">
                            {{trans($theme.'-app.lot_list.award_filter')}}
                        </label>
                    </div>
                </div>
            </div>
        </div>

</div>

