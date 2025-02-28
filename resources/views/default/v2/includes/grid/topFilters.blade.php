@php
$count_lots = 0;
foreach($tipos_sub as $typeSub => $desType) {
	$numLots = Tools::showNumLots($numActiveFilters, $filters, "typeSub", $typeSub);

	if(empty($filters['typeSub'])){
		$count_lots += $numLots;
	}elseif($typeSub == $filters['typeSub']){
		$count_lots = $numLots;
	}
}
@endphp

<div class="d-flex align-items-end flex-wrap gap-1 border-bottom pb-1">

	<button id="js-show-filters" class="btn btn-sm btn-outline-border-lb-primary d-flex align-items-center d-none" onclick="showFilters(event)" alt="mostrar filtros">
		<svg class="bi" width="16" height="16" fill="currentColor">
			<use xlink:href="/bootstrap-icons.svg#arrow-bar-right"/>
		</svg>
	</button>

	<p class="cantidad-res me-auto">{{ Tools::numberformat($count_lots) }} {{ trans('web.lot_list.results') }}</p>

	<button class="btn btn-sm btn-outline-border-lb-primary d-none d-sm-flex align-items-center align-self-stretch" data-grid="grid" onclick="changeGrid(event)">
		<svg class="bi" width="16" height="16" fill="currentColor">
			<use xlink:href="/bootstrap-icons.svg#grid-3x3-gap"/>
		</svg>
	</button>

	<button class="btn btn-sm btn-outline-border-lb-primary d-none d-sm-flex align-items-center align-self-stretch" data-grid="large" onclick="changeGrid(event)">
		<svg class="bi" width="16" height="16" fill="currentColor">
			<use xlink:href="/bootstrap-icons.svg#list"/>
		</svg>
	</button>

	<div>
		<select class="form-select form-select-sm" id="total_selected" >
			@foreach(\Config::get("app.filter_total_shown_options") as $numLots)
				<option value="{{$numLots}}" @if (request('total') == $numLots) selected @endif >    {{ trans('web.lot_list.see_num_lots',["num" => $numLots]) }}   </option>
			@endforeach
		</select>
	</div>

	<div>
		<select class="form-select form-select-sm" id="order_selected" >
			<option value="name" @if ($filters["order"] == 'name') selected @endif >
				{{ trans('web.lot_list.order') }}:   {{ trans('web.lot_list.name') }}
			</option>
			<option value="price_asc" @if ($filters["order"] == 'price_asc') selected @endif >
				{{ trans('web.lot_list.order') }}:    {{ trans('web.lot_list.price_asc') }}
			</option>
			<option value="price_desc" @if ($filters["order"] == 'price_desc') selected @endif >
				{{ trans('web.lot_list.order') }}:      {{ trans('web.lot_list.price_desc') }}
			</option>
			<option value="ref" @if ($filters["order"] == 'ref' || empty($filters["order"]) ) selected @endif >
				{{ trans('web.lot_list.order') }}:     {{ trans('web.lot_list.reference') }}
			</option>

			<option value="date_asc" @if ($filters["order"] == 'date_asc') selected @endif >
				{{ trans('web.lot_list.order') }}:    {{ trans('web.lot_list.date_asc') }}
			</option>
			<option value="date_desc" @if ($filters["order"] == 'date_desc') selected @endif >
				{{ trans('web.lot_list.order') }}:      {{ trans('web.lot_list.date_desc') }}
			</option>
			<option value="hbids" @if ($filters["order"] == 'hbids') selected  @endif >
				{{ trans('web.lot_list.order') }}:     {{ trans('web.lot_list.higher_bids') }}
			</option>
			<option value="mbids" @if ($filters["order"] == 'mbids') selected  @endif >
				{{ trans('web.lot_list.order') }}:     {{ trans('web.lot_list.more_bids') }}
			</option>
			<option value="lastbids" @if ($filters["order"] == 'lastbids') selected  @endif >
				{{ trans('web.lot_list.order') }}:     {{ trans('web.lot_list.last_bids') }}
			</option>

			@if(!empty($auction) && $auction->tipo_sub == 'O')
				<option value="ffin" @if ($filters["order"] == 'ffin') selected @endif >
					{{ trans('web.lot_list.order') }}:   <b>   {{ trans('web.lot_list.more_near') }} </b>
				</option>
			@endif
		</select>
	</div>
</div>

<div class="col-xs-12 pt-1 d-flex align-items-center mt-1">

		{{-- FILTRO DE SUBASTAS HISTÓRICAS --}}
		@if(\Config::get("app.gridHistoricoVentas"))
			@php
			/**
			 * estará oculto a no ser que haya lotes en el historico
			 * @todo seeHistoricLots_JS modificar clases para d-none d-block
			 * */
			@endphp

			<span id="seeHistoricLots_JS" class="gridFilterHistoric d-none">
				{!! trans('web.lot_list.see_historic_lots') !!}
			</span>

				@if(request('historic'))
					<span id="seeActiveLots_JS" class="gridFilterHistoric">
						{{ trans('web.lot_list.return_active_lots') }}
					</span>
						{{-- solo haremos la llamada si estamos en categorias y han buscado texto   && !empty(request('description')--}}
				@elseif(empty($auction))
					<script>$(function() { showHistoricLink(); })</script>
				@endif
		@endif

	{{-- FIN FILTRO DE SUBASTAS HISTÓRICAS --}}
</div>
