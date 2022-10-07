<?php

$cerrado = $lote_actual->cerrado_asigl0 == 'S'? true : false;
$cerrado_N = $lote_actual->cerrado_asigl0 == 'N'? true : false;
$hay_pujas = count($lote_actual->pujas) >0? true : false;
$devuelto= $lote_actual->cerrado_asigl0 == 'D'? true : false;
$remate = $lote_actual->remate_asigl0 =='S'? true : false;
$compra = $lote_actual->compra_asigl0 == 'S'? true : false;
$subasta_online = ($lote_actual->tipo_sub == 'P' || $lote_actual->tipo_sub == 'O')? true : false;
$subasta_venta = $lote_actual->tipo_sub == 'V' ? true : false;
$subasta_web = $lote_actual->tipo_sub == 'W' ? true : false;
$subasta_abierta_O = $lote_actual->subabierta_sub == 'O'? true : false;
$subasta_abierta_P = $lote_actual->subabierta_sub == 'P'? true : false;
$retirado = $lote_actual->retirado_asigl0 !='N'? true : false;
$sub_historica = $lote_actual->subc_sub == 'H'? true : false;
$sub_cerrada = ($lote_actual->subc_sub != 'A'  && $lote_actual->subc_sub != 'S')? true : false;
$remate = $lote_actual->remate_asigl0 =='S'? true : false;
$awarded = \Config::get('app.awarded');
// D = factura devuelta, R = factura pedniente de devolver
$fact_devuelta = ($lote_actual->fac_hces1 == 'D' || $lote_actual->fac_hces1 == 'R') ? true : false;
$fact_N = $lote_actual->fac_hces1=='N' ? true : false;
$start_session = strtotime("now") > strtotime($lote_actual->start_session);
$end_session = strtotime("now")  > strtotime($lote_actual->end_session);

$start_orders =strtotime("now") > strtotime($lote_actual->orders_start);
$end_orders = strtotime("now") > strtotime($lote_actual->orders_end);

$titulo = $lote_actual->descweb_hces1;
# lo fuerzo en todas las fichas ya que lo pide el de SEO 02/05/22
	$lote_actual->imgfriendly_hces1=  \Str::slug($titulo);


 #debemos poenr el código aqui par que lo usen en diferentes includes
if($subasta_venta){
	$nameCountdown = "countdown";
	$timeCountdown = $lote_actual->end_session;
}else if($subasta_online){
	$nameCountdown = "countdownficha";
	$timeCountdown = $lote_actual->close_at;
}else {
	$nameCountdown = "countdown";
	$timeCountdown = $lote_actual->start_session;
}

?>


