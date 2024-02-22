
            <!-- imagen y descripcion -->
            <div class="col-lg-8 col-md-8 ">
                <div class="fondo1 aside" style="margin-top:0;">
                    <div class="col-lg-6" style="border-right:0;">
                        <!-- imagen -->
                        <div class="" id="main_lot_box" style="position:relative;">
                            <div id="main_image_box">
                                <!-- INICIADA -->
                                <h4 class="pull-right"><strong></strong></h4>
                                <div class="img" >
                                    <img width="100%" class="img-lot img-responsive" src="data:image/jpg;base64,{{ $data['subasta_info']->lote_actual->imagen }}" style="display: inline">
                                </div>
                                <div id="count_down_msg" class="hidden notranslate">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6" style="border-left:0;">
                        <!-- desc-->
                        <div class=" descripcion started hidden">
                            <span id="lote_actual_main" class="" style="display:block">
                                <strong>{{ trans($theme.'-app.sheet_tr.lot') }} <span id="info_lot_actual">{{ $data['subasta_info']->lote_actual->ref_asigl0 }}</span></strong>
                            </span>
                            <span id="actual_titulo" style="font-size: 20px;">
                                <?php echo  $data['text_lang'][$data['js_item']['lang_code']]->titulo_hces1 ?>
                            </span>
                            <?php // MODO RESPONSIVE PARA DATOS */ ?>
                            @if(empty($data['js_item']['user']['is_gestor']))
                                <div id="salidaResponsive" class="salidaResponsive hidden-sm hidden-md hidden-lg"></div>
                                <?php /*<div id="tusOrdenes" class="tusOrdenes hidden-sm hidden-md hidden-lg">
                                    <p class="yourBid">{{ trans($theme.'-app.sheet_tr.your_actual_bid') }}: </p>
                                    <p class="yourOrder">{{ trans($theme.'-app.sheet_tr.your_actual_order') }}: </p>
                                </div>*/ ?>
                                <div id="actualResponsive" class="actualResponsive hidden-sm hidden-md hidden-lg"></div>
                                <div id="" class="pujarResponsive hide">
                                    <div id="inputResponsive"></div>
                                    <div id="btnPujarResponsive"></div>
                                </div>
                                <div id="estimadoResponsive" class="estimadoResponsive hidden-sm hidden-md hidden-lg"></div>
                            @endif
                            <?php // FIN MODO RESPONSIVE PARA DATOS */ ?>

                            <div id="actual_descripcion" class="hidden-xs hidden-sm" style="">
                                <?php echo  $data['text_lang'][$data['js_item']['lang_code']]->desc_hces1 ?>
                            </div>
                            <?php /* quitar preico estimado
                             <div id="precioestimado" class="text-center" style=" padding-top: 10px;">
                                <p>
                                    <strong>{{ trans($theme.'-app.subastas.estimate') }}:</strong>
                                    <span id="imptas" >{{ $data['subasta_info']->lote_actual->formatted_imptas_asigl0}} </span>-<span id="imptash" >  {{ $data['subasta_info']->lote_actual->formatted_imptash_asigl0}} </span> {{ $data['js_item']['subasta']['currency']->symbol }}
                                </p>
                            </div>
                            */ ?>
                            <div class="tr-price-control-content col-xs-12">
                                <div class="col-xs-6">
                                    <div id="precioSalida" class="precioSalida salida">
                                        <strong>{{ trans($theme.'-app.sheet_tr.start_price') }}:</strong>
                                        <span>{{ $data['subasta_info']->lote_actual->formatted_impsalhces_asigl0 }}</span> {{ $data['js_item']['subasta']['currency']->symbol }}
                                    </div>
                                    <div class="pactual salida">
                                        <div  class="text-center info-actual-content">
                                            <div id="text_actual_max_bid" class="fs-20 text-center <?= count($data['subasta_info']->lote_actual->pujas) > 0? '' : 'hidden' ?> ">
                                                {{ trans($theme.'-app.sheet_tr.max_actual_bid') }}
                                            </div>
                                            <span id="text_actual_no_bid" class="<?= count($data['subasta_info']->lote_actual->pujas) > 0? 'hidden' : '' ?> ">
                                                {{ trans($theme.'-app.sheet_tr.pending_bid') }}
                                            </span>
                                            <span id="actual_max_bid" class="fs-30 text-center @if (!empty($data['js_item']['user']) && !empty($data['subasta_info']->lote_actual->max_puja) &&  $data['subasta_info']->lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit']) mine @else other @endif">
                                                @if( count($data['subasta_info']->lote_actual->pujas) >0 )
                                                    {{ \Tools::moneyFormat($data['subasta_info']->lote_actual->actual_bid) }} {{ $data['js_item']['subasta']['currency']->symbol }}
                                                @endif
                                            </span>

                                                <div class="btn btn-danger d-block mh-10 ">
                                                    <span id="cancelarPuja" >{{ trans($theme.'-app.sheet_tr.cancel_bid') }}</span>
                                                </div>
                                                <div class="btn btn-danger mt-10 d-block mh-20 ">
                                                    <span id="cancelarOrden" >{{ trans($theme.'-app.sheet_tr.cancel_order') }}</span>
                                                </div>


                                        </div>

                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="">

                                        <input type="hidden" id="tiempo_real" value="1" readonly>
                                        <div style="padding-top: 5px">

                                                <!-- Panel de puja del gestor -->
                                                <div id="controles_puja_gestor">
                                                    <div  style="margin-bottom:20px;">
                                                        <input id="bid_amount"  autocomplete="off" type="text" class="form-control bid_amount_gestor" value="{{ $data['subasta_info']->lote_actual->importe_escalado_siguiente }}">
                                                    </div>
                                                    <div class="controles_tipo_puja">
                                                        <i data-type="S" class="add_bid fa fa-3x fa-hand-paper-o" aria-hidden="true"></i>
                                                        <i data-type="I" style="padding:0 30px 0 30px" class="add_bid fa fa-3x fa fa-wikipedia-w" aria-hidden="true"></i>
                                                        <i data-type="T" class="add_bid fa fa-3x fa-phone" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                                <div class="gestor_radios">
                                                    <label class="radio">
                                                        <input checked="checked" type="radio" name="puja_opts" value="normal"> {{ trans($theme.'-app.sheet_tr.order_bid') }}
                                                    </label>
                                                    <label class="radio">
                                                        <input type="radio" name="puja_opts" value="firme"> {{ trans($theme.'-app.sheet_tr.direct_bid') }}
                                                    </label>
                                                </div>
                                                <div  style="margin-top:20px;">
                                                    <input type="text" class="form-control" id="ges_cod_licit" name="ges_cod_licit" placeholder="nº Licitador"/>
                                                </div>


                                        </div>

                                    </div>
                            </div>
                            </div>
                            <!-- puja actual -->
                            <div id="actual_descripcion" class="hidden-lg hidden-md col-xs-12 pd-0 mt-10" style="">
                                <?php echo  $data['text_lang'][$data['js_item']['lang_code']]->desc_hces1 ?>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

            <!-- inicio pujas -->
            <div class="col-sm-4 col-lg-4 fondo2">
                @if (\Config::get('app.tr_show_pujas'))
                <div class="started hidden">
                    <div class="aside pujas">

                        <h2>{{ trans($theme.'-app.sheet_tr.last_bids') }}</h2>
                            <div id="pujas_list">

                            <?php
                            $ultima_orden =false;
                            foreach ($data['subasta_info']->lote_actual->pujas as $puja) : ?>

                                <?php
                                    $lat_order = false;
                                    foreach($data['subasta_info']->lote_actual->ordenes as $ordenes){
                                        if(!$ultima_orden && $puja->cod_licit == $ordenes->cod_licit && $puja->formatted_imp_asigl1 == $ordenes->himp_orlic_formatted &&  $puja->type_asigl1 == 'A')
                                         $lat_order = true;
                                        $ultima_orden = true;
                                    }
                                    /*Nombre de los licitadores*/
                                    $name_licit = '-';
                                    if(!empty($data['licitadores']) && !empty($data['js_item']['user']['is_gestor']) && $puja->cod_licit != Config::get('app.dummy_bidder')  ){
                                        $name_licit = !empty($data['licitadores'][$puja->cod_licit])? $data['licitadores'][$puja->cod_licit] : "-" ;
                                    }
                                    /*Fin de nombre de los licitadores*/

                                ?>
                                    <div class="pujas_model col-xs-12">
                                        <div class="col-lg-3 tipoPuja">
                                            <p data-type="I" @if ($puja->pujrep_asigl1 != 'I')class="hidden" @endif><i class="fa fa-wikipedia-w" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-web') }}</p>
                                            <p data-type="S" @if ($puja->pujrep_asigl1 != 'S')class="hidden" @endif><i class="fa fa-hand-paper-o" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-sala') }}</p>
                                            <p data-type="T" @if ($puja->pujrep_asigl1 != 'T' && $puja->pujrep_asigl1 != 'B')class="hidden" @endif><i class="fa fa-phone" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-telf') }}</p>
                                            <p data-type="E" @if ($puja->pujrep_asigl1 != 'E' && $puja->pujrep_asigl1 != 'P') class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i>  {{ trans($theme.'-app.sheet_tr.books_bid') }}</p>
                                            <p data-type="W" @if ($puja->pujrep_asigl1 != 'W')class="hidden" @endif><i class="fa fa-wikipedia-w" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-web') }}</p>
                                            <p data-type="O" @if ($puja->pujrep_asigl1 != 'O')class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.books_bid') }}</p>
                                            <p data-type="U" @if ($puja->pujrep_asigl1 != 'U')class="hidden" @endif><i class="fa fa-wikipedia-w" aria-hidden="true"></i> Subalia</p>
                                        </div>
                                        <div class="col-lg-6 importePuja">
                                            <p>
                                            <span>{{ $puja->formatted_imp_asigl1 }}</span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
                                            <?php if(!empty($data['js_item']['user']['is_gestor'])) { ?>
                                                <span class="licitadorPuja">({{ $puja->cod_licit }}) <span style="font-size: 12px;"> {{$name_licit}}</span></span>
                                            <?php } ?>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 ordenes">
                                            @if($lat_order)
                                            <span>{{ trans($theme.'-app.sheet_tr.last_order') }}</span>
                                            @endif
                                        </div>
                                    </div>

                            <?php endforeach;?>

                            <div class="pujas_model hidden col-xs-12" id="type_bid_model">
                                <div class="col-lg-3 tipoPuja">
                                    <p data-type="I"><i class="fa fa-wikipedia-w" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-web') }}</p>
                                    <p data-type="S" class="hidden"><i class="fa fa-hand-paper-o" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-sala') }}</p>
                                    <p data-type="T" class="hidden"><i class="fa fa-phone" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-telf') }}</p>
                                    <p data-type="B" class="hidden"><i class="fa fa-phone" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-telf') }}</p>
                                    <p data-type="E" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.books_bid') }}</p>
                                    <p data-type="P" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.books_bid') }}</p>
                                    <p data-type="W" class="hidden"><i class="fa fa-wikipedia-w" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-web') }}</p>
                                    <p data-type="O" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.books_bid') }}</p>
                                    <p data-type="U" class="hidden"><i class="fa fa-wikipedia-w" aria-hidden="true"></i> Subalia</p>
                                </div>
                                <div class="col-lg-6 importePuja">
                                    <p>
                                        <span class="puj_imp"></span>
                                        <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
                                        <?php if(!empty($data['js_item']['user']['is_gestor'])) { ?>
                                            <span class="licitadorPuja"></span>
                                        <?php } ?>

                                    </p>
                                </div>
                                <div class="col-lg-3 ordenes">
                                    <span class="orden hidden">{{ trans($theme.'-app.sheet_tr.last_order') }}</span>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <!-- fin pujas -->




            <!-- inicio lista ordenes de licitacion -->
            <div class="col-lg-4 started hidden">
                <div class="aside ol">

                    <h2>{{ trans($theme.'-app.sheet_tr.orders') }}</h2>
                        <div id="ol_list">

                        <?php foreach ($data['subasta_info']->lote_actual->ordenes as $orden) : ?>
                                <?php
                                /*Nombre de los licitadores*/
                                $name_licit = '-';
                                if(!empty($data['licitadores']) && !empty($data['js_item']['user']['is_gestor']) && $orden->cod_licit != Config::get('app.dummy_bidder')){
                                    $name_licit = !empty($data['licitadores'][$orden->cod_licit])? $data['licitadores'][$orden->cod_licit] : "-" ;
                                }
                                /*Fin de nombre de los licitadores*/
                                ?>
                                <div class="ol_model">
                                    <div class="col-lg-6 tipoOrden">
                                        <p data-type="I" @if ($orden->tipop_orlic != 'I')class="hidden" @endif><i class="fa fa-wikipedia-w" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-web') }}</p>
                                        <p data-type="W" @if ($orden->tipop_orlic != 'W')class="hidden" @endif ><i class="fa fa-wikipedia-w" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-web') }}</p>
                                        <p data-type="S" @if ($orden->tipop_orlic != 'S')class="hidden" @endif><i class="fa fa-hand-paper-o" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-sala') }}</p>
                                        <p data-type="T" @if ($orden->tipop_orlic != 'T')class="hidden" @endif><i class="fa fa-phone" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-telf') }}</p>
                                        <p data-type="E" @if ($orden->tipop_orlic != 'E' && $orden->tipop_orlic != 'P')class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.books_bid') }}</p>
                                        <p data-type="O" @if ($orden->tipop_orlic != 'O')class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.books_bid') }}</p>
                                        <p data-type="U" @if ($orden->tipop_orlic != 'U')class="hidden" @endif><i class="fa fa-wikipedia-w" aria-hidden="true"></i> Subalia</p>
                                 </div>

                                    <div class="col-lg-6 importeOrden">
                                        <p>
                                            <span class="puj_imp_order">{{ \Tools::moneyFormat($orden->himp_orlic) }}</span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
                                            <?php if(!empty($data['js_item']['user']['is_gestor'])) { ?>
                                                <span class="licitadorOrden">({{ $orden->cod_licit }})  <span style="font-size: 12px;"> {{$name_licit}}</span></span>
                                            <?php } ?>

                                        </p>
                                    </div>
                                </div>

                        <?php endforeach;?>

                        <div class="ol_model hidden" id="type_bid_model_order">
                            <div class="col-lg-6 tipoOrden">
                                <p data-type="I"><i class="fa fa-wikipedia-w" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-web') }}</p>
                                <p data-type="W"><i class="fa fa-wikipedia-w" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-web') }}</p>
                                <p data-type="S" class="hidden"><i class="fa fa-hand-paper-o" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-sala') }}</p>
                                <p data-type="T" class="hidden"><i class="fa fa-phone" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-telf') }}</p>
                                <p data-type="E" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.books_bid') }}</p>
                                <p data-type="P" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.books_bid') }}</p>
                                <p data-type="O" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.books_bid') }}</p>
                                <p data-type="U" class="hidden"><i class="fa fa-wikipedia-w" aria-hidden="true"></i> Subalia</p>
                            </div>
                            <div class="col-lg-6 importeOrden">
                                <p>

                                        <span class="licitadorOrden"></span>

                                    <span class="puj_imp_order"></span>
                                    <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- fin lista ordenes de licitacion -->








    </div>

     <!-- mensajes en sala chat -->
            <div class="col-sm-4 col-lg-6 hidden started" >

                @include('content.tr.msg_sala')

            </div>



            <!-- Buscador -->
            <div class="col-sm-12 col-lg-6 hidden started">

                    @include('content.tr.buscador')

            </div>

