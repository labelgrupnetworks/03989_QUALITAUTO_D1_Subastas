<?php

$historicas = array();
foreach ($data['auction_list'] as $value){
	$year = date("Y", strtotime($value->session_start));
	#agrupamos por auction
	$historicas[$year][$value->cod_sub] = $value;
/*
   usort($historicas[$year], function ($a, $b) {
	   return  strtotime($a->session_start) -  strtotime($b->session_start);
	});
*/
 }



?>


<div class="all-auctions color-letter">
        <div class="container">
            <div class="row">
                    <div class="auctions-list col-xs-12">

						@foreach ($historicas as $key =>  $subastas)
							<div class="col-xs-12 sub-h">
								<div class="dat year_auctions_list" >
									<strong>	{{ $key }} </strong>
								 </div>
							</div>
							@foreach($subastas as $subasta)
								<?php

									$url_lotes= \Tools::url_auction($subasta->cod_sub,$subasta->des_sub,$subasta->id_auc_sessions, '001');
									$url_tiempo_real=\Tools::url_real_time_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions);
									$url_subasta=\Tools::url_info_auction($subasta->cod_sub,$subasta->name);
									if($subasta->tipo_sub == "O"){
										$url_lotes.="?order=orden_asc";
									}elseif($subasta->tipo_sub == "V"){
										$url_lotes.="?order=orden_desc";
									}

								?>
								<div class="col-xs-12 col-md-2" style="height:436px">
									<a title="{{ $subasta->des_sub }}" href="{{ $url_lotes }}">
										<div class="col-xs-12 border-lot auction-container no-padding">
											<div class="col-xs-12 col-md-12 h-100 no-padding">
												<div class="col-md-12 col-xs-12 col-sm-12 no-padding auction-item-img">
													<div class="auction-size col-xs-12 no-padding">
														<div data-loader="loaderDetacados" class='text-input__loading--line'></div>
															<img

																data-src="{{\Tools::url_img_auction('subasta_medium',$subasta->cod_sub)}}"
																alt="{{ $subasta->des_sub }}"
																class="img-responsive lazy"
																style="display: none"
															/>
														</div>                                                          <div class="auction-list-title text-center">{{$subasta->des_sub }}</div>
											<div class="col-md-12 col-xs-12 col-sm-12 auction-desc-content d-flex justify-content-center flex-direction-column">
												<div class="mt-45n fechas">
													<p>{{ date("d-m-Y", strtotime($subasta->session_start)) }} </p>
												</div>
												{{-- de momento no quieren la hora
												<div class="">

													<small>{{ date("H:i", strtotime($subasta->session_start)) }} h</small>
												</div>
												--}}

												<div class="col-xs-12 col-sm-12 col-sm-offset-2 col-md-offset-0 col-md-12 d-flex justify-content-center h-100 align-items-center">

												</div>
											</div>
										</div>
									</a>
								</div>
							</div>
							</div>
							@endforeach
                        @endforeach
                    </div>
            </div>
    	</div>

</div>
