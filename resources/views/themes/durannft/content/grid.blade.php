@if(!empty($auction->cod_sub))
<div class="info-auction secondary-color" id="auction__lots">
	<div class="mt-1 mr-1">
		<div class="row">
			<div id="derechaACentro" class="col-xs-12 col-sm-5 text-right">
				<img  src="{{\Tools::url_img_auction('subasta_medium', $auction->cod_sub)}}">
			</div>
			<div class="col-xs-12 col-sm-7 col-md-4 text-justify mobilepl-3">
				<div id="js-read-desc" style="--max-line: 8;" class="desc-lot">
					<div>
						{!! $auction->descdet_sub !!}
					</div>
				</div>
				<p id="js-read-more" class="read-more">
					<small>Leer más</small>
				</p>
				<p id="js-read-less" class="hidden read-less">
					<small>Leer menos</small>
				</p>
			</div>
			<div class=".d-none .d-sm-block .d-sm-none .d-md-block col-xs-3"></div>

		</div>
	</div>
</div>
@endif

<div class="info-auction-tab-contet">
    <div class="container">
        <div class="row">

            <div class="col-xs-12">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="lots">

						<div class="filters-control-wrapper">
							<button id="filters-control" data-action="show">
								<i class="fa fa-filter"></i>
								<span id="filter-show" class="ml-1">{{ trans(\Config::get('app.theme') . '-app.lot_list.filters') }}</span>
							</button>
						</div>

						<div class="row">

							<div class="col-xs-12 col-md-3 auction-lots-view" style="display: none">
								@include('includes.grid.leftFilters')
							</div>
							<div id="aucion-lots-container" class="col-xs-12">
								<div class="row">
									@include('includes.grid.topFilters')
								</div>

								@if(\Config::get("app.paginacion_grid_lotes"))

										<div class="col-xs-12 col-sm-6 col-md-4">
											@include("includes.grid.lots")
										</div>

										<div class="col-xs-12 foot-pagination-grid">
											{{ $paginator->links() }}
										</div>

								@else
									<div class="row">
										<div class="col-xs-12">
											<div class="list_lot" id="lotsGrid"></div>
										</div>

										{{-- Código scroll infinito --}}
										<div id="endLotList" ></div>
										<div id="loading" class=" text-center">
												<img src="/default/img/loading.gif" alt="Loading…" />
										</div>
										{{-- Fin código de scroll infinito --}}
									</div>

								@endif

								{{-- El formulari odebe estar fuera para que funcione el ver histórico--}}
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
            </div>
        </div>
    </div>
</div>


@if (!isset($auction) && (!isset($_GET['page']) || $_GET['page'] == 1))
<div class="home_text">
    <div class="container">
		{!! $seo_data->meta_content !!}
		<?php
		#Solo debe aparecer si hay categioria, en el moment oque ha seccion seleccionada no debe aparecer
		 ?>
		@if (empty($filters["section"]))
			<div class="links-sections">
				@foreach($sections as $sec)
					<a class="mr-2" href="{{route('section',array( 'keycategory' => $infoOrtsec->key_ortsec0, 'keysection' => $sec['key_sec']))}}">{{ucfirst($sec["des_sec"])}}</a>
				@endforeach
			</div>
		@endif
    </div>
</div>

@endif


<script>
	var url_lots ="{{ route("getAjaxLots", ["lang" => \Config::get("app.locale")]) }}";

	// Si la pantalla mide menos de 516px de ancho en el elemento con id 'derechaACentro' quitar la class 'text-right' y poner 'text-center'
	if ($(window).width() < 516) {
		$("#derechaACentro").removeClass("text-right");
		$("#derechaACentro").addClass("text-center");
	}


</script>

@if( empty(\Config::get("app.paginacion_grid_lotes")))
	<script src="{{ Tools::urlAssetsCache("/js/default/grid_scroll.js") }}"></script>
@endif
<script src="{{ Tools::urlAssetsCache("/js/default/grid_filters.js") }}"></script>





