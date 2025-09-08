<div class="auction__filters-estado-lote">

    <p class="mb-1">
        {{ trans("$theme-app.lot_list.lots_status") }}
    </p>

    <ul class="list-group list-group-flush list-group-filters-category">
        {{-- <li class="list-group-item">
            <input class="js-check-award d-none" id="liveLots" name="liveLots" type="radio" value="1"
                @checked(!empty(request('liveLots'))) />

            <label class="radio-label" for="liveLots">
                {{ trans("$theme-app.lot_list.live_lots_filter") }}
            </label>
        </li> --}}

        <li class="list-group-item">
            <input class="js-check-award d-none" id="no-award" name="noAward" type="radio" value="1"
                @checked(!empty(request('noAward'))) />

            <label class="radio-label" for="no-award">
                {{ trans("$theme-app.lot_list.no_award_filter") }}
            </label>
        </li>

        <li class="list-group-item">
            <input class="js-check-award d-none" id="award" name="award" type="radio" value="1"
                @checked(!empty(request('award'))) />

            <label class="radio-label" for="award">
                {{ trans("$theme-app.lot_list.award_filter") }}
            </label>
        </li>

    </ul>

</div>
