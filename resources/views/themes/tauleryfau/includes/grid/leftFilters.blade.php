


	@if(!empty($auction))

		@php
			$SubastaTR = new \App\Models\SubastaTiempoReal();
			$SubastaTR->cod =$auction->cod_sub;
			$SubastaTR->session_reference =  $auction->reference;
			$status  = $SubastaTR->getStatus();
			$subasta_finalizada = false;
			if(!empty($status) && $status[0]->estado == "ended"){
				$subasta_finalizada = true;
			}
		@endphp

		<div class="filter-section-head filter-name-auction hidden-xs hidden-sm">
			<h4 class="text-center">{!! str_replace('-', '<br>', $auction->des_sub) !!}</h4>
		</div>

		<div class="lot-count">
			<div  class="text-center timeLeftOnLeft online-time">
				<span class=" hidden-md hidden-lg"> {{ explode('-', $auction->des_sub)[1] ?? $auction->des_sub }} </span>
				<span class="hidden-md hidden-lg"> | </span>
				<span>
					<span class="clock hidden-md hidden-lg"></span>
					<span data-countdown="{{ strtotime($auction->session_start) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($auction->session_start); ?>" data-closed="{{ 0 }}" class="timer"></span>
					<span class="clock hidden-xs hidden-sm"></span>
				</span>
			</div>
		</div>

		<div class="filters filters-padding filters-info-auciton hidden-xs hidden-sm">
			<div class="filter-buttons-info">

				<a href="{{  $auction->descdet_sub }}" class="btn btn-color" type="submit">{{ trans("$theme-app.lot_list.info_sub") }}</a>

				@if($auction->tipo_sub == 'W' && strtotime($auction->session_end) > time() && !$subasta_finalizada)
					<a href="{{ Tools::url_real_time_auction($auction->cod_sub, $auction->name, $auction->id_auc_sessions) }}" class="btn btn-live">{{ trans(\Config::get('app.theme').'-app.lot.bid_live') }}</a>
				@endif

			</div>
		</div>
	@endif

	<div class="filters">


	<div class="filter-section-head filter-order-auction">
		<h4>{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}</h4>
	</div>

	<div class="filters-padding">
		<select id="order_selected" name="order" class="form-control submit_on_change">
			<!-- Eloy: Desactivado por pettición del cliente 16/09/2019
			<option value="name" @if (app('request')->input('order') == 'name') selected @endif >
				{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   {{ trans(\Config::get('app.theme').'-app.lot_list.name') }}
		</option>
			-->
		<option value="price_asc" @if (app('request')->input('order') == 'price_asc') selected @endif >
				{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:    {{ trans(\Config::get('app.theme').'-app.lot_list.price_asc') }}
		</option>
		<option value="price_desc" @if (app('request')->input('order') == 'price_desc') selected @endif >
			{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:      {{ trans(\Config::get('app.theme').'-app.lot_list.price_desc') }}
		</option>
		<option value="ref" @if (empty(app('request')->input('order')) || app('request')->input('order') == 'ref') selected @endif >
				{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:     {{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}
		</option>
		<?php /*
			<option value="ffin" @if (app('request')->input('order') == 'ffin') selected @endif >
					{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b>   {{ trans(\Config::get('app.theme').'-app.lot_list.more_near') }} </b>
			</option>
			*/ ?>
			<option value="mbids" @if (app('request')->input('order') == 'mbids') selected @endif >
					{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b>   {{ trans(\Config::get('app.theme').'-app.lot_list.more_bids') }} </b>
			</option>

			<option value="hbids" @if (app('request')->input('order') == 'hbids') selected @endif >
					{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b>   {{ trans(\Config::get('app.theme').'-app.lot_list.higher_bids') }} </b>
			</option>


			<option value="lastbids" @if (app('request')->input('order') == 'lastbids') selected @endif >
					{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:    {{ trans(\Config::get('app.theme').'-app.lot_list.last_bids') }}
			</option>
			<option value="360" @if (app('request')->input('order') == '360') selected @endif >
					{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:    {{ trans(\Config::get('app.theme').'-app.lot_list.lots_360') }}
			</option>

		<?php //si son subastas presenciales y ya ha empezado que permita filtrar por lotes?>
		@if(!empty($data['sub_data']) && $data['sub_data']->tipo_sub == 'W' && ($data['sub_data']->subc_sub == 'S' ||   $data['sub_data']->subc_sub == 'A') && strtotime($data['sub_data']->start) < strtotime("now") )
			<option value="fbuy" @if (empty(app('request')->input('order')) || app('request')->input('order') == 'fbuy') selected @endif >
					{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b>   {{ trans(\Config::get('app.theme').'-app.lot_list.filter_by_buy') }} </b>
			</option>
		@endif
		</select>
	</div>

	</div>

	<div class="filters filters-auction-content">

		<div class="form-group">
			<form id="form_lotlist" class="color-text" method="get" action="{{ $url }}">
				{{-- oldpage es la página en la que estabamos antes de ir a la ficha, al volver debemos ir a ella --}}
			<input type="hidden" name="oldpage" id="oldpage" value="{{request('oldpage')}}"   />
			<input type="hidden" name="oldlot" id="oldlot" value="{{request('oldlot')}}"   />
				<input type="hidden" name="order" id="hidden_order" value="{{request('order')}}"   />
				<input type="hidden" name="total" id="hidden_total" value="{{request('total')}}"   />




				<div class="filter-section-head">
					<h4>{{ trans(\Config::get('app.theme').'-app.lot_list.search_placeholder') }}</h4>
				</div>
				<div class="filters-padding">
					<div class="input-search-text">

						<div class="input-group ">
							<input type="text" class="form-control text search-text"  placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.search_placeholder') }}" name="description" id="description" value="{{ request('description') }}">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-filter btn-color" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
							</span>
						</div>

						<div class="input-group mt-1">
							<input type="number" class="form-control text search-text without-arrow" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.go_to_lot_placeholder') }}" name="reference" id="reference"  value="{{ request('reference') }}" autocomplete="off">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-filter btn-color" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
							</span>
						</div>

					</div>

				</div>

				@if(Session::has('user'))
					@include('includes.grid.filter_my_lots')
			   	@endif

				@include('includes.grid.categories_list')

				@include('includes.grid.features_list')

				@if(!empty($auction) && strtotime($auction->session_start) < time())
					@include('includes.grid.filter_sold')
				@endif

			</form>
		</div>

	</div>
@if(!empty($auction))
	<div class="filters filters-padding filters-info-auciton hidden-md hidden-lg">
		<div class="filter-buttons-info">

			<a href="{{ Tools::url_info_auction($auction->cod_sub, $auction->name) }}" class="btn btn-color" type="submit">{{ trans("$theme-app.lot_list.info_sub") }}</a>

			@if($auction->tipo_sub == 'W' && strtotime($auction->session_end) > time() && !$subasta_finalizada)
				<a href="{{ Tools::url_real_time_auction($auction->cod_sub, $auction->name, $auction->id_auc_sessions) }}" class="btn btn-live">{{ trans(\Config::get('app.theme').'-app.lot.bid_live') }}</a>
			@endif

		</div>
	</div>
@endif

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
</script>







