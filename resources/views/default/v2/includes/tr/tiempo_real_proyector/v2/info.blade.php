<div class="lot">
    <span class="" id="lote_actual_main">{{ trans($theme . '-app.sheet_tr.lot') }} <strong><span
                id="info_lot_actual">


                {{ str_replace(['.1', '.2', '.3', '.4', '.5'], ['-A', '-B', '-C', '-D', '-E'], $data['subasta_info']->lote_actual->ref_asigl0) }}



            </span></strong></span>
</div>
<div class="starting-price">
    <div class="precioSalida salida text-center" id="precioSalida">
        <p>{{ trans($theme . '-app.sheet_tr.start_price') }}:</p>
        <p><span>{{ $data['subasta_info']->lote_actual->formatted_impsalhces_asigl0 }}</span>
            {{ $data['js_item']['subasta']['currency']->symbol }}</p>
    </div>
</div>
<div class="current-bid">
    <div class="salida text-center">
        <p>
            <span class="<?= count($data['subasta_info']->lote_actual->pujas) > 0 ? '' : 'hidden' ?> "
                id="text_actual_max_bid">
                {{ trans($theme . '-app.sheet_tr.max_actual_bid') }}
            </span>
            <span class="<?= count($data['subasta_info']->lote_actual->pujas) > 0 ? 'hidden' : '' ?> "
                id="text_actual_no_bid">
                {{ trans($theme . '-app.sheet_tr.pending_bid') }}
            </span>
        </p>
        <p><strong>
                <span class="@if (
                    !empty($data['js_item']['user']) &&
                        !empty($data['subasta_info']->lote_actual->max_puja) &&
                        $data['subasta_info']->lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit']
                ) mine @else other @endif" id="actual_max_bid">
                    @if (count($data['subasta_info']->lote_actual->pujas) > 0)
                        {{ \Tools::moneyFormat($data['subasta_info']->lote_actual->actual_bid) }}
                        {{ $data['js_item']['subasta']['currency']->symbol }}
                    @endif
                </span>
            </strong>
        </p>

    </div>
</div>
<div class="next-bid">
    <div class=" text-center" id="next-bid_JS">
        <p>{{ trans($theme . '-app.lot.next_min_bid') }}:</p>
        <p><span
                id="next_bid_JS">{{ \Tools::moneyFormat($data['subasta_info']->lote_actual->importe_escalado_siguiente) }}</span>
            {{ $data['js_item']['subasta']['currency']->symbol }}</p>
    </div>
</div>
<div class="last-bids-container">
    <div class="last-bids">
        @if (\Config::get('app.tr_show_pujas'))
            <div class="started hidden">
                <div class="aside pujas">

                    <p class="last-bids-title">{{ trans($theme . '-app.sheet_tr.last_bids') }}</p>

                    <div class="pujas_list" id="pujas_list">
						@foreach ($data['subasta_info']->lote_actual->pujas as $puja)
						<div class="pujas_model">

							<div class="tipoPuja">
                                <p data-type="I" @if ($puja->pujrep_asigl1 != 'I') class="hidden" @endif><i
                                        class="fa fa-globe" aria-hidden="true"></i> </p>
                                <p data-type="S" @if ($puja->pujrep_asigl1 != 'S') class="hidden" @endif><i
                                        class="fa fa-hand-paper-o" aria-hidden="true"></i> </p>
                                <p data-type="T" @if ($puja->pujrep_asigl1 != 'T' && $puja->pujrep_asigl1 != 'B') class="hidden" @endif><i
                                        class="fa fa-phone" aria-hidden="true"></i> </p>
                                <p data-type="E" @if ($puja->pujrep_asigl1 != 'E' && $puja->pujrep_asigl1 != 'P') class="hidden" @endif><i
                                        class="fa fa-desktop" aria-hidden="true"></i> </p>
                                <p data-type="W" @if ($puja->pujrep_asigl1 != 'W') class="hidden" @endif><i
                                        class="fa fa-wikipedia-w" aria-hidden="true"></i> </p>
                                <p data-type="O" @if ($puja->pujrep_asigl1 != 'O') class="hidden" @endif><i
                                        class="fa fa-desktop" aria-hidden="true"></i></p>
                                <p data-type="U" @if ($puja->pujrep_asigl1 != 'U') class="hidden" @endif><i
                                        class="fab fa-stripe-s" aria-hidden="true"></i></p>
                                <p data-type="R" @if ($puja->pujrep_asigl1 != 'R') class="hidden" @endif><i
                                        class="fa fa-desktop" aria-hidden="true"></i></p>

                            </div>
                            <div class="importePuja">
                                <p><span>{{ $puja->formatted_imp_asigl1 }}</span>
                                    <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
                                </p>
                            </div>

                        </div>
						@endforeach

                        <div class="pujas_model hidden" id="type_bid_model">

                            <div class="tipoPuja">
                                <p data-type="I"><i class="fa fa-globe" aria-hidden="true"></i> </p>
                                <p class="hidden" data-type="S"><i class="fa fa-hand-paper-o" aria-hidden="true"></i>
                                </p>
                                <p class="hidden" data-type="T"><i class="fa fa-phone" aria-hidden="true"></i> </p>
                                <p class="hidden" data-type="B"><i class="fa fa-phone" aria-hidden="true"></i> </p>
                                <p class="hidden" data-type="E"><i class="fa fa-desktop" aria-hidden="true"></i>
                                </p>
                                <p class="hidden" data-type="P"><i class="fa fa-desktop" aria-hidden="true"></i>
                                </p>
                                <p class="hidden" data-type="W"><i class="fa fa-wikipedia-w" aria-hidden="true"></i>
                                </p>
                                <p class="hidden" data-type="O"><i class="fa fa-desktop" aria-hidden="true"></i>
                                </p>
                                <p class="hidden" data-type="U"><i class="fab fa-stripe-s" aria-hidden="true"></i>
                                </p>
                                <p class="hidden" data-type="R"><i class="fa fa-desktop" aria-hidden="true"></i>
                                </p>

                            </div>
                            <div class="importePuja">
                                <p>
                                    <span class="puj_imp"></span>
                                    <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
                                </p>
                            </div>
                        </div>
                        {{-- <div class="clearfix"></div> --}}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
