<?php
use \App\libs\MobileDetect;
$MobileDetect = new MobileDetect();
?>

<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<h1 class="titleLot">
				@if(\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1'))
				{{$lote_actual->ref_asigl0}} - {{$lote_actual->titulo_hces1}}
				@elseif(!\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1'))
				{{$lote_actual->titulo_hces1}}
				@elseif(\Config::get('app.ref_asigl0'))
				{{trans(\Config::get('app.theme').'-app.lot.lot-name')}} {{$lote_actual->ref_asigl0}} ;
				@endif
			</h1>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="single">
			<div class="col-xs-12 col-md-7">
				<div class="col-xs-12 no-padding hidden-xs">
					<div id="video_main_wrapper" class="img_single_border video_single_border" @if(empty($lote_actual->videos)) style="display:none" @endif>
					</div>
					<div id="img_main_wrapper" class="img_single_border" style="position: relative; @if(!empty($lote_actual->videos) && count($lote_actual->videos) > 0) display:none @endif">
						@if( $lote_actual->retirado_asigl0 !='N')
						<div class="retired ">
							{{ trans(\Config::get('app.theme').'-app.lot.retired') }}
						</div>
						@elseif($lote_actual->fac_hces1 == 'D' || $lote_actual->fac_hces1 == 'R')
						<div class="retired" style="">
							{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
						</div>
						<?php //@elseif($lote_actual->cerrado_asigl0 == 'S' &&  (!empty($lote_actual->himp_csub) || ($lote_actual->subc_sub == 'H' && !empty($lote_actual->impadj_asigl0))))
                            //cambio realizado por que el cliente quiere que aparezcan los lotes como cerrados en el historico

                        /*2 cambio, se decide que no aparezca en la foto
                        @elseif($lote_actual->cerrado_asigl0 == 'S')
                            <div class="retired" style ="">
                                {{ trans(\Config::get('app.theme').'-app.subastas.dont_buy')}}
                            </div>
                         *
                         */
                        ?>
						@endif
						<div role="" class="chevron-left-button">
							<i class="fa fa-2x fa-chevron-left"></i>
						</div>
						<div id="img_main" class="img_single">
							<a title="{{$lote_actual->titulo_hces1}}" href="javascript:action_fav_modal('remove')">
								<img src="/img/load/real/{{ $lote_actual->imagen }}"
									alt="{{$lote_actual->titulo_hces1}}">
							</a>
						</div>
						<div role="" class="chevron-right-button">
							<i class="fa fa-2x fa-chevron-right"></i>
						</div>
					</div>
				</div>
				<div class="col-xs-12 slider-thumnail-container">
					<div onClick="clickControl(this)" class="row-up control hidden" style="display:none !important">
						<i class="fa fa-chevron-up" aria-hidden="true"></i>
					</div>
					<div class="miniImg row hidden-xs slider-thumnail">


						@if(!empty($lote_actual->videos) && count($lote_actual->videos) > 0)
						@foreach($lote_actual->videos as $key => $video)
						<div class="col-sm-3-custom thumnails">
							<a class="view-thumbs-open-dragon video-thumbs" data-index='{{ $video }}'>
								<img class="img-openDragon" src="{{ asset('/themes/'. Config::get('app.theme') .'/assets/img/play.png') }}" data-video ='{{ $video }}'/>
							</a>
						</div>
						@endforeach
						@endif

						<?php
                            /* generar imagenes */
                                $imageGenerate = new  \App\libs\ImageGenerate();
                            ?>
						<?php foreach($lote_actual->imagenes as $key => $imagen){
                                $img_64 = $imageGenerate->resize_img( "lote_small", $imagen, Config::get('app.theme'),true);
                            ?>
						<div class="col-sm-3-custom thumnails">

							<a class="view-thumbs-open-dragon" data-index='{{ $key }}'>
								<img class="img-openDragon" src="data:image/jpeg;base64,{{$img_64}}"
									alt="{{ $lote_actual->titulo_hces1}}" data-image='<?=$imagen?>' />
							</a>
						</div>
						<?php
                                }
                                ?>
					</div>
					<!-- Inicio Galeria Desktop -->
					<div onClick="clickControl(this)" class="row-down control hidden" style="display:none !important">
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
						@if(!empty($lote_actual->videos) && count($lote_actual->videos) > 0)
						@foreach($lote_actual->videos as $key => $video)
						<div class="item_content_img_single">
							<video width="100%" controls>
								<source src="{{$video}}" type="video/mp4">
							</video>
						</div>
						@endforeach
						@endif
						<?php foreach($lote_actual->imagenes as $key => $imagen){?>
						<div class="item_content_img_single"
							style="position: relative; height: 290px; overflow: hidden;">
							<img style="    max-width: 100%; max-height: 190px;top: 50%; transform: translateY(-50%); position: relative; width: auto !important;    display: inherit !important;    margin: 0 auto !important;"
								class="img-responsive" src="/img/load/lote_medium_large/<?php echo $imagen?>"
								alt="{{$lote_actual->titulo_hces1}}">
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-5">
				<div class="col-xs-12 col-sm-12 no-padding">
					@if($lote_actual->retirado_asigl0 =='N' && $lote_actual->fac_hces1 != 'D' && $lote_actual->fac_hces1
					!= 'R')
					@if ($lote_actual->subc_sub != 'A' && $lote_actual->subc_sub != 'S' )
					@include('includes.ficha.pujas_ficha_cerrada')
					@elseif($lote_actual->tipo_sub == 'V' && $lote_actual->cerrado_asigl0 != 'S' &&
					strtotime($lote_actual->end_session) > date("now"))
					@include('includes.ficha.pujas_ficha_V')

					<?php //si un lote cerrado no se ha vendido se podra comprar ?>
					@elseif( ($lote_actual->tipo_sub == 'W' || $lote_actual->tipo_sub == 'O') &&
					$lote_actual->cerrado_asigl0 == 'S' && empty($lote_actual->himp_csub) && $lote_actual->compra_asigl0
					== 'S' && $lote_actual->fac_hces1!='D' && $lote_actual->desadju_asigl0 =='N')
					@include('includes.ficha.pujas_ficha_V')

					@elseif( $lote_actual->tipo_sub == 'W' && ($lote_actual->cerrado_asigl0 != 'S' ))
					@include('includes.ficha.pujas_ficha_W')

					@elseif(($lote_actual->tipo_sub == 'O' || $lote_actual->tipo_sub == 'P')&&
					$lote_actual->cerrado_asigl0 != 'S')
					@include('includes.ficha.pujas_ficha_O')

					<?php //puede que este cerrado 'S' o devuelto 'D' ?>
					@else
					@include('includes.ficha.pujas_ficha_cerrada')
					@endif
					@endif
				</div>
			</div>

			@if( $lote_actual->descweb_hces1)
			<div class="col-xs-12 col-sm-12 col-lg-5 ">
				<div class="col-xs-12 col-sm-12 info_single">
					<p class="title_adj">{{ trans(\Config::get('app.theme').'-app.lot.adj') }}</p>
					<p class="text_adj"><?= $lote_actual->contextra_hces1 ?></p>
				</div>
			</div>
			@endif
			<div class="col-xs-12 col-sm-12 col-lg-5 pull-right right_row">
				@if((strtoupper($lote_actual->tipo_sub) == 'O' || strtoupper($lote_actual->tipo_sub) == 'P')&&
				$lote_actual->cerrado_asigl0 != 'S' && $lote_actual->retirado_asigl0 =='N')
				<div class="col-xs-12 col-sm-12 info_single">

					@include('includes.ficha.history')

				</div>
				@endif
			</div>
			<?php
                                $inf_subasta = new \App\Models\Subasta();
                                if(!empty($data['subasta_info']->lote_actual->cod_sub)){
                                    $inf_subasta->cod = $data['subasta_info']->lote_actual->cod_sub;
                                }

                                if(!empty($data['subasta_info']->lote_actual->id_auc_sessions)){
                                     $inf_subasta->id_auc_sessions = $data['subasta_info']->lote_actual->id_auc_sessions;
                                }


                                $ficha_subasta=$inf_subasta->getInfSubasta();
                            ?>
			<div class="col-xs-12 col-sm-12 col-lg-7" style="margin-top: 5px">
				<div class="desc_container">

					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab"
								data-toggle="tab">{{ trans(\Config::get('app.theme').'-app.lot.description') }}</a></li>
						@if(!empty($ficha_subasta->obs_sub ))
						<li role="presentation"><a href="#desc" aria-controls="profile" role="tab"
								data-toggle="tab">{{ trans(\Config::get('app.theme').'-app.lot.envio_pago') }}</a></li>
						@endif
						<?php

                                    //comprobamos si hay ficheros
										$ruta="files/". Config::get("app.emp")."/".$lote_actual->num_hces1."/".$lote_actual->lin_hces1."/files";
                                        $files=array();
                                        if(file_exists($ruta)){
                                            $dir=scandir($ruta);
                                            $files=array_diff($dir, array('..', '.'));

                                        }
                                    ?>
						@if(!empty($files))
						<li role="presentation"><a href="#ficheros" aria-controls="profile" role="tab"
								data-toggle="tab">{{ trans(\Config::get('app.theme').'-app.lot.adj') }}</a></li>
						@endif
					</ul>
					<!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="home">
							<div class="desc_content">
								@if( \Config::get('app.descweb_hces1'))
								<p><?= $lote_actual->descweb_hces1 ?></p>
								@elseif ( \Config::get('app.desc_hces1' ))
								<p><?= str_replace('<b>Peso</b>', '<b>Ref. Interna</b>', $lote_actual->desc_hces1) ?></p>
								@endif
								<p><strong>{{ trans(\Config::get('app.theme').'-app.lot.number_items') }}: </strong>
									{{$lote_actual->nobj_hces1}}</p>
							</div>
						</div>

						<div role="tabpanel" class="tab-pane" id="desc">
							<div class="desc_content">
								@if(!empty($ficha_subasta->obs_sub ))
								<p></p>
								<p><?= $ficha_subasta->obs_sub ?></p>
								@endif
							</div>
						</div>
						@if(!empty($files))
						<div role="tabpanel" class="tab-pane ficheros_adj" id="ficheros">
							<p></p>
							@foreach($files as $file)
							<a role="button" href="/{{$ruta."/".$file}}" target="_blank"
								style="display: block; font-weight: 600">{{$file}}</a>
							@endforeach
						</div>
						@endif
					</div>

				</div>
			</div>

			<div class="col-xs-12 col-sm-12 lotes_destacados" style="margin-top: 75px;">

				<div class="titulos-home ">
					<p>{{ trans(\Config::get('app.theme').'-app.lot.recommended_lots') }}</p>
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
					var key = "{{$key}}";

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
	var indexImg = 0;
        function loadSeaDragon(img, el){
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

        $('.chevron-left-button').click(function(){
            if(indexImg != 0){
                indexImg--
                var container = $('.col-sm-3-custom')[indexImg];
            	image = $(container).find('img').attr('data-image')
            	loadSeaDragon(image);
            }

        })
        $('.chevron-right-button').click(function(){
            if(indexImg != $('.col-sm-3-custom').length - 1){
                indexImg++
                var container = $('.col-sm-3-custom')[indexImg];
            	image = $(container).find('img').attr('data-image');
            	loadSeaDragon(image);
            }

        })

        //loadSeaDragon('<?= $lote_actual->imagen ?>');

            //Slider vertical lote
       /*
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
            */
            $('body').keydown(function (e) {
               if(e.keyCode === 37){

                $('.chevron-left-button').click()
               }
               if(e.keyCode === 39){
                $('.chevron-right-button').click()
               }
            })

    $('.view-thumbs-open-dragon').click(function(){

        indexImg = $(this).attr('data-index');
		let image = $(this).find('img').data('image');
		console.log(image);

		if(typeof image != 'undefined'){
			$('#video_main_wrapper').hide();
			//loadSeaDragon('<?= $lote_actual->imagen ?>');
			$('#img_main_wrapper').show();
			loadSeaDragon(image);
			return;
		}

		let videoHref = $(this).find('img').data("video");
		loadVideo(videoHref);
	});

	let videos = @json($lote_actual->videos);

	if(videos.length > 0){
		loadVideo(videos[0]);
	}
	else{
		loadSeaDragon('{{$lote_actual->imagen}}');
	}

</script>


@include('includes.ficha.modals_ficha')
