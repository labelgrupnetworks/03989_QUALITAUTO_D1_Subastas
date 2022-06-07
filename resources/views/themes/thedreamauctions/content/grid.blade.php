<div class="info-auction-tab-contet">
    <div class="container">
        <div class="row">

            <div class="col-xs-12">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="lots">
						<div class="row">
							<div class="col-xs-12 col-md-3 auction-lots-view">
								@include('includes.grid.leftFilters')

							</div>
							<div class="col-xs-12 col-md-9 p-0">

								<div class="hidden-xs hidden-sm mb-1">
									{!! \BannerLib::bannersPorKey('grid_banner', 'grid_banner', $options = ['dots' => false, 'autoplay' => true, 'autoplaySpeed' => 5000, 'slidesToScroll' => 1, 'arrows' => false]) !!}
								</div>

								@include('includes.grid.topFilters')
								@if(\Config::get("app.paginacion_grid_lotes"))

										<div class="col-xs-12 p-0">
											@include("includes.grid.lots")
										</div>


										<div class="col-xs-12 foot-pagination-grid">
											{{ $paginator->links() }}
										</div>

									@else
									<div class="clearfix"></div>

									<div class="col-xs-12 p-0">
										<div class="list_lot" id="lotsGrid">
										</div>
									</div>

										{{-- Código scroll infinito --}}

										<div class="clearfix"></div>
										<div id="endLotList" ></div>
										<div id="loading" class=" text-center">
												<img src="/default/img/loading.gif" alt="Loading…" />

										</div>

										{{-- Fin código de scroll infinito --}}
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

</script>

@if( empty(\Config::get("app.paginacion_grid_lotes")))
	<script src="{{ Tools::urlAssetsCache("/js/default/grid_scroll.js") }}"></script>
@endif
<script src="{{ Tools::urlAssetsCache("/js/default/grid_filters.js") }}"></script>





