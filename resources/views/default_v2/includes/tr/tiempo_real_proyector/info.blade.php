<div class="tr_proyector_info">
    <div class="lot">
        <span id="lote_actual_main" class="">{{ trans(\Config::get('app.theme').'-app.sheet_tr.lot') }} <strong><span id="info_lot_actual">


			{{ str_replace(array(".1",".2",".3", ".4", ".5"), array("-A", "-B", "-C", "-D", "-E"),  $data['subasta_info']->lote_actual->ref_asigl0)}}



		</span></strong></span>
    </div>
    <div class="starting-price">
        <div id="precioSalida" class="precioSalida salida text-center">
            <p>{{ trans(\Config::get('app.theme').'-app.sheet_tr.start_price') }}:</p>
            <p><span>{{ $data['subasta_info']->lote_actual->formatted_impsalhces_asigl0 }}</span> {{ $data['js_item']['subasta']['currency']->symbol }}</p>
        </div>
    </div>
    <div class="current-bid">
        <div class="salida text-center">
            <p>
                <span id="text_actual_max_bid" class="<?= count($data['subasta_info']->lote_actual->pujas) > 0 ? '' : 'hidden' ?> ">
                    {{ trans(\Config::get('app.theme').'-app.sheet_tr.max_actual_bid') }}
                </span>
                <span id="text_actual_no_bid" class="<?= count($data['subasta_info']->lote_actual->pujas) > 0 ? 'hidden' : '' ?> ">
                    {{ trans(\Config::get('app.theme').'-app.sheet_tr.pending_bid') }}
                </span>
            </p>
            <p><strong>
                    <span id="actual_max_bid" class="@if (!empty($data['js_item']['user']) && !empty($data['subasta_info']->lote_actual->max_puja) &&  $data['subasta_info']->lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit']) mine @else other @endif">
                        @if( count($data['subasta_info']->lote_actual->pujas) >0 )
                        {{ \Tools::moneyFormat($data['subasta_info']->lote_actual->actual_bid) }} {{ $data['js_item']['subasta']['currency']->symbol }}
						@endif
					</span>
                </strong>
            </p>

        </div>
	</div>
	<div class="next-bid">
        <div id="next-bid_JS" class=" text-center">
            <p>{{ trans(\Config::get('app.theme').'-app.lot.next_min_bid') }}:</p>
            <p><span id="next_bid_JS">{{ \Tools::moneyFormat($data['subasta_info']->lote_actual->importe_escalado_siguiente) }}</span> {{ $data['js_item']['subasta']['currency']->symbol }}</p>
        </div>
    </div>
    <div class="last-bids-container">
        <div class="last-bids">
            @if (\Config::get('app.tr_show_pujas'))
            <div class="started hidden">
                <div class="aside pujas">

                    <p class="last-bids-title">{{ trans(\Config::get('app.theme').'-app.sheet_tr.last_bids') }}</p>
                    <div id="pujas_list" class="pujas_list" style="height: 320px !important;">

                        <?php foreach ($data['subasta_info']->lote_actual->pujas as $puja) : ?>
                            <div class="pujas_model row">
                                <div class="col-md-2 col-lg-2"></div>
                                <div class="col-md-4 col-lg-4 tipoPuja">
                                    <p data-type="I" @if ($puja->pujrep_asigl1 != 'I')class="hidden" @endif><i class="fa fa-globe" aria-hidden="true"></i> </p>
                                    <p data-type="S" @if ($puja->pujrep_asigl1 != 'S')class="hidden" @endif><i class="fa fa-hand-paper-o" aria-hidden="true"></i> </p>
                                    <p data-type="T" @if ($puja->pujrep_asigl1 != 'T' && $puja->pujrep_asigl1 != 'B')class="hidden" @endif><i class="fa fa-phone" aria-hidden="true"></i> </p>
                                    <p data-type="E" @if ($puja->pujrep_asigl1 != 'E' && $puja->pujrep_asigl1 != 'P') class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i> </p>
                                    <p data-type="W" @if ($puja->pujrep_asigl1 != 'W')class="hidden" @endif><i class="fa fa-wikipedia-w" aria-hidden="true"></i> </p>
                                    <p data-type="O" @if ($puja->pujrep_asigl1 != 'O')class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i></p>
                                    <p data-type="U" @if ($puja->pujrep_asigl1 != 'U')class="hidden" @endif><i class="fab fa-stripe-s" aria-hidden="true"></i></p>
									<p data-type="R" @if ($puja->pujrep_asigl1 != 'R')class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i></p>

                                </div>
                                <div class="col-md-4 col-lg-4 importePuja">
                                    <p><span>{{ $puja->formatted_imp_asigl1 }}</span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span></p>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <?php /* Mostrar o no codigo licitador
                                      @if (!empty($data['js_item']['user']['is_gestor']))
                                      <span class="licitadorPuja">({{ $puja->cod_licit }})</span>
                                      @endif
                                     */ ?>
                                </div>
                            </div>

                        <?php endforeach; ?>

                        <div class="pujas_model hidden row" id="type_bid_model">
                            <div class="col-md-2 col-lg-2"></div>
                            <div class="col-md-4 col-lg-4 tipoPuja">
                                <p data-type="I"><i class="fa fa-globe" aria-hidden="true"></i> </p>
                                <p data-type="S" class="hidden"><i class="fa fa-hand-paper-o" aria-hidden="true"></i> </p>
                                <p data-type="T" class="hidden"><i class="fa fa-phone" aria-hidden="true"></i> </p>
                                <p data-type="B" class="hidden"><i class="fa fa-phone" aria-hidden="true"></i> </p>
                                <p data-type="E" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> </p>
                                <p data-type="P" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i></p>
                                <p data-type="W" class="hidden"><i class="fa fa-wikipedia-w" aria-hidden="true"></i></p>
                                <p data-type="O" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i></p>
                                <p data-type="U" class="hidden"><i class="fab fa-stripe-s" aria-hidden="true"></i></p>
                                <p data-type="R" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i></p>

                            </div>
                            <div class="col-md-4 col-lg-4 importePuja">
                                <p>
                                    <span class="puj_imp"></span>
                                    <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
                                </p>
                            </div>
                            <div class="col-md-2 col-lg-2"></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
