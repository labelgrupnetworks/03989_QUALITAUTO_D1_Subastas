<div class="d-flex align-items-end flex-wrap gap-1 border-bottom pb-1">

	<button id="js-show-filters" class="btn btn-sm btn-outline-border-lb-primary d-flex align-items-center d-none" onclick="showFilters(event)" alt="mostrar filtros">
		<svg class="bi" width="16" height="16" fill="currentColor">
			<use xlink:href="/bootstrap-icons.svg#arrow-bar-right"/>
		</svg>
	</button>

	<p class="cantidad-res me-auto">{{ Tools::numberformat($count_lots) }} {{ trans($theme.'-app.lot_list.results') }}</p>

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
				{!! trans($theme.'-app.lot_list.see_historic_lots') !!}
			</span>

				@if(request('historic'))
					<span id="seeActiveLots_JS" class="gridFilterHistoric">
						{{ trans($theme.'-app.lot_list.return_active_lots') }}
					</span>
						{{-- solo haremos la llamada si estamos en categorias y han buscado texto   && !empty(request('description')--}}
				@elseif(empty($auction))
					<script>$(function() { showHistoricLink(); })</script>
				@endif
		@endif

	{{-- FIN FILTRO DE SUBASTAS HISTÓRICAS --}}
</div>
