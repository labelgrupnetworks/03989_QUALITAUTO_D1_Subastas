@php
use Carbon\Carbon;


   $completeLocale = Tools::getLanguageComplete(\Config::get('app.locale'));
   $localeToTime = str_replace('-', '_', $completeLocale);
   $dateFormat = $localeToTime === 'es_ES' ? 'D  MMMM YYYY - HH:mm [h]' : 'MMMM Do YYYY - HH:mm [h]';
   $dateFormat_foot = $localeToTime === 'es_ES' ? 'D  MMMM YYYY'  : 'MMMM Do YYYY';
@endphp

	@if(!empty($auction))
		@php


		$sql = 'SELECT ESTADO, "reference", "start", "end", "name", "id_auc_sessions" FROM "auc_sessions" left join WEB_SUBASTAS  on ID_EMP="company" and ID_SUB="auction" and SESSION_REFERENCE="reference" WHERE "company" = :emp and "auction" = :cod_sub order by "reference"';
        $bindings = array(
                    'emp'           => Config::get('app.emp'),
                    'cod_sub'       => $auction->cod_sub
                    );

        $sessiones = DB::select($sql, $bindings);


		$subasta_finalizada = false;
			$pos = strpos($auction->name, '-');
			if ($pos !== false) {
				$auction->name = substr_replace($auction->name, '<br>', $pos, strlen('-'));
			}

	@endphp


<div class="filters" >
		<div class="hidden-md hidden-lg " style="margin-top: 10px"></div>
		<div class="filter-section-head filter-name-auction ">
			<h4 class="text-center">{!! $auction->name !!}</h4>
		</div>

			<div >


				@foreach($sessiones as $session)
					@php
						$estadoSesiones[$session->reference] =$session->estado;

						$fecha = Carbon::parse($session->start);

					@endphp
					<div class=" sessionLeft ">
						@if(count($sessiones)>1)
							{{trans($theme.'-app.lot_list.sesion')}} {{abs($session->reference)}} |
						@endif
							{{ ucwords($fecha->locale($localeToTime)->isoFormat($dateFormat)) }}

					</div>

					<div class=" text-center timeLeftOnLeft online-time">

						<span>

							@if( $session->estado != "ended")
								<span data-countdown="{{ strtotime($session->start) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($session->start); ?>" data-closed="{{ 0 }}" class="timer"></span>
								<span class="clock "></span>
							@else
								<span>	{{trans($theme.'-app.subastas.finalized')}}</span>
							@endif


						</span>
					</div>
				@endforeach

			</div>
</div>

<div class="lot-count hidden-md hidden-lg">
	<div  class="text-center timeLeftOnLeft online-time online-time-foot">
		<span class=" hidden-md hidden-lg"> {{ explode('-', $auction->des_sub)[1] ?? $auction->des_sub }} </span>
		<span class="hidden-md hidden-lg"> | </span>
		<span>

			@if(count($sessiones) == 1)
							@if( $session->estado != "ended")
								<span  class="hidden-md hidden-lg" data-countdown="{{ strtotime($session->start) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($session->start); ?>" data-closed="{{ 0 }}" class="timer"></span>
								<span class="clock hidden-md hidden-lg"></span>
							@else
								<span  class="hidden-md hidden-lg">	{{trans($theme.'-app.subastas.finalized')}}</span>
							@endif


			@else
				@foreach($sessiones as $session)
					@php

						$fecha = Carbon::parse($session->start);
						#cojemos el día de la primera sesion para mostrarlo despues concatenado con al fecha de la segunda sesión
						if(empty($primerDia)){
							$primerDia= $fecha->isoFormat("D");
						}
					@endphp

				@endforeach
				<span class="hidden-md hidden-lg">{{$primerDia}}-{{ ucwords($fecha->locale($localeToTime)->isoFormat($dateFormat_foot)) }}</span></br>
			@endif
		</span>
	</div>
