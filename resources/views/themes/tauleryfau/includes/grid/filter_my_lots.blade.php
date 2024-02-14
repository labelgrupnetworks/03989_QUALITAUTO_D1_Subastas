<div class="auction__filters-estado-lote">
    <div class="filter-section-head">
        <h4>{{ trans($theme . '-app.lot_list.my_lots') }}</h4>
    </div>

    <div class="auction__filters-type-list" id="estado_lotes">
        <div class="filters-padding d-flex justify-content-space-between flex-wrap">
			<div class="category_level_01 d-flex align-items-center justify-content-space-between">
				<div class="radio">
					<input type="radio" class="js-check-my-lots" name="myLotsClient" id="myLotsClient" value="1"
					<?= !empty(request('myLotsClient')) ? 'checked="checked"' : '' ?> />
                    <label for="myLotsClient" class="radio-label">
						{{ trans($theme . '-app.lot_list.my_lots_clients') }}
                    </label>
                </div>
            </div>
			<div class="category_level_01 d-flex align-items-center justify-content-space-between">
				<div class="radio">
					<input type="radio" class="js-check-my-lots" name="myLotsProperty" id="myLotsProperty" value="1"
						<?= !empty(request('myLotsProperty')) ? 'checked="checked"' : '' ?> />
					<label for="myLotsProperty" class="radio-label">
						{{ trans($theme . '-app.lot_list.my_lots_property') }}
					</label>
				</div>
			</div>

        </div>
    </div>
</div>
