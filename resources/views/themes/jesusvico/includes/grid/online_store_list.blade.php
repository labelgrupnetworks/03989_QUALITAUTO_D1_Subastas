@php
	$tienda_numis = "libreria_Tienda1-001?category=1";
	$tienda_arqueo = "001_Tienda2-001";
	$tienda_libreria = "libreria_Tienda1-001?category=3";
	$tienda_material = "libreria_Tienda1-001?category=4";

	$url = url('');
	$actualURLSplitted = explode('/', url()->full());
	$auc_url = end($actualURLSplitted);
@endphp


<div class="auction__filters-online-stores">

	<div class="auction__filters-collapse filter-parent-collapse with-caret text-center bg-lb-primary-150"
		data-bs-toggle="collapse" href="#online-stores" role="button" aria-expanded="true" aria-controls="online-stores">

		<div class="filter-title">{{ trans("$theme-app.foot.direct_sale") }}</div>
	</div>

	<div class="auction__filters-type-list show filter-child-collapse p-0" id="online-stores">

		<div class="input-category bg-lb-primary-50">
			<div>
				@php
					/* Numismática */
					$url_numis = $url.trans("$theme-app.links.tienda-numismatica");
				@endphp
				<input type="radio" class="form-check-input" id="tienda_numis" name="tienda_online" value="{{ $tienda_numis }}"
					@checked($auc_url == $tienda_numis) onclick="redirectURL('{{ $url_numis }}')" />
				<label for="tienda_numis">{{ trans("$theme-app.subastas.tienda-numismatica") }}</label>
			</div>
			<div>
				@php
					/* Arqueología */
					$url_arqueo = $url.trans("$theme-app.links.tienda-arqueologia");
				@endphp
				<input type="radio" class="form-check-input" id="tienda_arqueo" name="tienda_online" value="{{ $tienda_arqueo }}"
					@checked($auc_url == $tienda_arqueo) onclick="redirectURL('{{ $url_arqueo }}')" />
				<label for="tienda_arqueo">{{ trans("$theme-app.subastas.tienda-arqueologia") }}</label>
			</div>
			<div>
				@php
					/* Librería */
					$url_libreria = $url.trans("$theme-app.links.tienda-libreria");
				@endphp
				<input type="radio" class="form-check-input" id="tienda_libreria" name="tienda_online" value="{{ $tienda_libreria }}"
					@checked($auc_url == $tienda_libreria) onclick="redirectURL('{{ $url_libreria }}')" />
				<label for="tienda_libreria">{{ trans("$theme-app.subastas.tienda-libreria") }}</label>
			</div>
			<div>
				@php
					/* Material */
					$url_material = $url.trans("$theme-app.links.tienda-material");
				@endphp
				<input type="radio" class="form-check-input" id="tienda_material" name="tienda_online" value="{{ $tienda_material }}"
					@checked($auc_url == $tienda_material) onclick="redirectURL('{{ $url_material }}')" />
				<label for="tienda_material">{{ trans("$theme-app.subastas.tienda-material") }}</label>
			</div>

			{{-- <div class="row">
				<div class="col-12 col-sm-6">
					<a class="page-link d-flex align-items-center justify-content-center text-center mt-2 mb-1"
						href="{{ trans("$theme-app.links.tienda-numismatica") }}">{{ trans("$theme-app.subastas.tienda-numismatica") }}</a>
				</div>
				<div class="col-12 col-sm-6">
					<a class="page-link d-flex align-items-center justify-content-center text-center mt-2 mb-1"
						href="{{ trans("$theme-app.links.tienda-arqueologia") }}">{{ trans("$theme-app.subastas.tienda-arqueologia") }}</a>
				</div>
			</div>
			<div class="row">
				<div class="col-12 col-sm-6">
					<a class="page-link d-flex align-items-center justify-content-center text-center mt-1 mb-2"
						href="{{ trans("$theme-app.links.tienda-libreria") }}">{{ trans("$theme-app.subastas.tienda-libreria") }}</a>
				</div>
				<div class="col-12 col-sm-6">
					<a class="page-link d-flex align-items-center justify-content-center text-center mt-1 mb-2"
						href="{{ trans("$theme-app.links.tienda-material") }}">{{ trans("$theme-app.subastas.tienda-material") }}</a>
				</div>
			</div> --}}
		</div>


	</div>
</div>


<script>
	function redirectURL(url) {
		/* Redirigir a la página con la siguiente URL */
		location.href = url;
	}
</script>
