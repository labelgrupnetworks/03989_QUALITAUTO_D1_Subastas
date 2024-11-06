@php
    use App\Models\Cookies;
    $styleLotSeeConfiguration = (new Cookies())->getLotConfiguration();
@endphp

<div class="info-auction-tab-contet">
    <div class="container">

		<div class="row gy-5">
			<div id="js-filters-col" class="col-lg-3">
				<aside class="section-grid-filters sticky-lg-top">
					@include('includes.grid.leftFilters')
				</aside>
			</div>

			<div id="js-lots-col" class="col-lg-9">

				<div class="section-grid-top-filters border-bottom mb-4">
					@include('includes.grid.topFilters')
				</div>

				@if(config("app.paginacion_grid_lotes"))

					<div class="section-grid-lots mb-2 {{$styleLotSeeConfiguration}}">
						@include("includes.grid.lots")
					</div>

					<div class="section-grid-pagination pagination-wrapper">
						{{ $paginator->links() }}
					</div>
				@else
					<div class="section-grid-lots {{$styleLotSeeConfiguration}}">
						<div class="section-grid-lots" id="lotsGrid"></div>
					</div>

					<div id="endLotList"></div>
					<div id="loading" class=" text-center">
						<img src="/default/img/loading.gif" alt="Loading…" />
					</div>
				@endif

				{{-- El formulari odebe estar fuera para que funcione el ver histórico--}}
				<form id="infiniteScrollForm" autocomplete="off">
					{{ csrf_field() }}
					@foreach($filters as $nameFilter => $valueFilter)
						@if (is_array($valueFilter))
							@foreach($valueFilter as $kFilter => $vFilter)
								@if(is_array($vFilter))
									@foreach ($vFilter as $valuesMultipleFilter)
										<input type="hidden" name="{{$nameFilter}}[{{$kFilter}}][]" value="{{$valuesMultipleFilter}}">
									@endforeach
								@else
									<input type="hidden" name="{{$nameFilter}}[{{$kFilter}}]" value="{{$vFilter}}">
								@endif
							@endforeach
						@else
							<input type="hidden" name="{{$nameFilter}}" value="{{$valueFilter}}">
						@endif
					@endforeach
					<input type="hidden" id="actualPage" name="actualPage" value="1">
					<input type="hidden"  name="codSub" value="{{$codSub}}">
					<input type="hidden"  name="refSession" value="{{$refSession}}">
					<input type="hidden"  name="historic" value="{{request("historic")}}">

					{{-- Página que buscamos en este momento--}}
					<input type="hidden" id="searchingPage"  value="0">
					<input type="hidden" id="lastLot"  value="false">
				</form>

			</div>
		</div>
    </div>
</div>

@if(!isset($auction) && request('page', 1) == 1)
<div class="home_text">
    <div class="container">
		{!! $seo_data->meta_content !!}
		{{-- Solo debe aparecer si hay categioria, en el moment oque ha seccion seleccionada no debe aparecer --}}
		@if(empty($filters["section"]))
			<div class="links-sections">
				@foreach($sections as $sec)
					<a class="mr-2" href="{{route('section',array( 'keycategory' => $infoOrtsec->key_ortsec0, 'keysection' => $sec['key_sec'] ?? ' '  ))}}">{{ucfirst($sec["des_sec"])}}</a>
				@endforeach
			</div>
		@endif
    </div>
</div>
@endif

<script>
	var url_lots = "{{ route('getAjaxLots', ['lang' => config('app.locale')]) }}";
</script>

@if(empty(\Config::get("app.paginacion_grid_lotes")))
	<script src="{{ Tools::urlAssetsCache("/js/default/grid_scroll.js") }}"></script>
@endif
<script src="{{ Tools::urlAssetsCache("/js/default/grid_filters.js") }}"></script>





