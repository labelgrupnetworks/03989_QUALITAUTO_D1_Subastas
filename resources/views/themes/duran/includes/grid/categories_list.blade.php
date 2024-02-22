<span class="titulo-filtro">{{ trans($theme.'-app.lot_list.categories') }}</span>
	<div class="filtros-caja">

		<div class="input-radio-div d-flex- align-items-center hidden">
			<input type="radio" name="category" id="all_categories" value="" <?=  empty($filters["category"])? 'checked="checked"' : '' ?>  />
		</div>

	@foreach($categories as $category)
		<?php
		$linOrtsec0 = $category["lin_ortsec0"];
		$numCategoryLots = Tools::showNumLots($numActiveFilters, $filters, "category", $linOrtsec0);
			# si una categoria no tiene lotes no puede estar marcada ni buscar las subcategorias  ?>
		@if( $numCategoryLots > 0)

			<div class="input-radio-div d-flex- align-items-center">
				<div class="radio">
					@php
						# El funcionamiento debe ser:
						#si pulsna en una categoria se debe desplegar sus secciones a no ser que ya estenm desplegadas
						#si ya hay una seleccionada se  debe limpiar la seccion seleccionada
						# filter_lot_list_js despliega secciones y desmarca la seccion si hay alguna seleccionada
						# del_filter_category_js desmarca categorias y secciones

					@endphp
					<input class="radio-filtro <?= ($linOrtsec0 != $filters["category"] || !empty($filters["section"] ) )?  'filter_lot_list_js' : '' ?> " type="radio" name="category" id="category_{{$linOrtsec0}}" value="{{$linOrtsec0}}"  <?= ($linOrtsec0 == $filters["category"])?  'checked="checked"' : '' ?>>
						<label class="radio-label-filtro <?= ($linOrtsec0 == $filters["category"] && empty($filters["section"] ) )?  'del_filter_category_js' : '' ?>" for="category_{{$linOrtsec0}}">{{$category["des_ortsec0"]}} ({{Tools::numberformat($numCategoryLots)}})

						</label>
				</div>

				@if($linOrtsec0 == $filters["category"])
					@include('includes.grid.sections_list')
				@endif

			</div>
		@else
			<input class="radio-filtro hidden <?= ($linOrtsec0 != $filters["category"] || !empty($filters["section"] ) )?  'filter_lot_list_js' : '' ?> " type="radio" name="category" id="category_{{$linOrtsec0}}" value="{{$linOrtsec0}}"  <?= ($linOrtsec0 == $filters["category"])?  'checked="checked"' : '' ?>>

		@endif


	@endforeach
	</div>
