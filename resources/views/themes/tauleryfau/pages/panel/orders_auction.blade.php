<div class="orders-auction" id="{{ $all_inf['inf']->cod_sub }}">

    <div class="table-grid table-grid_header">
        <div class="orders-title-auction">
            <p>
                {{ $all_inf['inf']->name }}
            </p>
        </div>

        <div class="table-header">
            <p>{{ trans($theme . '-app.user_panel.lot') }}</p>
        </div>

        <div class="table-header">

            <p>{{ trans($theme . '-app.lot.description') }}</p>

        </div>

        <div class="table-header">
            <p>{{ trans($theme . '-app.lot.lot-price') }}</p>
        </div>

        <div class="table-header">
            <p>
                @if ($subasta_finalizada)
                    {{ trans($theme . '-app.user_panel.award_price') }}
                @else
                    {{ trans($theme . '-app.lot.puja_actual') }}
                @endif
            </p>
        </div>

        <div class="table-header">
            <p>Mi puja actual</p>
        </div>
    </div>

    @foreach ($all_inf['lotes'] as $inf_lot)
        <div class="table-grid table-grid_body">
            @php

                $tileFriendly = str_slug($inf_lot->titulo_hces1);
                $sesionFriendly = str_slug($inf_lot->session_name);
                $lotUrl = "$inf_lot->cod_sub-$sesionFriendly-$inf_lot->id_auc_sessions/$inf_lot->ref_asigl0-$inf_lot->num_hces1-$tileFriendly";
                $lotLiveUrl = "$inf_lot->cod_sub-$sesionFriendly-$inf_lot->id_auc_sessions";

                $url_friendly = Routing::translateSeo('lote') . $lotUrl;
                $urlLive = Routing::translateSeo('api/subasta') . $lotLiveUrl;

                $style = 'other';
                $bid_mine = false;
                if ($inf_lot->cod_licit == $inf_lot->licit_winner_bid) {
                    $style = 'mine';
                    $bid_mine = true;
                } elseif (!Config::get('app.notice_over_bid') && $inf_lot->tipo_sub == 'W') {
                    $style = 'gold';
                }

                //escalado se inicia en cada subasta.
                $escalado->sin_pujas = true;
                if (!empty($inf_lot->licit_winner_bid)) {
                    $escalado->sin_pujas = false;
                }

                $nextScale = $escalado->NextScaleBid($inf_lot->impsalhces_asigl0, $inf_lot->implic_hces1);

                $isNotClose =
                    $inf_lot->cerrado_asigl0 != 'S' &&
                    $inf_lot->retirado_asigl0 != 'S' &&
                    strtotime('now') < strtotime($all_inf['inf']->start);

                $isInLive =
                    strtotime('now') > strtotime($all_inf['inf']->start) &&
                    strtotime('now') < strtotime($all_inf['inf']->end);
            @endphp

            <div class="lot-image">
                <img class="img-responsive"
                    src="{{ Tools::url_img('lote_medium', $inf_lot->num_hces1, $inf_lot->lin_hces1) }}">
            </div>

            <div class="lot-ref">
                <p>{{ $inf_lot->ref_asigl0 }}</p>
            </div>

            <div class="lot-desc">
                {!! $inf_lot->desc_hces1 !!}
            </div>

            <div>
                <span class="js-divisa" value="{{ $inf_lot->impsalhces_asigl0 }}">
                    {!! $currency->getPriceSymbol(2, $inf_lot->impsalhces_asigl0) !!}
                </span>

            </div>

            <div class="{{ $style }}">
                <p class="js-divisa" value="{{ $inf_lot->implic_hces1 }}">
                    {!! $currency->getPriceSymbol(2, $inf_lot->implic_hces1) !!}
                </p>
            </div>

            <div>
                @if (!empty($inf_lot->imp))
                    <p class="js-divisa" value="{{ $inf_lot->imp }}">
                        {!! $currency->getPriceSymbol(2, $inf_lot->imp) !!}
                    </p>
                @else
                    <p>
                        <span class="my-max-bid">{{ trans($theme . '-app.lot_list.no_bids') }}</span>
                    </p>
                @endif

            </div>

            <div class="lot-actions">
                @if (true || $isNotClose)
                    <button
                        class="btn js-lot-action_pujar_panel btn-puja-panel btn-color @if ($bid_mine) bid-mine @endif"
                        data-from="modal" data-sub="{{ $inf_lot->cod_sub }}" data-ref="{{ $inf_lot->ref_asigl0 }}"
                        data-imp="{{ $nextScale }}" type="button" @disabled($bid_mine)>

                        <p @class(['js-max-bid', 'hidden' => !$bid_mine])>
                            {{ trans($theme . '-app.user_panel.higher_bid_es') }}
                        </p>
                        <p @class(['js-place-bid', 'hidden' => $bid_mine])>
                            {{ trans($theme . '-app.sheet_tr.place_bid') }}
                            <span id="button-escalado"
                                value="{{ $nextScale }}">{{ Tools::moneyFormat($nextScale) }}</span>
                            {{ trans($theme . '-app.subastas.euros') }}
                        </p>
                    </button>
                @endif

                @if (true || $isNotClose || $isInLive)
                    <a class="btn btn-puja-panel btn-color btn-live js-button-bid-live"
                        data-from="modal" href="{{ $urlLive }}">
                        {{ trans($theme . '-app.lot_list.bid_live') }}
                    </a>
                @endif

            </div>

            <div>
                <a href="{{ $url_friendly }}">
                    <i class="fa fa-eye"></i>
                </a>

                @if (!empty($data['favorites']))
                    <a class="delete-fav btn-del"
                        href="javascript:action_fav_lote('remove','{{ $inf_lot->ref_asigl0 }}','{{ $inf_lot->cod_sub }}',' <?= $data['codigos_licitador'][$inf_lot->cod_sub] ?>')"
                        title="{{ trans($theme . '-app.lot.del_from_fav') }}"><i class="fas fa-minus"></i></a>
                @endif
            </div>
        </div>
    @endforeach

</div>
