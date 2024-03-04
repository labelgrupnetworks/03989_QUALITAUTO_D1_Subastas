@php
	$previuos = $data['previous'];
	$next = $data['next'];

	$searchUrl = Tools::url_lot_to_js(
	    $lote_actual->cod_sub,
	    $lote_actual->id_auc_sessions,
	    $lote_actual->ref_asigl0,
	    $lote_actual->num_hces1,
	);
@endphp

<div class="d-flex align-content-center" style="height: 40px;">
	@if ($previuos)
		<div class="btn-group me-auto">
			<a class="btn btn-lb-primary d-flex align-items-center" href="{{ $previuos }}">
				@include('components.boostrap_icon', ['icon' => 'chevron-left'])
			</a>
			<a class="btn btn-light" href="{{ $previuos }}">{{ trans("$theme-app.subastas.last") }}</a>
		</div>
	@endif

	<div class="btn-group search-lot-container d-none d-md-block">
		<form id="searchLot" class="form-single-lot samsung-visual" method="get" action="{{ $searchUrl }}">

			<div class="input-group">
				<input class="form-control control-number search-lot-bar-input" type="number" name="reference">
				<button id="single-lot" class="btn btn-lb-primary search-lot-bar-button" type="submit">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search"
						viewBox="0 0 20 20">
						<path
							d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
					</svg>
				</button>

			</div>


		</form>
	</div>

	@if ($next)
		<div class="btn-group ms-auto">
			<a class="btn btn-light" href="{{ $next }}">{{ trans("$theme-app.subastas.next") }}</a>
			<a class="btn btn-lb-primary d-flex align-items-center" href="{{ $next }}">
				@include('components.boostrap_icon', ['icon' => 'chevron-right'])
			</a>
		</div>
	@endif
</div>
