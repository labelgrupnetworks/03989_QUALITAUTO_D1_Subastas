<?php

$sobre_cerrado = $lote_actual->opcioncar_sub == 'S' ? true : false;
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
$lot_close_at = strtotime("now") > strtotime($lote_actual->close_at);

$start_orders =strtotime("now") > strtotime($lote_actual->orders_start);
$end_orders = strtotime("now") > strtotime($lote_actual->orders_end);
$vendido = (!empty($lote_actual->himp_csub)|| $lote_actual->desadju_asigl0 =='S' )? true : false;

if ($retirado || $fact_devuelta || $cerrado || $lot_close_at) {
	header("Location: " . URL::to('/404'), true, 301);
	exit();
}

?>


<div class="ficha-content color-letter">
	<div class="container">
		<div class="row">

			<div class="col-sm-7 col-xs-12" style="position: relative">


				@include('includes.ficha.header_time')
				<div class="col-xs-12 no-padding col-sm-2 col-md-2 slider-thumnail-container">

					<div class="owl-theme owl-carousel visible-xs" id="owl-carousel-responsive">

						<?php foreach($lote_actual->imagenes as $key => $imagen){?>
						<div class="item_content_img_single"
							style="position: relative; height: 290px; overflow: hidden;">
							<img style="    max-width: 100%; max-height: 190px;top: 50%; transform: translateY(-50%); position: relative; width: auto !important;    display: inherit !important;    margin: 0 auto !important;"
								class="img-responsive"
								loading="lazy"
								src="{{ \Tools::url_img("lote_medium_large", $lote_actual->num_hces1, $lote_actual->lin_hces1, $key) }}">
						</div>
						<?php } ?>
					</div>

				</div>

				<div class="col-xs-12 no-padding hidden-xs">
					@if( $retirado)
					<div class="retired">
						{{ trans(\Config::get('app.theme').'-app.lot.retired') }}
					</div>
					@elseif($fact_devuelta)
					<div class="retired" style="">
						{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
					</div>
					@elseif($cerrado && ($vendido || ($sub_historica && !empty($lote_actual->impadj_asigl0))))
					<div class="retired" style="">
						{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
					</div>
					@endif

					<div id="img_main" class="img_single">
						<a title="{{$lote_actual->titulo_hces1 ?? $lote_actual->descweb_hces1}}" href="javascript:action_fav_modal('remove')">
							<img class="img-responsive"
								src=""
								alt="{{$lote_actual->titulo_hces1 ?? $lote_actual->descweb_hces1}}">
						</a>
					</div>
					@if(Session::has('user') && !$retirado)
					<div class="col-xs-12 no-padding favoritos">
						<a class="secondary-button  <?= $lote_actual->favorito? 'hidden':'' ?>" id="add_fav"
							href="javascript:action_fav_modal('add')">
							{{ trans(\Config::get('app.theme').'-app.lot.add_to_fav') }}
						</a>
						<a class="secondary-button  <?= $lote_actual->favorito? '':'hidden' ?>" id="del_fav"
							href="javascript:action_fav_modal('remove')">
							{{ trans(\Config::get('app.theme').'-app.lot.del_from_fav') }}
						</a>
					</div>
					@endif
					<div class="col-xs-12 no-padding">
						<div class="minis-content">
							<?php foreach($lote_actual->imagenes as $key => $imagen){?>
							<div class="mini-img-ficha">

								<div class="img-openDragon img-thumbs" data-pos="{{$key}}"
									style="background-image:url('{{ \Tools::url_img("lote_small", $lote_actual->num_hces1, $lote_actual->lin_hces1, $key) }}'); background-size: contain; background-position: center; background-repeat: no-repeat;"
									alt="{{$lote_actual->titulo_hces1}}">
								</div>
							</div>
							<?php } ?>
						</div>
					</div>

				</div>
			</div>

			<div class="col-sm-5 col-xs-12 content-right-ficha d-flex justify-content-space-between flex-column">
				<div class="time-remaining-block mb-2">
					@if($cerrado_N && $subasta_online && !empty($lote_actual->close_at) &&
					strtotime($lote_actual->close_at) > getdate()[0])
					<div class="col-xs-12 ficha-info-clock d-flex align-items-center justify-content-center no-padding">
						<span class="clock">
							<span>{{ trans(\Config::get('app.theme').'-app.subastas.closes_in') }}</span>
							<span data-countdownficha="{{ strtotime($lote_actual->close_at) - getdate()[0] }}"
								data-format="<?= \Tools::down_timer($lote_actual->close_at); ?>" class="timer">
							</span>
						</span>
					</div>
					@endif
				</div>

				<div class="d-flex justify-content-space-between flex-column" style="flex:1">
					<div class="ficha-info-title col-xs-12 no-padding">
						<div class="titleficha col-xs-12 no-padding  secondary-color-text no-padding color-brand">
							{{$lote_actual->ref_asigl0}} - {!!$lote_actual->titulo_hces1 ?? $lote_actual->descweb_hces1!!}
						</div>
					</div>
					@if($data['categories']->isNotEmpty())
					<div class="col-xs-12 no-padding fincha-info-cats hide">
						<div class="cat">{{ trans(\Config::get('app.theme').'-app.lot.categories') }}</div>
						@foreach($data['categories'] as $sec)
						<span class="badge">{{$sec->des_tsec}}</span>
						@endforeach
					</div>
					@endif
					<div
						class="ficha-info-content col-xs-12 no-padding h-100 flex-column d-flex" @if($subasta_online && !$start_session) style="flex: 1;" @endif>

						@if(!$retirado && !$devuelto && !$fact_devuelta)
						<div class="ficha-info-items">
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

						 	{{-- Si la variable de $sobre_cerrado tiene una S en base de datos pasará a la ficha de sobre cerrado --}}
							@elseif($sobre_cerrado)
							@include('includes.ficha.pujas_ficha_custom')

							@elseif($subasta_venta && !$cerrado && !$end_session)
							@include('includes.ficha.pujas_ficha_V')

							<?php //si un lote cerrado no se ha vendido se podra comprar ?>
							@elseif( ($subasta_web || $subasta_online) && $cerrado && !$vendido && $compra &&
							!$fact_devuelta)

							@include('includes.ficha.pujas_ficha_V')
							<?php //si una subasta es abierta p solo entraremso a la tipo online si no esta iniciada la subasta ?>
							@elseif( ($subasta_online || ($subasta_web && $subasta_abierta_P && !$start_session)) &&
							!$cerrado)
							@include('includes.ficha.pujas_ficha_O')

							@elseif( $subasta_web && !$cerrado)
							@include('includes.ficha.pujas_ficha_W')


							@else
							@include('includes.ficha.pujas_ficha_cerrada')
							@endif

						</div>
						@endif
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 no-padding">
					@if(( $subasta_online && $start_session || ($subasta_web && $subasta_abierta_P )) && !$cerrado && !$retirado)
						@if ($sobre_cerrado == false)
						@include('includes.ficha.history')
						@endif
					@endif
				</div>
				@include('includes.ficha.share')

			</div>
		</div>
	</div>
</div>

@php
$path = "/files/".Config::get('app.emp')."/$lote_actual->num_hces1/$lote_actual->lin_hces1/files/";
$files = [];
if(is_dir(getcwd() . $path)){
	$files = array_diff(scandir(getcwd() . $path), ['.', '..']);
}
@endphp

<div class="container mt-3">
	<div class="col-xs-12 no-padding ficha-tipo-v">

			@foreach ($files as $file)
			@if (strpos(strtolower($file), 'dossier comercial') !== false)

			<div class="col-xs-12 col-sm-7 no-padding mt-1 mb-3 pt-2 pb-2 d-flex align-items-center justify-content-center">
				<div class="file-dossier">
					<a class="text-center" href="{{\Tools::urlAssetsCache($path . $file)}}" target="_blank">
						<img src="/img/icons/pdf.png" alt="{{ $file }}" style="max-width: 75px">
						<br>
						<span><b>{{ $file }}</b></span>
					</a>
				</div>
			</div>

			@endif
			@endforeach



		<div class="col-xs-12 no-padding desc-lot-title d-flex justify-content-space-between">
			<p class="desc-lot-profile-title">{{ trans(\Config::get('app.theme').'-app.lot.description') }}</p>
		</div>
		<div class="col-xs-12 no-padding desc-lot-profile-content" style="text-align: justify">
			<p>{!! $lote_actual->desc_hces1 !!}</p>
		</div>

	</div>


	@if (count($files)>0)

	<div class="col-xs-12 no-padding ficha-tipo-v mt-5">
		<div class="col-xs-12 no-padding desc-lot-title">
			<p class="desc-lot-profile-title">{{ trans(\Config::get('app.theme').'-app.lot.documentos') }}</p>
		</div>

		<div class="col-xs-12 no-padding desc-lot-profile-content mt-1">
			@foreach ($files as $file)

			@if (strpos(strtolower($file), 'dossier comercial') === false)
			<a href="{{\Tools::urlAssetsCache($path . $file)}}" target="_blank">
				<span class="mt-3">{{ $file }}</span>
			</a>
			<br>
			@endif

			@endforeach
		</div>
	</div>

	@endif


	<div class="row">
		<div class="single">
			<div class="col-xs-12 col-md-7 mt-5">
			</div>


			<div class="col-xs-12 col-sm-12 lotes_destacados mt-1">
				<div class="mas-pujados-title color-letter">
					<span>{{ trans(\Config::get('app.theme').'-app.lot.recommended_lots') }}</span></div>

				<div class='loader hidden'></div>
				<div id="lotes_recomendados" class="owl-theme owl-carousel"></div>
				<div class="owl-theme owl-carousel owl-loaded owl-drag m-0 pl-10" id="navs-arrows">
					<div class="owl-nav">
						<div class="owl-prev"><i class="fas fa-chevron-left"></i></div>
						<div class="owl-next"><i class="fas fa-chevron-right"></i></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
$replace = array(
    'emp' => Config::get('app.emp') ,
    'sec_hces1' => $lote_actual->sec_hces1,
    'id_hces1' => $lote_actual->id_hces1,
    'lang' => Config::get('app.language_complete')[''.Config::get('app.locale').''] ,
);

?>




<script>
	var replace = @json($replace);
	var key = "lotes_recomendados";
    $( document ).ready(function() {
        ajax_carousel(key,replace);
     });
</script>



<script>
	var seed;
	$(document).ready(function() {
        //Mostramos la fecha

		$("#cierre_lote").html(format_date_large(new Date("{{$timeCountdown}}".replace(/-/g, "/")),''));

		$('.img-thumbs').on('click', function(){
			seed.goToPage(this.dataset.pos);
		});
	});

    function loadSeaDragon(img){

        var element = document.getElementById("img_main");
        console.log()
        while (element.firstChild) {
          element.removeChild(element.firstChild);
        }
        seed = OpenSeadragon({
        id:"img_main",
        prefixUrl: "/img/opendragon/",

        showReferenceStrip:  false,


        tileSources: [
			@foreach($lote_actual->imagenes as $key => $imagen)
			{
                type: 'image',
                url:  '/img/load/real/{{$imagen}}'
            },
			@endforeach
		],
        showNavigator:false,
		sequenceMode: true,
        });
    }
    loadSeaDragon();




        //Slider vertical lote


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


</script>




@include('includes.ficha.modals_ficha')
