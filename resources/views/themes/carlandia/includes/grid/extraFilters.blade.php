<div class="filters-searching">
	<div class="d-flex align-items-center justify-content-center">
			<div class="form-group">
				<input id="description" class="form-control input-custom"
					value="{{ app('request')->input('description') }}" placeholder="{{ trans($theme.'-app.head.search_label') }}" type="text" name="description" />
			</div>
			<button role="button" type="submit" class="btn btn-custom-search">{{ trans($theme.'-app.head.search_button') }}</button>
	</div>
</div>

<div class="filters-searching">
	<div class="d-flex align-items-center justify-content-center">
		<div class="form-group">
			<input id="reference" class="form-control input-custom"
				value="{{ app('request')->input('reference') }}" placeholder="{{ trans($theme.'-app.lot_list.find_offer_number') }}" type="text" name="reference" />
		</div>
		<button role="button" type="submit" class="btn btn-custom-search">{{ trans($theme.'-app.head.search_button') }}</button>
	</div>
</div>

@if(\Config::get("app.paginacion_grid_lotes"))
@php
	$count_lots = 0;

	foreach($tipos_sub as $typeSub =>$desType) {

		$numLots = Tools::showNumLots($numActiveFilters, $filters, "typeSub", $typeSub);

		if(empty($filters['typeSub'])){
			$count_lots += $numLots;

		}elseif($typeSub == $filters['typeSub']){
			$count_lots = $numLots;

		}

	}
@endphp
<div class="filters-pagination">
		{{-- ponemos puntos de millar --}}
		<p class="mb-0">{{ Tools::numberformat($count_lots) }} {{ trans(\Config::get('app.theme').'-app.lot_list.results') }}</p>

		{{ $paginator->links('front::includes.grid.paginator_pers') }}
	</div>
@endif

@include('includes.grid.typeAuction_list')

{{-- <div class="filters-auction-content num-lots-view">

	<div class="filters-auction-title d-flex align-items-center justify-content-space-between" role="button" aria-expanded="true"
			aria-controls="collapse_typeAuction_filter" data-toggle="collapse" data-target="#collapse_typeAuction_filter">
		<span>VER</span>
		<span id="js-collapse_simbol" style="float: right"><i class="fa fa-plus" aria-hidden="true" style="font-size: 16px"></i></span>
	</div>

	<div class="form-group collapse-js collapse" id="collapse_typeAuction_filter" aria-expanded="true">

		<div class="input-category d-flex align-items-center hidden">
			<input type="radio" name="typeSub" id="all_typesSub" value="" {{ empty(request('typeSub'))? 'checked="checked"' : '' }}  />
		</div>

		@include('includes.grid.typeAuction_list')

	</div>
</div> --}}

<div class="filters-auction-content num-lots-view">

	<div class="filters-auction-title d-flex align-items-center justify-content-space-between" role="button" aria-expanded="true"
			aria-controls="collapse_total_filter" data-toggle="collapse" data-target="#collapse_total_filter">
		<span>VEHÍCULOS POR PÁGINA</span>
		<span id="js-collapse_simbol" style="float: right"><i class="fa fa-plus" aria-hidden="true" style="font-size: 16px"></i></span>
	</div>

	<div class="form-group collapse-js collapse" id="collapse_total_filter" aria-expanded="true">

		@foreach(config("app.filter_total_shown_options") as $key => $numLots)
		<div>
			<input type="radio" name="total" id="total_{{$key}}" value="{{$numLots}}" class="filter_lot_list_js" @if (request('total') == $numLots) checked=checked @endif/>
			<label for="total_{{$key}}" class="radio-label "> {{ trans(\Config::get('app.theme').'-app.lot_list.see_num_lots',["num" => $numLots]) }}</label>
		</div>
		@endforeach

	</div>
</div>

