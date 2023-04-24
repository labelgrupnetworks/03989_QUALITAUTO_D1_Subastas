@if (config('app.countdown_ingrid', 0) && !empty($auction))
	<div class="filters-auction-content mb-1">
		<b>
			<p data-countdown="{{ strtotime($auction->session_start) - getdate()[0] }}"
				data-format="<?= \Tools::down_timer($auction->session_start) ?>" data-closed="{{ 0 }}" class="timer mt-1">
			</p>
		</b>
		<p>{{ \Tools::getDateFormat($auction->session_start, 'Y-m-d H:i:s', 'd/m/Y H:i') }}
			{{ trans(\Config::get('app.theme') . '-app.lot_list.time_zone') }}</p>
	</div>
@endif

<div class="filters-auction-content">

	@php
		/* Sección de archivo de sesión */
	@endphp
	@php
		if ( !empty($auction)){
			$sessionFiles = new App\Models\V5\AucSessionsFiles();
			$fileSession = $sessionFiles
				->where('"auction"', $auction->cod_sub)
				->where('"reference"', $auction->reference)
				->first();
		}
	@endphp
	@if (!empty($fileSession))
		<div class="row">
			<div class="col-xs-12">
				<div class="filters-auction-title text-center mb-1">
					<span>{{ trans("$theme-app.lot_list.catalog") }}</span>
				</div>
				<a class="descargar descargaPDF_JS" href="/files{{ $fileSession->path }}" target="_blank">
					<img class="img-responsive img-catalog-lot-list" src="{{ $fileSession->img }}" alt="{{ $fileSession->description }}">
				</a>
			</div>
		</div>
	@endif
	@php
		/* Fin sección de archivo de sesión */
	@endphp


	<div class="form-group">
		<form id="form_lotlist" class="color-text" method="get" action="{{ $url }}">
			{{-- oldpage es la página en la que estabamos antes de ir a la ficha, al volver debemos ir a ella --}}
			<input type="hidden" name="oldpage" id="oldpage" value="{{ request('oldpage') }}" />
			<input type="hidden" name="oldlot" id="oldlot" value="{{ request('oldlot') }}" />
			<input type="hidden" name="order" id="hidden_order" value="{{ request('order') }}" />
			<input type="hidden" name="total" id="hidden_total" value="{{ request('total') }}" />
			<input type="hidden" name="historic" id="hidden_historic" value="{{ request('historic') }}" />

			<div class="filters-auction-title d-flex align-items-center justify-content-space-between">
				<span>{{ trans(\Config::get('app.theme') . '-app.lot_list.filters') }}</span>
			</div>
			<div class="filters-auction-texts">
				<label class="filters-auction-label"
					for="description"><span>{{ trans(\Config::get('app.theme') . '-app.lot_list.search') }}</span></label>
				<input id="description" placeholder="{{ trans(\Config::get('app.theme') . '-app.lot_list.search_placeholder') }}"
					name="description" type="text" class="form-control input-sm filter-auction-input search-input_js"
					value="{{ app('request')->input('description') }}">
				<div class="filters-auction-divider-medium"></div>
				@if (!empty($codSub) && !empty($refSession))
					<label class="filters-auction-label"
						for="reference">{{ trans(\Config::get('app.theme') . '-app.lot_list.reference') }}</label>
					<input id="reference" placeholder="{{ trans(\Config::get('app.theme') . '-app.lot_list.reference') }}"
						name="reference" type="text" class="form-control input-sm filter-auction-input search-input_js"
						value="{{ app('request')->input('reference') }}">
				@endif


				<div class="filters-auction-divider-medium"></div>
				<button class="btn btn-filter color-letter"
					type="submit">{{ trans(\Config::get('app.theme') . '-app.lot_list.filter') }}</button>

			</div>


			<div class="filters-auction-divider-medium"></div>
			@include('includes.grid.categories_list')
			<div class="filters-auction-divider-medium"></div>
			@include('includes.grid.features_list')

			@if (!empty($auction))
				@if ( (strtotime($auction->session_start) < time() && $auction->tipo_sub == 'W') || (strtotime($auction->session_end) < time() && $auction->tipo_sub == 'O') )
					@include('includes.grid.filter_sold')
				@endif
			@else
				@include('includes.grid.typeAuction_list')
			@endif
		</form>
	</div>

</div>


<script>
	if (screen.width > 768) {
		$("#estado_lotes").addClass("in");
		$("#auction_type").addClass("in");
		$("#auction_categories").addClass("in");
		@foreach ($features as $idFeature => $feature)
			@if (!empty($featuresCount[$idFeature]))
				$("#feature_{{ $idFeature }}").addClass("in");
			@endif
		@endforeach
	}
</script>
