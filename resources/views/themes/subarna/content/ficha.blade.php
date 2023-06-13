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
?>

<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<h1 class="titlePage" style="">



				{{$lote_actual->ref_asigl0}} - <?= strip_tags ($lote_actual->descweb_hces1) ?>


			</h1>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="single">
			<div class="col-xs-12 col-md-7">
				<div class="col-xs-12 col-sm-10 col-md-10 hidden-xs">

					<div class="img_single_border">

						<div class="button-follow" style="display:none;">
							<div class="spinner">
								<div class="double-bounce1"></div>
								<div class="double-bounce2"></div>
							</div>

						</div>


						@if( $lote_actual->retirado_asigl0 !='N')
						<div class="retired ">
							{{ trans(\Config::get('app.theme').'-app.lot.retired') }}
						</div>
						@elseif($lote_actual->fac_hces1 == 'D' || $lote_actual->fac_hces1 == 'R')
						<div class="retired" style="background:#777777;text-transform: lowercase;">
							{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
						</div>
						@elseif($lote_actual->cerrado_asigl0 == 'S' && (!empty($lote_actual->himp_csub) ||
						$lote_actual->desadju_asigl0 =='S' || ($lote_actual->subc_sub == 'H' &&
						!empty($lote_actual->impadj_asigl0))))
						<div class="retired" style="background:#777777;text-transform: lowercase;">
							{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
						</div>
						@endif
						<div id="img_main" class="img_single">


							<a title="{{$lote_actual->titulo_hces1}}" href="javascript:action_fav_modal('remove')">
								<img src="/img/load/real/{{ $lote_actual->imagen }}"
									alt="{{$lote_actual->titulo_hces1}}">
							</a>

						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-2 col-md-2 slider-thumnail-container">

					<div onClick="clickControl(this)" class="row-up control">
						<i class="fa fa-chevron-up" aria-hidden="true"></i>
					</div>


					<div class="miniImg row hidden-xs slider-thumnail">

						@foreach($lote_actual->videos ?? [] as $key => $video)
						<div class="col-sm-3-custom">
							<a style="cursor: pointer;">
								<img class="btn-play img-responsive" src="/themes/{{$theme}}/assets/img/play_1.png" alt="video"
        							data-toggle="modal" data-target="#modalVideo" data-video="{{$video}}" style="background-image: url({{ \Tools::url_img("lote_small", $lote_actual->num_hces1, $lote_actual->lin_hces1, 0) }}); background-size: cover;"></a>
							</a>
						</div>
						@endforeach

						@foreach ($lote_actual->imagenes as $key => $imagen)
						<div class="col-sm-3-custom">
							<a href="javascript:loadSeaDragon('<?=$imagen?>');">
								<div class="img-openDragon"
									style="background-image:url('{{ \Tools::url_img("lote_small", $lote_actual->num_hces1, $lote_actual->lin_hces1, $key) }}'); background-size: contain; background-position: center; background-repeat: no-repeat;"
									alt="{{$lote_actual->titulo_hces1}}"></div>
							</a>
						</div>
						@endforeach



					</div>

					<!-- Inicio Galeria Desktop -->
					<div onClick="clickControl(this)" class="row-down control">
						<i class="fa fa-chevron-down" aria-hidden="true"></i>
					</div>

					<script>
						if($('.slider-thumnail')[0].scrollHeight > 486){
                                        $('.control').show()
                                    }
					</script>
					<!-- Fin Galeria Desktop -->
					<!-- Inicio Galeria Responsive -->
					<div class="owl-theme owl-carousel visible-xs" id="owl-carousel-responsive">

						@foreach($lote_actual->videos ?? [] as $key => $video)
						<div class="item_content_img_single" style="position: relative; height: 290px; overflow: hidden;">
							<video width="100%" controls class="video_mobile">
								<source src="{{$video}}" type="video/mp4">
							</video>
						</div>
						@endforeach

						@foreach ($lote_actual->imagenes as $key => $imagen)
						<div class="item_content_img_single"
							style="position: relative; height: 290px; overflow: hidden;">

							<img style="    max-width: 100%; max-height: 190px;top: 50%; transform: translateY(-50%); position: relative; width: auto !important;    display: inherit !important;    margin: 0 auto !important;"
								class="img-responsive"
								src="{{ \Tools::url_img("lote_medium_large", $lote_actual->num_hces1, $lote_actual->lin_hces1, $key) }}"
								alt="{{$lote_actual->titulo_hces1}}">
						</div>
						@endforeach

					</div>

				</div>
			</div>

			<div class="col-xs-12 col-md-5">
				<div class="col-xs-12 col-sm-12">

					@if(!$retirado && !$devuelto && !$fact_devuelta)

					<?php
							#debemos poenr el código aqui par que lo usen en diferentes includes
							if($subasta_web){
							   $nameCountdown = "countdown";
							   $timeCountdown = $lote_actual->start_session;
							}else if($subasta_venta){
							   $nameCountdown = "countdown";
							   $timeCountdown = $lote_actual->end_session;
							}else if($subasta_online){
							   $nameCountdown = "countdownficha";
							   $timeCountdown = $lote_actual->close_at;
							}
					   		?>

					@if ($sub_cerrada)
					@include('includes.ficha.pujas_ficha_cerrada')

					@elseif($subasta_venta && !$cerrado && !$end_session)
					@include('includes.ficha.pujas_ficha_V')

					<?php //si un lote cerrado no se ha vendido se podra comprar ?>
					@elseif( ($subasta_web || $subasta_online) && $cerrado && empty($lote_actual->himp_csub) && $compra
					&& !$fact_devuelta)
					@include('includes.ficha.pujas_ficha_V')

					@elseif( ($subasta_online || ($subasta_web && $subasta_abierta_P )) && !$cerrado)
					@include('includes.ficha.pujas_ficha_O')

					@elseif( $subasta_web && !$cerrado)
					@include('includes.ficha.pujas_ficha_W')

					<?php //puede que este cerrado 'S' o devuelto 'D' ?>
					@else
					@include('includes.ficha.pujas_ficha_cerrada')
					@endif
					@endif
				</div>
			</div>


			<div class="col-xs-12 col-sm-12 col-lg-5 pull-right right_row">
				<div class="col-xs-12 col-sm-12">
					@if(( $subasta_online || ($subasta_web && $subasta_abierta_P )) && !$cerrado && !$retirado)
					@include('includes.ficha.history')
					@endif
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-lg-7">
				<div class="desc">
					<div class="desc_tit">
						{{ trans(\Config::get('app.theme').'-app.lot.description') }}
					</div>
					<div class="desc_content">
						<p><?= $lote_actual->desc_hces1 ?></p>

					</div>
				</div>
			</div>




			<div class="col-xs-12 col-sm-12 lotes_destacados">
				<div class="title_single">
					{{ trans(\Config::get('app.theme').'-app.lot.recommended_lots') }}
				</div>

				@php
				$key = "lotes_recomendados";
				$replace = array(
				'emp' => Config::get('app.emp') ,
				'sec_hces1' => $lote_actual->sec_hces1,
				'id_hces1' => $lote_actual->id_hces1,
				'lang' => Config::get('app.language_complete')[''.Config::get('app.locale').''] ,
				);
				@endphp

				<script>
					var replace = @json($replace);
					var key = "{{ $key }}";

					$(document).ready(function(){

						ajax_carousel(key,replace);

					});
				</script>
				<div class='loader hidden'></div>
				<div id="lotes_recomendados" class="owl-theme owl-carousel"></div>
			</div>
		</div>
	</div>
</div>

<script>
	function loadSeaDragon(img){
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
    loadSeaDragon('<?= $lote_actual->imagen ?>');


    //Slider vertical lote
    if($('.slider-thumnail')[0].scrollHeight > 485){
        $('.control').show()
    }else {
        $('.control').hide()
    }

    function clickControl(el){
        var posScroll = $('.slider-thumnail').scrollTop();
        if($(el).hasClass('row-up')){
            $('.slider-thumnail').animate({
                scrollTop: posScroll - 76.40,
            },200);
            }else{

            $('.slider-thumnail').animate({
                scrollTop: posScroll + 66,
            },200);
            }
        }



		$(document).ready(function(){


			$('.btn-play').on('click', function(e){

				var element = document.getElementById("js-video");
        		while (element.firstChild) {
          			element.removeChild(element.firstChild);
        		}

				let $video = $('<video />', {
        			id: 'video',
        			src: this.dataset.video,
        			type: 'video/mp4',
        			controls: true,
					autoplay: true
    			}).css('width', '100%');
    			$video.appendTo(element);

			});

			$('#modalVideo').on('hidden.bs.modal', function (e) {
				let $video = document.getElementById("video");
  				$video.pause();
          		$video.currentTime = 0;
			});
		});

</script>

@include('includes.ficha.modals_ficha')
