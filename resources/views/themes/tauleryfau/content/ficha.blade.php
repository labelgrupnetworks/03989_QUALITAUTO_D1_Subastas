<?php
use App\libs\MobileDetect;
$MobileDetect = new MobileDetect();

//   capturando la conversion de la moneda
$moneda = \Tools::conservationCurrency($data['subasta_info']->lote_actual->num_hces1, $data['subasta_info']->lote_actual->lin_hces1, ['conservation_1', 'conservation_2']);
$existMoneda = !empty($moneda) && !empty($moneda->conservation_1);
//condición subasta O/W
$conservationW = $lote_actual->cerrado_asigl0 == 'N' && $lote_actual->fac_hces1 == 'N' && strtotime('now') < strtotime($lote_actual->start_session);
//condición subasta V
$conservationV = $lote_actual->retirado_asigl0 == 'N' && empty($lote_actual->himp_csub) && ($lote_actual->subc_sub == 'S' || $lote_actual->subc_sub == 'A') && ($lote_actual->tipo_sub != 'V' && !empty($moneda) && !empty($moneda->conservation_1));

$minMaxLot = \App\Models\V5\FgAsigl0::joinSessionAsigl0()
    ->where('SUB_ASIGL0', $lote_actual->sub_hces1)
    ->selectRaw(' MIN(ref_asigl0) AS min, MAX(ref_asigl0) AS max')
    ->get();

?>
<style>
	.class3 {
		width: calc(63px * 5.4) !important;
	}
</style>
<script src="https://hammerjs.github.io/dist/hammer.min.js"></script>
<script src="/vendor/photoswipe/photoswipe.umd.min.js"></script>
<script src="/vendor/photoswipe/photoswipe-lightbox.umd.min.js"></script>
<link href="/vendor/photoswipe/photoswipe.css" rel="stylesheet">

