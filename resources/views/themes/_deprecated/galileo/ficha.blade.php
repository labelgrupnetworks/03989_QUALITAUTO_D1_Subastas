<div class="ficha-content color-letter">
    <div class="container">
        <div class="row">
            <div class="ficha-info-title col-xs-12">
                <div class="titleficha secondary-color-text">
                    @if(\Config::get('app.ref_asigl0') && \Config::get('app.descweb_hces1'))
                        {{$lote_actual->ref_asigl0}} - <?= $lote_actual->descweb_hces1 // str_replace(array("<p>","<br>","</p>"),"",$lote_actual->descweb_hces1) ?>
                    @elseif(!\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1'))
                        {{$lote_actual->titulo_hces1}}    
                    @elseif(\Config::get('app.ref_asigl0'))
                        {{trans(\Config::get('app.theme').'-app.lot.lot-name')}}  {{$lote_actual->ref_asigl0}} 
                    @endif
                </div>
            </div>
            <div class="col-sm-7 col-xs-12" style="position: relative">
                    @if(Session::has('user') &&  $lote_actual->retirado_asigl0 =='N')
                    <div class="col-xs-12 no-padding favoritos">
                       <a  class="secondary-button  <?= $lote_actual->favorito? 'hidden':'' ?>" id="add_fav" href="javascript:action_fav_modal('add')">
                           {{ trans(\Config::get('app.theme').'-app.lot.add_to_fav') }}
                       </a>
                       <a class="secondary-button  <?= $lote_actual->favorito? '':'hidden' ?>" id="del_fav" href="javascript:action_fav_modal('remove')">
                           {{ trans(\Config::get('app.theme').'-app.lot.del_from_fav') }}
                       </a> 
                    </div> 
                    @endif
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
                        @if( $lote_actual->retirado_asigl0 !='N')
                        <div class="retired">
                            {{ trans(\Config::get('app.theme').'-app.lot.retired') }}
                        </div>
                    @elseif($lote_actual->fac_hces1 == 'D' || $lote_actual->fac_hces1 == 'R')
                        <div class="retired" style ="">
                            {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
                        </div>
                    @elseif($lote_actual->cerrado_asigl0 == 'S' &&  !empty($lote_actual->himp_csub))
                        <div class="retired" style ="">
                            {{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
                        </div>
                    @endif
                        <div id="img_main" class="img_single">
                                <a title="{{$lote_actual->titulo_hces1}}" href="javascript:action_fav_modal('remove')">
                                <img class="img-responsive" src="{{Tools::url_img('lote_large',$lote_actual->num_hces1,$lote_actual->lin_hces1)}}" alt="{{$lote_actual->titulo_hces1}}">
                                </a>
                            </div>
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
            @include('includes.ficha.share')
        </div>
    </div>
    <div class="col-sm-5 col-xs-12">
        <div class="ficha-info-content col-xs-12 no-padding" >
            <div class="ficha-info-items">
                    @if($lote_actual->retirado_asigl0 =='N' && $lote_actual->fac_hces1 != 'D' && $lote_actual->fac_hces1 != 'R')
                        @if ($lote_actual->subc_sub != 'A'  && $lote_actual->subc_sub != 'S' )
                            @include('includes.ficha.pujas_ficha_cerrada')
                        @elseif($lote_actual->tipo_sub == 'V' && $lote_actual->cerrado_asigl0 != 'S' && strtotime($lote_actual->end_session) > date("now"))
                            @include('includes.ficha.pujas_ficha_V')
                                <?php //si un lote cerrado no se ha vendido se podra comprar ?> 
                            @elseif( ($lote_actual->tipo_sub == 'W' || $lote_actual->tipo_sub == 'O') && $lote_actual->cerrado_asigl0 == 'S' && empty($lote_actual->himp_csub) && $lote_actual->compra_asigl0 == 'S' && $lote_actual->fac_hces1!='D')
                                @include('includes.ficha.pujas_ficha_V')
                             @elseif( $lote_actual->tipo_sub == 'W' && ($lote_actual->cerrado_asigl0 != 'S' ))
                                @include('includes.ficha.pujas_ficha_W')
                            @elseif(($lote_actual->tipo_sub == 'O' || $lote_actual->tipo_sub == 'P')&& $lote_actual->cerrado_asigl0 != 'S')
                                 @include('includes.ficha.pujas_ficha_O')
                                <?php //puede que este cerrado 'S' o devuelto 'D' ?>
                            @else
                                @include('includes.ficha.pujas_ficha_cerrada')
                            @endif
                        @endif
                        
                    </div>
                    
                            @if((strtoupper($lote_actual->tipo_sub) == 'O' || strtoupper($lote_actual->tipo_sub) == 'P')&& $lote_actual->cerrado_asigl0 != 'S' &&  $lote_actual->retirado_asigl0 =='N')
                            <div class="col-xs-12 col-sm-12 col-lg-7 pull-right historic-right_row no-padding">
                            @include('includes.ficha.history')
                        </div>
                         @endif
                    
                </div>
                
                
            </div>
        </div>
    </div>
</div>



<div class="container">
    <div class="row">
        <div class="single">
            <div class="col-xs-12 col-md-7">

               
                </div>


                <?php /*

                <div class="col-xs-12">
                        <ul class="nav nav-tabs ficha-info-nav-titles" id="info-ficha" role="tablist">
                                <li class="nav-item active">
                                  <a class="nav-link ficha-info-nav-title" id="first-tab" data-toggle="tab" href="#first" role="tab" aria-controls="home" aria-selected="true">{{ trans(\Config::get('app.theme').'-app.lot.description') }}</a>
                                </li>

                              </ul>
                              <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active in" id="first" role="tabpanel" aria-labelledby="first-tab">
                                    <div class="info-ficha-desc_content">das
                                            <p><?= $lote_actual->desc_hces1 ?></p>
                                    </div>
                                </div>
                              </div>
                </div>
                                              */?>




                <div class="col-xs-12 col-sm-12 lotes_destacados">
                        <div class="mas-pujados-title color-letter"><span>{{ trans(\Config::get('app.theme').'-app.lot.recommended_lots') }}</span></div>
                    <script>
                    <?php
                        $key = "lotes_recomendados";
                         $replace = array(
                                      'emp' => Config::get('app.emp') ,
                                      'sec_hces1' => $lote_actual->sec_hces1,
                                      'id_hces1' => $lote_actual->id_hces1,
                                      'sub_hces1' => $lote_actual->sub_hces1,
                                      'lang' => Config::get('app.language_complete')[''.Config::get('app.locale').''] ,
                        );

                    ?>
                    var replace = <?= json_encode($replace) ?>;
                    var key ="<?= $key ?>";
                    $( document ).ready(function() {
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