@php
	$isOnlyPurchasables = request('purchasable') == true;
    $defaultOrder = 'ref';
    $orders = [
        'name' => 'name',
        'price_asc' => 'price_asc',
        'price_desc' => 'price_desc',
        'ref' => 'reference',
        'hbids' => 'higher_bids',
        'mbids' => 'more_bids',
        'lastbids' => 'last_bids',
        'name' => 'name',
    ];
@endphp

@if (config('app.countdown_ingrid', 0) && !empty($auction))
    <div class="filters-auction-content mb-1">
        <b>
            <p data-countdown="{{ strtotime($auction->session_start) - getdate()[0] }}"
                data-format="<?= \Tools::down_timer($auction->session_start) ?>" data-closed="{{ 0 }}"
                class="timer mt-1"></p>
        </b>
        <p>{{ \Tools::getDateFormat($auction->session_start, 'Y-m-d H:i:s', 'd/m/Y H:i') }}
            {{ trans($theme . '-app.lot_list.time_zone') }}</p>
    </div>
@endif

<div class="filters-auction-content position-relative">

    <form id="form_lotlist" class="" method="get" action="{{ $url }}#grid-lots">
        <input type="hidden" name="oldpage" id="oldpage" value="{{ request('oldpage') }}" />
        <input type="hidden" name="oldlot" id="oldlot" value="{{ request('oldlot') }}" />
        {{-- <input type="hidden" name="order" id="hidden_order" value="{{ request('order') }}" /> --}}
        <input type="hidden" name="total" id="hidden_total" value="{{ request('total') }}" />
        <input type="hidden" name="historic" id="hidden_historic" value="{{ request('historic') }}" />

		@if(request('purchasable'))
			<input type="hidden" name="purchasable" value="1" />
			<input type="hidden" name="noAward" value="1" />
		@endif

        <div class="form-filters">
            <div class="filters-auction-texts">

                <div class="position-relative mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control newsletter-input" id="description" name="description"
                            placeholder="BUSCAR POR TEXTO" aria-label="buscar lote por texto"
                            value="{{ app('request')->input('description') }}">
                        <label for="description">{{ trans("$theme-app.lot_list.search") }}</label>
                    </div>

                    <button type="submit"
                        class="btn btn-lb-primary btn-medium newsletter-submit">{{ trans("$theme-app.head.search_button") }}</button>
                </div>

                @if (!empty($codSub) && !empty($refSession))
                    <div class="position-relative">
                        <div class="form-floating">
                            <input type="text" class="form-control newsletter-input" id="reference" name="reference"
                                placeholder="BUSCAR POR REFERENCIA" aria-label="buscar lote por reference"
                                value="{{ app('request')->input('reference') }}">
                            <label for="description">{{ trans("$theme-app.lot_list.search_by_reference") }}</label>
                        </div>

                        <button type="submit"
                            class="btn btn-lb-primary btn-medium newsletter-submit">{{ trans("$theme-app.head.search_button") }}</button>
                    </div>
                @endif

            </div>



			<div class="">
				@if($isOnlyPurchasables)
					@include('front::includes.grid.badges_section_purchasable')
				@else
					@include('front::includes.grid.badges_section')
				@endif
            </div>

			@if(!$isOnlyPurchasables)
				@include('includes.grid.categories_list')

				@include('includes.grid.features_list')

				@if (!empty($auction))
					@if (strtotime($auction->session_start) < time() && $auction->tipo_sub == 'W' && session('user'))
						@include('includes.grid.filter_sold')
					@endif
				@else
					@include('includes.grid.typeAuction_list')
				@endif
			@endif
        </div>

        <div class="order-auction-lot">

            <fieldset>
                <legend class="ff-highlight">{{ trans("$theme-app.lot_list.order") }}</legend>

                @foreach ($orders as $orderValue => $orderTranslate)
                    <div class="form-check">
                        <input type="radio" name="order" class="form-check-input" id="order_{{ $orderValue }}"
                            value="{{ $orderValue }}" @if (request('order', $defaultOrder) === $orderValue) checked @endif />

                        <label for="order_{{ $orderValue }}" class="form-check-label">
                            {{ trans("$theme-app.lot_list.$orderTranslate") }}
                        </label>
                    </div>
                @endforeach

                @if (!empty($auction) && $auction->tipo_sub == 'O')
                    <div class="form-check">
                        <input type="radio" name="order" class="form-check-input" id="order_ffin" value="ffin"
                            @if (request('order', $defaultOrder) === 'ffin') checked @endif />

                        <label for="order_ffin" class="form-check-label">
                            {{ trans("$theme-app.lot_list.more_near") }}
                        </label>
                    </div>
                @endif
            </fieldset>

        </div>

    </form>

</div>
