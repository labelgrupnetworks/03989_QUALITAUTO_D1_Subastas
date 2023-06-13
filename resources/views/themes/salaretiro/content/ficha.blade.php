<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <h1 class="titlePage">
				@php
					 $lote_actual->descweb_hces1 = str_replace ('<p>',' ',$lote_actual->descweb_hces1);
                        $lote_actual->descweb_hces1 = str_replace ('</p>',' ',$lote_actual->descweb_hces1);
				@endphp
				{{$lote_actual->ref_asigl0}} - {!!$lote_actual->descweb_hces1!!}

            </h1>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="single">
            <div class="col-xs-12 col-md-7" style="position: relative">
                <div class="col-xs-12 col-sm-10 col-md-10 hidden-xs" >
                    <div class="img_single_border">
                        <div class="button-follow" style="display:none;">
                            <div class="spinner">
                                <div class="double-bounce1"></div>
                                <div class="double-bounce2"></div>
                            </div>
                        </div>
                        @if(Session::has('user') &&  $lote_actual->retirado_asigl0 =='N')
                            <a  class="btn hidden-xs <?= $data['subasta_info']->lote_actual->favorito? 'hidden':'' ?>" id="add_fav" href="javascript:action_fav_modal('add')">
                                <p>{{ trans(\Config::get('app.theme').'-app.lot.add_to_fav') }} </p><i class="fa fa-star-o" aria-hidden="true"></i>
                            </a>
                            <a class="btn  hidden-xs <?= $data['subasta_info']->lote_actual->favorito? '':'hidden' ?>" id="del_fav" href="javascript:action_fav_modal('remove')">
                                <p>{{trans(\Config::get('app.theme').'-app.lot.del_from_fav')}} </p><i class="fa fa-star" aria-hidden="true"></i>
                            </a>
                        @endif
                        @if(($lote_actual->cerrado_asigl0 == 'S' && $lote_actual->lic_hces1 == 'S' && $lote_actual->subc_sub == 'H') ||( $lote_actual->retirado_asigl0 !='N') || ($lote_actual->fac_hces1 == 'D' || $lote_actual->fac_hces1 == 'R')|| ($lote_actual->cerrado_asigl0 == 'S' &&  !empty($lote_actual->himp_csub)))
                            <div class="no_dispo-band">
                                <div class="no_dispo"></div>
                                <p>{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
                            </div>
                        @endif
                        <div id="img_main" class="img_single">
                            <a title="{{$lote_actual->titulo_hces1}}" href="javascript:action_fav_modal('remove')">
                                <img src="/img/load/real/{{ $lote_actual->imagen }}" alt="{{$lote_actual->titulo_hces1}}">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-2 col-md-2 slider-thumnail-container">
                    <div onClick="clickControl(this)" class="row-up control">
                        <i class="fa fa-chevron-up" aria-hidden="true"></i>
                    </div>
                    <div class="miniImg row hidden-xs slider-thumnail">
                        <?php foreach($lote_actual->imagenes as $key => $imagen){?>
                            <div class="col-sm-3-custom">
                                <a href="javascript:loadSeaDragon('<?=$imagen?>');">
                                    <div class="img-openDragon" style="background-image:url('/img/thumbs/100/{{\Config::get("app.emp")}}/{{$lote_actual->num_hces1}}/<?php echo $imagen?>'); background-size: contain; background-position: center; background-repeat: no-repeat;" alt="{{$lote_actual->titulo_hces1}}"></div>
                                </a>
                            </div>
                        <?php

                            }
                        ?>
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

                        <?php foreach($lote_actual->imagenes as $key => $imagen){?>
                            <?php

                            ?>
                               <div class="item_content_img_single" style="position: relative; height: 290px; overflow: hidden;">
                                @if(($lote_actual->cerrado_asigl0 == 'S' && $lote_actual->lic_hces1 == 'S' && $lote_actual->subc_sub == 'H') ||  ( $lote_actual->retirado_asigl0 !='N') || ($lote_actual->fac_hces1 == 'D' || $lote_actual->fac_hces1 == 'R')|| ($lote_actual->cerrado_asigl0 == 'S' &&  !empty($lote_actual->himp_csub)))
                                    <div class="no_dispo-band">
                                        <div class="no_dispo"></div>
                                        <p>{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
                                    </div>
                                @endif
                                       <img style="    max-width: 100%; max-height: 190px;top: 50%; transform: translateY(-50%); position: relative; width: auto !important;    display: inherit !important;    margin: 0 auto !important;" class="img-responsive" src="/img/thumbs/500/{{\Config::get("app.emp")}}/{{$lote_actual->num_hces1}}/<?php echo $imagen?>" alt="{{$lote_actual->titulo_hces1}}">
                               </div>
                         <?php } ?>
                    </div>
                    @if(Session::has('user') &&  $lote_actual->retirado_asigl0 =='N')
                       <a  class="btn hidden-sm hidden-md hidden-lg <?= $data['subasta_info']->lote_actual->favorito? 'hidden':'' ?>" id="add_fav" href="javascript:action_fav_modal('add')">
                           <i class="fa fa-star-o" aria-hidden="true"></i>
                       </a>
                       <a class="btn hidden-sm hidden-md hidden-lg <?= $data['subasta_info']->lote_actual->favorito? '':'hidden' ?>" id="del_fav" href="javascript:action_fav_modal('remove')">
                           <i class="fa fa-star" aria-hidden="true"></i>
                       </a>
                    @endif
                </div>
            </div>
            <div class="col-xs-12 col-md-5">
                <div class="col-xs-12 col-sm-12">

                    @if($lote_actual->retirado_asigl0 =='N' && $lote_actual->fac_hces1 != 'D' && $lote_actual->fac_hces1 != 'R')
                        @if(($lote_actual->cerrado_asigl0 == 'S' && $lote_actual->lic_hces1 != 'S' && $lote_actual->subc_sub == 'H') && (($lote_actual->cerrado_asigl0 == 'S' && empty($lote_actual->himp_csub) && $lote_actual->subc_sub == 'H') || ($lote_actual->formatted_impsalhces_asigl0 == '0' && $lote_actual->cerrado_asigl0 == 'N' && $lote_actual->tipo_sub == 'W')))
                            @include('includes.ficha.consult_lot')
                        @elseif ($lote_actual->subc_sub != 'A'  && $lote_actual->subc_sub != 'S' )
                            @include('includes.ficha.pujas_ficha_cerrada')

                        @elseif($lote_actual->tipo_sub == 'V' && $lote_actual->cerrado_asigl0 != 'S' && strtotime($lote_actual->end_session) > date("now") && $lote_actual->desadju_asigl0 =='N')
                            @include('includes.ficha.pujas_ficha_V')

                    <?php //si un lote cerrado no se ha vendido se podra comprar ?>
                    @elseif( ($lote_actual->tipo_sub == 'W' || $lote_actual->tipo_sub == 'O') && $lote_actual->cerrado_asigl0 == 'S' && empty($lote_actual->himp_csub) && $lote_actual->compra_asigl0 == 'S' && $lote_actual->fac_hces1!='D'  && $lote_actual->desadju_asigl0 =='N')
                        @include('includes.ficha.pujas_ficha_V')

                     @elseif( $lote_actual->tipo_sub == 'W' && ($lote_actual->cerrado_asigl0 != 'S' ))
                        @include('includes.ficha.pujas_ficha_W')

                    @elseif(($lote_actual->tipo_sub == 'O' || $lote_actual->tipo_sub == 'P')&& $lote_actual->cerrado_asigl0 != 'S')
                         @include('includes.ficha.pujas_ficha_O')

                    <?php //puede que este cerrado 'S' o devuelto 'D' ?>
                    @else
                        @include('includes.ficha.pujas_ficha_cerrada')
                    @endif
                @else
                @include('includes.ficha.pujas_ficha_cerrada')
                @endif
                </div>
            </div>


            <div class="col-xs-12 col-sm-12 col-lg-5 pull-right right_row">
                <div class="col-xs-12 col-sm-12">
                    @if((strtoupper($lote_actual->tipo_sub) == 'O' || strtoupper($lote_actual->tipo_sub) == 'P')&& $lote_actual->cerrado_asigl0 != 'S' &&  $lote_actual->retirado_asigl0 =='N')
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
                        @if( \Config::get('app.descweb_hces1'))
                            <p><?= $lote_actual->descweb_hces1 ?></p>
                        @elseif ( \Config::get('app.desc_hces1' ))
                            <p><?= $lote_actual->desc_hces1 ?></p>
                        @endif
                      </div>
              </div>
            </div>
            {{-- <div class="col-xs-12 col-sm-12 lotes_destacados">
                <div class="title_single">
                         {{ trans(\Config::get('app.theme').'-app.lot.recommended_lots') }}
                </div>
                <script>
                < ?php
                    $key = "lotes_recomendados";
                     $replace = array(
                                  'emp' => Config::get('app.emp') ,
                                  'sec_hces1' => $lote_actual->sec_hces1,
                                  'id_hces1' => $lote_actual->id_hces1,
                                  'lang' => Config::get('app.language_complete')[''.Config::get('app.locale').''] ,
                    );

                ?>
                var replace = < ?= json_encode($replace) ?>;
                var key ="< ?= $key ?>";
                $( document ).ready(function() {
                        ajax_carousel(key,replace);
                 });
                </script>
                <div class='loader hidden'></div>
                <div id="lotes_recomendados" class="owl-theme owl-carousel"></div>
            </div> --}}
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


 </script>

@include('includes.ficha.modals_ficha')
