<div class="filters-auction-texts bg-lb-primary-50">
    <div class="auction__filters-collapse filter-parent-colapse with-caret text-center bg-lb-primary-150"
        data-bs-toggle="collapse" href="#auction_search" role="button" aria-expanded="true" aria-controls="auction_search">

        <div class="filter-title">{{ trans("$theme-app.head.search_button") }}</div>
    </div>

    <div class="collapse show filter-child-collapse" id="auction_search">
        <div class="input-group mb-2">
            <input class="form-control filter-auction-input search-input_js" id="description" name="description"
                type="text" value="{{ app('request')->input('description') }}"
                placeholder="{{ trans($theme . '-app.lot_list.search_placeholder') }}">
            <button class="btn btn-sm btn-lb-primary d-flex align-items-center" type="submit">
                @include('components.boostrap_icon', ['icon' => 'search'])
            </button>
        </div>


        @if (!empty($codSub) && !empty($refSession))
            <div class="input-group mb-2">
                <input class="form-control filter-auction-input search-input_js" id="reference" name="reference"
                    type="text" value="{{ app('request')->input('reference') }}"
                    placeholder="{{ trans($theme . '-app.lot_list.reference') }}">
                <button class="btn btn-sm btn-lb-primary d-flex align-items-center" type="submit">
                    @include('components.boostrap_icon', ['icon' => 'search'])
                </button>
            </div>
        @endif

        {{-- <div>
            <select class="form-select form-select-sm" id="total_selected">
                @foreach (\Config::get('app.filter_total_shown_options') as $numLots)
                    <option value="{{ $numLots }}" @if (request('total') == $numLots) selected @endif>
                        {{ trans($theme . '-app.lot_list.see_num_lots', ['num' => $numLots]) }}
                    </option>
                @endforeach
            </select>
        </div> --}}

        <div class="input-group">
            <select class="form-select filter-auction-input" id="order_selected">
                <option value="name" @if ($filters['order'] == 'name') selected @endif>
                    {{ trans($theme . '-app.lot_list.order') }}:
                    {{ trans($theme . '-app.lot_list.name') }}
                </option>
                <option value="price_asc" @if ($filters['order'] == 'price_asc') selected @endif>
                    {{ trans($theme . '-app.lot_list.order') }}:
                    {{ trans($theme . '-app.lot_list.price_asc') }}
                </option>
                <option value="price_desc" @if ($filters['order'] == 'price_desc') selected @endif>
                    {{ trans($theme . '-app.lot_list.order') }}:
                    {{ trans($theme . '-app.lot_list.price_desc') }}
                </option>
                <option value="ref" @if ($filters['order'] == 'ref' || empty($filters['order'])) selected @endif>
                    {{ trans($theme . '-app.lot_list.order') }}:
                    {{ trans($theme . '-app.lot_list.reference') }}
                </option>

                <option value="date_asc" @if ($filters['order'] == 'date_asc') selected @endif>
                    {{ trans($theme . '-app.lot_list.order') }}:
                    {{ trans($theme . '-app.lot_list.date_asc') }}
                </option>
                <option value="date_desc" @if ($filters['order'] == 'date_desc') selected @endif>
                    {{ trans($theme . '-app.lot_list.order') }}:
                    {{ trans($theme . '-app.lot_list.date_desc') }}
                </option>
                <option value="hbids" @if ($filters['order'] == 'hbids') selected @endif>
                    {{ trans($theme . '-app.lot_list.order') }}:
                    {{ trans($theme . '-app.lot_list.higher_bids') }}
                </option>
                <option value="mbids" @if ($filters['order'] == 'mbids') selected @endif>
                    {{ trans($theme . '-app.lot_list.order') }}:
                    {{ trans($theme . '-app.lot_list.more_bids') }}
                </option>
                <option value="lastbids" @if ($filters['order'] == 'lastbids') selected @endif>
                    {{ trans($theme . '-app.lot_list.order') }}:
                    {{ trans($theme . '-app.lot_list.last_bids') }}
                </option>

                @if (!empty($auction) && $auction->tipo_sub == 'O')
                    <option value="ffin" @if ($filters['order'] == 'ffin') selected @endif>
                        {{ trans($theme . '-app.lot_list.order') }}: <b>
                            {{ trans($theme . '-app.lot_list.more_near') }} </b>
                    </option>
                @endif
            </select>
        </div>
    </div>
</div>
