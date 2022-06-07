<?php

use App\Models\V5\FgHces1Files;
use App\Models\V5\FgDeposito;

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

$userSession = session('user');
$deposito = (new FgDeposito())->isValid($userSession['cod'], $lote_actual->cod_sub, $lote_actual->ref_asigl0);
$files = FgHces1Files::getAllFilesByLotCanViewUser($userSession, $lote_actual->num_hces1, $lote_actual->lin_hces1, $deposito);

?>


<div class="ficha-content color-letter">
    <div class="container">
        <div class="row">

            <div class="col-sm-7 col-xs-12" style="position: relative">
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

                @include('includes.ficha.header_time')
                <div class="col-xs-12 no-padding col-sm-2 col-md-2 slider-thumnail-container">

                        <div class="owl-theme owl-carousel visible-xs" id="owl-carousel-responsive">
                            @foreach($lote_actual->imagenes as $key => $imagen)
                                   <div class="item_content_img_single" style="position: relative; height: 290px; overflow: hidden;">
									<img style="max-width: 100%; height: auto; position: relative; display: inherit !important; margin: 0 auto !important; max-height: 100%; width: auto;"
										class="img-responsive"
										src="{{Tools::url_img('lote_medium_large',$lote_actual->num_hces1,$lote_actual->lin_hces1, $key)}}"
										alt="{{$lote_actual->titulo_hces1}}">
                                   </div>
                             @endforeach
                        </div>
                        <?php if(!empty($lote_actual->contextra_hces1)) { ?>
                            <div id="360imgMobile" class="img-360-real img-360-mobile-content" style="display:none">

                            </div>
                            <?php } ?>
                        </div>
                        <?php if(!empty($lote_actual->contextra_hces1)) { ?>
                        <div class="visible-xs visible-sm hidden-md hidden-ls">
                                <div id="360imgbtn-mobile" data-active="disabled" class="img-360 img360-mobile col-xs-12"
                                    style=" background-repeat: no-repeat;background-position:center;background-size: contain;background-image: url('/themes/demo/img/<?php echo $lote_actual->imagenes[0]?>')">
                                    <img style="position:relative; z-index: 1;" width="40px"
                                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQyIDc5LjE2MDkyNCwgMjAxNy8wNy8xMy0wMTowNjozOSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpFRDM4OEVEMDNEOUExMUU4QTQ1QkRDOEFFM0VERUZFMyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpFRDM4OEVEMTNEOUExMUU4QTQ1QkRDOEFFM0VERUZFMyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkVEMzg4RUNFM0Q5QTExRThBNDVCREM4QUUzRURFRkUzIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkVEMzg4RUNGM0Q5QTExRThBNDVCREM4QUUzRURFRkUzIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+43smUgAAFppJREFUeNrsXAl0G/WZ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">
                                </div>
                            </div>
                            <?php } ?>


                @if(Session::has('user') &&  !$retirado)
                <div class="col-xs-12 no-padding favoritos visible-xs hidden-sm hidden-md hidden-ls">
                    <a  class="secondary-button  <?= $lote_actual->favorito ? 'hidden' : '' ?>" id="add_fav" href="javascript:action_fav_modal('add')">
                        {{ trans(\Config::get('app.theme').'-app.lot.add_to_fav') }}
                    </a>
                    <a class="secondary-button  <?= $lote_actual->favorito ? '' : 'hidden' ?>" id="del_fav" href="javascript:action_fav_modal('remove')">
                        {{ trans(\Config::get('app.theme').'-app.lot.del_from_fav') }}
                    </a>
                </div>
                @endif

                <div class="col-xs-12 no-padding hidden-xs">
                    @if( $retirado)
                        <div class="retired">
                            {{ trans(\Config::get('app.theme').'-app.lot.retired') }}
                        </div>
                    @elseif($fact_devuelta)
                        <div class="retired" style ="">
                            {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
                        </div>
                    @elseif($cerrado &&  (!empty($lote_actual->himp_csub) || ($sub_historica && !empty($lote_actual->impadj_asigl0))))
                        <div class="retired" style ="">
                            {{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
                        </div>
                    @endif
                    <div class="img-global-content position-relative">

                    <div id="img_main" class="img_single">
                            <a title="{{$lote_actual->titulo_hces1}}" href="javascript:action_fav_modal('remove')">
                            <img class="img-responsive" src="{{Tools::url_img('lote_large',$lote_actual->num_hces1,$lote_actual->lin_hces1)}}" alt="{{$lote_actual->titulo_hces1}}">
                            </a>
                        </div>

                        <div id="360img" class="d-none img-content">
                                <div class="img-360-real">
                                    <?= $lote_actual->contextra_hces1 ?>

                                </div>


                            </div>
                        </div>
                        @if(Session::has('user') &&  !$retirado)
                        <div class="col-xs-12 no-padding favoritos">
                           <a  class="secondary-button  <?= $lote_actual->favorito? 'hidden':'' ?>" id="add_fav" href="javascript:action_fav_modal('add')">
                               {{ trans(\Config::get('app.theme').'-app.lot.add_to_fav') }}
                           </a>
                           <a class="secondary-button  <?= $lote_actual->favorito? '':'hidden' ?>" id="del_fav" href="javascript:action_fav_modal('remove')">
                               {{ trans(\Config::get('app.theme').'-app.lot.del_from_fav') }}
                           </a>
                        </div>
                        @endif
                <div class="col-xs-12 no-padding">
                    <div class="minis-content d-flex flex-wrap">
                        <?php foreach($lote_actual->imagenes as $key => $imagen){?>
                            <div   class="mini-img-ficha no-360">

                                <a href="javascript:loadSeaDragon('<?=$imagen?>');">
                                    <div class="img-openDragon" style="background-image:url('{{ \Tools::url_img("lote_small", $lote_actual->num_hces1, $lote_actual->lin_hces1, $key) }}'); background-size: contain; background-position: center; background-repeat: no-repeat;" alt="{{$lote_actual->titulo_hces1}}"></div>
                                </a>
                            </div>
                        <?php } ?>
                        <div class="mini-img-ficha">

                                <div id="360img-button" data-active="disabled"
                                    class='mini-img-ficha-content img-360-desktop more-img img-360 d-flex align-items-center justify-content-center'
                                    style="display:none; background-repeat: no-repeat;padding: 15px;background-position:center;background-size: contain;background-image: url('{{ \Tools::url_img("lote_small", $lote_actual->num_hces1, $lote_actual->lin_hces1) }}')">
                                    <img class="img-responsive"
                                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQyIDc5LjE2MDkyNCwgMjAxNy8wNy8xMy0wMTowNjozOSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpFRDM4OEVEMDNEOUExMUU4QTQ1QkRDOEFFM0VERUZFMyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpFRDM4OEVEMTNEOUExMUU4QTQ1QkRDOEFFM0VERUZFMyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkVEMzg4RUNFM0Q5QTExRThBNDVCREM4QUUzRURFRkUzIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkVEMzg4RUNGM0Q5QTExRThBNDVCREM4QUUzRURFRkUzIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+43smUgAAFppJREFUeNrsXAl0G/WZ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" />
                                </div>
                            </div>
                </div>
            </div>

    </div>
    </div>

    <div class="col-sm-5 col-xs-12 content-right-ficha d-flex justify-content-space-between flex-column">

       <div class="d-flex  flex-column">
            <div class="ficha-info-title col-xs-12 no-padding">
                    <div class="titleficha col-xs-12 no-padding  secondary-color-text no-padding">
						@php
							$refLot = $lote_actual->ref_asigl0;
							#si  tiene el . decimal hay que ver si se debe separar
							if(strpos($refLot,'.')!==false){

									$refLot =str_replace(array(".1",".2",".3", ".4", ".5"), array("-A", "-B", "-C", "-D", "-E"),  $refLot);

								#si hay que recortar
							}elseif( \config::get("app.substrRef")){
								#cogemos solo los últimos x numeros, ya que se usaran hasta 9, los  primeros para diferenciar un lote cuando se ha vuelto a subir a subasta
								#le sumamos 0 para convertirlo en numero y así eliminamos los 0 a la izquierda
								$refLot = substr($refLot,-\config::get("app.substrRef"))+0;
							}
						@endphp
						{{$refLot}}

						- {!!$lote_actual->descweb_hces1 ?? $lote_actual->titulo_hces1!!}

                    </div>

            </div>

            <?php
            $categorys = new \App\Models\Category();
            $tipo_sec = $categorys->getSecciones($data['js_item']['lote_actual']->sec_hces1);
            ?>
            @if(count($tipo_sec) !== 0)
                <div class="col-xs-12 no-padding fincha-info-cats">
                    <div class="cat">{{ trans(\Config::get('app.theme').'-app.lot.categories') }}</div>
                    @foreach($tipo_sec as $sec)
                        <span class="badge">{{$sec->des_tsec}}</span>
                    @endforeach
                </div>
            @endif
        <div class="ficha-info-content col-xs-12 no-padding h-100 flex-column justify-content-center d-flex">

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

                        @elseif($subasta_venta && !$cerrado && !$end_session)
							@if( \Config::get("app.shoppingCart") )
								@include('includes.ficha.pujas_ficha_ShoppingCart')
							@else

								@include('includes.ficha.pujas_ficha_V')
							@endif

                        <?php //si un lote cerrado no se ha vendido se podra comprar ?>
                        @elseif( ($subasta_web || $subasta_online) && $cerrado && empty($lote_actual->himp_csub) && $compra && !$fact_devuelta)

                            @include('includes.ficha.pujas_ficha_V')
                        <?php //si una subasta es abierta p solo entraremso a la tipo online si no esta iniciada la subasta ?>
                        @elseif( ($subasta_online || ($subasta_web && $subasta_abierta_P && !$start_session)) && !$cerrado)

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
            @if(( $subasta_online  || ($subasta_web && $subasta_abierta_P )) && !$cerrado &&  !$retirado)
                @include('includes.ficha.history')
            @endif
    </div>
        @include('includes.ficha.share')

            </div>
        </div>
    </div>
</div>



<div class="container">
	{{-- ¿? <div class="@if($subasta_online && !$cerrado) col-sm-7 @endif col-xs-12 no-padding ficha-tipo-v"> --}}
    <div class="col-xs-12 no-padding ficha-tipo-v">

            <div class="col-xs-12 no-padding desc-lot-title d-flex justify-content-space-between">
                    <p class="desc-lot-profile-title">{{ trans(\Config::get('app.theme').'-app.lot.description') }}</p>

            </div>
            <div class="col-xs-12 no-padding desc-lot-profile-content">
                    <p><?= $lote_actual->desc_hces1 ?></p>
            </div>

</div>

<section class="container">
	<div class="row">
		<div class="col-xs-12">

			@foreach ($files as $file)
			<p>
				<a href="{{ $file->download_path }}" alt="{{ $file->name_hces1_files }}" target="_blank">
					{{ $file->name_hces1_files }}
				</a>
			</p>
			@endforeach

		</div>
	</div>
</section>


    <div class="row">
        <div class="single">
            <div class="col-xs-12 col-md-7">
                </div>


                <div class="col-xs-12 col-sm-12 lotes_destacados" id="lotes_recomendados-content">
                        <div class="mas-pujados-title color-letter"><span>{{ trans(\Config::get('app.theme').'-app.lot.recommended_lots') }}</span></div>

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




<script>
var replace = <?= json_encode($replace) ?>;
var key ="<?= $key ?>";
    $( document ).ready(function() {
			ajax_newcarousel(key,replace, '{{ $lang }}');


            //Mostramos la fecha

            $("#cierre_lote").html(format_date_large(new Date("{{$timeCountdown}}".replace(/-/g, "/")),''));



            <?php
    if (!empty($lote_actual->contextra_hces1)) {?>


        (function($) {
            var image360 = {
                init: function() {
                    this.cache();
                    this.bindEvents();
                },
                cache: function() {
                    this.btn = $('#360img-button');
                    this.btnDesktop = $('.img-360-desktop');
                    this.backgroundCache = $('.img-360-desktop').css('background-image');
                    this.btnMobile = $('#360imgbtn-mobile');
                    this.btnPic = $('.pic');
                    this.actualImage = $('.zoomPad').find('img');
                    this.img360 = $('#360img');
                    this.img360Mobile = $('#360imgMobile');
                    this.zoomPad = $('.jqzoom');
                    this.gif = $('#360img').find('img');
                    this.gifMobile = $('#360imgMobile img');
                    this.no360 = $('.no-360');
                    this.btnMobile.show();
                    this.btn.show();

                },
                show: function(e) {

                    if (this.btn.attr('data-active') === 'active') {
                        this.hideContainer();

                    } else {
                        this.showContainer(false);
                        //this.loadGif()
                        this.activeBtn();
                    }
                },

                hideContainer: function() {

                    this.img360.addClass('d-none');
                    this.img360Mobile.hide();
                    $('#owl-carousel-responsive').show();
                    this.zoomPad.show();
                    this.disabledBtn();

                },
                showContainer: function(isMobile) {
                    if ($(window).width() > 1199) {
                        // this.img360.css('min-height', '350px');
                    } else {
                        if ($(window).width() > 991) {
                            // this.img360.css('min-height', '400px');
                        }


                    }
                    if ($(window).width() < 991) {
                        this.img360Mobile.append($('.orbitvu-viewer'));
                        // this.img360Mobile.css('min-height', '320px');
                    }

                    this.zoomPad.hide();
                    this.img360.removeClass('d-none');

                    $('#owl-carousel-responsive').hide();
                    this.img360Mobile.show();


                },

                loadGif: function() {


                    this.gif
                        .attr('src', srcImage)
                        .load(function() {
                            $('.loader').hide();
                            $(this).fadeIn();
                        });


                    this.gifMobile
                        .attr('src', srcImage)
                        .load(function() {
                            $('.loader').hide();
                            $(this).fadeIn();
                        });



                },

                activeBtn: function() {

                    this.btnDesktop.css('background-color', '#eee');
                    this.btnDesktop.css('background-image', 'none');
                    this.btnMobile.css('background-color', '#eee');
                    this.btnMobile.css('background-image', 'none');
                    this.btn.attr('data-active', 'active');
                    this.btnMobile.attr('data-active', 'active');
                    this.btnMobile.attr('data-active', 'active');

                },
                disabledBtn: function() {

                    this.btnDesktop.css('background-image', this.backgroundCache);
                    this.btnDesktop.css('background-color', 'transparent');
                    this.btnMobile.css('background-image', this.backgroundCache);
                    this.btnMobile.css('background-color', 'transparent');
                    this.btn.attr('data-active', 'disabled');
                    this.btnMobile.attr('data-active', 'disabled');
                },
                bindEvents: function() {
                    this.btn.on('click', this.show.bind(this));
                    this.btnMobile.on('click', this.show.bind(this));
                    this.btnPic.on('click', this.hideContainer.bind(this));
                    this.no360.on('click', this.hideContainer.bind(this));
                }
            };

            image360.init();

        })($);


        <?php
    } ?>


        });
    function loadSeaDragon(img){

        var element = document.getElementById("img_main");
        console.log()
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
