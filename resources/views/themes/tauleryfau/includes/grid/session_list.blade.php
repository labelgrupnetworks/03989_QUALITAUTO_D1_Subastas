
<div class="auction__filters-categories">

	<div class="filter-section-head">
		<h4>{{ trans($theme.'-app.lot_list.categories') }}</h4>
	</div>
	<div class="filters-padding">
		<div class="auction__filters-type-list mt-1  " id="auction_sessions" >



			@php
				# en sesiones no tenemos que tener en cuenta lso filtros de seccion y subseccion
				$filtersForSession = $filters;
				$filtersForSession["section"]="";
				$filtersForSession["subsection"]="";
				if(!empty($codSub)){
					$sesiones = App\Models\V5\AucSessions::WhereAuction($codSub)->get()	;
				}

				$totalLotes=0;
				foreach($sesiones as $ses){
					#ponemos un array vacio en filtros para que sque todos los valores
					$numSessionLots[$ses->reference] = Tools::showNumLots($numActiveFilters, $filtersForSession, "session", $ses->reference);
					$totalLotes+=$numSessionLots[$ses->reference];
				}

			@endphp
			<div class="input-category d-flex align-items-center">
				<div class="radio">
					<input type="radio" name="filter_session" id="all_sessions" value="" class="filter_lot_list_js" <?=   empty(request("filter_session"))? 'checked="checked"' : '' ?>  />
					<label for="all_sesions" class="ratio-label">
						{{trans($theme.'-app.lot_list.all_categories')}} ({{$totalLotes}})
					</label>
				</div>
			</div>

			@foreach($sesiones as $ses)
			<div class="input-category d-flex align-items-center">
				<div class="radio">
					<input type="radio" name="filter_session" id="sesion_{{$ses->reference}}" value="{{$ses->reference}}" class="filter_lot_list_js" <?= ($ses->reference == request("filter_session"))?  'checked="checked"' : '' ?>  />
					<label for="sesion_{{$ses->reference}}" class="ratio-label">
						{{trans($theme.'-app.lot_list.sesion')}} {{abs($ses->reference)}}  ({{$numSessionLots[$ses->reference] }})
					</label>
				</div>
			</div>
			{{-- cargamos directamente secciones en vez de categorias por que solo hay 1 categoria --}}
				@include('includes.grid.categories_list')
			@endforeach
		</div>
	</div>
</div>
