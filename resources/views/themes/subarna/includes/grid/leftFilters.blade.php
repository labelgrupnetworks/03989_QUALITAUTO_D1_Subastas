@if ($auction && $codSub && $codSub != 'VDJ')
    <div class="expo-container">

		<div class="align-items-center d-flex justify-content-space-between" data-toggle="collapse" href="#info-collapse" role="button"
                aria-expanded="true" aria-controls="info-collapse">

                <h4 class="m-0">{{ trans($theme . '-app.subastas.see_subasta') }}</h4>
				<i class="fa fa-sort-down hidden-md hidden-lg"></i>
            </div>

            <div class="filters-collapse collapse pt-1" id="info-collapse">
				<p>{{ trans($theme . '-app.subastas.auction_day') }} {{ $auction->sesfechas_sub }} -
					{{ $auction->seshorario_sub }}</p>
				<p>{{ trans($theme . '-app.calendar.expo') }} {{ $auction->expofechas_sub }}</p>
				<p>{{ trans($theme . '-app.lot.location') }}: {{ $auction->seslocal_sub }}</p>
			</div>


    </div>
@elseif($auction && $codSub && $codSub == 'VDJ')
    <div class="expo-container">
        <p>
            {{ trans("$theme-app.lot_list.jewel_auction") }}
        </p>
    </div>
@endif

<div class="filters-auction-content">

    <div class="form-group">
        <form id="form_lotlist" method="get" action="{{ $url }}">

            {{-- oldpage es la página en la que estabamos antes de ir a la ficha, al volver debemos ir a ella --}}
            <input id="oldpage" name="oldpage" type="hidden" value="{{ request('oldpage') }}" />
            <input id="oldlot" name="oldlot" type="hidden" value="{{ request('oldlot') }}" />
            <input id="hidden_order" name="order" type="hidden" value="{{ request('order') }}" />
            <input id="hidden_total" name="total" type="hidden" value="{{ request('total') }}" />

            <div class="filters-auction-title bold" data-toggle="collapse" href="#js-filters-collapse" role="button"
                aria-expanded="true" aria-controls="js-filters-collapse">
                <p>{{ trans($theme . '-app.lot_list.filters') }}</p>
				<i class="fa fa-sort-down hidden-md hidden-lg"></i>
            </div>

            <div class="filters-collapse collapse" id="js-filters-collapse">

                <div class="filters-auction-texts">
                    <label class="filters-auction-label"
                        for="description"><span>{{ trans($theme . '-app.lot_list.search') }}</span></label>

                    <input class="form-control input-sm filter-auction-input search-input_js" id="description"
                        name="description" type="text" value="{{ app('request')->input('description') }}"
                        placeholder="{{ trans($theme . '-app.lot_list.search_placeholder') }}">



                    @if ((!empty($codSub) && !empty($refSession)) || request()->typeSub == 'P')
                        <label class="filters-auction-label"
                            for="reference">{{ trans($theme . '-app.lot_list.reference') }}</label>
                        <input class="form-control input-sm filter-auction-input search-input_js" id="reference"
                            name="reference" type="text" value="{{ app('request')->input('reference') }}"
                            placeholder="{{ trans($theme . '-app.lot_list.reference') }}">
                    @endif


                    <button class="btn btn-block btn-lb-primary text-dark" type="submit">
                        {{ trans($theme . '-app.lot_list.filter') }}
                    </button>
                </div>

                <div class="filters-auctions-categories">
                    @include('includes.grid.categories_list')

                    @include('includes.grid.features_list')

                    @if (!empty($auction))
                        @include('includes.grid.filter_sold')
                    @else
                        <div style="display: none">
                            @include('includes.grid.typeAuction_list')
                        </div>
                    @endif
                </div>

            </div>

        </form>
    </div>

</div>


<script>
    $("#estado_lotes").addClass("in");
    $("#auction_type").addClass("in");
    $("#auction_categories").addClass("in");
    @foreach ($features as $idFeature => $feature)
        @if (!empty($featuresCount[$idFeature]))
            $("#feature_{{ $idFeature }}").addClass("in");
        @endif
    @endforeach

	//if min-width 992px
	if ($(window).width() > 992) {
		$('.filters-collapse').addClass('in');
	}

	$('#js-filters-collapse').on('show.bs.collapse', function (event) {
		if($(window).width() > 992) {
			return false;
		}
	});

	$('#js-filters-collapse').on('hide.bs.collapse', function (event) {
		if($(window).width() > 992) {
			return false;
		}
	});

	//se propaga el evento a los collapse superiores. mirar si se puede solucionar.
	/* $('.filters-auction-content .collapse').each((_, el) => {

		$('.fa-sort-down').addClass('fa-sort-up').removeClass('fa-sort-down');

		$(el).on('show.bs.collapse', function (event) {
			const iconDom = $(`[aria-controls=${this.id}] i`);
			iconDom.removeClass('fa-sort-down').addClass('fa-sort-up');
		});

		$(el).on('hide.bs.collapse', function (event) {
			const iconDom = $(`[aria-controls=${this.id}] i`);
			iconDom.removeClass('fa-sort-up').addClass('fa-sort-down');
		});
	}); */
</script>
