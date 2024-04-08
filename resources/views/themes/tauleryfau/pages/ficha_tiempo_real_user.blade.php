
			<!-- imagen y descripcion -->
            <div class="container-custom zone-user">
                <div class="row info-lot-height align-items-center no-padding flex-wrap">
                    <?php //IMAGE LOTE // ?>
                    <div class="col-lg-6 col-md-6 col-xs-12 h-100">
                        <div id="main_lot_box" class=" h-100">
                            <div id="main_image_box" class=" h-100">
                                <div class="img aside h-100 d-flex align-items-center justify-content-center">
                                    <img width="100%" class="img-lot img-responsive"
                                        src="data:image/jpg;base64,{{ $data['subasta_info']->lote_actual->imagen }}"
                                        style="display:inline">
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php // FIN IMAGEN LOTE // ?>

                    <?php //DESCRIPCIONES + INFO PRECIOS LOTE USER // ?>

                    <div class="col-lg-6 col-md-6 col-xs-12 h-100 ">

                        <div class="aside h-100 d-flex flex-column justify-content-center info-lot-desc" style="">
                            <div style="position: relative">
                                <div id="count_down_msg" class="hidden  notranslate">
                                    <p></p>
                                </div>
                                <?php //DESCRIPCIONES LOTE // ?>
                                <div class="col-xs-12">
                                    <div class=" descripcion started hidden">
                                        <span id="lote_actual_main" class="desc-whith-scroll" style="display:block">
                                            <strong>{{ trans($theme.'-app.sheet_tr.lot') }} <span
                                                    id="info_lot_actual">{{ $data['subasta_info']->lote_actual->ref_asigl0 }}</span></strong>
                                        </span>
                                         <span id="actual_titulo"   class="hidden-xs" style="font-size: 20px;">
                                            <?php echo  $data['text_lang'][$data['js_item']['lang_code']]->titulo_hces1 ?>
                                        </span>
                                        <div id="actual_descripcion" class="hidden-xs" style="">
                                            <?php echo  $data['text_lang'][$data['js_item']['lang_code']]->desc_hces1; ?>
                                        </div>
                                    </div>

                                    <?php //PRECIOS LOTES // ?>

                                </div>
                                <div class="aside prices-lots-content" style=" border-radius: 5px">

                                    <?php //PRECIO DE SALIDA LOTE // ?>
                                    <div class="col-xs-6 prices-lots">
                                        <div id="precioSalida" class="precioSalida salida">
                                            <strong>{{ trans($theme.'-app.sheet_tr.start_price') }}:</strong>
                                            <span>{{ $data['subasta_info']->lote_actual->formatted_impsalhces_asigl0 }}</span>
                                            {{ $data['js_item']['subasta']['currency']->symbol }}
                                        </div>
                                    </div>

                                    <?php //PRECIO ESTIMADO // ?>
                                    <div class="col-xs-6 prices-lots">

                                        <div class="content-your-order text-right salida hidden">
                                            <div class="">
                                                {{ trans($theme.'-app.subastas.precio_estimado') }}:
                                                <span id="imptas">{{ $data['subasta_info']->lote_actual->formatted_imptas_asigl0 }}</span> {{ $data['js_item']['subasta']['currency']->symbol }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <?php //BOTONES + PUJAR LOTE // ?>

                            <div class="buttons-content">
                                <div class="buttons-content col-xs-6">

                                    <?php //PUJA ACTUAL LOTE // ?>
                                    <div class="text-center info-actual-content">
                                        <div id="text_actual_max_bid"
                                            class="fs-20 text-center <?= count($data['subasta_info']->lote_actual->pujas) > 0? '' : 'hidden' ?> ">
                                            {{ trans($theme.'-app.sheet_tr.max_actual_bid') }}
                                        </div>
                                        <span id="text_actual_no_bid"
                                            class=" fs-30 text-center <?= count($data['subasta_info']->lote_actual->pujas) > 0? 'hidden' : '' ?> ">
                                            {{ trans($theme.'-app.sheet_tr.pending_bid') }}
                                        </span>
                                        <span id="actual_max_bid"
                                            class="fs-30 text-center @if (!empty($data['js_item']['user']) && !empty($data['subasta_info']->lote_actual->max_puja) &&  $data['subasta_info']->lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit']) mine @else other @endif">
                                            @if( count($data['subasta_info']->lote_actual->pujas) >0 )
                                            {{ \Tools::moneyFormat($data['subasta_info']->lote_actual->actual_bid) }}
                                            {{ $data['js_item']['subasta']['currency']->symbol }}
                                            @endif
                                        </span>
                                        @if(\Config::get('app.tr_show_canel_bid_client') && Session::has('user'))
                                        <span id="cancelarPujaUser"
                                            class="@if (!empty($data['js_item']['user']) && !empty($data['subasta_info']->lote_actual->max_puja) &&  $data['subasta_info']->lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'])  @else hidden  @endif">{{ trans($theme.'-app.sheet_tr.cancel_bid') }}</span>
                                        @endif
                                    </div>

                                    <div class="convertion text-center">
                                        <div class="convertion-number">
                                            <span id="impsalexchange-actual"> </span>
                                        </div>
                                    </div>

                                </div>


                                <?php //BOTON PUJAR LOTE // ?>
                                <div class="buttons-content col-xs-6">
                                    <input type="hidden" id="tiempo_real" value="1" readonly>
                                    @if(Session::has('user'))
                                        <div>
                                            <a class="add_bid btn btn-success btn-custom-save">
                                                <div class="ls-2 fs-20 font-weight-900 title-bid-button" style="@if (!empty($data['js_item']['user']) && !empty($data['subasta_info']->lote_actual->max_puja) &&  $data['subasta_info']->lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit']) display:none @else @endif">
                                                    {{ trans($theme.'-app.sheet_tr.place_bid') }}</div>
                                                <div class="input-gestor-content" style="position: relative; @if (!empty($data['js_item']['user']) && !empty($data['subasta_info']->lote_actual->max_puja) &&  $data['subasta_info']->lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit']) display:none @else @endif">
                                                    <div id="value-view">
                                                        {{ \Tools::moneyFormat($data['subasta_info']->lote_actual->importe_escalado_siguiente) }}
                                                        {{ $data['js_item']['subasta']['currency']->symbol }}</div>
                                                    <input id="bid_amount" disabled autocomplete="off" type="text"
                                                        class="hide form-control bid_amount_gestor custom"
                                                        value="{{ $data['subasta_info']->lote_actual->importe_escalado_siguiente }}"><span
                                                        style="position: absolute; right: 0; top: -3"></span>
												</div>
												<div class="user-higher-bidder" style="@if (!empty($data['js_item']['user']) && !empty($data['subasta_info']->lote_actual->max_puja) &&  $data['subasta_info']->lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit']) @else display:none @endif">
													<p class="m-0" style="margin: 0">{{ trans($theme.'-app.sheet_tr.higher_bid_es') }}</p>
													<hr style="margin: 0">
													<p class="m-0" style="margin: 0">{{ trans($theme.'-app.sheet_tr.higher_bid_en') }}</p>
												</div>
                                            </a>
                                        </div>
                                        <div class="convertion text-center">
                                            <div class="convertion-number">
                                                <span id="impsalexchange-next"> </span>
                                            </div>
                                        </div>
                                    @else
                                    <div role="button" class="info-actual-content d-flex align-items-center justify-content-center hover-gold" style="border: 0;background: #283747;    border: 0;
                                    background: #283747;
                                    height: 92px;
                                    overflow: hidden;
                                    border-radius: 5px;">
                                            <div class="d-flex align-items-center justify-content-center open_own_box h-100 w-100 hover-gold" style="    background: #283747;
                                            height: 92px;
                                            width: 100%;
                                            color: white;
                                            font-weight: bold;
                                            text-align: center;
                                            font-size: 20px;" data-ref="login">{{ trans($theme.'-app.login_register.ini_tr') }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 mt-1 visible-xs">
                                <span id="actual_titulo" style="font-size: 20px;">
                                     <strong><?php echo  $data['text_lang'][$data['js_item']['lang_code']]->titulo_hces1 ?> </strong>
                                 </span>
                                <div id="actual_descripcion_mobile" class="" style="">

                                    <?php echo  $data['text_lang'][$data['js_item']['lang_code']]->desc_hces1;?>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
            <div class="row info-rows">

				<div class="col-lg-3 col-xs-12 h-100" style="float:right">
                    @if (\Config::get('app.tr_show_pujas'))
                    	<div class="started hidden h-100 last-bids">

							@include('includes.tr.tiempo_real_user.streaming')
                        </div>
                    @endif
                </div>

                <div class="col-lg-9 col-xs-12 tr-tabs h-100">
					<!-- Carrusel de lotes -->
					@include('includes.tr.carrousel_tr')

                    <div class="only-border h-100">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a class="trigger_hist" href="#historic" aria-controls="historic"
                                    role="tab" data-toggle="tab">{{ trans($theme.'-app.sheet_tr.historic') }}</a>
                                </li>
                                    @if((Session::has('user')))
                                        <li id="tab-adj" role="presentation"><a class="trigger_adj" href="#win-bids" aria-controls="win-bids" role="tab"
										data-toggle="tab">{{ trans($theme.'-app.sheet_tr.my_bids') }}</a>
										<div class="alert-adj"></div>
									</li>
                                        <li role="presentation"><a class="trigger_favs" href="#favorites" aria-controls="favorites" role="tab"
                                        data-toggle="tab">{{ trans($theme.'-app.sheet_tr.my_favorites') }}</a></li>

                                        <li id="tab-messages" role="presentation"><a href="#messages" aria-controls="favorites" role="tab"
                                                data-toggle="tab">{{ trans($theme.'-app.sheet_tr.mesagges') }}</a>
                                            <div class="alert-messages" ></div>
                                            </li>
                                    @endif

                        </ul>

                        <!-- Tab panes -->

                        <div class="tab-content">
                            <!-- HISTORICO -->
                            <div role="tabpanel" class="tab-pane active" id="historic">
                                <div   class="update-hist trigger_hist"><span class="hover-gold"  role="button">{{ trans($theme.'-app.sheet_tr.update_tab') }}</span></div>
                                <div class="started hidden zone-tabs">
                                    <div class="adjudicaciones-header">
                                        <div class="adjudicaciones aside col-xs-12" style="padding: 0; border: 0">
                                        <div class="header-tr_tabs col-xs-12 no-padding">
                                            <div class="col-md-2 col-lg-1 col-xs-2 title-tables">{{ trans($theme.'-app.sheet_tr.lot') }}</div>
                                            <div class="col-xs-10 col-sm-8 col-xs-7 col-lg-9 title-tables">{{ trans($theme.'-app.sheet_tr.description') }}</div>
                                            <div class=" col-lg-2 col-sm-2 col-xs-3 col-lg-2  title-tables text-right">{{ trans($theme.'-app.sheet_tr.adjudicate') }}</div>
                                        </div>
                                        <div id="historic_list" class="col-xs-12 no-padding">

                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>

                            @if((Session::has('user')))
                               <!-- ADJUDICACIONES -->
                               <div role="tabpanel" class="tab-pane" id="win-bids">
                               <div    class="update-hist trigger_adj"><span class="hover-gold" role="button">{{ trans($theme.'-app.sheet_tr.update_tab') }}</span></div>

                                <div class="started hidden zone-tabs">
                                    <div class="adjudicaciones-header">
                                        <div class="adjudicaciones aside col-xs-12" style="padding: 0; border: 0">
                                        <div class="header-tr_tabs col-xs-12 no-padding">
                                            <div class="col-md-2 col-lg-1 col-xs-2 title-tables">{{ trans($theme.'-app.sheet_tr.lot') }}</div>
                                            <div class="col-xs-10 col-sm-8 col-xs-7 col-lg-9 title-tables">{{ trans($theme.'-app.sheet_tr.description') }}</div>
                                            <div class=" col-lg-2 col-sm-2 col-xs-3 col-lg-2  title-tables text-right">{{ trans($theme.'-app.sheet_tr.adjudicate') }}</div>
                                        </div>
                                        <div id="adjudicaciones_list" class="col-xs-12 no-padding">

                                        </div>
                                    </div>
                                </div>
                                </div>
                               </div>

                               <!-- FAVORITES -->
                               <div role="tabpanel" class="tab-pane" id="favorites">
                                       <div id="update-fav" role="button" class="update-hist trigger_favs"><span class="hover-gold" role="button">{{ trans($theme.'-app.sheet_tr.update_tab') }}</span></div>
                                       <div class="started hidden zone-tabs ">
                                            <div id="favoritos_list" class="adjudicaciones tabs-favorites-content mt-1">

                                            </div>
                                        </div>
                               </div>

                             <!-- Chat -->
                                <div role="tabpanel" class="tab-pane" id="messages" class="hidden started">
                                    @if (\Config::get('app.tr_show_chat')  )
                                        @include('content.tr.msg_sala')
                                    @endif
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

                </div>
            </div>


<script>
     $(document).ready(function(){
            $('#tab-messages').click(function() {
             $('.alert-messages').removeClass('pending')
            })

            $('.trigger_hist').click(function() {
                 reloadTabHistoric();
            })

             $('.trigger_adj').click(function() {
                 reloadTabAdjudicado();
            })

            $('.trigger_favs').click(function() {
                 reloadTabFavs();
			})

			$('#tab-adj').click(function() {
             $('.alert-adj').removeClass('pending')
            })

            $('#tab-messages a').trigger("click");

     })

    function reloadTabHistoric(){
        historicTab("{{\Config::get('app.locale')}}","{{$data['subasta_info']->lote_actual->cod_sub}}","{{$data['subasta_info']->lote_actual->id_auc_sessions}}" )
    }

     function reloadTabFavs(){
        <?php
            $licit = "";
            if(Session::has('user')){
                $licit = $data['js_item']['user']['cod_licit'];
            }
        ?>
        favoriteTab("{{\Config::get('app.locale')}}","{{$data['subasta_info']->lote_actual->cod_sub}}","{{$licit}}" )
    }

    function reloadTabAdjudicado(){
        <?php
            $licit = "";
            if(Session::has('user')){
                $licit = $data['js_item']['user']['cod_licit'];
            }
        ?>
        adjudicadoTab("{{\Config::get('app.locale')}}","{{$data['subasta_info']->lote_actual->cod_sub}}","{{$data['subasta_info']->lote_actual->id_auc_sessions}}","{{$licit}}" )
    }


    <?php
    //Cargamos el historico
    //Eloy: Comentado para evitar que se ejecute al refrescar la web sobrecarge la base de datos.
    //reloadTabHistoric();
    ?>

</script>
