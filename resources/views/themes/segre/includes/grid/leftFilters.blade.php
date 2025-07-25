@if (config('app.countdown_ingrid', 0) && !empty($auction))
    <div class="filters-auction-content mb-1">
        <b>
            <p class="timer mt-1" data-countdown="{{ strtotime($auction->session_start) - getdate()[0] }}"
                data-format="<?= \Tools::down_timer($auction->session_start) ?>" data-closed="{{ 0 }}"></p>
        </b>
        <p>{{ \Tools::getDateFormat($auction->session_start, 'Y-m-d H:i:s', 'd/m/Y H:i') }}
            {{ trans($theme . '-app.lot_list.time_zone') }}</p>
    </div>
@endif

<div class="filters-auction-content">

    <div class="form-group">
        <form class="color-text" id="form_lotlist" method="get" action="{{ $url }}">
            {{-- oldpage es la página en la que estabamos antes de ir a la ficha, al volver debemos ir a ella --}}
            <input id="oldpage" name="oldpage" type="hidden" value="{{ request('oldpage') }}" />
            <input id="oldlot" name="oldlot" type="hidden" value="{{ request('oldlot') }}" />
            <input id="hidden_order" name="order" type="hidden" value="{{ request('order') }}" />
            <input id="hidden_total" name="total" type="hidden" value="{{ request('total') }}" />
            <input id="hidden_historic" name="historic" type="hidden" value="{{ request('historic') }}" />

			<input id="reference" name="reference" type="hidden" value="{{ request('reference') }}" />
			<input id="description" name="description" type="hidden" value="{{ request('description') }}" />

            <div class="filters-types mb-1">
                @include('includes.grid.badges_section')

                <div class="filter-title">{{ trans("$theme-app.head.search_button") }}</div>

                @include('includes.grid.categories_list')

                @if (!empty($features))
                    @include('includes.grid.features_list')
                @endif

				@include('includes.grid.filter_sold')

            </div>


        </form>
    </div>

</div>
