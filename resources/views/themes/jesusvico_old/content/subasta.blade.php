<script>
	var cod_sub = '{{$data["cod_sub"]}}';
routing.node_url 	 = '{{ Config::get("app.node_url") }}';
routing.comprar		 = '{{ $data["node"]["comprar"] }}';
routing.ol		 = '{{ $data["node"]["ol"] }}';
</script>

<form id="form_lotlist" method="get" action="{{ $data['url'] }}">
	<div class="auction-lots min-height">
		<div class="container">
			<div class="row" style="position: relative">
				<div class="order-views col-xs-12 no-padding">

					<div class="row grid-flex">
						<div class="col-xs-12 col-lg-3 mt-xs-10">
							@if(!empty( $data['subastas']) && $data['subastas'][0]->tipo_sub == 'W' &&
							($data['subastas'][0]->subc_sub == 'A' ||$data['subastas'][0]->subc_sub == 'S' ) &&
							strtotime($data['subastas'][0]->start_session) > time())
							<div class="widget timeLeft">
								<i class="fas fa-clock"></i>
								<span
									data-countdown="{{ strtotime($data['subastas'][0]->start_session) - getdate()[0] }}"
									data-format="<?= \Tools::down_timer($data['subastas'][0]->start_session); ?>"
									data-closed="{{ $data['subastas'][0]->cerrado_asigl0 }}" class="timer"></span>
								<span class="clock"></span>
							</div>
							@endif


							<?php
						$subasta_finalizada = false;
						if(!empty($data['sub_data'])){
							//ver si la subasta está cerrada
							$SubastaTR      = new \App\Models\SubastaTiempoReal();
							$SubastaTR->cod = $data['sub_data']->auction;
							$SubastaTR->session_reference =  $data['sub_data']->reference; //$subasta->get_reference_auc_session($subasta->id_auc_sessions);
							$status  = $SubastaTR->getStatus();

							if(!empty($status) && $status[0]->estado == "ended" ){
								$subasta_finalizada = true;

							}
						}
					?>
							@if(!empty($data['sub_data']) && $data['sub_data']->tipo_sub =='W' &&
							strtotime($data['sub_data']->end) > time() && strtotime($data['sub_data']->start) < time()
								&& $subasta_finalizada==false) <?php
							//en caso de que este el tiempo real pujando en ese momento, activamos un texto que le
							//avisa al cliente y lo dirige a pujar en vivo.
						$url_tiempo_real = \Tools::url_real_time_auction($data['sub_data']->cod_sub,$data['sub_data']->name,$data['sub_data']->id_auc_sessions);
						?> <div class=" widget full-screen d-inline-flex" style="position: relative">
								<div class="bid-online"></div>
								<div class="bid-online animationPulseRed"></div>
								<a href="{{ $url_tiempo_real }}" target="_blank"
									class="bid-live grid-icon-square color-letter d-flex">{{ trans(\Config::get('app.theme').'-app.lot_list.bid_live') }}</a>
						</div>
						@endif

						<?php
                $inf_subasta = new \App\Models\Subasta();
                if(!empty($data['sub_data'])){
                    $inf_subasta->cod = $data['sub_data']->cod_sub;
                }else{
                    $inf_subasta->cod = $data['cod_sub'];
                }
                $ficha_subasta=$inf_subasta->getInfSubasta();
            ?>
				@include('includes.subasta_filters')
					</div>



					<div class="col-xs-12 col-lg-9 fullscreen-content">

						<div class="col-xs-12">
							{!! \BannerLib::bannersPorKey('banner_lotes', 'banner_lotes') !!}
						</div>
						<br>

						<div class="col-xs-12 d-flex views widgets-auction pull-right">

							<!-- Paginador -->
							<div class="d-inline-flex flex-grow2">
								<?php
							$paginator = $data['subastas.paginator'];
							$paginator->setMaxPagesToShow(8);
							echo $paginator; ?>
							</div>

							<?php
							/* Se decide quitar el desplegable para la selección de lotes por página
							<div class="input-order-quantity hidden-xs d-flex align-items-center views-content widget">
								<label>{{ trans(\Config::get('app.theme').'-app.lot_list.to_show') }}</label>
								<select name="total" class="form-control submit_on_change">
									@foreach (\Config::get('app.filter_total_shown_options') as $option)
									<option value="{{ $option }}" @if (app('request')->input('total') == $option)
										selected
										@endif >
										{{ $option }}
									</option>
									@endforeach
								</select>
							</div>
							*/
							?>

							<?php // si es uan subasta w y abierta o si es uan subasta tipo O o P ?>
							@if(!empty( $data['subastas']) && ( ($data['subastas'][0]->tipo_sub == 'W' &&
							$data['subastas'][0]->subabierta_sub == 'S') || $data['subastas'][0]->tipo_sub == 'P' ||
							$data['subastas'][0]->tipo_sub == 'O' ) && ($data['subastas'][0]->subc_sub == 'A'
							||$data['subastas'][0]->subc_sub == 'S' ) )
							<div class="full-screen widget d-inline-flex">
								<a class="refresh d-block color-letter" href="">
									{{ trans(\Config::get('app.theme').'-app.lot_list.refresh_prices') }} <i
										class="fa fa-refresh" aria-hidden="true"></i></a>
							</div>
							@endif
							@if(!empty($data['sub_data']) && !empty($data['sub_data']->opcioncar_sub &&
							!empty($data['subastas'][0])) && $data['sub_data']->opcioncar_sub == 'S' &&
							strtotime($data['subastas'][0]->start_session) > time())
							<div class="full-screen widget d-inline-flex">
								@if(Session::has('user'))
								<i class="fa fa-gavel  fa-1x"></i> <a
									href="{{ \Routing::slug('user/panel/modification-orders') }}?sub={{$data['sub_data']->cod_sub}}"><?= trans(\Config::get('app.theme').'-app.lot_list.ver_ofertas') ?></a>
								@endif
							</div>
							@endif

							<div class="views-content d-inline-flex hidden-xs hidden-md">
								<a id="square" class="grid-icon-square color-letter d-block" href="javascript:;"><i
										class="fas fa-th"></i></a>
								<a id="large_square" class="grid-icon-square d-block color-letter"
									href="javascript:;"><i class="fas fa-bars"></i></a>
							</div>
							<div class="hidden-xs widget full-screen d-inline-flex">
								<a id="full-screen" class="grid-icon-square color-letter d-flex" href="javascript:;"><i
										class="fas fa-expand"></i></a>
							</div>

						</div>


						<div class="row">

							<div class="col-xs-12 list_lot_content mt-3">
								<div class="list_lot">
									@foreach ($data['subastas'] as $key => $item)
									<?php

									$titulo ="$item->ref_asigl0  -  $item->descweb_hces1";
									$cerrado = $item->cerrado_asigl0 == 'S'? true : false;
									$hay_pujas = !empty($item->max_puja)? true : false;
									$devuelto= $item->cerrado_asigl0 == 'D'? true : false;
									$remate = $item->remate_asigl0 =='S'? true : false;
									$subasta_online = ($item->tipo_sub == 'P' || $item->tipo_sub == 'O')? true : false;
									$subasta_venta = $item->tipo_sub == 'V' ? true : false;
									$subasta_web = $item->tipo_sub == 'W' ? true : false;
									$subasta_abierta_O = $item->subabierta_sub == 'O'? true : false;
									$subasta_abierta_P = $item->subabierta_sub == 'P'? true : false;
									$sub_cerrada = ($item->subc_sub != 'A'  && $item->subc_sub != 'S')? true : false;
									$retirado = $item->retirado_asigl0 !='N'? true : false;
									$sub_historica = $item->subc_sub == 'H'? true : false;
									$remate = $item->remate_asigl0 =='S'? true : false;
									$awarded = \Config::get('app.awarded');
									// D = factura devuelta, R = factura pedniente de devolver
									$fact_devuelta = ($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R') ? true : false;
									$precio_salida = $item->impsalweb_asigl0 != 0 ? $item->formatted_impsalweb_asigl0 : $item->formatted_impsalhces_asigl0;
									$compra = $item->compra_asigl0 == 'S'? true : false;
									$start_session = strtotime("now") > strtotime($item->start_session);
									$end_session = strtotime("now")  > strtotime($item->end_session);



										$url = "";
										//Si no esta retirado tendrá enlaces
										if(!$retirado && !$fact_devuelta ){
											$webfriend = !empty($item->webfriend_hces1)? $item->webfriend_hces1 :  str_slug($item->titulo_hces1);
											if($data['type'] == "theme"){
												$url_vars = "?theme=".$data['theme'];
											}else{
												$url_vars ="";
											}
											$url_friendly = \Tools::url_lot($item->cod_sub,$item->id_auc_sessions,$item->name,$item->ref_asigl0,$item->num_hces1,$item->webfriend_hces1,$item->titulo_hces1);
											$url = "href='$url_friendly'";
										}


										$precio_venta=NULL;
										if (!empty($item->himp_csub)){
												$precio_venta=$item->himp_csub;
										}
										//si es un histórico y la subasta del asigl0 = a la del hces1 es que no está en otra subasta y podemso coger su valor de compra de implic_hces1
										elseif($item->subc_sub == 'H' && $item->cod_sub == $item->sub_hces1 && $item->lic_hces1 == 'S' and $item->implic_hces1 >0){
												$precio_venta = $item->implic_hces1;
										}

										//Si hay precio de venta y  impsalweb_asigl0 contiene valor, mostramos este como precio de venta
										$precio_venta = (!empty($precio_venta) && $item->impsalweb_asigl0 != 0) ? $item->impsalweb_asigl0 : $precio_venta;

										$winner = "";
										if($subasta_web && $subasta_abierta_O){
											//si el usuario actual es el
											if(isset($data['js_item']['user']) && count($item->ordenes) > 0 && head($item->ordenes)->cod_licit == $data['js_item']['user']['cod_licit']){
												$winner = "winner";
											}
											//si hay usuario conectado pero no es el ganador, y hay ordenes
											elseif(isset($data['js_item']['user']) && count($item->ordenes) > 0){
												$winner = "no_winner";
											}
										}
										else if ($subasta_online || ($subasta_web && $subasta_abierta_P)){
											 if(isset($data['js_item']['user']) && $hay_pujas && $item->max_puja->cod_licit == $data['js_item']['user']['cod_licit']){
												$winner = "winner";
											}
											//si hay usuario conectado pero no es el ganador, y hay ordenes
											elseif(isset($data['js_item']['user']) && $hay_pujas){
												$winner = "no_winner";
											}
										}
										$img = Tools::url_img('lote_medium',$item->num_hces1,$item->lin_hces1);


										$class_square = 'col-xs-12 col-sm-6 col-lg-4';
									?>
									@include('includes.lotlist')
									<?php
										$class_square = 'col-xs-12';
									?>
									@include('includes.lotlist_large')
									<?php
										$class_square = 'col-xs-4 col-sm-3 col-md-2';
									?>
									@include('includes.lotlist_mini')
									@endforeach

								</div>

									<!-- Paginador -->
								<div class="text-center">
									{!! $paginator !!}
								</div>

							</div>
						</div>



					</div>
				</div>


			</div>

		</div>
	</div>
	</div>

	<div class="container">

	</div>

	@if(!empty($data['seo']->meta_content) && $data['subastas.paginator']->currentPage == 1)
	<div class="container category">
		<div class="row">
			<div class="col-lg-12">
				<?= $data['seo']->meta_content?>
			</div>
		</div>
	</div>
	@endif
</form>
