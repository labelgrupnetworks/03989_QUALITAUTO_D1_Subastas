<div class="all-auctions mt-5 color-letter">
	<div class="container">
		<div class="row">
			<div class="auctions-list col-xs-12">
				<?php
					$subastas=array();
					$subastas_cerradas= array();
					foreach($data['auction_list'] as $subasta ){
						#si cerrada
						if(  strtotime($subasta->session_end) < time() ){
							$subastas_cerradas[]=$subasta;
						}else{
							$subastas[] = $subasta;
						}
					}
					$subastas = array_merge($subastas,$subastas_cerradas);
				?>
				@foreach ($subastas as $subasta)
				<?php
                        $url_lotes= \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions);
                        $url_tiempo_real=\Tools::url_real_time_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions);
						$url_subasta=\Tools::url_info_auction($subasta->cod_sub,$subasta->name);

						//url a lote
						//$lote = \App\Models\V5\FgAsigl0::select('titulo_hces1','ref_asigl0','num_hces1','webfriend_hces1')->JoinFghces1Asigl0()->where(['SUB_ASIGL0' => $subasta->cod_sub])->first();
						//$url_friendly = \Tools::url_lot($subasta->cod_sub,$subasta->id_auc_sessions,$subasta->name,$lote->ref_asigl0,$lote->num_hces1,$lote->webfriend_hces1,$lote->titulo_hces1);
                    ?>

				<div class="col-xs-12 col-md-6">
					<div
						class="col-xs-12 auction-container position-relativ  position-relative d-flex flex-wrap align-items-center">
						@if( $subasta->tipo_sub =='W' && strtotime($subasta->session_end) > time() )
						<div class="bid-online"></div>
						<div class="bid-online animationPulseRed"></div>
						@endif
						<div class="col-xs-12 p-0">
							<div class="auction-list-title color-brand">{{$subasta->name }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-7 col-lg-8 p-0">

							<div class="d-flex flex-wrap  align-items-center auction-img-data">
								<div class="auction-size col-xs-12 no-padding col-md-7">
									<div data-loader="loaderDetacados" class='text-input__loading--line'></div>
									<img data-src="{{\Tools::url_img_session('subasta_medium',$subasta->cod_sub,$subasta->reference)}}"
										alt="{{ $subasta->name }}" class="img-responsive lazy" style="display: none" />
								</div>

								<div class=" col-xs-12 col-md-5 col-lg-5 text-center date-auction " style="font-size: 15px; font-weight: bold;">
									@if(  strtotime($subasta->session_end) < time() )

										<p > <span class=" estado-subasta button-cerrada"> {{ trans(\Config::get('app.theme').'-app.subastas.cerrada') }} </span></p>

									@elseif(  strtotime($subasta->session_start) > time() )
										<p>{{ trans(\Config::get('app.theme').'-app.subastas.inicio_subasta') }}</p>

										<p >{{ date("d-m-Y", strtotime($subasta->session_start)) }}</p>
										<p >{{ date("H:i", strtotime($subasta->session_start)) }} h</p>
										<p > <span class=" estado-subasta button-proximamente"> {{ trans(\Config::get('app.theme').'-app.subastas.proximamente') }} </span></p>
									@elseif(  strtotime($subasta->session_start) < time() )
										<p>{{ trans(\Config::get('app.theme').'-app.subastas.fecha_fin_subasta') }}</p>

										<p >{{ date("d-m-Y", strtotime($subasta->session_end)) }}</p>
										<p >{{ date("H:i", strtotime($subasta->session_end)) }} h</p>
										<p > <span class=" estado-subasta button-encurso"> {{ trans(\Config::get('app.theme').'-app.subastas.en_curso') }} </span></p>

									@endif

									<div class="documents">
										<ul class="ul-format">
											<?php
                                                $pdf_cat = Tools::url_pdf($subasta->cod_sub,$subasta->reference,'cat');
                                                $pdf_man = Tools::url_pdf($subasta->cod_sub,$subasta->reference,'man');
                                                $pdf_pre = Tools::url_pdf($subasta->cod_sub,$subasta->reference,'pre');
                                                //Ya no se usa desde el ERP, lo mantengo por si acaso
                                                $pdf_adj = Tools::url_pdf($subasta->cod_sub,$subasta->reference,'adj');
                                            ?>
											@if($pdf_cat)
											<li class="col-md-12 col-xs-6 no-padding">
												<a target="_blank" class="cat-pdf color-letter d-flex"
													href="{{$pdf_cat}}" role="button">
													<div class="text-center"><i class="fa  fa-file-download"></i></div>
													<small>{{ trans(\Config::get('app.theme').'-app.subastas.pdf_catalog') }}</small>
												</a>
											</li>
											@endif
											@if($pdf_man)
											<li class="col-md-12 col-xs-6 no-padding">
												<a target="_blank" class="cat-pdf color-letter d-flex"
													href="{{$pdf_man}}" role="button">
													<div class="text-center"><i class="fa fa-file-download"></i></div>
													<small>{{ trans(\Config::get('app.theme').'-app.subastas.pdf_man') }}</small>
												</a>
											</li>
											@endif

											@if($pdf_pre)
											<li class="col-md-12 col-xs-6 no-padding">
												<a target="_blank" class="cat-pdf color-letter d-flex"
													href="{{$pdf_pre}}" role="button">
													<div class="text-center"><i class="fa fa-file-download"></i></div>
													<small>{{ trans(\Config::get('app.theme').'-app.subastas.pdf_pre') }}</small>
												</a>
											</li>
											@endif

											@if($pdf_adj)
											<li class="col-md-12 col-xs-6 no-padding">
												<a target="_blank" class="cat-pdf color-letter d-flex"
													href="{{$pdf_adj}}" role="button">
													<div class="text-center"><i class="fa fa-file-download"></i></div>
													<small>{{ trans(\Config::get('app.theme').'-app.subastas.pdf_adj') }}</small>
												</a>
											</li>
											@endif
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div
							class="col-xs-12 col-sm-6 col-md-5 col-lg-4 auction-button d-flex align-items-center pr-0 pl-1">
							<div class="auction-item-links w-100">
								<div class="auction-item-icon-desc d-block">
									<a title="{{ $subasta->name }}" href="{{ $url_lotes }}"
										class=" btn-view-lots button-principal">{{ trans(\Config::get('app.theme').'-app.subastas.see_lotes') }}</a>
								</div>
								<div class="auction-item-icon-desc  d-block">
									<a title="{{ $subasta->name }}" href="{{ $url_subasta }}"
										class="btn-info-auction tercer-button"><strong>{{ trans(\Config::get('app.theme').'-app.subastas.see_subasta') }}</strong></a>
								</div>
								@if( $subasta->tipo_sub =='W' && strtotime($subasta->session_end) > time() )
								<div class="bid-life d-block">
									<a style="color:#FFFFFF" class="btn-bid-life d-block " href="{{ $url_tiempo_real }}"
										title="{{ trans(\Config::get('app.theme').'-app.header.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_start),'d/m/Y H:i') }} {{ trans(\Config::get('app.theme').'-app.header.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_end),'d/m/Y H:i') }}"
										target="_blank">{{ trans(\Config::get('app.theme').'-app.lot.bid_live') }}</a>
								</div>
								@endif
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>
</div>


<section class="banner-doble mt-3">
	<div class="container">
		<div class="row">
			@if($data['type'] == 'O')
			<div class="col-xs-3 p-0">
				{!! \BannerLib::bannersPorKey('banner_izq_subastas', '') !!}
			</div>
			<div class="col-xs-9 p-0">
				{!! \BannerLib::bannersPorKey('banner_dch_subastas', '') !!}
			</div>
			@else
			<div class="col-xs-3 p-0">
				{!! \BannerLib::bannersPorKey('banner_izq_vd', '') !!}
			</div>
			<div class="col-xs-9 p-0">
				{!! \BannerLib::bannersPorKey('banner_drch_vd', '') !!}
			</div>
			@endif
		</div>
	</div>
</section>

<section>
	<div class="container mt-5 custom-banner">
		<div class="row">

			<div class="col-xs-12">
				<div class="title-custom-banner">{{ trans(\Config::get('app.theme').'-app.global.how_to_buy') }}</div>
			</div>
			<div class="col-xs-12 mb-4">
				<div class="desc-custom-banner">{!!
					trans(\Config::get('app.theme').'-app.global.how_to_buy_description') !!}</div>
			</div>

			{{--
			<div class="text-center d-flex alig-items-center justify-content-center flex-wrap col-xs-12 p-0">
				<div class="row">
					<div class="col-xs-12 col-md-3 col-lg-3-custom text-center mt-2">
						<div class="img-content">
							<img src="/themes/{{\Config::get('app.theme')}}/assets/img/camion.png" />
						</div>
						<div class="img-text mt-2 text-center color-brand">
							{{ trans(\Config::get('app.theme').'-app.global.shipping-service') }}</div>
					</div>
					<div class="col-xs-12 col-md-3 col-lg-3-custom text-center mt-2">
						<div class="img-content">
							<img src="/themes/{{\Config::get('app.theme')}}/assets/img/mundo.png" />
						</div>
						<div class="img-text mt-2 color-banner text-center color-brand">
							{{ trans(\Config::get('app.theme').'-app.global.customer-service') }}</div>
					</div>
					<div class="col-xs-12 col-md-3 col-lg-3-custom text-center mt-2">
						<div class="img-content">
							<img src="/themes/{{\Config::get('app.theme')}}/assets/img/visa.png" />
						</div>
						<div class="img-text mt-2 color-banner text-center color-brand">
							{{ trans(\Config::get('app.theme').'-app.global.online_payment') }}</div>
					</div>
					<div class="col-xs-12 col-md-3 col-lg-3-custom text-center mt-2">
						<div class="img-content">
							<img src="/themes/{{\Config::get('app.theme')}}/assets/img/seguridad.png" />
						</div>
						<div class="img-text mt-2 color-brand text-center">
							{{ trans(\Config::get('app.theme').'-app.global.security_pay') }}</div>
					</div>
				</div>
			</div>
			--}}
		</div>
	</div>
</section>

<section class="banner_inf_subastas mt-5 pt-3">
	@if($data['type'] == 'O')
	{!! \BannerLib::bannersPorKey('banner_inf_subastas', '') !!}
	@else
	{!! \BannerLib::bannersPorKey('banner_inf_vd', '') !!}
	@endif
</section>
