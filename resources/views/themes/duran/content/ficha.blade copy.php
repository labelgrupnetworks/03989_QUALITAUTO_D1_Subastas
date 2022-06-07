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

                            <?php foreach($lote_actual->imagenes as $key => $imagen){?>
                                   <div class="item_content_img_single" style="position: relative; height: 290px; overflow: hidden;">
                                        <img style="    max-width: 100%; max-height: 190px;top: 50%; transform: translateY(-50%); position: relative; width: auto !important;    display: inherit !important;    margin: 0 auto !important;" class="img-responsive" src="/img/load/lote_medium_large/<?php echo $imagen?>" alt="{{$lote_actual->titulo_hces1}}">
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
                        <div class="retired" style ="">
                            {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
                        </div>
                    @elseif($cerrado &&  (!empty($lote_actual->himp_csub) || ($sub_historica && !empty($lote_actual->impadj_asigl0))))
                        <div class="retired" style ="">
                            {{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
                        </div>
                    @endif

                    <div id="img_main" class="img_single">
                            <a title="{{$lote_actual->titulo_hces1}}" href="javascript:action_fav_modal('remove')">
                            <img class="img-responsive" src="{{Tools::url_img('lote_large',$lote_actual->num_hces1,$lote_actual->lin_hces1)}}" alt="{{$lote_actual->titulo_hces1}}">
                            </a>
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
                    <div class="minis-content">
                        <?php foreach($lote_actual->imagenes as $key => $imagen){?>
                            <div   class="mini-img-ficha">

                                <a href="javascript:loadSeaDragon('<?=$imagen?>');">
                                    <div class="img-openDragon" style="background-image:url('/img/load/lote_small/<?php echo $imagen?>'); background-size: contain; background-position: center; background-repeat: no-repeat;" alt="{{$lote_actual->titulo_hces1}}"></div>

                                </a>
                            </div>
                        <?php } ?>
                </div>
            </div>

    </div>
    </div>

    <div class="col-sm-5 col-xs-12 content-right-ficha d-flex justify-content-space-between flex-column">

       <div class="d-flex  flex-column">
            <div class="ficha-info-title col-xs-12 no-padding">
                    <div class="titleficha col-xs-12 no-padding  secondary-color-text no-padding">
                                {{$lote_actual->ref_asigl0}} - {{$lote_actual->titulo_hces1}}
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
                            @include('includes.ficha.pujas_ficha_V')

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
    <div class="@if($subasta_online && !$cerrado) col-sm-7 @endif col-xs-12 no-padding ficha-tipo-v">

            <div class="col-xs-12 no-padding desc-lot-title d-flex justify-content-space-between">
                    <p class="desc-lot-profile-title">{{ trans(\Config::get('app.theme').'-app.lot.description') }}</p>

            </div>
            <div class="col-xs-12 no-padding desc-lot-profile-content">
                    <p><?= $lote_actual->desc_hces1 ?></p>
            </div>

</div>
    <div class="row">
        <div class="single">
            <div class="col-xs-12 col-md-7">
                </div>


                <div class="col-xs-12 col-sm-12 lotes_destacados">
                        <div class="mas-pujados-title color-letter"><span>{{ trans(\Config::get('app.theme').'-app.lot.recommended_lots') }}</span></div>

                    <div class='loader hidden'></div>
                    <div id="lotes_recomendados" class="owl-theme owl-carousel"></div>
                </div>
            </div>
        </div>
</div>

<?php
$key = "lotes_recomendados";
 $replace = array(
    'emp' => Config::get('app.emp') ,
    'sec_hces1' => $lote_actual->sec_hces1,
    'id_hces1' => $lote_actual->id_hces1,
    'lang' => Config::get('app.language_complete')[''.Config::get('app.locale').''] ,
);

?>




<script>
var replace = <?= json_encode($replace) ?>;
var key ="<?= $key ?>";
    $( document ).ready(function() {
            ajax_carousel(key,replace);
     });
    </script>



<script>
     $(document).ready(function() {
            //Mostramos la fecha

            $("#cierre_lote").html(format_date_large(new Date("{{$timeCountdown}}".replace(/-/g, "/")),''));
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