<div class="filters-auction-content order-lots-grid">

	<div class="filters-auction-title d-flex align-items-center justify-content-space-between" role="button" aria-expanded="true"
		aria-controls="collapse_filter" data-toggle="collapse" data-target="#collapse_order_filter">
		<span>{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}</span>
		<span id="js-collapse_simbol" style="float: right"><i class="fa fa-plus" aria-hidden="true" style="font-size: 16px"></i></span>
	</div>

	<div class="form-group collapse-js collapse" id="collapse_order_filter" aria-expanded="false">

		<div class="order_filter_group">
			<div class="d-flex justify-content-space-between"
				role="button" data-toggle="collapse"
				href="#order_1" aria-expanded="false">

				<div>{{ trans("$theme-app.lot_list.vehicles") }}</div>
				<i class="fa fa-sort-down"></i>
			</div>

			<div class="mt-1 collapse in collapse-options" id="order_1" aria-expanded="false" style="">
				<div class="d-flex justify-content-space-between align-items-center" style="gap:5px;">
					<input type="radio" name="order" id="order_bestPrice" value="bestPrice" class="filter_lot_list_js m-0"  @if ($filters["order"] == 'bestPrice') checked=checked @endif/>
					<label for="order_bestPrice" class="radio-label m-0">
						{{ trans(\Config::get('app.theme').'-app.lot_list.bestPrice') }}
					</label>
					<i style="margin-left: auto;" class="fa fa-info-circle ml-1 js-grid-modal" data-html="true" aria-hidden="true" data-title="{{ trans($theme.'-app.lot_list.bestPrice') }}" data-content="{{ trans($theme.'-app.lot_list.info_bestPrice') }}"></i>

				</div>
				<div class="d-flex justify-content-space-between align-items-center" style="gap:5px;">
					<input type="radio" name="order" id="order_posible_bestPrice" value="posibleBestPrice" class="filter_lot_list_js m-0"  @if ($filters["order"] == 'posibleBestPrice') checked=checked @endif/>
					<label for="order_posible_bestPrice" class="radio-label m-0">
						{{ trans(\Config::get('app.theme').'-app.lot_list.posibleBestPrice') }}
					</label>
					<i style="margin-left: auto;" class="fa fa-info-circle ml-1 js-grid-modal" data-html="true" aria-hidden="true" data-title="{{ trans($theme.'-app.lot_list.posibleBestPrice') }}" data-content="{{ trans($theme.'-app.lot_list.info_posibleBestPrice') }}"></i>

				</div>
			</div>
		</div>

		<div class="order_filter_group mb-1">
			<div class="d-flex justify-content-space-between"
				role="button" data-toggle="collapse"
				href="#order_2" aria-expanded="false">

				<div>{{ trans("$theme-app.lot_list.prices") }}</div>
				<i class="fa fa-sort-down"></i>
			</div>

			<div class="mt-1 collapse collapse-options" id="order_2" aria-expanded="false" style="">
				<div>
					<input type="radio" name="order" id="order_price_asc" value="price_asc" class="filter_lot_list_js"  @if ($filters["order"] == 'price_asc') checked=checked @endif/>
					<label for="order_price_asc" class="radio-label"> {{ trans(\Config::get('app.theme').'-app.lot_list.price_asc') }}</label>
				</div>
				<div>
					<input type="radio" name="order" id="order_price_desc" value="price_desc" class="filter_lot_list_js"  @if ($filters["order"] == 'price_desc') checked=checked @endif/>
					<label for="order_price_desc" class="radio-label"> {{ trans(\Config::get('app.theme').'-app.lot_list.price_desc') }}</label>
				</div>
			</div>
		</div>

		<div class="order_filter_group">
			<div class="d-flex justify-content-space-between"
				role="button" data-toggle="collapse"
				href="#order_3" aria-expanded="false">

				<div>{{ trans("$theme-app.lot_list.offers_that") }}</div>
				<i class="fa fa-sort-down"></i>
			</div>

			<div class="mt-1 collapse collapse-options" id="order_3" aria-expanded="false" style="">
				<div>
					<input type="radio" name="order" id="order_date_asc" value="date_asc" class="filter_lot_list_js"  @if ($filters["order"] == 'date_asc') checked=checked @endif/>
					<label for="order_date_asc" class="radio-label"> {{ trans(\Config::get('app.theme').'-app.lot_list.date_asc') }}</label>
				</div>
				<div>
					<input type="radio" name="order" id="order_date_desc" value="date_desc" class="filter_lot_list_js"  @if ($filters["order"] == 'date_desc') checked=checked @endif/>
					<label for="order_date_desc" class="radio-label"> {{ trans(\Config::get('app.theme').'-app.lot_list.date_desc') }}</label>
				</div>
				<div>
					<input type="radio" name="order" id="order_olderLots" value="olderLots" class="filter_lot_list_js"  @if ($filters["order"] == 'olderLots') checked=checked @endif/>
					<label for="order_olderLots" class="radio-label"> {{ trans(\Config::get('app.theme').'-app.lot_list.olderLots') }}</label>
				</div>
				<div>
					<input type="radio" name="order" id="order_newerLots" value="newerLots" class="filter_lot_list_js"  @if ($filters["order"] == 'newerLots') checked=checked @endif/>
					<label for="order_newerLots" class="radio-label"> {{ trans(\Config::get('app.theme').'-app.lot_list.newerLots') }}</label>
				</div>
				<div class="d-flex" style="gap:5px;">
					<input type="radio" name="order" id="order_more_bids" value="mbids" class="filter_lot_list_js"  @if ($filters["order"] == 'mbids') checked=checked @endif/>
					<label for="order_more_bids" class="radio-label"> {{ trans(\Config::get('app.theme').'-app.lot_list.more_bids') }}</label>
				</div>
			</div>
		</div>

		{{-- <div class="order_filter_group">
			<div class="d-flex align-items-center justify-content-space-between"
				role="button" data-toggle="collapse"
				href="#order_4" aria-expanded="false">

				<div>{{ trans("$theme-app.lot_list.advertisements") }}</div>
				<i class="fa fa-sort-down"></i>
			</div>

			<div class="mt-1 collapse collapse-options" id="order_4" aria-expanded="false" style="">

			</div>
		</div> --}}

		{{-- <div class="order_filter_group">
			<div class="d-flex align-items-center justify-content-space-between"
				role="button" data-toggle="collapse"
				href="#order_5" aria-expanded="false">

				<div>{{ trans("$theme-app.lot_list.ads_with") }}</div>
				<i class="fa fa-sort-down"></i>
			</div>

			<div class="mt-1 collapse" id="order_5" aria-expanded="false" style="">

				<div>
					<input type="radio" name="order" id="order_lastbids" value="lastbids" class="filter_lot_list_js"  @if ($filters["order"] == 'lastbids') checked=checked @endif/>
					<label for="order_lastbids" class="radio-label"> {{ trans(\Config::get('app.theme').'-app.lot_list.last_bids') }}</label>
				</div>
			</div>
		</div> --}}

		{{-- <div class="order_filter_group">
			<div class="d-flex justify-content-space-between"
				role="button" data-toggle="collapse"
				href="#order_6" aria-expanded="false">

				<div>{{ trans("$theme-app.lot_list.to_show") }}</div>
				<i class="fa fa-sort-down"></i>
			</div>

			<div class="mt-1 collapse" id="order_6" aria-expanded="false" style="">
				<div>
					<input type="radio" name="order" id="order_auctionFirst" value="auctionFirst" class="filter_lot_list_js"  @if ($filters["order"] == 'auctionFirst') checked=checked @endif/>
					<label for="order_auctionFirst" class="radio-label"> {{ trans(\Config::get('app.theme').'-app.lot_list.auctionFirst') }}</label>
				</div>
				<div>
					<input type="radio" name="order" id="order_directSaleFirst" value="directSaleFirst" class="filter_lot_list_js"  @if ($filters["order"] == 'directSaleFirst') checked=checked @endif/>
					<label for="order_directSaleFirst" class="radio-label"> {{ trans(\Config::get('app.theme').'-app.lot_list.directSaleFirst') }}</label>
				</div>
			</div>
		</div> --}}



		{{-- <div>
			<input type="radio" name="order" id="order_name" value="name" class="filter_lot_list_js"  @if ($filters["order"] == 'name') checked=checked @endif/>
			<label for="order_name" class="radio-label"> {{ trans(\Config::get('app.theme').'-app.lot_list.name') }}</label>
		</div>

		<div>
			<input type="radio" name="order" id="order_hbids" value="hbids" class="filter_lot_list_js"  @if ($filters["order"] == 'hbids') checked=checked @endif/>
			<label for="order_phbids" class="radio-label"> {{ trans(\Config::get('app.theme').'-app.lot_list.higher_bids') }}</label>
		</div> --}}

		@if(!empty($auction) && $auction->tipo_sub == 'O')
		<div>
			<input type="radio" name="order" id="order_ffin" value="ffin" class="filter_lot_list_js"  @if ($filters["order"] == 'ffin') checked=checked @endif/>
			<label for="order_ffin" class="radio-label"> {{ trans(\Config::get('app.theme').'-app.lot_list.more_near') }}</label>
		</div>
		@endif

	</div>

</div>