<input type="hidden" name="_token" id="token" value="{{ Session::token() }}" />
<section class="title-ficha">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="title-wrapper valign">
					<div class="row">
						<div class="col-xs-12 col-md-7 col-lg-8">
							<div class="title-lot">
								<h2>
									@if (\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1'))
										{{ trans($theme . '-app.lot.lot-name') }}
										{{ $lote_actual->ref_asigl0 }} - {{ $lote_actual->titulo_hces1 }}
									@elseif(!\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1'))
										{{ $lote_actual->titulo_hces1 }}
									@elseif(\Config::get('app.ref_asigl0'))
										{{ trans($theme . '-app.lot.lot-name') }}
										{{ $lote_actual->ref_asigl0 }}
									@endif
								</h2>
							</div>
						</div>
						<div class="col-xs-12 col-md-5 col-lg-4 hidden-xs hidden-sm">
							<div class="nav-next">
								<div class="prev-lot">
									@if (!empty($data['previous']))
										<a class="nextLeft" title="{{ trans($theme . '-app.subastas.last') }}" href="{{ $data['previous'] }}">
											<span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
											<span>{{ trans($theme . '-app.subastas.last') }}</span>
										</a>
									@endif
								</div>
								<div class="next-lot">
									@if (!empty($data['next']))
										<a class="nextRight" title="{{ trans($theme . '-app.subastas.next') }}" href="{{ $data['next'] }}">
											<span>{{ trans($theme . '-app.subastas.next') }}</span>
											<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
										</a>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="body-ficha">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-7 col-lg-8">
				<div class="single-lot">
					@if (
						$lote_actual->tipo_sub == 'V' &&
							!empty($lote_actual->oferta_asigl0) &&
							($lote_actual->oferta_asigl0 == 1 || $lote_actual->oferta_asigl0 == 2))
						<?php
						$porcentaje = 0;
						if ($lote_actual->imptas_asigl0 > 0) {
						    $porcentaje = round((($lote_actual->imptas_asigl0 - $lote_actual->impsalhces_asigl0) / $lote_actual->imptas_asigl0) * 100, 0);
						}
						$porcentaje = round((($lote_actual->imptas_asigl0 - $lote_actual->impsalhces_asigl0) / $lote_actual->imptas_asigl0) * 100, 0);
						if ($porcentaje > 19) {
						    $class_color = 'd-50';
						} elseif ($porcentaje <= 19 && $porcentaje > 0) {
						    $class_color = 'd-10';
						} else {
						    $class_color = 'd-20';
						}

						?>
						@if ($lote_actual->oferta_asigl0 == 2)
							@php($class_color = 'hot-sale')
							@endphp
						@endif
						@if ($lote_actual->oferta_asigl0 == 1)
							<div class="discount {{ $class_color }}">
								<div>{{ $porcentaje }}%</div>
								<div>{{ trans($theme . '-app.lot_list.discount') }}</div>
							</div>
						@elseif($lote_actual->oferta_asigl0 == 2)
							<div class="discount {{ $class_color }}">
								<div>{{ trans($theme . '-app.lot_list.gran') }}</div>
								<div>{{ trans($theme . '-app.lot_list.hot_sale') }}</div>
							</div>
						@endif
					@endif


					<div class="b360-responsive hidden-sm hidden-md hidden-lg" style=""></div>


					<div id="toolbarDiv">
						<div role="" class="chevron-left-button">
							<i class="fa fa-2x fa-chevron-left" id="chevron-left"></i>
						</div>
						<div role="" class="chevron-right-button">
							<i class="fa fa-2x fa-chevron-right" id="chevron-right"></i>
						</div>
					</div>

					<div id="img_main" class="img-single-lot flex valign hidden-xs hidden-sm hidden-md"></div>

					@if (count($lote_actual->videos ?? []) > 0 && !$MobileDetect->is('iOS'))
						@foreach ($lote_actual->videos as $key => $video)
							<video playsinline loop controls id='video<?= $key ?>'
								style="position:absolute;top:-1000px;width:1px;height:1px;">
								<source src="{{ $video }}" type="video/mp4">
							</video>
						@endforeach
					@endif

					<div class="ficha-lot-galery owl-theme owl-carousel visible-xs visible-sm visible-md" id="owl-carousel-responsive-ficha">
						@if(count($lote_actual->imagenes) > 0)
						@foreach($lote_actual->imagenes as $key => $imagen)
						@php
							$imageUrlCompressed = Tools::url_img('lote_medium_large', $lote_actual->num_hces1, $lote_actual->lin_hces1, $key);
							$imageUrlReal = Config::get('app.url').Tools::url_img('real', $lote_actual->num_hces1, $lote_actual->lin_hces1, $key);
							$imageSize = getimagesize($imageUrlReal);
						@endphp
						<a class="d-block" data-pswp-width="{{ $imageSize[0] }}" data-pswp-height="{{ $imageSize[1] }}"
							href="{{ $imageUrlCompressed }}">
							<div class="item_content_img_single" style="position: relative; height: 250px; overflow: hidden;">
								<img loading="lazy" style="max-width: 100%; max-height: 190px;top: 50%; transform: translateY(-50%); position: relative; width: auto !important; display: inherit !important; margin: 0 auto !important;"
									class="img-responsive" data-pos="{{ $key }}"
									src="{{ $imageUrlCompressed }}"
									alt="{{$lote_actual->titulo_hces1}}">

							</div>
						</a>
						@endforeach
						@endif

					</div>

					@if(count($lote_actual->imagenes) > 0)
						<div class="image-lot-miniature-container" style="display: none">
							@foreach($lote_actual->imagenes as $key => $imagen)
								<a class="image-selector" data-key-image="{{ $key }}">
									<img class="micro-image" src="{{ Tools::url_img('lote_medium', $lote_actual->num_hces1, $lote_actual->lin_hces1, $key) }}">
								</a>
							@endforeach
						</div>
					@endif

					<div class="col-xs-12 no-padding hidden-lg">
						<div class="btn-responsive flex">
							@if ($lote_actual->retirado_asigl0 == 'N')
								<div class="btn-add-fav flex valign">
									<div class="loaderheart hidden"><i class="fas fa-star heart active"></i></div>
									<a
										class="inline-flex valign btn add_fav-responsive <?= $data['subasta_info']->lote_actual->favorito ? 'hidden' : '' ?>"
										href="javascript:action_fav_modal('add')">
										<i class="far fa-star"></i>
									</a>
									<a
										class="inline-flex valign btn del_fav-responsive <?= $data['subasta_info']->lote_actual->favorito ? '' : 'hidden' ?>"
										href="javascript:action_fav_modal('remove')">
										<i class="fas fa-star heart"></i>
									</a>
								</div>
							@endif
							@if (count($lote_actual->videos ?? []) > 0)
								<a
									href="javascript:loadVideoMobileGrid('{{ $lote_actual->videos[0] }}', '{{ $lote_actual->ref_asigl0 }}', '{{ $lote_actual->cod_sub }}')">
									<button class="currency-show-button video-button">
										{{ trans($theme . '-app.lot.pic360') }}
									</button>
								</a>
							@endif


							<div id="btn-360Responsive" class="btn-360 hidden">
								<div class="btn-360-logo flex valign">
									<div class="btn-360-text">{{ trans($theme . '-app.lot.photo') }}
									</div>
									<div class="btn-360-close">{{ trans($theme . '-app.lot.close') }}
									</div>
									<i class="fas fa-2x fa-times" style="display:none"></i>
									<svg version="1.1" id="Layer_1" xmlns="https://www.w3.org/2000/svg"
										xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 480 480"
										style="enable-background:new 0 0 480 480;" xml:space="preserve">
										<style type="text/css">
											.st0 {
												font-family: 'Lato-Black';
											}

											.st1 {
												font-size: 146px;
											}
										</style>
										<g>
											<g>
												<g>
													<path
														d="M391.5,210.7c-5.3-1.5-10.8,1.6-12.4,6.9c-1.5,5.3,1.6,10.8,6.9,12.4c45.6,13.1,74,32,74,49.4
																																																								c0,12.8-15.5,26.7-42.6,38.4c-29.8,12.8-72,22.2-118.9,26.4l-41-0.6c0,0-0.1,0-0.1,0c-5.5,0-9.9,4.4-10,9.9
																																																								c-0.1,5.5,4.3,10.1,9.9,10.1l41.5,0.6c0,0,0.1,0,0.1,0c0.3,0,0.6,0,0.9,0c49.2-4.4,93.8-14.3,125.5-28
																																																								c45.2-19.5,54.6-41.6,54.6-56.8C480,251.4,448.6,227,391.5,210.7z">
													</path>
													<path d="M182.9,305.5c-3.6-4.2-9.9-4.7-14.1-1.2c-4.2,3.6-4.7,9.9-1.2,14.1l22.4,26.4c-47.2-3.6-89.9-12.2-121.4-24.6
																																																								C37.7,308.1,20,293.2,20,279.4c0-16,23.7-33.3,63.5-46.2c5.3-1.7,8.1-7.3,6.4-12.6c-1.7-5.3-7.3-8.1-12.6-6.4
																																																								c-23.1,7.5-41.5,16.4-54.5,26.5C7.7,252.4,0,265.4,0,279.4c0,23.1,21.2,43.7,61.2,59.5c32.6,12.8,76.2,21.9,124.2,25.8
																																																								l-19.9,22.1c-3.7,4.1-3.4,10.4,0.7,14.1c1.9,1.7,4.3,2.6,6.7,2.6c2.7,0,5.5-1.1,7.4-3.3l36.4-40.3c1.7-1.8,2.6-4.2,2.6-6.7v-0.8
																																																								c0-2.4-0.8-4.7-2.4-6.5L182.9,305.5z"></path>
													<text transform="matrix(1 0 0 1 105 226)" class="st0 st1">360º
													</text>
												</g>
											</g>
										</g>
									</svg>
								</div>
							</div>


							<div class="currency-show-container">
								@if ($existMoneda && ($conservationW || $conservationV))
									<button data-toggle="modal" data-target="#currency-types" class="currency-show-button">
										{{ !empty($moneda->conservation_2) ? $moneda->conservation_1 . ' / ' . $moneda->conservation_2 : $moneda->conservation_1 }}
										<a data-toggle="modal" data-target="#currency-types" href=""><span><i
													class="fa fa-info fa-lg"></i></span></a>
									</button>
								@endif
							</div>
							@if (!empty($lote_actual->descdet_hces1))
								<div class="btn-context hidden flex valign">
									<a>
										<p><?= trans($theme . '-app.lot.context') ?></p>
									</a>
								</div>
							@endif
						</div>
					</div>
					<div class="single-lot-bar inline-flex valign hidden-xs hidden-sm hidden-md">
						<div class="slider-images-mini">

							<div class="btn-slider-mini">
								<div class="btn-left d-flex align-items-center" style="display: none"><i
										class="fa fa-chevron-circle-left"></i></div>

								@if (count($lote_actual->imagenes) > 3)
									<div class="btn-right d-flex align-items-center"><i class="fa fa-chevron-circle-right"></i></div>
								@endif
							</div>
							@if (count($lote_actual->imagenes) > 1 || count($lote_actual->videos ?? []) > 0)
								<div class="carousel-img-btn inline-flex"
									style="<?= !empty($lote_actual->contextra_hces1) ? 'width: calc( 63px * 7);' : '' ?>">
									<div class="img-thumnail inline-flex valign">
										@if (count($lote_actual->imagenes) > 1)
											@foreach ($lote_actual->imagenes as $key => $imagen)
												<div class="col-sm-3-custom">
													{{-- <a href="javascript:loadSeaDragon('{{ $imagen }}', {{ $key }});"> --}}
													<div class="img-openDragon img-thumb-item flex valign" data-pos="{{ $key }}">
														<img loading="lazy"
															src="{{ \Tools::url_img('lote_small', $lote_actual->num_hces1, $lote_actual->lin_hces1, $key) }}"
															data-image="{{ $imagen }}">
													</div>
													{{-- </a> --}}
												</div>
											@endforeach
										@endif
									</div>
								</div>
							@endif
						</div>
						<div class="btn-single-lot inline-flex">

							<!-- Video -->
							@if (count($lote_actual->videos ?? []) > 0)
								<a
									href="javascript:loadVideo('{{ $lote_actual->videos[0] }}','{{ $lote_actual->ref_asigl0 }}','{{ $lote_actual->cod_sub }}');"
									class="video-btn flex valign">
									{{-- <img src="{{ asset('/themes/tauleryfau/assets/img/play.png') }}" style="border: 1px
								solid #b79d81"/> --}}
									<p>{{ trans($theme . '-app.lot.pic360') }}</p>
								</a>
							@endif

							<!-- 360 -->
							<div id="btn-360" class="btn-360 flex animation-parpadeo valign hidden" data-toggle="modal"
								data-target="#modal360">
								<div class="d-flex flex-column justify-content-center alig-items-center cube">
									<i class="fas fa-2x fa-arrow-up"></i>
								</div>
								<div class="btn-360-logo flex valign">
									<svg fill="#fff" height="24" viewBox="0 0 24 24" width="24" xmlns="https://www.w3.org/2000/svg">
										<path d="M0 0h24v24H0V0zm0 0h24v24H0V0z" fill="none" />
										<path
											d="M7.47 21.49C4.2 19.93 1.86 16.76 1.5 13H0c.51 6.16 5.66 11 11.95 11 .23 0 .44-.02.66-.03L8.8 20.15l-1.33 1.34zM12.05 0c-.23 0-.44.02-.66.04l3.81 3.81 1.33-1.33C19.8 4.07 22.14 7.24 22.5 11H24c-.51-6.16-5.66-11-11.95-11zM16 14h2V8c0-1.11-.9-2-2-2h-6v2h6v6zm-8 2V4H6v2H4v2h2v8c0 1.1.89 2 2 2h8v2h2v-2h2v-2H8z" />
									</svg>
								</div>
								<span>{{ trans($theme . '-app.lot.pic360') }}</span>
							</div>

							<!-- Conservación -->
							@if ($existMoneda && ($conservationW || $conservationV))
								<button id="currency-show-button" data-toggle="modal" data-target="#currency-types"
									class="currency-show-button d-flex align-items-center">
									<div style="width: 100%;">
										{{ !empty($moneda->conservation_2) ? $moneda->conservation_1 . ' / ' . $moneda->conservation_2 : $moneda->conservation_1 }}
									</div>
									<a data-toggle="modal" data-target="#currency-types" href=""><span><i
												class="fa fa-info fa-lg"></i></span></a>
								</button>
								<script>
									var btn = $('#currency-show-button > div');
									var text = btn.html().split('<a')[0].trim();
									if (text.length > 10) {
										btn.css('font-size', '10px');
									}
								</script>
							@endif

							@if (!empty($lote_actual->descdet_hces1))
								<div class="btn-context hidden flex valign">
									<a>
										<p><?= trans($theme . '-app.lot.context') ?></p>
									</a>
								</div>
							@endif

							<!-- Fav -->
							@if ($lote_actual->retirado_asigl0 == 'N')
								<div class="btn-add-fav flex valign">
									<div class="loader mini" style="display: none"></div>
									<a
										class="inline-flex valign btn hidden-xs <?= $data['subasta_info']->lote_actual->favorito ? 'hidden' : '' ?>"
										id="add_fav" href="javascript:action_fav_modal('add')">
										<p class="hidden">{{ trans($theme . '-app.lot.add_to_fav') }}</p>
										<i class="far fa-star"></i>
									</a>
									<a
										class="inline-flex valign btn  hidden-xs <?= $data['subasta_info']->lote_actual->favorito ? '' : 'hidden' ?>"
										id="del_fav" href="javascript:action_fav_modal('remove')">
										{{-- <p class="hidden-xs hidden-sm"> trans($theme .'-app.lot.add_to_fav') </p> --}}
										<i class="fas fa-star heart"></i>
									</a>
								</div>
							@endif



						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-5 col-lg-4">
				<div class="single-lot-des">
					<div class="single-lot-desc-wrapper">
						<div class="context-content" style="display:none">
							<div role="button" class="context-close">X</div>
							@if (!empty($lote_actual->descdet_hces1))
								<h4 class="context-title">{{ trans($theme . '-app.lot.context') }}</h4>
								<div class="context-text">
									<?= $lote_actual->descdet_hces1 ?>
								</div>
							@endif
						</div>
						@if ($lote_actual->retirado_asigl0 == 'N' && $lote_actual->fac_hces1 != 'D' && $lote_actual->fac_hces1 != 'R')

							@if ($lote_actual->subc_sub != 'A' && $lote_actual->subc_sub != 'S')
								@include('includes.ficha.pujas_ficha_cerrada')
							@elseif(
								$lote_actual->tipo_sub == 'V' &&
									$lote_actual->cerrado_asigl0 != 'S' &&
									strtotime($lote_actual->end_session) > date('now'))
								@include('includes.ficha.pujas_ficha_V')

								<?php //si un lote cerrado no se ha vendido se podra comprar
								?>
							@elseif(
								($lote_actual->tipo_sub == 'W' || $lote_actual->tipo_sub == 'O') &&
									$lote_actual->cerrado_asigl0 == 'S' &&
									empty($lote_actual->himp_csub) &&
									$lote_actual->compra_asigl0 == 'S' &&
									$lote_actual->fac_hces1 == 'N' &&
									$lote_actual->desadju_asigl0 == 'N')
								@include('includes.ficha.pujas_ficha_V')
							@elseif(
								($lote_actual->tipo_sub == 'O' || $lote_actual->tipo_sub == 'P' || $lote_actual->subabierta_sub == 'P') &&
									$lote_actual->cerrado_asigl0 != 'S')
								@include('includes.ficha.pujas_ficha_O')
							@elseif($lote_actual->tipo_sub == 'W' && $lote_actual->cerrado_asigl0 != 'S')
								@include('includes.ficha.pujas_ficha_W')



								<?php //puede que este cerrado 'S' o devuelto 'D'
								?>
							@else
								@include('includes.ficha.pujas_ficha_cerrada')
							@endif

							@if (
								(strtoupper($lote_actual->tipo_sub) == 'O' ||
									strtoupper($lote_actual->tipo_sub) == 'P' ||
									$lote_actual->subabierta_sub == 'P') &&
									$lote_actual->cerrado_asigl0 == 'N' &&
									$lote_actual->retirado_asigl0 == 'N')
								<div class="historial">
									@include('includes.ficha.history')
								</div>
							@endif

						@endif
						<div class="shared">

							@include('includes.ficha.share')
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="interest">
	<div class="container">
		<div class="row">
			<div class="single col-xs-12">
				<div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 interest-wrapper lotes_destacados">
					<div class="interest-title text-center title_single">
						<div class="interest-line"></div>
						<p>{{ trans($theme . '-app.lot.recommended_lots') }}</p>
						<div class="interest-line"></div>
					</div>
				</div>

				@php
					$loteInicial = $lote_actual->ref_asigl0;

					if ($lote_actual->ref_asigl0 > $minMaxLot[0]->max - 20) {
					    $loteInicial = $minMaxLot[0]->max - 20;
					}

					$replace = [
					    'emp' => Config::get('app.emp'),
					    'id_auc_sessions' => $lote_actual->id_auc_sessions,
					    'sub_asigl0' => $lote_actual->cod_sub,
					    'lang' => Config::get('app.language_complete')['' . Config::get('app.locale') . ''],
					    'ref_asigl0' => $loteInicial,
					];
				@endphp

				<script>
					var replace = @json($replace);
					var ref = @json($lote_actual->ref_asigl0);
					var minLotAuction = @json($minMaxLot[0]->min);
					var maxLotAuction = @json($minMaxLot[0]->max);

					$(document).ready(function() {

						ajax_carousel("lotes_subasta", replace, parseInt(ref) + 1);

						if ($('#historial_pujas').length) {
							$('.exeption-one').css('margin-top', '115px')
						}

					});
				</script>

				<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 lot-list-interest">
					<div class='loader hidden'></div>
					<div id="lotes_subasta" class="owl-theme owl-carousel"></div>

				</div>
			</div>
		</div>
	</div>

</section>
{{-- <div id="zommImg" class="zoomImg" style="display: none">
	<i class="zoomImgClose">×</i>
	<img id="" src="" class="zoom" data-magnify-src="" style="display: block;max-width: 350%">
</div> --}}

<script>

	const fichaCarousel = $("#owl-carousel-responsive-ficha").owlCarousel({
		items: 1,
		autoplay: false,
		margin: 20,
		dots: true,
		nav: false,
		responsiveClass: true,
	});

	let pasadaASegundaImagen = false;

	fichaCarousel.on('changed.owl.carousel', function(event) {
		if (!pasadaASegundaImagen) {
			fichaCarousel.trigger('prev.owl.carousel', [0])
			pasadaASegundaImagen = true;
		}
	});

	$(window).ready(function() {

		var lightbox = new PhotoSwipeLightbox({
			gallery: '.ficha-lot-galery',
			children: 'a',
			pswpModule: PhotoSwipe,
			loop: false
		});
		lightbox.init();

		const imageMiniatureContainer = $('.image-lot-miniature-container');

		lightbox.on('beforeOpen', () => {
			selectGaleryMiniature(lightbox.pswp.currIndex, imageMiniatureContainer);
			imageMiniatureContainer.css('align-items', 'flex-end');
			imageMiniatureContainer.fadeIn(400, function() {
				$(this).css('display', 'flex');
			});
		});

		lightbox.on('change', () => {
			fichaCarousel.trigger('to.owl.carousel', [lightbox.pswp.currIndex, 0])
			selectGaleryMiniature(lightbox.pswp.currIndex, imageMiniatureContainer);
			moveMiniatureScroll(lightbox.pswp.currIndex, imageMiniatureContainer);
		});

		lightbox.on('close', () => {
			deselectAllGaleryMiniature(imageMiniatureContainer);
			imageMiniatureContainer.slideUp(400);
			imageMiniatureContainer.css('align-items', 'initial');
		});

		$('a.image-selector').click(openThatImage);

		function openThatImage(){
			let index = $(this).data('key-image');
			const pswp = lightbox.pswp;
			pswp.goTo(index)
			fichaCarousel.trigger('to.owl.carousel', [pswp.currIndex, 0])
		}

		if ($('.context-text p span').text().length > 10) {
			$('.btn-context').removeClass('hidden')
		}

		let containerSingleLot = document.querySelector('.single-lot-bar');
		let btnSingleLot = document.querySelector('.btn-single-lot');


		/* era para aprovechar espacio disponible, pero solo quieren 3 miniaturas
		let availableSpace = containerSingleLot.offsetWidth - btnSingleLot.offsetWidth;
		let imgContainer = document.querySelector('.carousel-img-btn');
		if(imgContainer != null){
			imgContainer.style.width = availableSpace + 'px';
		} */


		(function($) {
			var carouselThumnail = {
				init: function() {
					this.cache()
					this.bindEvents()
					this.move()

				},
				cache: function() {
					this.chevrons = $('.btn-carousel-thumnail')
					this.chLeft = $('.btn-slider-mini').find('.btn-left')
					this.chRight = $('.btn-slider-mini').find('.btn-right')
					this.carousel = $('.carousel-img-btn')
					this.scrollLeft = 0
					this.scrollTotal = $('.img-thumnail').width() - $('.carousel-img-btn').width()

				},

				scroll: function(e) {
					this.scrollTotal = $('.img-thumnail').width() - $('.carousel-img-btn').width()
					if (this.scrollLeft >= 0 && this.scrollLeft <= this.scrollTotal) {
						if ((e.currentTarget.className.includes('btn-right'))) {
							if (this.scrollLeft !== this.scrollTotal) {
								this.scrollLeft = this.scrollLeft + 136
								if (this.scrollLeft > this.scrollTotal) {
									this.scrollLeft = this.scrollTotal
								}
							}
							(this.scrollLeft == this.scrollTotal) ? $(e.currentTarget).hide(): $(e
								.currentTarget).show();
							(this.scrollLeft == 0) ? $('.btn-left').hide(): $('.btn-left').show();
						}


						if ((e.currentTarget.className.includes('btn-left'))) {
							if (this.scrollLeft !== 0) {
								this.scrollLeft = this.scrollLeft - 136
								if (this.scrollLeft < 0) {
									this.scrollLeft = 0
								}
							}
							(this.scrollLeft == 0) ? $(e.currentTarget).hide(): $(e.currentTarget)
								.show();
							(this.scrollLeft == this.scrollTotal) ? $('.btn-right').hide(): $(
								'.btn-right').show();
						}
						this.move();
					}
				},
				move: function() {
					this.carousel.animate({
						scrollLeft: this.scrollLeft
					}, 200)
				},


				bindEvents: function() {

					this.chLeft.on('click', this.scroll.bind(this))
					this.chRight.on('click', this.scroll.bind(this))
				}
			}

			carouselThumnail.init()
		})($);



		//mostrar 360 en contenedor de carousel
		@if (!empty($lote_actual->contextra_hces1))
			(function($) {
				var image360Mobile = {
					init: function() {
						this.cache()
						this.bindEvents()
						this.showBtn()

					},
					cache: function() {
						this.btnResponsive = $('#btn-360Responsive')
						this.frame360 = $('.b360-responsive')
						this.carousel = $('#owl-carousel-responsive')
						this.orvitvu = $('.orbitvu-viewer')
						this.btn360 = $('#btn-360')

					},
					show: function() {
						if (this.btnResponsive.hasClass('active-360')) {
							this.btnResponsive.find('svg').hide()
							this.btnResponsive.find('i').show()
							this.frame360.append($('.orbitvu-viewer'))
							this.frame360.css('visibility', 'visible')
							this.carousel.css('visibility', 'hidden');

							if ($(window).width() < 400) {
								this.orvitvu.css('width', '100%');
							} else {
								this.orvitvu.css('width', '400px');
							}

							this.orvitvu.css('height', '300px');
						} else {
							this.btnResponsive.find('svg').show()
							this.btnResponsive.find('i').hide()
							this.frame360.css('visibility', 'hidden')
							this.carousel.css('visibility', 'visible');

						}
					},

					showBtn: function() {
						this.btnResponsive.removeClass('hidden')
						this.btn360.removeClass('hidden')
					},

					bindEvents: function() {
						this.btnResponsive.on('click', this.show.bind(this))
					}
				}
				image360Mobile.init()
			})($)
		@endif



	});
</script>



<script>
	/* $('.item_content_img_single').click(function(event) {
	var pos = event.target.dataset.pos;
	seed.goToPage(parseInt(pos));
	seed.setFullScreen(true);
}); */

	/* $('.zoomImgClose').click(function(){
	    $("#img_main").addClass('hidden-xs').addClass('hidden-sm').addClass('hidden-md');
		seed.setFullScreen(false);
	}); */



	$('.btn-context').click(function() {
		closeOpenModal()
	})
	$('.context-close').click(function() {
		closeOpenModal()
	})

	/* indexImg = 0;
	$('.chevron-left-button').click(function(){
            if(indexImg != 0){
                indexImg--
                var container = $('.col-sm-3-custom')[indexImg];
            	image = $(container).find('img').attr('data-image')
            	loadSeaDragon(image, indexImg);
            }

        })
        $('.chevron-right-button').click(function(){
            if(indexImg != $('.col-sm-3-custom').length - 1){
                indexImg++
                var container = $('.col-sm-3-custom')[indexImg];
            	image = $(container).find('img').attr('data-image');
            	loadSeaDragon(image, indexImg);
            }

        }) */

	function readMore(jObj, lineNum) { //function

		if (isNaN(lineNum)) {
			lineNum = 2;
		}
		var go = new ReadMore(jObj, lineNum);
	}

	function ReadMore(_jObj, lineNum) { //class

		var READ_MORE_LABEL = "{{ trans($theme . '-app.lot.viewMore') }}";
		var HIDE_LABEL = "{{ trans($theme . '-app.lot.hideMore') }}";

		var jObj = _jObj;

		var textMinHeight = "" + (parseInt(jObj.children("p").css("line-height"), 19) * lineNum) + "px";
		var textMaxHeight = "" + jObj.children("p").css("height");
		if (parseInt(jObj.children("p").css("height")) > 52 && $(document).width() > 768) {
			jObj.children("p").css("height", "" + textMaxHeight);
			jObj.children("p").css("transition", "height .5s");
			jObj.children("p").css("height", "" + textMinHeight);

			jObj.append("<span class=read-more>" + READ_MORE_LABEL + "</span>");

			jObj.children(".read-more").css({
				"color": "#283747",
				"font-weight": "bold",
				"cursor": "pointer",
				"margin": "0",
				"height": "auto"
			});

			jObj.children("span").click(function() {
				if (jObj.children("p").css("height") == textMinHeight) {
					jObj.children("p").css("height", "" + textMaxHeight);
					jObj.children(".read-more").html(HIDE_LABEL);
				} else {
					jObj.children("p").css("height", "" + textMinHeight);
					jObj.children(".read-more").html(READ_MORE_LABEL);
				}
			});
		}



	}

	/* function loadSeaDragon(img, position){
		indexImg = position;
        var element = document.getElementById("img_main");
        while (element.firstChild) {
          element.removeChild(element.firstChild);
        }
        OpenSeadragon({
        id:"img_main",
        prefixUrl: "/img/opendragon/",

        showReferenceStrip:  true,


        tileSources: [{
                type: 'image',
                url:  '/img/load/real/'+img
            }],
        showNavigator:false,
        });
    }
    loadSeaDragon('{{ $lote_actual->imagen }}', 0); */


	var seed;
	$(window).ready(function() {

		if ($('.btn-add-fav').length && $('.btn-context').length && !($('#btn-360').hasClass('hidden'))) {
			if ($(window).width() > 1200) {
				$('.carousel-img-btn').css('width', 'calc( 63px * 5.4)')

			}
			if ($(window).width() < 1200) {
				$('.carousel-img-btn').css('width', 'calc( 63px * 3.1)')
			}
		}

		$('.img-thumb-item').on('click', function() {
			seed.goToPage(parseInt(this.dataset.pos));
		});

		/* seed.addHandler('full-screen', function (viewer) {
			if($(window).width() < 1200){
				if(viewer.fullScreen){
					$("#img_main, #chevron-left-button, #chevron-right-button, #toolbarDiv").removeClass('hidden-xs').removeClass('hidden-sm').removeClass('hidden-md');
					return;
				}
				$("#img_main, #chevron-left-button, #chevron-right-button, #toolbarDiv").addClass('hidden-xs').addClass('hidden-sm').addClass('hidden-md');
				return;
			}
		}); */

	});

	function loadSeaDragon() {

		var element = document.getElementById("img_main");
		/*while (element.firstChild) {
  			element.removeChild(element.firstChild);
		}*/
		seed = OpenSeadragon({
			id: "img_main",
			prefixUrl: "/img/opendragon/",
			showReferenceStrip: false,
			maxZoomPixelRatio: 2.5,
			minZoomImageRatio: 0.1,
			visibilityRatio: 1.0,
			constrainDuringPan: true,
			preserveImageSizeOnResize: false,
			tileSources: [
				@foreach ($lote_actual->imagenes as $key => $imagen)
					{
						type: 'image',
						url: '/img/{{ config('app.emp') }}/{{ $lote_actual->num_hces1 }}/{{ $imagen }}?a={{ rand() }}'
					},
				@endforeach
			],
			showNavigator: false,
			sequenceMode: true,
			nextButton: "chevron-right",
			previousButton: "chevron-left",
			toolbar: "toolbarDiv",
		});
		if ($(window).width() < 1200) {
			$("#chevron-left-button, #chevron-right-button, #toolbarDiv").addClass('hidden-xs').addClass('hidden-sm')
				.addClass('hidden-md');
		}

	}
	loadSeaDragon();



	readMore($("#box"), 2);
</script>
<?php

$imagenes = [];
foreach ($lote_actual->imagenes as $key => $imagen) {
    $imagenes[] = '"/img/load/lote_small/' . $imagen . '"';
}
$imagenes = implode(',', $imagenes);

?>
<script type="application/ld+json">
	{
  "@context": "https://schema.org/",
  "@type": "Product",
  "additionalType":"http://www.productontology.org/id/Auction",
  "name": "{!! str_replace('"',"'",$lote_actual->titulo_hces1) !!}",
  "image": [{!!$imagenes!!}],
  "description": "<?= strip_tags( !empty($lote_actual->desc_hces1)? $lote_actual->desc_hces1 : $lote_actual->descweb_hces1 ) ?>",
  "brand": {
    "@type": "Brand",
    "name": "{{ \Config::get('app.name') }}",
    "logo": "<?= url('themes/'.$theme .'/assets/img/logo.png') ?>"
  },
    "sku": "{{\Config::get('app.emp')."-".$lote_actual->num_hces1."-".$lote_actual->lin_hces1}}",
  "offers": {
    "@type": "Offer",
    @if($lote_actual->subc_sub != 'A'  && $lote_actual->subc_sub != 'S')
    "availability": "https://schema.org/OutOfStock",
    @else
    "availability": "https://schema.org/InStock",
    @endif
    "url": "{{ \Url::current()}}",
    "priceCurrency": "EUR",
    "price": "{{ $lote_actual->impsalhces_asigl0 }}",
    "priceValidUntil": "{{ $lote_actual->end_session }}",
    "itemCondition": "https://schema.org/UsedCondition",

    "seller": {
      "@type": "Organization",
      "name": "{{ \Config::get('app.name') }}"
    }
  }
}
</script>
<script>
	function loadVideoMobile(video) {

		var elem = document.getElementById(video);
		if (elem.requestFullscreen) {
			elem.requestFullscreen();
		} else if (elem.mozRequestFullScreen) {
			elem.mozRequestFullScreen();
		} else if (elem.webkitRequestFullscreen) {
			elem.webkitRequestFullscreen();
		} else if (elem.msRequestFullscreen) {
			elem.msRequestFullscreen();
		}
		elem.play();

	}

	function addReproduccion(video, ref, sub) {

		token = $("#token").val();

		$.post("/subasta/reproducciones", {
			_token: token,
			video: video,
			ref: ref,
			sub: sub
		}, function(data) {

			a = data.split("-");
			if (a[2] == 1) {
				$("#corazon").html('<i class="fa fa-heart red"></i>');
			} else {
				$("#corazon").html('<i class="fa fa-heart"></i>');
			}
			$("#reproducciones").html(a[0]);
			$("#megusta").html(a[1]);

		});
	}

	function loadVideo(video, ref, sub) {

		//addReproduccion(video,ref,sub);
		actualiza_importes();
		$("#modalVideo").modal("show");
		$("#reproductor").html('');
		$("#reproductor").append('<video width="100%" controls autoplay  id="elvideo" onplay="addReproduccion(\'' + video +
			'\',\'' + ref + '\',\'' + sub + '\')"><source src="' + video + '" type="video/mp4"></video>');
		$("#modalVideo").find(".read-more").remove();
		$(".description").parent().css("height", "auto");

		if ($('#impsalexchange-actual').length) {
			$("#impsalexchange-actual-modal").html($("#impsalexchange-actual").html())
		}
		setTimeout('readMore( $("#box2"), 2)', 1000);
	}


	function megusta(ref, sub) {

		token = $("#token").val();

		video = $("#reproductor").find("source").attr("src");

		$.post("/subasta/megusta", {
			_token: token,
			video: video,
			ref: ref,
			sub: sub
		}, function(data) {

			a = data.split("-");
			if (a[1] == 1) {
				$("#corazon").html('<i class="fa fa-heart red"></i>');
			} else {
				$("#corazon").html('<i class="fa fa-heart"></i>');
			}
			$("#megusta").html(a[0]);

		});

	}

	function actualiza_importes() {
		$(".actualizable").html($(".origenactualizable").html());
		total_pujas = 0;
		pujadores = new Array();

		$("#pujas_list .hist_item").each(function(key, value) {
			total_pujas = total_pujas + 1;
			a = $(this).find(".uno").html();
			if (a != "") {
				pujadores[a] = 1;
			}
		});
		$(".tot_pujas").html(total_pujas);
		if (pujadores.length > 0) {
			$(".total_postores").html(pujadores.length - 1);
		} else {
			$(".total_postores").html(pujadores.length);
		}
		setTimeout("actualiza_importes()", 3000);
	}
	actualiza_importes();
</script>

@include('includes.ficha.modals_ficha')