<div class="ficha-content color-letter">

	<div class="container ficha-container">
		<div class="row equal">

					<div class="col-xs-12 col-sm-8 ficha-left" style="position: relative">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-6 ficha-top">
								@if($lote_actual->tipo_sub=="E" )
									@include('includes.ficha.bloque_izquierdoPrivada')
								@else
									@include('includes.ficha.bloque_izquierdo')
								@endif
							</div>


							<div class="galeria-imagenes hidden-xs hidden-sm col-sm-12 col-md-6">
								@if(Session::has('user') &&  !$retirado)
									<div class=" favoritos">
									<a  class="  <?= $lote_actual->favorito? 'hidden':'' ?>" id="add_fav" href="javascript:action_fav_modal('add')">
										<i class="fa fa-heart" aria-hidden="true" ></i>
									</a>
									<a class="  <?= $lote_actual->favorito? '':'hidden' ?>" id="del_fav" href="javascript:action_fav_modal('remove')">
										<i class="fa fa-heart" aria-hidden="true" ></i>
									</a>
									</div>
								@endif

								<div class="row">
									<div class="col-xs-12 no-padding">
										<div id="video_main_wrapper" class="img_single_border video_single_border" style="display:none">
										</div>
										<div id="img_main2" class="img-global-content position-relative slide-imagenes ">
											<a class="jqzoom" rel="gal1" title="{{$titulo}}" alt="{{$titulo}}"
												href="{{Tools::url_img_friendly('real',$lote_actual->num_hces1,$lote_actual->lin_hces1,0,$lote_actual->imgfriendly_hces1)}}">
												@php
													if(!empty($lote_actual->imgfriendly_hces1)){
														$src = Tools::url_img_friendly('lote_large',$lote_actual->num_hces1,$lote_actual->lin_hces1,0,$lote_actual->imgfriendly_hces1);
													}else{
														$src = Tools::url_img('lote_large',$lote_actual->num_hces1,$lote_actual->lin_hces1,null,1);
													}
												@endphp
												<img style=" width: 100%;"
													src="{{$src}}"
													alt="{{$titulo}}"
													title="{{$titulo}}">
											</a>


										</div>
										<div class="col-xs-12 no-padding">
											<div class="minis-content d-flex flex-wrap">




												@foreach($lote_actual->imagenes as $key => $imagen)
													@php
													$active='';
													if($key == 0){
														$active='zoomThumbActive';
													}
													#cargamos la url amigable para la imagen large
													if(!empty($lote_actual->imgfriendly_hces1)){
														$src = Tools::url_img_friendly('lote_large',$lote_actual->num_hces1,$lote_actual->lin_hces1,$key,$lote_actual->imgfriendly_hces1);
													}else{
														$src = Tools::url_img('lote_large',$lote_actual->num_hces1,$lote_actual->lin_hces1,$key,1);
													}
													@endphp
													<div class="mini-img-ficha no-360">
														<a class="{{$active}}" href="javascript:void(0);"
															rel="{gallery: 'gal1', smallimage: '{{$src}}',largeimage: '{{Tools::url_img('real',$lote_actual->num_hces1,$lote_actual->lin_hces1,$key,1)}}'}">
															<div class=" img-thumbs-ficha js_img_mini"
																style="background-image:url('{{ \Tools::url_img("lote_small", $lote_actual->num_hces1, $lote_actual->lin_hces1, $key, 1) }}'); background-size: contain; background-position: center; background-repeat: no-repeat;"
																alt="{{$lote_actual->titulo_hces1}}"></div>
														</a>
													</div>
												@endforeach

												@if(!empty($lote_actual->videos) && count($lote_actual->videos) > 0)
													@foreach($lote_actual->videos as $key => $video)
													<div class="col-sm-3-custom thumnails">
														<a class=" video-thumbs" href="javascript:void(0);" data-video ='{{ $video }}'>
															<img class="img-openDragon" src="/default/img/icons/video.png"  style="width: 50px;margin-top: 30px;" />
														</a>
													</div>
													@endforeach
												@endif
											</div>
										</div>
									</div>

									@if($lote_actual->ministerio_hces1=='S')
									<div class="col-xs-12 pt-2">
										{!! trans(\Config::get('app.theme').'-app.lot.info-ministerio') !!}
									</div>
									@endif





								</div>


							</div>
							@if($lote_actual->tipo_sub !="E")
								<div class="recomendados-div col-xs-12">
									<div class="bloque-lotes-recomendados pl-1">
										<div class="row">
											<div class="single">
												<div class="col-xs-12 col-md-7">
												</div>


												<div class="col-xs-12 col-sm-12 lotes_destacados">
													<div class="mas-pujados-title color-letter"><span> {{ trans(\Config::get('app.theme').'-app.artist.relatedLots') }}</span>
													</div>

													<div class='loader hidden'></div>

													<div id="lotes_recomendados" class="owl-theme owl-carousel"></div>

												</div>
											</div>
										</div>
									</div>
								</div>

							@endif

						</div>
						<?php
							$key = "lotes_recomendados";
							$replace = array(
								'emp' => Config::get('app.emp') ,
								'sec_hces1' => $lote_actual->sec_hces1,
								'num_hces1' => $lote_actual->num_hces1,
								'lin_hces1' => $lote_actual->lin_hces1,
								'lang' => Config::get('app.language_complete')[''.Config::get('app.locale').''] ,
							);
							$lang = Config::get('app.locale');

						?>




					</div>
			@if($lote_actual->tipo_sub =="E")
				<div class="col-sm-4 col-xs-12 content-right-ficha d-flex justify-content-space-between flex-column">
					<div class="d-flex flex-column bloque-fijo ficha-info-type-auction">
						@include('includes.ficha.pujas_fichaPrivada')
					</div>
				</div>
			@else
					<div class="col-sm-4 col-xs-12 content-right-ficha d-flex justify-content-space-between flex-column">

								<div class="d-flex flex-column bloque-fijo ficha-info-type-auction">
									<div class="row d-flex align-items-baseline hidden-xs hidden-sm">
										<div class="col-md-8">
											<div
												class="info-type-auction @if($subasta_online) sub-online @elseif($subasta_web) sub-web @elseif($subasta_venta) sub-venta @endif">

												@if($subasta_online)
												{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_online') }}
												@elseif($subasta_web)
												{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_presencial') }}
												@elseif($subasta_venta)
												{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_venta') }}
												@endif
											</div>
										</div>
										{{-- De moment ose oculta
												<div class="col-md-4 text-right">
														<div class="seguir-lotes d-flex align-items-center justify-content-flex-end">
														<a href="">  {{ trans(\Config::get('app.theme').'-app.lot.seguir_similares') }} </a>
													<i class="fas fa-info-circle"></i>
												</div>
											</div>
											--}}
									</div>
							@if( $subasta_web)
								@php #si es web queremos que muestre el nombre de la subastas, si no n oes necesario por que seran permanentes
								@endphp
								<div class="titulo-subasta hidden-xs hidden-sm">
									<span>{{ $lote_actual->name}}</span>
								</div>
							@endif
							@if(!$subasta_venta)
							<div class="info_single_title info-type-auction-title no-padding d-flex justify-content-space-between">
								<div class="no-padding">
									<div class="fecha-subasta">
										@if($subasta_web)
										{{ trans(\Config::get('app.theme').'-app.lot.subasta_empieza') }}
										@else
										{{ trans(\Config::get('app.theme').'-app.lot.subasta_cierra') }}
										@endif
									</div>
								</div>
								<div class="div-fecha pr-2 text-right">
									<div class="ficha-info-close-lot ">
										<div class="date_top_side_small">
											<span id="cierre_lote"></span>
										</div>
									</div>

									@if($cerrado_N && $subasta_online && !empty($timeCountdown) && strtotime($timeCountdown) >
									getdate()[0])
									<div class="col-xs-12 ficha-info-clock no-padding">
										<span class="clock">
											<i class="fas fa-clock"></i>
											<span data-{{$nameCountdown}}="{{ strtotime($timeCountdown) - getdate()[0] }}"
												data-format="<?= \Tools::down_timer($timeCountdown); ?>" class="timer">
											</span>
										</span>
									</div>
									@elseif($cerrado)
									<div class="info-type-auction finalizado">
										{{ trans(\Config::get('app.theme').'-app.subastas.finalized') }}</div>

									@endif


								</div>
							</div>
							@endif

							<div class="ficha-info-content col-xs-12 no-padding flex-column justify-content-center d-flex">


								@if(!$retirado && !$devuelto && !$fact_devuelta)
								<div class="ficha-info-items">


									@if ($sub_cerrada)
										@include('includes.ficha.pujas_ficha_cerrada')
									@elseif($subasta_venta && !$cerrado && !$end_session)
											@if( \Config::get("app.shoppingCart") )
												@include('includes.ficha.pujas_ficha_ShoppingCart')
											@else
												@include('includes.ficha.pujas_ficha_V')
											@endif
									<?php //si un lote cerrado no se ha vendido se podra comprar ?>
									@elseif( ($subasta_web || $subasta_online) && $cerrado && empty($lote_actual->himp_csub) && $compra	&&	!$fact_devuelta)

									@include('includes.ficha.pujas_ficha_V')
									<?php //si una subasta es abierta p solo entraremso a la tipo online si no esta iniciada la subasta ?>
									@elseif( ($subasta_online || ($subasta_web && $subasta_abierta_P && !$start_session)) && !$cerrado)
									@include('includes.ficha.pujas_ficha_O')

									@elseif( $subasta_web && !$cerrado)
									@include('includes.ficha.pujas_ficha_W')


									@else
									@include('includes.ficha.pujas_ficha_cerrada')
									@endif


									<div class="terminos">{!! trans(\Config::get('app.theme').'-app.lot.terminos_condiciones') !!}</div>

									@if(!Session::has('user') && !$cerrado && !$subasta_venta)
									<div class="info-compra">{!! trans(\Config::get('app.theme').'-app.msg_error.mustLogin') !!}</div>
									@endif
									<div class="metodos-pago">

										<a href="javascript:;" data-toggle="modal" data-target="#modalAjax"
											class="info-ficha-lot c_bordered"
											data-ref="{{ Routing::translateSeo('pagina')."info-metodos-pago"  }}?modal=1"
											data-title="{{ trans(\Config::get('app.theme').'-app.lot.title_info_metodos_pago') }}"><i
												class="fas fa-info-circle"></i></a>
										<p>{{ trans(\Config::get('app.theme').'-app.lot.metodos_pago') }}</p>
										<i class="fab fa-cc-visa"></i>
										<i class="fab fa-cc-mastercard"></i>
										<span>{!! trans(\Config::get('app.theme').'-app.lot.transferencia') !!}</span>
										<span style='margin-left: 15px;'><img src="/default/img/logos/bizum-ico.png" style="height: 35px;"> <strong>	Bizum </strong>
									</div>
								</div>
								@endif
								<div class="col-xs-12 col-sm-12 no-padding">
									<?php /*
										@if(( $subasta_online  || ($subasta_web && $subasta_abierta_P )) && !$cerrado &&  !$retirado)
											@include('includes.ficha.history')
										@endif
										*/
									?>
								</div>
							</div>
						</div>
					</div>
			@endif
		</div>
	</div>
</div>

@if( ($subasta_online || ($subasta_web && $subasta_abierta_P && !$start_session)) && !$cerrado && (!$retirado && !$devuelto && !$fact_devuelta))
<div class="visible-xs-block d-flex  flex-column bloque-fijo-mobile">

	<div class="info_single_title info-type-auction-title no-padding d-flex justify-content-space-between">
		<div class="no-padding">
			<div class="fecha-subasta">La subasta cierra</div>
		</div>
		<div class="div-fecha text-right">
			<div class="ficha-info-close-lot ">
				<div class="date_top_side_small">
					<span id="cierre_lote"></span>
				</div>
			</div>


			@if($cerrado_N && !empty($timeCountdown) && strtotime($timeCountdown) > getdate()[0])
			<div class="col-xs-12 ficha-info-clock no-padding">
				<span class="clock">
					<i class="fas fa-clock"></i>
					<span data-{{$nameCountdown}}="{{ strtotime($timeCountdown) - getdate()[0] }}"
						data-format="<?= \Tools::down_timer($timeCountdown); ?>" class="timer">
					</span>
				</span>
			</div>
			@elseif($cerrado)
			<div class="info-type-auction finalizado">{{ trans(\Config::get('app.theme').'-app.subastas.finalized') }}
			</div>

			@endif

		</div>
	</div>

	<div class="ficha-info-content col-xs-12 no-padding h-100 flex-column justify-content-center d-flex">
		<div class="ficha-info-items">
			@include('includes.ficha.pujas_ficha_O')
		</div>
	</div>
</div>
@endif

<script>

    $( document ).ready(function() {
		@if($lote_actual->tipo_sub !="E")
			var replace = <?= json_encode($replace) ?>;
			var key ="<?= $key ?>";
			ajax_newcarousel(key,replace, '{{ $lang }}');
		@endif
           //Cargar tiempo
            $("#cierre_lote").html(format_date_large(new Date("{{$timeCountdown}}".replace(/-/g, "/")),''));


			$('.jqzoom').jqzoom({
				zoomType: 'standard',
				lens: true,
				preloadImages: false,
				alwaysOn: false,
				xOffset: 45,
				//xOffset:60,
				zoomWidth: $('.bloque-izquierda-1').width(),
				zoomHeight: 500,

				position: "left"
			});

			$('.video-thumbs').on("click", function(){
				let videoHref = $(this).data("video");
				$('#video_main_wrapper').empty();
				$('#img_main2').hide();
				$videoDom = $('<video width="100%" height="auto" controls>').append($(`<source src="${videoHref}">`));
				$('#video_main_wrapper').append($videoDom);
				$('#video_main_wrapper').show();
			} )

			$('.js_img_mini').on("click", function(){

				$('#video_main_wrapper').empty();
				$('#video_main_wrapper').hide();
				$('#img_main2').show();

			} )








    });



</script>




@include('includes.ficha.modals_ficha')
