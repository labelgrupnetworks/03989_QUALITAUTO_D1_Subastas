<div class="auction__filters-estado-lote">

	<fieldset>
		<legend class="ff-highlight">{{ trans("$theme-app.lot_list.lots_status") }}</legend>

		<div class="auction__filters-type-list bg-lb-primary-50">
			@if ($auction->subc_sub == App\Models\V5\FgSub::SUBC_SUB_ACTIVO)
				<div class="form-check">
					<input class="js-check-award form-check-input" id="liveLots" name="liveLots" type="radio" value="1"
						@checked(!empty(request('liveLots'))) />

					<label class="radio-label form-check-label" for="liveLots">
						{{ trans("$theme-app.lot_list.live_lots_filter") }}
					</label>
				</div>
			@endif
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

	</fieldset>

</div>
