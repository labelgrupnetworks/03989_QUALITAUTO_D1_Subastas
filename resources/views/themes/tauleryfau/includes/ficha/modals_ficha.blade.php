<div id="ordenFicha" class="container modal-block mfp-hide ">
	<div data-to="pujarLoteFicha" class="modal-sub-w">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class=" text-center single_item_content">
						<p class="class_h1"><?=trans(\Config::get('app.theme').'-app.lot.confirm_bid')?></p><br />
						<span for="bid" class='desc_auc'>{{trans(\Config::get('app.theme').'-app.lot.bidding_for')}}
						</span> <strong><span class="precio_orden"></span> €</strong><br />
						</br>
						<button id="confirm_orden"
							class="btn btn-color button_modal_confirm btn-custom"><?=trans(\Config::get('app.theme').'-app.lot.confirm')?>
						</button>

						<div class='mb-10'></div>
						<ul class="items_list">
							<li><?=trans(\Config::get('app.theme').'-app.lot.tax_not_included')?> </li>
						</ul>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>


<div id="modalPujarFicha" class="container modal-block mfp-hide ">
	<div data-to="pujarLoteFicha" class="modal-sub-w">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class=" text-center single_item_content_">
						<p class="class_h1">{{ trans(\Config::get('app.theme').'-app.lot.confirm_bid') }}</p><br />
						<span for="bid"
							class='desc_auc'>{{ trans(\Config::get('app.theme').'-app.lot.you_are_bidding') }} </span>
						<strong><span class="precio_orden"></span> €</strong><br />
						</br>
						<button
							class="confirm_puja btn btn-color button_modal_confirm btn-custom">{{ trans(\Config::get('app.theme').'-app.lot.confirm') }}</button>
						<div class='mb-10'></div>
						<div class='mb-10'></div>
						<ul class="items_list">
							<li><?=trans(\Config::get('app.theme').'-app.lot.tax_not_included')?> </li>

						</ul>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>




<div id="modal_frame" data-to="pujarLoteFichaBalclis" class="container modal-block mfp-hide ">
	<div class='modal-dialog modal-sm'>
	</div>
</div>


<div id="modalCloseBids" class="modal-block mfp-hide">
	<section class="panel">
		<div class="panel-body">
			<div class="modal-wrapper">
				<div class="modal-text text-center">
					<p class="txt_loading"> {{ trans(\Config::get('app.theme').'-app.lot.loading') }}</p>
					<p class="txt_esperando_sala hidden">
						{{ trans(\Config::get('app.theme').'-app.sheet_tr.esperando_sala') }} </p>
					<div class="loader"></div>
				</div>
			</div>
		</div>
	</section>
</div>
<div id="modalDisconnected" class="modal-block mfp-hide">
	<section class="panel">
		<div class="panel-body">
			<div class="modal-wrapper">
				<div class="modal-text text-center">
					<p class="txt_loading"> {{ trans(\Config::get('app.theme').'-app.lot.connect_to_serv') }}</p>
					<div class="loader"></div>
				</div>
			</div>
		</div>
	</section>
</div>
<div id="modalComprarFicha" class="modal-block mfp-hide" data-to="comprarLoteFicha">
	<section class="panel">
		<div class="panel-body">
			<div class="modal-wrapper">
				<div class="modal-text text-center">
					<p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.buy_lot_question') }}</p>
					<button
						class="btn btn-color modal-confirm btn-custom">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
					<button
						class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
				</div>
			</div>
		</div>
	</section>
</div>


<?php  //estructura necesaria para crear lso elementos del listado de pujas  ?>
<div id="duplicalte_list_pujas" class="hist_item hidden">
	<span>
		<span class="yo">{{ trans(\Config::get('app.theme').'-app.lot.my_bid') }}</span>
		<span class="otro">{{ trans(\Config::get('app.theme').'-app.lot.bidder') }}</span>
		(
			<!-- <span class="yo">{{ trans(\Config::get('app.theme').'-app.lot.I') }}</span> -->
			<span class="uno"></span>
			<span class="dos" data-hint="<?= nl2br(trans(\Config::get('app.theme').'-app.lot.puja_automatica')) ?>">A</span>
		)
	</span>
	<span class="date"></span>
	<span class="tres_item"><em class="price "> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</em></span>
