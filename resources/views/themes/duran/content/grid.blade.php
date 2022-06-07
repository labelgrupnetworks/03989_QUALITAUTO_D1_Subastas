

	<div class="container">
		<div class="row equal">


			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 d-flex flex-column px-xs-0 filter-col">

				<div class="hidden-md hidden-lg  " id="moreInfoMD_XS">
					@include('includes.grid.info_auction')
					<script>ReadMore("moreInfoMD_XS",1);</script>
				</div>

				@include('includes.grid.leftFilters')
			</div>
			<div class="col-xs-12 col-md-9 col-lg-10 list_lot_content">

				<div class="hidden-xs hidden-sm mb-2" id="moreInfoMD_JS">
					@include('includes.grid.info_auction')
					<script>ReadMore("moreInfoMD_JS",1);</script>
				</div>


				@include('includes.grid.topFilters')
				@if(\Config::get("app.paginacion_grid_lotes"))

					<div class="col-xs-12 p-0">
						@include("includes.grid.lots")
					</div>

				@else
					<div class="list_lot"  id="lotsGrid">
					<?php
								#AQUI VAN LOS LOTES
					?>

					</div>

					{{-- Código scroll infinito --}}

					<div class="clearfix"></div>
					<div id="endLotList" ></div>
					<div id="loading" class=" text-center">
							<img src="/default/img/loading.gif" alt="Loading…" />

					</div>
					<form id="infiniteScrollForm" autocomplete="off">
						{{ csrf_field() }}
						@foreach($filters as $nameFilter => $valueFilter)
							@if (is_array($valueFilter))

								@foreach($valueFilter as $kFilter => $vFilter)
									<input type="hidden" name="{{$nameFilter}}[{{$kFilter}}]" value="{{$vFilter}}">
								@endforeach
							@else

								<input type="hidden" name="{{$nameFilter}}" value="{{$valueFilter}}">
							@endif
						@endforeach
						<input type="hidden" id="actualPage" name="actualPage" value="1">
						<input type="hidden"  name="historic" value="{{request("historic")}}">
						<input type="hidden"  name="codSub" value="{{$codSub}}">
						<input type="hidden"  name="refSession" value="{{$refSession}}">

						{{-- Página que buscamos en este momento--}}
						<input type="hidden" id="searchingPage"  value="0">
						<input type="hidden" id="lastLot"  value="false">
					</form>
					{{-- Fin código de scroll infinito --}}
				@endif
			</div>

		</div>
	</div>

	<div class="container category mt-1 mb-1">

		<div class="row">
			<div class="col-lg-12">
			@if(!empty($data['seo']->meta_content) )

				<?= $data['seo']->meta_content?>


			@endif
			</div>
		</div>
	</div>


<script>
var url_lots ="{{ route("getAjaxLots", ["lang" => \Config::get("app.locale")]) }}";

</script>

@if( empty(\Config::get("app.paginacion_grid_lotes")))
	<script src="{{ Tools::urlAssetsCache("/js/default/grid_scroll.js") }}"></script>
@endif
<script src="{{ Tools::urlAssetsCache("/js/default/grid_filters.js") }}"></script>