</div>


		<div class="filters filters-padding filters-info-auciton ">
			<div class="filter-buttons-info">

				<a href="{{  $auction->descdet_sub }}" class="btn btn-color" type="submit">{{ trans("$theme-app.lot_list.info_sub") }}</a>

				@foreach($sessiones as $session)
					@if($auction->tipo_sub == 'W' && strtotime($session->end) > time() && $session->estado != "ended")
						<a href="{{ Tools::url_real_time_auction($auction->cod_sub, $session->name, $session->id_auc_sessions) }}" class="btn btn-live">{{ trans($theme.'-app.lot.bid_live') }}</a>
						@break
					@endif
				@endforeach
			</div>
		</div>
	@endif

	<div class="filters">


	<div class="filter-section-head filter-order-auction">
		<h4>{{ trans($theme.'-app.lot_list.order') }}</h4>
	</div>

	<div class="filters-padding">
		<select id="order_selected" name="order" class="form-control submit_on_change">
			<!-- Eloy: Desactivado por pettición del cliente 16/09/2019
			<option value="name" @if (app('request')->input('order') == 'name') selected @endif >
				{{ trans($theme.'-app.lot_list.order') }}:   {{ trans($theme.'-app.lot_list.name') }}
		</option>
			-->
		<option value="price_asc" @if (app('request')->input('order') == 'price_asc') selected @endif >
				{{ trans($theme.'-app.lot_list.order') }}:    {{ trans($theme.'-app.lot_list.price_asc') }}
		</option>
		<option value="price_desc" @if (app('request')->input('order') == 'price_desc') selected @endif >
			{{ trans($theme.'-app.lot_list.order') }}:      {{ trans($theme.'-app.lot_list.price_desc') }}
		</option>
		<option value="ref" @if (empty(app('request')->input('order')) || app('request')->input('order') == 'ref') selected @endif >
				{{ trans($theme.'-app.lot_list.order') }}:     {{ trans($theme.'-app.lot_list.reference') }}
		</option>
		<?php /*
			<option value="ffin" @if (app('request')->input('order') == 'ffin') selected @endif >
					{{ trans($theme.'-app.lot_list.order') }}:   <b>   {{ trans($theme.'-app.lot_list.more_near') }} </b>
			</option>
			*/ ?>
			<option value="mbids" @if (app('request')->input('order') == 'mbids') selected @endif >
					{{ trans($theme.'-app.lot_list.order') }}:   <b>   {{ trans($theme.'-app.lot_list.more_bids') }} </b>
			</option>

			<option value="hbids" @if (app('request')->input('order') == 'hbids') selected @endif >
					{{ trans($theme.'-app.lot_list.order') }}:   <b>   {{ trans($theme.'-app.lot_list.higher_bids') }} </b>
			</option>


			<option value="lastbids" @if (app('request')->input('order') == 'lastbids') selected @endif >
					{{ trans($theme.'-app.lot_list.order') }}:    {{ trans($theme.'-app.lot_list.last_bids') }}
			</option>
			<option value="media" @if (app('request')->input('order') == 'media') selected @endif >
					{{ trans($theme.'-app.lot_list.order') }}:    {{ trans($theme.'-app.lot_list.lots_360') }}
			</option>

		<?php //si son subastas presenciales y ya ha empezado que permita filtrar por lotes?>
		@if(!empty($data['sub_data']) && $data['sub_data']->tipo_sub == 'W' && ($data['sub_data']->subc_sub == 'S' ||   $data['sub_data']->subc_sub == 'A') && strtotime($data['sub_data']->start) < strtotime("now") )
			<option value="fbuy" @if (empty(app('request')->input('order')) || app('request')->input('order') == 'fbuy') selected @endif >
					{{ trans($theme.'-app.lot_list.order') }}:   <b>   {{ trans($theme.'-app.lot_list.filter_by_buy') }} </b>
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
					<h4>{{ trans($theme.'-app.lot_list.search_placeholder') }}</h4>
				</div>
				<div class="filters-padding">
					<div class="input-search-text">

						<div class="input-group ">
							<input type="text" class="form-control text search-text"  placeholder="{{ trans($theme.'-app.lot_list.search_placeholder') }}" name="description" id="description" value="{{ request('description') }}">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-filter btn-color" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
							</span>
						</div>

						<div class="input-group mt-1">
							<input type="number" class="form-control text search-text without-arrow" placeholder="{{ trans($theme.'-app.lot_list.go_to_lot_placeholder') }}" name="reference" id="reference"  value="{{ request('reference') }}" autocomplete="off">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-filter btn-color" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
							</span>
						</div>

					</div>

				</div>

				@if(Session::has('user'))
					@include('includes.grid.filter_my_lots')
			   	@endif

				@if(!empty($auction) && strtotime($auction->session_start) < time())
				   @include('includes.grid.filter_sold')
			   	@endif

				@include('includes.grid.session_list')

				@include('includes.grid.features_list')



			</form>
		</div>

	</div>


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