</div>

<div id="view_more" class="more hidden col-xs-12 text-right">

</div>

@if(!empty($lote_actual->contextra_hces1))
<div class="modal fade" id="modal360" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog flex valign" role="document">
		<div class="modal-content">
			<button class="close-modal-360"><i class="fas fa-times"></i></button>
			<div class="modal-body">
				<?=$lote_actual->contextra_hces1?>
			</div>
		</div>
	</div>
</div>
@endif


<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="currency-types" tabindex="-1" role="dialog"
	aria-labelledby="#currency-types" aria-hidden="true">
	<div class="modal-dialog-especial" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="modal-img">

					<img class="" src="/themes/{{\Config::get('app.theme')}}/assets/img/tabla-conversion_new.png"
						alt="{{(\Config::get( 'app.name' ))}}">
				</div>
			</div>
		</div>
	</div>
</div>


<!--- Modal video -->

<div class="modal fade bs-example-modal-lg" id="modalVideo" tabindex="-1" role="dialog" aria-labelledby="#modalVideo"
	aria-hidden="true">
	<div class="modal-dialog-especial" role="document">


		<div id="reproductor"></div>

		<div class="videoInfo">
			<button type="button" id="close_reproductor" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>


			<div class="single-lot-des">
				<h2>{{$lote_actual->des_sub}}</h2>
				<div class="single-lot-desc-wrapper">
					<br>

					<div class="single-lot-desc-title desc">
						<h4>{{trans(\Config::get('app.theme').'-app.lot.lot-name')}} {{$lote_actual->ref_asigl0}}</h4>
					</div>
					<div class="single-lot-desc-content" id="box2"
						style="background:#FFF;z-index:99999992;width:100%;position:relative;">
						@if( \Config::get('app.descweb_hces1'))
						<?= $lote_actual->descweb_hces1 ?>
						@elseif ( \Config::get('app.desc_hces1' ))
						<?= $lote_actual->desc_hces1 ?>
						@endif
					</div>

				</div>

				<div class="bloquepujar">

					@if($lote_actual->retirado_asigl0 == 'N' && $lote_actual->fac_hces1 != 'D' &&
					$lote_actual->fac_hces1 != 'R')



					@if ($lote_actual->subc_sub != 'A' && $lote_actual->subc_sub != 'S' )















					<!-- Codigo de ficha cerrada -->

					<div class="info_single ">
						<div class="info_single_title">
							<div class="exit-price prices">

								{{-- Si V --}}
								@if($lote_actual->tipo_sub == 'V' )

								<div
									class="price  <?=!empty( $lote_actual->oferta_asigl0) && $lote_actual->oferta_asigl0 == 1?'tachado':'';?>">
									@if(!empty( $lote_actual->oferta_asigl0) && $lote_actual->oferta_asigl0 == 1)
									<span
										class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.price_salida') }}</span>
									@elseif((empty( $lote_actual->oferta_asigl0)) || (!empty(
									$lote_actual->oferta_asigl0) && $lote_actual->oferta_asigl0 == 2))
									<span
										class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.precio_estimado') }}</span>
									@endif
									<span class="pre">{{$lote_actual->formatted_imptas_asigl0}}
										{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
								</div>
								<div class="divider-prices"></div>
								@endif

								<div class="price starting-price">
									@if($lote_actual->tipo_sub == 'V' &&!empty($lote_actual->oferta_asigl0) &&
									($lote_actual->oferta_asigl0 == 1 || $lote_actual->oferta_asigl0 == 2))
									<span
										class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.nuestro_precio') }}</span>
									@elseif($lote_actual->tipo_sub == 'V' )
									<span
										class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</span>
									@else
									<span class="pre">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</span>
									@endif
									<span id="impsalexchange2" class="currency-trnas"
										style="float:right; margin-top:4px;">
										{{ trans(\Config::get('app.theme').'-app.subastas.dolares') }}
										{{ $lote_actual->formatted_impsalhces_asigl0}}
									</span>
									<span class="pre" style="float:right;">
										{{$lote_actual->formatted_impsalhces_asigl0}}
										{{ trans(\Config::get('app.theme').'-app.subastas.euros') }} |
									</span>

								</div>

								@if($lote_actual->cerrado_asigl0 == 'S' && $lote_actual->tipo_sub == 'V' )

								@elseif($lote_actual->cerrado_asigl0 == 'S' && ((!empty($precio_venta) &&
								$lote_actual->remate_asigl0 !='S') || $lote_actual->desadju_asigl0 =='S' ))
								<div class="price price-awarded" style="text-align: center;">
									<p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</p>
								</div>
								@elseif($lote_actual->cerrado_asigl0 == 'S' && $lote_actual->remate_asigl0 =='S' &&
								(!empty($precio_venta))|| ($lote_actual->subc_sub == 'H' &&
								!empty($lote_actual->impadj_asigl0)) )
								@if($lote_actual->subc_sub == 'H' && !empty($lote_actual->impadj_asigl0))
								@php($precio_venta = $lote_actual->impadj_asigl0)
								@endif
								<div class="price price-awarded">
									<p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}:
										{{\Tools::moneyFormat($precio_venta)}} €</p>
								</div>
								<div class="price-awarded-salechange">
									<p id="impsalexchange-actual2" class="" style="margin: 0px; font-size: 14px;"></p>
								</div>
								@elseif(strtoupper($lote_actual->tipo_sub) == 'V' && $lote_actual->cerrado_asigl0 == 'N'
								&& $lote_actual->end_session > date("now"))

								<div class="price" style="text-align: center;">
									<p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</p>
								</div>
								@elseif($lote_actual->cerrado_asigl0 == 'S' && empty($precio_venta) )

								<div class="price text-center">
									<p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
									</p>

								</div>
								@elseif($lote_actual->cerrado_asigl0 == 'D')

								<div class="price text-center">
									<p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
									</p>

								</div>
								@endif
							</div>

						</div>


						<!-- FIN - Codigo de ficha cerrada -->












						@elseif(
						($lote_actual->tipo_sub == 'V' && $lote_actual->cerrado_asigl0 != 'S' &&
						strtotime($lote_actual->end_session) > date("now"))
						||
						($lote_actual->tipo_sub == 'W' || $lote_actual->tipo_sub == 'O') && $lote_actual->cerrado_asigl0
						== 'S' && empty($lote_actual->himp_csub) && $lote_actual->compra_asigl0 == 'S' &&
						$lote_actual->fac_hces1=='N' && $lote_actual->desadju_asigl0 =='N')
						)









						<!-- Codigo de puja para tipo V -->


						<div class="info_single ficha_V">
							<div class="exit-price prices">
								<div class="price">
									@if(!empty($lote_actual->oferta_asigl0) && ($lote_actual->oferta_asigl0 == 1 ||
									$lote_actual->oferta_asigl0 == 2))
									<span
										class="pre title">{{ trans(\Config::get('app.theme').'-app.subastas.price_salida_venta') }}</span>
									@else
									<span
										class="pre title">{{ trans(\Config::get('app.theme').'-app.subastas.precio_estimado') }}
									</span>
									@endif
									<div class="text-right">
										<span
											class="pre <?=!empty( $lote_actual->oferta_asigl0) && $lote_actual->oferta_asigl0 == 1?'tachado':'';?>">{{ $lote_actual->formatted_imptas_asigl0 }}
											{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
										<div class="vertical-bar">|</div>
										<span id="impsalexchange-tas2"
											class="currency-trnas <?=!empty( $lote_actual->oferta_asigl0) && $lote_actual->oferta_asigl0 == 1?'tachado':'';?>"></span>


									</div>
								</div>
								<style>
									.exit-price.prices {
										margin-bottom: 30px;
									}
								</style>
								<div class="divider-prices"></div>
								<div class="price">
									@if(!empty($lote_actual->oferta_asigl0) && ($lote_actual->oferta_asigl0 == 1 ||
									$lote_actual->oferta_asigl0 == 2))
									<span
										class="pre title">{{ trans(\Config::get('app.theme').'-app.subastas.nuestro_precio') }}</span>
									@else
									<span
										class="pre title">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</span>
									@endif
									<div class="text-right">
										<span class="">{{$lote_actual->formatted_actual_bid}}
											{{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </span>
										<div class="vertical-bar">|</div>
										<span id="impsalexchange-actual2" class="currency-trnas"></span>

									</div>
								</div>
							</div>

							@if ($lote_actual->retirado_asigl0 == 'N' && empty($lote_actual->himp_csub) &&
							($lote_actual->subc_sub == 'S' || $lote_actual->subc_sub == 'A'))
							<div class="input-group direct-puja">
								<button data-from="modal" class="lot-action_comprar_lot btn-color" type="button"
									ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}"
									codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}"><i
										class="fa fa-shopping-cart" aria-hidden="true"></i>
									{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</button>
							</div>
							@endif

						</div>

						<!-- FIN - Codigo de puja para tipo V -->







						@elseif(($lote_actual->tipo_sub == 'O' || $lote_actual->tipo_sub == 'P' ||
						$lote_actual->subabierta_sub == 'P') && $lote_actual->cerrado_asigl0 != 'S')








						<!-- Codigo de puja para tipo O -->

						<div class="info_single">

							<div class="exit-price prices actualizable">

								<div class="price">
									<span
										class="pre title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</span>
									<div class="text-center">
										<span>{{$lote_actual->formatted_impsalhces_asigl0}}
											{{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </span>
										<div class="vertical-bar">|</div>
										<span id="impsalexchange2" class="currency-trnas"></span>
									</div>
								</div>

								<div class="divider-prices"></div>
								<div class="price">
									<?php
										$you_bid = false;
										foreach($lote_actual->pujas as $bid){

											if(!empty($data['js_item']['user']) && $bid->cod_licit == $data['js_item']['user']['cod_licit']){
												$you_bid = true;
											}

										}
										?>
									@if (count($lote_actual->pujas) > 0 && Session::has('user') && $you_bid == true)
									<span id="text_actual_max_bid"
										class="pre title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
									<div>
										<span id="actual_max_bid"
											class="<?=  ($lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'])? 'mine':'other' ?>">{{ $lote_actual->formatted_actual_bid }}
											€</span>
										<div class="vertical-bar <?= (count($lote_actual->pujas) == 0)? 'hidden':'' ?>">
											|</div>
										<span id="impsalexchange-actual2" class="currency-trnas"></span>
									</div>
									@elseif(count($lote_actual->pujas) == 0)

									<span id="text_actual_no_bid">
										{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }} </span>
									<span id="text_actual_max_bid"
										class="hidden">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
									<div>
										<span id="actual_max_bid" class="hidden"></span>
										<div class="vertical-bar hidden">|</div>
										<span id="impsalexchange-actual2" class="currency-trnas hidden"></span>
									</div>
									@elseif($lote_actual->pujas > 0 && $you_bid == false)
									<span id="text_actual_max_bid"
										class="pre title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
									<div>
										<span id="actual_max_bid" class="gold">{{ $lote_actual->formatted_actual_bid }}
											€</span>
										<div class="vertical-bar <?= (count($lote_actual->pujas) == 0)? 'hidden':'' ?>">
											|</div>
										<span id="impsalexchange-actual2" class="currency-trnas"></span>
									</div>
									@endif
								</div>
							</div>
						</div>
						<div class="info_single ficha-puja-o" style="margin-top: 20px;">
							<?php
									if(empty($data['js_item']['user']['ordenMaxima']) && !empty($data['js_item']['user']['pujaMaxima']) ) {
										$data['js_item']['user']['ordenMaxima'] = $data['js_item']['user']['pujaMaxima']->formatted_imp_asigl1;
									}


								?>

							@if( $lote_actual->cerrado_asigl0=='N' && $lote_actual->fac_hces1=='N' && strtotime("now") >
							strtotime($lote_actual->start_session) && strtotime("now") < strtotime($lote_actual->end_session) )
								<div class="info_single_content_button">
									<div class="text-center">
										<a target="_blank"
											href='{{  Routing::translateSeo('api/subasta').$data['subasta_info']->lote_actual->cod_sub."-".str_slug($data['subasta_info']->lote_actual->name)."-".$data['subasta_info']->lote_actual->id_auc_sessions }}'>
											<span
												class="btn btn-custom live-btn"><?=trans(\Config::get('app.theme').'-app.lot.bid_live')?></span>
										</a>
									</div>
								</div>
								@endif

								@if($lote_actual->cerrado_asigl0=='N' && $lote_actual->fac_hces1=='N')
								<div class="info_single_content">

									<?php
											   //   capturando la conversion de la moneda
											$moneda=\Tools::conservationCurrency( $data['subasta_info']->lote_actual->num_hces1,  $data['subasta_info']->lote_actual->lin_hces1, array("conservation_1","conservation_2"));
											 ?>
									<div class="input-group direct-puja">
										@if(strtotime("now") < strtotime($lote_actual->start_session))
											<input id="bid_amount" class="form-control" type="hidden"
												value="{{ $data['precio_salida'] }}" type="text"
												placeholder="{{ trans(\Config::get('app.theme').'-app.lot.insert_max_puja') }}">
											<div class="currency-input">
												<span class="currency-simbol"></span>
												<div class="col-xs-7 no-padding">
													<input maxlength="6"
														class="form-control control-number input-currency" type="text"
														type="text"
														placeholder="{{ trans(\Config::get('app.theme').'-app.lot.insert_max_puja') }}"
														onchange="javascript:$('#bid_amount_libre').val($(this).val())">
												</div>
												<div class="col-xs-5 no-padding">
													<button type="button" data-from="modal"
														class="btn-color lot-action_pujar_on_line <?= Session::has('user')?'add_favs':''; ?>"
														style="width:100%">{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</button>
												</div>
											</div>

											<div class="escalados-container d-flex justify-content-space-between">
												@foreach ($lote_actual->siguientes_escalados as $escalado)
												<button type="button" data-escalado-position="{{$loop->index}}" data-from="modal" value="{{$escalado}}" class="btn-color lot-action_pujar_on_line js-lot-action_pujar_escalado <?= Session::has('user')?'add_favs':''; ?>">
														{{ trans(\Config::get('app.theme').'-app.sheet_tr.place_bid') }}
														<span value="{{$escalado}}" id="button-escalado">{{ \Tools::moneyFormat($escalado) }}</span>
														{{trans(\Config::get('app.theme').'-app.subastas.euros')}}
												</button>
												@endforeach
											</div>

											@endif
									</div>
								</div>
								@endif

						</div>


						<!-- FIN - Codigo de puja para tipo O -->








						@elseif( $lote_actual->tipo_sub == 'W' && $lote_actual->cerrado_asigl0 != 'S')









						<!-- Codigo de puja para tipo W -->

						<div class="info_single">
							<div class="exit-price prices">
								<div class="price">
									<span class="pre">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</span>
									<span class="pre">
										{{$lote_actual->formatted_impsalhces_asigl0}}
										{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
									</span>
								</div>
								<div class="divider-prices"></div>
								<div id="actualizable">
									@if ($lote_actual->tipo_sub == 'W' && $lote_actual->subabierta_sub == 'O' &&
									$lote_actual->cerrado_asigl0 == 'N' )
									<div class="price">
										<span id="text_actual_max_bid"
											class="pre <?=  $lote_actual->open_price >0? '':'hidden' ?>">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
										<span id="actual_max_bid"
											class="pre <?= (count($data['ordenes']) > 0 && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'])? 'winner':'no_winner' ?>">{{\Tools::moneyFormat($lote_actual->open_price) }}
											{{trans(\Config::get('app.theme').'-app.subastas.euros')}}</span>
									</div>
									<div class="info_single_title">
										<div id="text_actual_no_bid"
											class=" <?=  $lote_actual->open_price >0? 'hidden':'' ?>">
											{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }} </div>
									</div>

									@endif
								</div>

								<div class="date_top_side_small">
									<span class="cierre_lote"></span>
								</div>
							</div>
						</div>

						@if($lote_actual->fac_hces1!='D')
						<div class="info_single  ficha-puja">

							<div class="info_single_content_button">
								@if( $lote_actual->cerrado_asigl0=='N' && $lote_actual->fac_hces1=='N' &&
								strtotime("now") > strtotime($lote_actual->orders_start) && strtotime("now") <
									strtotime($lote_actual->orders_end))
									<small
										style="display: block;margin-bottom:5px;"><strong><?=trans(\Config::get('app.theme').'-app.lot.insert_max_puja_start')?></strong></small>
									<div class="input-group direct-puja row" style="display:block;">
										<div class="col-xs-7" style="padding-right:0;">
											<input placeholder="{{ $data['precio_salida'] }}"
												class="form-control input-lg control-number"
												value="{{ $data['precio_salida'] }}" type="text"
												onchange="javascript:$('#bid_modal_pujar').val($(this).val())">
										</div>
										<div class="col-xs-5 no-padding">
											<button type="button" class="btn-color" style="width:100%;"
												onclick="javascript:$('#pujar_ordenes_w').trigger('click');">{{strtolower( trans(\Config::get('app.theme').'-app.lot.place_bid')) }}</button>
										</div>
									</div>
									@endif
									@if( $lote_actual->cerrado_asigl0=='N' && $lote_actual->fac_hces1=='N' &&
									strtotime("now") > strtotime($lote_actual->start_session) && strtotime("now") <
										strtotime($lote_actual->end_session) )
										<br>
										<div class="text-center">
											<a
												href='{{  Routing::translateSeo('api/subasta').$data['subasta_info']->lote_actual->cod_sub."-".str_slug($data['subasta_info']->lote_actual->name)."-".$data['subasta_info']->lote_actual->id_auc_sessions }}'>
												<span
													class="btn btn-custom live-btn"><?=trans(\Config::get('app.theme').'-app.lot.bid_live')?></span>
											</a>
										</div>
										@endif


							</div>


						</div>

						@endif

						<!-- FIN - Codigo de puja para tipo W -->

						{{-- INICIO PUJA CERRADA --}}
						@else

						<?php
							$precio_venta=NULL;
							if (!empty($lote_actual->himp_csub)){
    							$precio_venta=$lote_actual->himp_csub;
							}
							//si es un histórico y la subasta del asigl0 = a la del hces1 es que no está en otra subasta y podemso coger su valor de compra de implic_hces1
							elseif($lote_actual->subc_sub == 'H' && $lote_actual->cod_sub == $lote_actual->sub_hces1 && $lote_actual->lic_hces1 == 'S' and $lote_actual->implic_hces1 >0){
    							$precio_venta = $lote_actual->implic_hces1;
							}
						?>



							<div class="info_single_title">


						<div class="exit-price prices">
							<div class="price starting-price" >
							@if($lote_actual->tipo_sub == 'V' &&!empty($lote_actual->oferta_asigl0) && ($lote_actual->oferta_asigl0 == 1 || $lote_actual->oferta_asigl0 == 2))
								<span class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.nuestro_precio') }}</span>
							@elseif($lote_actual->tipo_sub == 'V' )
								<span class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</span>
							@else
								<span class="pre">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</span>
							@endif
							<span id="impsalexchange" class="currency-trnas" style="float:right; margin-top:4px; margin-left: 5px;" >
								{{ trans(\Config::get('app.theme').'-app.subastas.dolares') }} {{ $lote_actual->formatted_impsalhces_asigl0}}
							</span>
							<span class="pre" style="float:right;">
								{{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}  |
							</span>

							</div>
						</div>
							</div>


						@if($lote_actual->cerrado_asigl0 == 'S' && $lote_actual->tipo_sub == 'V' )

						@elseif($lote_actual->cerrado_asigl0 == 'S' && ((!empty($precio_venta) &&
						$lote_actual->remate_asigl0 !='S') || $lote_actual->desadju_asigl0 =='S' ))
						<div class="price price-awarded" style="text-align: center;">
							<p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</p>
						</div>
						@elseif($lote_actual->cerrado_asigl0 == 'S' && $lote_actual->remate_asigl0 =='S' &&
						(!empty($precio_venta))|| ($lote_actual->subc_sub == 'H' && !empty($lote_actual->impadj_asigl0))
						)
						@if($lote_actual->subc_sub == 'H' && !empty($lote_actual->impadj_asigl0))
						@php($precio_venta = $lote_actual->impadj_asigl0)
						@endif
						<div class="price prices price-awarded">
							<p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}:
								{{\Tools::moneyFormat($precio_venta)}} €</p>
						</div>
						<div class="price-awarded-salechange prices">
							<p id="impsalexchange-actual-modal" class="" style="margin: 0px; font-size: 14px;"></p>
						</div>

						@elseif(strtoupper($lote_actual->tipo_sub) == 'V' && $lote_actual->cerrado_asigl0 == 'N' &&
						$lote_actual->end_session > date("now"))

						<div class="price" style="text-align: center;">
							<p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</p>
						</div>
						@elseif($lote_actual->cerrado_asigl0 == 'S' && empty($precio_venta) )

						<div class="price text-center">
							<p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>

						</div>
						@elseif($lote_actual->cerrado_asigl0 == 'D')

						<div class="price text-center">
							<p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>

						</div>
						@endif
						{{-- FIN PUJA CERRADA --}}


						@endif

						@endif

					</div>


					<!--- Datos estadisticos -->
					<br>
					<div class="row statics">
						<div class="col-xs-7">

							{{-- <span id="reproducciones"></span>
							{{trans(\Config::get('app.theme').'-app.lot.reproducciones')}} --}}

						</div>
						<div class="col-xs-5 text-right" style="font-style: normal">
							@if ($lote_actual->tipo_sub != 'V')
							<img src="/themes/{{\Config::get('app.theme')}}/assets/img/auction.png" width="16px"
								height="16px" style="margin-right: 2px;" /><span
								class="tot_pujas">{{ $lote_actual->total_pujas }}</span>
							<img src="/themes/{{\Config::get('app.theme')}}/assets/img/man-user.png" width="16px"
								height="16px" style="margin-left: 10px; margin-right: 2px;" /><span
								class="total_postores">{{ $lote_actual->total_postores }}</span>
							@endif
						</div>

					</div>

					{{-- <div class="row megusta-row">
						<div class="col-xs-7 marron">
							@if (!\Session::has('user'))
							<a href="javascript:return(false);" data-toggle="modal" data-target="#modalLogin">
								@else
								<a
									href="javascript:megusta('{{$lote_actual->ref_asigl0}}','{{$lote_actual->cod_sub}}');">
									@endif
									<span id="corazon"></span>

									<span id="megusta"></span>
									{{trans(\Config::get('app.theme').'-app.lot.megusta')}}
								</a>
						</div>
					</div> --}}

				</div>
			</div>

		</div>


	</div>
</div>

</div>
