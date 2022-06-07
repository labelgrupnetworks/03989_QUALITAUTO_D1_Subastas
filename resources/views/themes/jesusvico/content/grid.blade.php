

    <div class="info-auction secondary-color hidden-md hidden-lg" id="auction__lots">

        <div class="container">
            <div class="row">
                @if(!empty($auction))
                    <?php    $url_info = route('urlAuctionInfo',["lang" => \Config::get('app.locale') ,"texto" => \Str::slug($auction->name) ,"cod" => $auction->cod_sub]); ?>
                    <div class="col-lg-12">

                        <div class="row">
                            <div class="col-xs-12">
                                <a class="hidden" href="{{ $url_info }}" class="button-follow extra-three-background info-button btn-default" style="width:100%">
                                    <i class="fa fa-info text-center"></i>
                                    <span class="hidden-xs hidden-md">{{ trans(\Config::get('app.theme').'-app.subastas.see_subasta') }}</span>
                                </a>
                            </div>
							<div class="col-xs-12 hidden-md hidden-lg text-center">
								<a href="javascript:viewFilter()" class="info-button info-filter" style="width:100%">
									<input type="hidden" id="js-viewFilter" value="{{ trans(\Config::get('app.theme').'-app.lot_list.view_filter') }}"/>
									<input type="hidden" id="js-hiddenFilter" value="{{ trans(\Config::get('app.theme').'-app.lot_list.not_view_filter') }}"/>
									<span class="ocultar" id="span-viewFilter">{{ trans(\Config::get('app.theme').'-app.lot_list.view_filter') }}</span>
                                </a>
							</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

<div class="info-auction-tab-contet">
    <div class="container">
        <div class="row">

            <div class="col-xs-12 p-0">
					{{-- Desactivado para poder hacer sticky no se aun si necesitaba estar activado para algo --}}
                    {{-- <div role="tabpanel" class="tab-pane active" id="lots"> --}}
                        <div class="col-xs-12 col-md-3 auction-lots-view">
                            @include('includes.grid.leftFilters')

                        </div>
                        <div class="col-xs-12 col-md-9 p-0">

							<div class="col-xs-12 hidden-xs hidden-sm">
								{!! \BannerLib::bannersPorKey('banner_lotes', 'banner_lotes', $options = ['dots' => false, 'autoplay' => true, 'autoplaySpeed' => 5000, 'slidesToScroll' => 1, 'arrows' => false]) !!}
							</div>

						@include('includes.grid.topFilters')

							<div class="clearfix"></div>
							<?php
							use App\libs\Currency;
							$currency = new Currency();
							$divisas = $currency->getAllCurrencies();

							$subastasExternas = [
							 'NAC' => "CHF",
							];
							?>

							@if(\Config::get("app.paginacion_grid_lotes"))

								<div class="col-xs-12">

									<div class="grid-lots">
										@include("includes.grid.lots")
									</div>


									<div class="col-xs-12 mt-2 mb-2 pagination-container text-center">
										@if(\Config::get("app.paginacion_grid_lotes"))
										{{ $paginator->links('front::includes.grid.paginator_pers') }}
										@endif
									</div>
								</div>

							@else
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
					{{-- Desactivado para poder hacer sticky no se aun si necesitaba estar activado para algo --}}
                    {{--</div>--}}
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
	var requestCategoty = @json(request('category'));

	if(requestCategoty){
		viewFilter();
	}
</script>

@if( empty(\Config::get("app.paginacion_grid_lotes")))
	<script src="{{ Tools::urlAssetsCache("/js/default/grid_scroll.js") }}"></script>
@endif
<script src="{{ Tools::urlAssetsCache("/js/default/grid_filters.js") }}"></script>


