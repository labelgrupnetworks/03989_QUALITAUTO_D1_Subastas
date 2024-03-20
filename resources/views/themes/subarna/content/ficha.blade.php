@php
$cerrado = $lote_actual->cerrado_asigl0 == 'S';
$cerrado_N = $lote_actual->cerrado_asigl0 == 'N';
$hay_pujas = count($lote_actual->pujas) >0;
$devuelto= $lote_actual->cerrado_asigl0 == 'D';
$remate = $lote_actual->remate_asigl0 =='S';
$compra = $lote_actual->compra_asigl0 == 'S';
$subasta_online = ($lote_actual->tipo_sub == 'P' || $lote_actual->tipo_sub == 'O');
$subasta_venta = $lote_actual->tipo_sub == 'V' ;
$subasta_web = $lote_actual->tipo_sub == 'W' ;
$subasta_abierta_O = $lote_actual->subabierta_sub == 'O';
$subasta_abierta_P = $lote_actual->subabierta_sub == 'P';
$retirado = $lote_actual->retirado_asigl0 !='N';
$sub_historica = $lote_actual->subc_sub == 'H';
$sub_cerrada = ($lote_actual->subc_sub != 'A'  && $lote_actual->subc_sub != 'S');
$remate = $lote_actual->remate_asigl0 =='S';
$awarded = \Config::get('app.awarded');
// D = factura devuelta, R = factura pedniente de devolver
$fact_devuelta = ($lote_actual->fac_hces1 == 'D' || $lote_actual->fac_hces1 == 'R');
$fact_N = $lote_actual->fac_hces1=='N';
$start_session = strtotime("now") > strtotime($lote_actual->start_session);
$end_session = strtotime("now")  > strtotime($lote_actual->end_session);

$start_orders =strtotime("now") > strtotime($lote_actual->orders_start);
$end_orders = strtotime("now") > strtotime($lote_actual->orders_end);

$auctionName = match ($lote_actual->tipo_sub) {
	'P' => trans("$theme-app.foot.online_auction"),
	'V' => trans("$theme-app.subastas.lot_subasta_venta"),
	'W' => trans("$theme-app.subastas.lot_subasta_presencial"),
	'O' => trans("$theme-app.subastas.lot_subasta_online"),
	default => trans("$theme-app.subastas.lot_subasta_presencial"),
};
$dateFormat = Tools::getDateFormatDayMonthLocale($lote_actual->start_session);

@endphp


<div class="ficha-content container-fluid">
	<div class="ficha-grid" data-tipe-sub="{{ $lote_actual->tipo_sub }}">

		{{-- image --}}
		<section class="ficha-image">
			@include('includes.ficha.ficha_image')
		</section>

		{{-- minatures --}}
		<section class="ficha-miniatures">
			@include('includes.ficha.ficha_miniatures')
		</section>

		{{-- title --}}
		<section class="ficha-title">
			<p>
				{{ $auctionName . ' - ' . $dateFormat }}
			</p>
			<h1>
				{!! strip_tags ($lote_actual->descweb_hces1) !!}
			</h1>
		</section>

		{{-- block pujas --}}
		<section class="ficha-pujas">
			@include('includes.ficha.ficha_pujas')
		</section>
	</div>

	{{-- description --}}
	<section class="ficha-desciption">
		<h2>{{ trans("$theme-app.lot.description") }}</h2>
		<p>
			{!! $lote_actual->desc_hces1 !!}
		</p>
	</section>

	{{-- recommended lots --}}
	@php
	$replace = [
	'emp' => Config::get('app.emp') ,
	'sec_hces1' => $lote_actual->sec_hces1,
	'id_hces1' => $lote_actual->id_hces1,
	'lang' => Config::get('app.language_complete')[''.Config::get('app.locale').''] ,
	];
	@endphp
	<section class="lotes_destacados">
		<h2>{{ trans("$theme-app.lot.recommended_lots") }}</h2>
		<div class='loader hidden'></div>
		<div id="lotes_recomendados" class="owl-theme owl-carousel"></div>

		<script>
			const replace = @json($replace);
			$(document).ready(function(){

ajax_carousel("lotes_recomendados", replace);

});
		</script>
	</section>
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
