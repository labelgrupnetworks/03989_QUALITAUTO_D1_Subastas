
@php

$pagina = new App\Models\Page();

$menuEstaticoHtml  = $pagina->getPagina(\Config::get('app.locale'),"MENUSUBASTAS");

$sesiones = App\Models\V5\AucSessions::select('"id_auc_sessions","auction","reference", nvl("name_lang","name") name, "start", "end", "init_lot", "end_lot"')->
										leftjoin('"auc_sessions_lang"','"id_auc_session_lang" = "id_auc_sessions" and "lang_auc_sessions_lang" =\''. \Tools::getLanguageComplete(\Config::get('app.locale')).'\'' )->
										where('"auction"', $auction->cod_sub)->orderby('"reference"')->get();


@endphp

{!! $menuEstaticoHtml->content_web_page !!}
	<!--
    <div class="info-auction secondary-color" id="auction__lots">

        <div class="container">
            <div class="row">
                @if(!empty($auction))
                    <?php    $url_info = route('urlAuctionInfo',["lang" => \Config::get('app.locale') ,"texto" => \Str::slug($auction->name) ,"cod" => $auction->cod_sub]); ?>
                    <div class="col-lg-12">

                        <div class="row">
                            <div class="col-lg-3 col-xs-4 p-0">
                                <a href="{{ $url_info }}" class="button-follow extra-three-background info-button btn-default" style="width:100%">
                                    <i class="fa fa-info text-center"></i>
                                    <span class="hidden-xs hidden-md">{{ trans(\Config::get('app.theme').'-app.subastas.see_subasta') }}</span>
                                </a>
                            </div>
                            <div class="col-lg-2 hidden-sm hidden-xs"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
	</div>
	-->

<div class="info-auction-tab-contet">
    <div class="container">
        <div class="row">

            <div class="col-xs-12">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="lots">
						<div class="row ">

							@if (!empty($auction))
							<div class="col-xs-12 d-flex mb-3 p-0 grid-auction-wrapper">
								<div class="gridTitleAuction" >
									<div class="gridTextTitleAuction">
										<h1>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_subasta') }} {{$auction->cod_sub }}</h1>
										<h2> {{$auction->sesfechas_sub }}</h2>
										<div class="buttons-auction">
											<p> <a href="/catalogos/{{$auction->cod_sub }}" target="_blank" >{!! trans(\Config::get('app.theme').'-app.lot_list.ver_catalogo') !!}</a></p>

											@if($auction->tipo_sub == "W")
												@foreach($sesiones as $session)
													@if( strtotime($session->end) > time())
														<p  class="realTime"> <a href="{{ \Tools::url_real_time_auction($session->auction,$session->name,$session->id_auc_sessions)}}" target="_blank" >{{ trans(\Config::get('app.theme').'-app.lot_list.bid_live') }}</a></p>
														@break;
													@endif
												@endforeach
											@endif
										</div>

									</div>
								</div>
								<div class="gridImgAuction">
									<img
									src="{{\Tools::url_img_auction('subasta_large',$auction->cod_sub)}}"
									alt="{{ $auction->name }}"/>
								</div>
							</div>
							@endif
						</div>
					<div class="row">


							<div class="col-xs-12">

								@if ($auction->tipo_sub == 'W')
								<div class="col-xs-12 fondo_sesiones">


									@foreach($sesiones as $sesion)
										@php
										$day = date("d", strtotime($sesion->start));
										$month = date("M", strtotime($sesion->start));
										$monthName =mb_strtoupper( \Tools::get_month_lang($month, trans(\Config::get('app.theme')."-app.global.month_large")));
										$urlSession=\Tools::url_auction($sesion->auction,$sesion->name,$sesion->id_auc_sessions,'001');
										#poner esto antes de la página a la que debe ir
										if(empty($url)){
											$url = $urlSession;
										}
										#calculamos en que página empieza la sesion
											$cuantosLotes = App\Models\V5\FgAsigl0::select("count(ref_asigl0) cuantos")->where("SUB_ASIGL0",$auction->cod_sub )->where("ref_asigl0","<",$sesion->init_lot )->first();
											#por defecto 24 como en lotlistcontroller
											$lotsPerPage  = request('total');
											if(empty($lotsPerPage)){
												$lotsPerPage=24;
											}
											$pagina = intdiv($cuantosLotes->cuantos , $lotsPerPage);
											#le sumamos 1 por que la página no empieza em 0 si no en 1
											$pagina +=1;
											$urlSession.="?page=$pagina#".$auction->cod_sub."-".$sesion->init_lot;

										@endphp
										<div class="col-xs-12 col-md-4">
											<a href="{{$urlSession}}">
												<div class="sesiones">

													{!!	trans(\Config::get('app.theme')."-app.lot_list.session_secription", array("number" => intval($sesion->reference), "day" => $day, "month" => $monthName, "name" => $sesion->name, "init_lot" => $sesion->init_lot, "end_lot" => $sesion->end_lot )) !!}

												</div>
											</a>
										</div>

									@endforeach
								</div>
								@endif

							</div>
							<div class="col-xs-12 col-md-3 auction-lots-view">
								@include('includes.grid.leftFilters')

							</div>
							<div class="col-xs-12 col-md-9 p-0">

								@include('includes.grid.topFilters')
								@if(\Config::get("app.paginacion_grid_lotes"))

										<div class="col-xs-12 p-0">
											@include("includes.grid.lots")
										</div>

										<div class="col-xs-12 text-center">
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





