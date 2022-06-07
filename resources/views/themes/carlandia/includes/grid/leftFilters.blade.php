<form id="form_lotlist" class="color-text" method="get" action="{{ $url }}">
	{{-- oldpage es la página en la que estabamos antes de ir a la ficha, al volver debemos ir a ella --}}

	@include('includes.grid.extraFilters')

<div class="filters-auction-content uppercase-text">

	<div class="filters-auction-title d-flex align-items-center justify-content-space-between" role="button" aria-expanded="true"
		aria-controls="collapse_filter" data-toggle="collapse" data-target="#collapse_filter">
		<span>{{ trans(\Config::get('app.theme').'-app.lot_list.filters') }}</span>
		<span id="js-collapse_simbol" style="float: right"><i class="fa fa-plus" aria-hidden="true" style="font-size: 16px"></i></span>
	</div>

	<div class="form-group collapse-js collapse" id="collapse_filter" aria-expanded="true">

		<input type="hidden" name="oldpage" id="oldpage" value="{{request('oldpage')}}"   />
		<input type="hidden" name="oldlot" id="oldlot" value="{{request('oldlot')}}"   />
		{{-- <input type="hidden" name="order" id="hidden_order" value="{{request('order')}}"   /> --}}

		<input type="hidden" name="historic" id="hidden_historic" value="{{request('historic')}}"   />

			{{-- <div class="filters-auction-texts">
				<label class="filters-auction-label" for="description"><span>{{ trans(\Config::get('app.theme').'-app.lot_list.search') }}</span></label>
				<input id="description" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.search_placeholder') }}" name="description" type="text" class="form-control input-sm filter-auction-input search-input_js" value="{{ app('request')->input('description') }}">
				<div class="filters-auction-divider-medium"></div>
				@if(!empty($codSub) && !empty($refSession))
					<label class="filters-auction-label" for="reference">{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}</label>
					<input id="reference" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}" name="reference" type="text" class="form-control input-sm filter-auction-input search-input_js" value="{{ app('request')->input('reference') }}">

				@endif


			<div class="filters-auction-divider-medium"></div>
			<button class="btn btn-filter color-letter" type="submit">{{ trans(\Config::get('app.theme').'-app.lot_list.filter') }}</button>

			</div> --}}

			@if(!empty($auction))
				@if (strtotime($auction->session_start) < time() && ($auction->tipo_sub=='W'))
					@include('includes.grid.filter_sold')
				@endif
			@else
				{{-- @include('includes.grid.typeAuction_list') --}}
            @endif
			<div class="filters-auction-divider-medium"></div>

			@php
				$rangesIds = array_map('trim', explode(',',config('app.typeSelectorRange', '')));
				$radioIds = array_map('trim', explode(',',config('app.typeSelectorRadio', '')));

				$minMaxRanges = \App\Models\V5\FgCaracteristicas_Value::selectRaw('max(cast(value_caracteristicas_value as int)) as max, min(cast(value_caracteristicas_value as int)) as min')
						->addSelect('idcar_caracteristicas_value')
						->whereRaw("TRANSLATE(value_caracteristicas_value, 'T 0123456789', 'T') IS NULL")
						->whereIn('idcar_caracteristicas_value', $rangesIds)
						->groupBy('idcar_caracteristicas_value')->get()->keyBy('idcar_caracteristicas_value');

				$minMaxPrices = \App\Models\V5\FgAsigl0::joinFghces1Asigl0()->selectRaw('min(cast(fgasigl0.impsalhces_asigl0 as int)) as min, max(cast(greatest(fgasigl0.impsalhces_asigl0, fghces1.implic_hces1)as int)) as max')->first();

				//id's de collapses abiertos por defecto (estado, carroceria)
				$collapseOpenIds = [];
				$seeBeforeCategories = [54, 21,13,35];
			@endphp



@php
	#ordenacion estado
if(!empty($featuresCount[21]) && count($featuresCount[21]) > 0){


	$ordenEstado=["nuevo"=>0,"km 0"=>1,"seminuevo"=>2,"ocasion"=>3, "ocasión" => 3];
	$estados = $featuresCount[21] ?? null;
	if($estados){
		$featuresCount[21] = array();
		#usare el array orden estado para darle nuevas posiciones
		foreach($estados as $estado){
			$nuevoOrden = $ordenEstado[$estado["value_caracteristicas_value"]];
			if($estado['value_caracteristicas_value'] == 'ocasion'){
				$estado['value_caracteristicas_value'] = 'ocasión';
			}
			$featuresCount[21][$nuevoOrden] = $estado;
		}
		#ordenamos en base a los indices
		ksort($featuresCount[21]);
	}
	#ordenamos en base a los indices
	ksort($featuresCount[21]);
}

@endphp

			@include('includes.grid.features_list_top')
			{{-- <div class="filters-auction-divider-medium"></div> --}}
			@include('includes.grid.categories_list')
			<div class="filters-auction-divider-medium"></div>
			@include('includes.grid.features_list')



	</div>

</div>

</form>


<script>

	/* if (screen.width>768) {
        $("#estado_lotes").addClass("in");
        $("#auction_type").addClass("in");
		@foreach($features as $idFeature => $feature)
			@if(!empty($featuresCount[$idFeature]))
				$("#feature_{{$idFeature}}").addClass("in");
			@endif
		@endforeach
    } */
</script>







