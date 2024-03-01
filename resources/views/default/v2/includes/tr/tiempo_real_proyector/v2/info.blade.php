@php
    $loteActual = $data['subasta_info']->lote_actual;

    $refFormat = str_replace(['.1', '.2', '.3', '.4', '.5'], ['-A', '-B', '-C', '-D', '-E'], $loteActual->ref_asigl0);

    $withBids = count($loteActual->pujas) > 0;

    $iWinner =
        !empty($data['js_item']['user']) &&
        !empty($loteActual->max_puja) &&
        $loteActual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'];

    $simbol = $data['js_item']['subasta']['currency']->symbol;
@endphp

<div class="lot">
    <p id="lote_actual_main">
        {{ trans("$theme-app.sheet_tr.lot") }}
        <span id="info_lot_actual">{{ $refFormat }}</span>
    </p>
</div>

<div class="starting-price">
    <div class="precioSalida column-block" id="precioSalida">
        <p>{{ trans($theme . '-app.sheet_tr.start_price') }}</p>
        <p>
            <span>{{ $loteActual->formatted_impsalhces_asigl0 }}</span>
            {{ $simbol }}
        </p>
    </div>
</div>

<div class="current-bid">
    <div class="column-block">
        <p>
            <span id="text_actual_max_bid" @class(['hidden' => !$withBids])>
                {{ trans($theme . '-app.sheet_tr.max_actual_bid') }}
            </span>

            <span id="text_actual_no_bid" @class(['hidden' => $withBids])>
                {{ trans($theme . '-app.sheet_tr.pending_bid') }}
            </span>
        </p>

        <p>
            <span id="actual_max_bid" @class([
                'mine' => $iWinner,
                'other' => !$iWinner,
            ])>
                @if ($withBids)
                    {{ Tools::moneyFormat($loteActual->actual_bid) }}
                    {{ $simbol }}
                @endif
            </span>
        </p>
    </div>
</div>

<div class="next-bid">
    <div class="column-block" id="next-bid_JS">
        <p>{{ trans($theme . '-app.lot.next_min_bid') }}</p>
        <p>
            <span id="next_bid_JS">{{ Tools::moneyFormat($loteActual->importe_escalado_siguiente) }}</span>
            {{ $simbol }}
        </p>
    </div>
</div>

<div class="last-bids-container">
    <div class="last-bids">
        @if (Config::get('app.tr_show_pujas'))
            <div class="started hidden">
                <div class="aside pujas">

                    <p class="last-bids-title">{{ trans($theme . '-app.sheet_tr.last_bids') }}</p>

                    <div class="pujas_list" id="pujas_list">
                        @foreach ($loteActual->pujas as $puja)
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
                                        <span>{{ $simbol }}</span>
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
                                    <span>{{ $simbol }}</span>
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
