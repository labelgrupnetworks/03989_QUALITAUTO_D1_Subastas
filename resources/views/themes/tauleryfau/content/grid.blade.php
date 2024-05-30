<div class="info-auction-tab-contet">

	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 hidden-md hidden-lg banner_grid p-0">
				{!! \BannerLib::bannersPorKey('GRID_LOTES', 'banner_grid', '{dots:false, arrows:false, autoplay: true,
					autoplaySpeed: 5000, slidesToScroll:1}') !!}
			</div>
		</div>
	</div>

    <div class="container">

		@php
		$subasta_finalizada = false;
		if(!empty($auction)){

			$sql = 'SELECT ESTADO, "reference", "start", "end", "name", "id_auc_sessions" FROM "auc_sessions" left join WEB_SUBASTAS  on ID_EMP="company" and ID_SUB="auction" and SESSION_REFERENCE="reference" WHERE "company" = :emp and "auction" = :cod_sub order by "reference"';
       $bindings = array(
						'emp'           => Config::get('app.emp'),
						'cod_sub'       => $auction->cod_sub
						);

			$sessiones = DB::select($sql, $bindings);
			foreach($sessiones as $session){
				if($auction->tipo_sub == 'W' && strtotime($session->end) > time() && strtotime($auction->session_start) < time() && $session->estado != "ended"){
					$url_tiempo_real=Tools::url_real_time_auction($auction->cod_sub, $session->name, $session->id_auc_sessions);
					break;
				}
			}


		}
		@endphp

		@if(!empty($url_tiempo_real))
		<div class="row mb-1 mt-1">
			<div class="col-xs-12 col-md-4 col-lg-3 col-md-offset-5 col-lg-offset-6">
				<a href="{{ $url_tiempo_real }}" target="_blank" class="puja-online texto-puja-online">
					{{ trans($theme.'-app.subastas.bid_online_now') }}
				</a>
			</div>
		</div>
		@endif

		<div class="row">

			<div class="col-xs-12 d-flex justify-content-space-bettween align-items-center hidden-lg hidden-md mt-1 flex-wrap">

				<div class="filters-if-show">
					<button class="btn btn-default switcher">
						<span class="title-filt">{{ trans($theme.'-app.lot_list.show_filters') }}</span>
						<span class="title-filt">{{ trans($theme.'-app.lot_list.hide_filters') }}</span>
						<span><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAVCAYAAACpF6WWAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyhpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTM4IDc5LjE1OTgyNCwgMjAxNi8wOS8xNC0wMTowOTowMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTcgKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QzczMTRFODdCMEM2MTFFNzgwNTdDMDU0RjJCRTNGN0QiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QzczMTRFODhCMEM2MTFFNzgwNTdDMDU0RjJCRTNGN0QiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpDNzMxNEU4NUIwQzYxMUU3ODA1N0MwNTRGMkJFM0Y3RCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpDNzMxNEU4NkIwQzYxMUU3ODA1N0MwNTRGMkJFM0Y3RCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PnHXpV0AAACBSURBVHjaYvz//z8DtQETAw3AqKHUByy4JPz8/Ig2ZNOmTQPv/UNA7IZF3AmIT5Nr6EwgXgfE5khi5lCxGSSHKRQsBWJBIN4JxLZADMp+m4G4BYjnkmsoCEwBYmGowX+hhvWQFftooBHqYlYgriQ7SWEBBQOa+BlHi74RbChAgAEAcGMXwkehP00AAAAASUVORK5CYII=" alt=""></span>
					</button>
				</div>

				@if(\Config::get("app.paginacion_grid_lotes"))
					<div class="paginador-container">
						{{ $paginator->links('front::includes.grid.paginator_pers_select') }}
					</div>
				@endif

			</div>

			<div class="col-xs-12 hidden-md hidden-lg">
				<div class="tags-top-filters d-flex align-items-center flex-wrap">
					@include('front::includes.grid._tags_filters')
				</div>
			</div>

            <div class="col-xs-12 p-0 mb-5">
                <div class="tab-content tab-content-custom">
                    <div role="tabpanel" class="tab-pane active" id="lots">
						<div class="row" >
							<div class="col-xs-12 col-md-3 filter-panel">
								@include('includes.grid.leftFilters')

							</div>


							<div class="col-xs-12 col-md-9 p-0">


								@include('includes.grid.topFilters')
								@if(\Config::get("app.paginacion_grid_lotes"))

									<div class="col-xs-12 p-0">
										@include("includes.grid.lots")
									</div>
									<div class="col-xs-12 text-right hidden-xs hidden-sm">
										{{ $paginator->links('front::includes.grid.paginator_pers') }}
									</div>

									<div class="col-xs-12 text-right hidden-md hidden-lg">
										{{ $paginator->links('front::includes.grid.paginator_pers_select') }}
									</div>


								@else
										<div class="clearfix"></div>

										<div class="col-xs-12 p-0 mb-5">
											<div class="list_lot" id="lotsGrid">
											</div>
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
                </div>
			</div>
			@if(!empty($infoSec) && $paginator->currentPage() == 1)
			<div class="container category mb-3">
				<div class="row">
					<div class="col-xs-12" style="margin-bottom: 40px;margin-left:10px">
						<p>{!!$infoSec->meta_contenido_sec!!}</p>
					</div>
				</div>
			</div>
			@endif
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
					<a class="mr-2" href="{{route('section',array( 'keycategory' => $infoOrtsec->key_ortsec0 ?? '', 'keysection' => $sec['key_sec']))}}">{{ucfirst($sec["des_sec"])}}</a>
				@endforeach
			</div>
		@endif
    </div>
</div>

@endif

@if (isset($auction))

<div id="modal-current-auction_{{$auction->cod_sub }}" class="modal fade modal-current-auctions" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content modal-current-auction d-flex align-items-center flex-wrap">
        <div class="img-modal-current col-md-4 col-xs-12">
            <div class="alert-ball"></div>
            <img src="/img/load/subasta_medium/AUCTION_{{ $auction->emp_sub }}_{{$auction->cod_sub }}.jpg"  class="img-responsive img-auction-new"  />
        </div>
        <div class="col-md-8 col-xs-12">
            <div class="modal-name-aution mb-2 title-modal-current-auction">
                {{ trans($theme.'-app.lot_list.la') }} {{ $auction->name }} {{ trans($theme.'-app.lot_list.isBegin') }}
            </div>
            <div class="modal-desc-auction mb-2 title-modal-current-auction text-underline">
                    {{ $auction->description }}

            </div>
            <div class="modal-desc-auction mb-2 title-modal-current-auction">
                    {{ trans($theme.'-app.lot_list.begin_auction') }}
            </div>
            <div class="modal-button-auction mb-3 d-flex align-items-center justify-content-space-bettween flex-wrap">
                <div class="btn-current-action gotoauction col-md-6 col-xs-12 ">
                    @php
						if(empty($url_tiempo_real)){
                   		 	$url_tiempo_real=\Routing::translateSeo('api/subasta').$auction->cod_sub."-".str_slug($auction->name)."-".$auction->id_auc_sessions;
						}
					@endphp
					<a href="{{ $url_tiempo_real }}" target="_blank" class="puja-online texto-puja-online">{{ trans($theme.'-app.subastas.bid_online_now') }}</a>
                </div>
                <div class="btn-current-action continue-here col-md-6 col-xs-12">
                    <a href="javascript:($('#modal-current-auction_{{$auction->cod_sub }}').modal('hide'))" class="bid-large-button-view view">{{ trans($theme.'-app.lot_list.continue_here') }}</a>
                </div>
            </div>


        </div>
    </div>
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





