<div class="auction__filters-estado-lote">
        <div id="auction_filters_top" class="auction__filters-collapse-title" role="button" data-toggle="" href="#estado_lotes" aria-expanded="true" aria-controls="estado_lotes">
            <div class="d-flex align-items-center">
				<p  class="m-0" style="flex: 1">{{ trans(\Config::get('app.theme').'-app.lot_list.lots_status') }}</p>
					<i style="float: right; font-size: 14px" class="fas fa-plus"></i>
			</div>
        </div>

        <div class="auction__filters-type-list mt-1 collapse" id="estado_lotes" >
            <div class="input-category auction__filters-collapse d-flex align-items-center justify-content-space-between collapse in" id="">
                <div class="category_level_01 d-flex align-items-center justify-content-space-between">
                      <div class="radio">
                        <input type="radio" class="js-check-award" name="noAward" id="no-award" value="1" <?=  !empty(request('noAward'))? 'checked="checked"' : '' ?>  />
                        <label for="no-award" class="radio-label">
                            {{trans(\Config::get('app.theme').'-app.lot_list.no_award_filter')}}
                        </label>
                    </div>
                </div>
                 <div class="category_level_01 d-flex align-items-center justify-content-space-between">
                    <div class="radio">
                        <input type="radio" class="js-check-award" name="award" id="award" value="1" <?=  !empty(request('award'))? 'checked="checked"' : '' ?>  />
                        <label for="award" class="radio-label">
                            {{trans(\Config::get('app.theme').'-app.lot_list.award_filter')}}
                        </label>
                    </div>
                </div>
            </div>
        </div>

</div>