</div> <!-- row -->

       <div id="controles_gestor_box">
           <div class="gestor_buttons">
               <p>{{ trans($theme.'-app.sheet_tr.user_conectet') }} <span id="users_conectet"></span></p>
                <button class="change_end_lot btn" data-status="end" type="button">{{ trans($theme.'-app.sheet_tr.end_lot') }}</button>
                <button class="change_end_lot btn hidden" data-status="cancel" type="button">{{ trans($theme.'-app.sheet_tr.cancel_end_lot') }}</button>

                <button class="change_auction_status btn @if ($data['js_item']['subasta']['status'] == 'stopped') hidden @endif" data-status="stopped" class="btn" type="button">{{ trans($theme.'-app.sheet_tr.stop_auction') }}</button>
                <button class="change_auction_status btn @if ($data['js_item']['subasta']['status'] == 'stopped') hidden @endif" data-status="stopped-time" class="btn" type="button">{{ trans($theme.'-app.sheet_tr.put_off_auction') }}</button>
                <button class="change_auction_status btn @if ($data['js_item']['subasta']['status'] == 'in_progress') hidden @endif" data-status="in_progress" class="btn" type="button">{{ trans($theme.'-app.sheet_tr.restart_lot') }}</button>

                <button id="msg_predef" class="btn" type="button">{{ trans($theme.'-app.sheet_tr.msg_predef') }}</button>
                <button id="show_stopped_lots" class="btn" type="button">{{ trans($theme.'-app.sheet_tr.show_stopped_lots') }}</button>
                <button id="show_stopped_lots_disabled" class="btn hidden" style="background:red" type="button">{{ trans($theme.'-app.sheet_tr.show_stopped_lots') }}</button>
                <button id="jump_to_lots" class="btn" type="button">{{ trans($theme.'-app.sheet_tr.jump_to_lots') }}</button>
                <button id="jump_to_lots_disabled" class="btn hidden" style="background:red" type="button">{{ trans($theme.'-app.sheet_tr.jump_to_lots') }}</button>
                <button id="baja_client" class="btn" type="button">{{ trans($theme.'-app.sheet_tr.baja_client') }}</button>
                <button id="baja_client_disabled" class="btn hidden" style="background:red" type="button">{{ trans($theme.'-app.sheet_tr.baja_client') }}</button>

                <?php /*automátic auctions */ ?>
                @if (\Config::get('app.tr_show_automatic_auction'))
                <div>
                    <button id="automatic_auction" style="background: #337ab7;    display: inline-block;    width: 170px;" class="btn" type="button">{{ trans($theme.'-app.sheet_tr.automatic_auction') }}</button>
                    <input style="display: inline-block; width: 55px;height: 34px;vertical-align: bottom;padding-left: 5px;" id="seconds_automatic_auctions" type="text" placeholder="Segundos" value="<?= !empty(Config::get('app.seconds_automatic_auction'))? Config::get('app.seconds_automatic_auction') : '5'  ?>" >
                </div>
                <div style="height: 20px">
                    <span id="msg_contador_automatico" ></span>
                </div>
                @endif
            </div>
           <div class="desplegable">
               <i class="fa fa-angle-right" aria-hidden="true"></i>
           </div>
       </div>


