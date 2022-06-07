@if(!empty($auction) && $auction->tipo_sub != 'V')
	<div class="filters-auction-content">
		<b><p data-countdown="{{ strtotime($auction->session_start) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($auction->session_start); ?>" data-closed="{{ 0 }}" class="timer mt-1"></p></b>
		<p>{{ \Tools::getDateFormat($auction->session_start, 'Y-m-d H:i:s', 'd/m/Y H:i') }} {{ trans(\Config::get('app.theme').'-app.lot_list.time_zone') }}</p>
	</div>
@endif


<form id="form_lotlist" class="color-text d-flex flex-column" method="get" action="{{ $url }}">

	<div class="filters-auction-content">

		<div class="form-group">

			{{-- oldpage es la página en la que estabamos antes de ir a la ficha, al volver debemos ir a ella --}}
			<input type="hidden" name="oldpage" id="oldpage" value="{{request('oldpage')}}" />
			<input type="hidden" name="oldlot" id="oldlot" value="{{request('oldlot')}}" />
			<input type="hidden" name="order" id="hidden_order" value="{{request('order')}}" />

			<div id="auction_search_top" class="filters-auction-title text-center" role="button" data-toggle="collapse"
				href="#filters-auction-texts" aria-expanded="true" aria-controls="filters-auction-texts">
				<div class="d-flex align-items-center">
					<p class="m-0" style="flex: 1">{{ trans(\Config::get('app.theme').'-app.lot_list.filters') }}</p>
					<i style="float: right; font-size: 14px;" class="fas fa-minus"></i>
				</div>
			</div>
			<div class="filters-auction-texts collapse in" id="filters-auction-texts">
				<div class="input-group-search" style="position: relative">
					<input id="description" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.search') }}"
						name="description" type="text" class="form-control input-sm filter-auction-input"
						value="{{ app('request')->input('description') }}">

					<button type="submit" class="btn btn-search-filter">
							<i class="fa fa-search"></i>
							<div style="display: none;top:0;" class="loader mini"></div>
					</button>
				</div>

				<div class="filters-auction-divider-medium"></div>

				@if(!empty($codSub) && !empty($refSession))
				<div class="input-group-search" style="position: relative">
					<input id="reference" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}"
						name="reference" type="text" class="form-control input-sm filter-auction-input"
						value="{{ app('request')->input('reference') }}">

					<button type="submit" class="btn btn-search-filter">
							<i class="fa fa-search"></i>
							<div style="display: none;top:0;" class="loader mini"></div>
					</button>

				</div>
				@endif

				<div class="filters-auction-divider-medium"></div>

				<div class="order-auction-lot">
					<select class="form-control input-sm" id="order_selected">

						<option value="nameweb" @if ($filters["order"]=='nameweb' ) selected @endif>
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:
							{{ trans(\Config::get('app.theme').'-app.lot_list.name') }}
						</option>
						<option value="price_asc" @if ($filters["order"]=='price_asc' ) selected @endif>
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:
							{{ trans(\Config::get('app.theme').'-app.lot_list.price_asc') }}
						</option>
						<option value="price_desc" @if ($filters["order"]=='price_desc' ) selected @endif>
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:
							{{ trans(\Config::get('app.theme').'-app.lot_list.price_desc') }}
						</option>
						<option value="ref" @if ($filters["order"]=='ref' || empty($filters["order"]) ) selected @endif>
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:
							{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}
						</option>
						<option value="hbids" @if ($filters["order"]=='hbids' ) selected @endif>
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:
							{{ trans(\Config::get('app.theme').'-app.lot_list.higher_bids') }}
						</option>
						<option value="mbids" @if ($filters["order"]=='mbids' ) selected @endif>
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:
							{{ trans(\Config::get('app.theme').'-app.lot_list.more_bids') }}
						</option>
						<option value="lastbids" @if ($filters["order"]=='lastbids' ) selected @endif>
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:
							{{ trans(\Config::get('app.theme').'-app.lot_list.last_bids') }}
						</option>
						@if(!empty($auction) && $auction->tipo_sub == 'O'))
						<option value="ffin" @if ($filters["order"]=='ffin' ) selected @endif>
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}: <b>
								{{ trans(\Config::get('app.theme').'-app.lot_list.more_near') }} </b>
						</option>
						@endif
						<option value="award" @if ($filters["order"]=='award' ) selected @endif>
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}: <b>
								{{ trans(\Config::get('app.theme').'-app.lot_list.award_filter') }} </b>
						</option>
						<option value="noaward" @if ($filters["order"]=='noaward' ) selected @endif>
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}: <b>
								{{ trans(\Config::get('app.theme').'-app.lot_list.no_award_filter') }} </b>
						</option>

					</select>
				</div>


				<div class="filters-auction-divider-medium"></div>
				{{--
				<button class="btn btn-filter color-letter"
					type="submit">{{ trans(\Config::get('app.theme').'-app.lot_list.filter') }}
				</button>
				--}}

			</div>

		</div>
	</div>

	@if(!empty($auction) && $auction->tipo_sub == 'V')

	@php
	//$url_lotes= \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions, $subasta->reference);
	$subastaObj = new \App\Models\Subasta();
	$shops = $subastaObj->auctionList('S', 'V');
	if (Session::has('user') && Session::get('user.admin')) {
		$shopsAdmin = $subastaObj->auctionList('A', 'V');
		$shops = array_merge($shopsAdmin, $shops);
	}
	@endphp
	<div class="filters-auction-content">
		<div class="form-group">

			<div>
				<div id="js-shop_links" class="auction__filters-collapse-title" role="button" data-toggle=""
					href="#shop_links" aria-expanded="true" aria-controls="shop_links">
					<div class="d-flex align-items-center">
						<p class="m-0" style="flex: 1">{{ trans("$theme-app.subastas.stores") }}</p>
						<i style="float: right; font-size: 14px" class="fas fa-plus"></i>
					</div>
				</div>

				<div class="auction__filters-type-list mt-1 " id="shop_links" style="display: none">
					<div class="filters-padding">
						@foreach ($shops as $shop)
						<div class="category_level_01 d-flex align-items-center justify-content-space-between">
							<div class="radio">
								<input name="shop" type="radio" class="js-link-to-shop" id="radio_{{$shop->cod_sub}}"
									data-to="{{Tools::url_auction($shop->cod_sub, $shop->name, $shop->id_auc_sessions , $shop->reference) }}"
									@if($auction->cod_sub == $shop->cod_sub) checked @endif
								/>

								<label for="radio_{{$shop->cod_sub}}" class="radio-label">
									{{ $shop->des_sub }}
								</label>
							</div>
						</div>
						@endforeach

					</div>
				</div>
			</div>


		</div>
	</div>
	@endif


	<div class="filters-auction-content">
		<div class="form-group">

			@include('includes.grid.categories_list')

			@include('includes.grid.features_list')

		</div>
	</div>



	@if(!empty($auction))
	{{--@include('includes.grid.filter_sold')--}}
	@else
	<div class="filters-auction-content">
		<div class="form-group">
			@include('includes.grid.typeAuction_list')
		</div>
	</div>
	@endif


	@if(Session::has('user'))
	<div class="filters-auction-content">
		<div class="form-group">
			@include('includes.grid.filter_my_lots')
		</div>
	</div>
	@endif


</form>


<script>
	if (screen.width>768) {
        $("#estado_lotes").addClass("in");
        $("#auction_type").addClass("in");
        $("#auction_categories").addClass("in");
		@foreach($features as $idFeature => $feature)
			@if(!empty($featuresCount[$idFeature]))
				$("#feature_{{$idFeature}}").addClass("in");
			@endif
		@endforeach
	}

	$('#auction_search_top').click(() => {
		var busquedas = $('#filters-auction-texts');
		var icon = $('#auction_search_top i');
		if (busquedas.is(':hidden')) {
			icon.removeClass();
			icon.addClass('fas fa-minus');
		} else {
			icon.removeClass();
			icon.addClass('fas fa-plus');
		}
	});
</script>
