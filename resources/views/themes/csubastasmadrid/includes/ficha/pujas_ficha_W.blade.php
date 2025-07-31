@php
    use App\Support\Date;

    $showActualBid =
        $lote_actual->tipo_sub == 'W' && $lote_actual->subabierta_sub == 'O' && $lote_actual->cerrado_asigl0 == 'N';

    $showBidButton =
        $lote_actual->cerrado_asigl0 == 'N' &&
        $lote_actual->fac_hces1 == 'N' &&
        strtotime('now') > strtotime($lote_actual->orders_start) &&
        strtotime('now') < strtotime($lote_actual->orders_end);

    $showLiveButton =
        $lote_actual->cerrado_asigl0 == 'N' &&
        $lote_actual->fac_hces1 == 'N' &&
        strtotime('now') > strtotime($lote_actual->start_session) &&
        strtotime('now') < strtotime($lote_actual->end_session);

    $liveURL = Tools::url_real_time_auction($lote_actual->cod_sub, $lote_actual->name, $lote_actual->id_auc_sessions);
    $startSession = Date::toISOFormat($lote_actual->start_session, 'DD MMMM YYYY | HH\h mm');
@endphp

<div class="ficha_W">
    @if (Config::get('app.estimacion'))
        <div class="ficha_prices ficha_prices_estimate">
            <p class="price_label">{{ trans('web.subastas.estimate') }}</p>
            <p class="price_value">
                {{ $lote_actual->formatted_imptas_asigl0 }} - {{ $lote_actual->formatted_imptash_asigl0 }}
                {{ trans('web.subastas.euros') }}
            </p>
        </div>
    @elseif(Config::get('app.impsalhces_asigl0') && $lote_actual->ocultarps_asigl0 != 'S')
        <div class="ficha_prices">
            <p class="price_label">{{ trans('web.lot.lot-price') }}</p>
            @if ($lote_actual->impsalhces_asigl0 == 0)
                {{ trans('web.lot.free') }}
            @else
                <p class="price_value">
                    {{ $lote_actual->formatted_impsalhces_asigl0 }} {{ trans('web.subastas.euros') }}
                </p>
            @endif
        </div>
    @endif

    @if ($lote_actual->fac_hces1 != 'D')

        <div @class([
            'ficha_prices hist_new',
            'hidden' => empty($data['js_item']['user']['ordenMaxima']),
        ])>
            <p class="price_label">{{ trans('web.lot.max_puja') }}</p>
            <p class="price_value">
                <span id="tuorden">
                    {{ $data['js_item']['user']['ordenMaxima'] ?? '0' }}
                </span>
                <span>{{ trans('web.subastas.euros') }}</span>
            </p>
        </div>

        @if ($showActualBid)
            <div @class([
                'ficha_prices border-0',
                'hidden' => empty($lote_actual->open_price),
            ])>
                <p class="price_label" id="text_actual_max_bid">{{ trans('web.lot.puja_actual') }}</p>
                <p class="price_value">
                    <span id="actual_max_bid">
                        {{ $lote_actual->open_price }}
                    </span>
                    <span>{{ trans('web.subastas.euros') }}</span>
                </p>
            </div>

            <div @class([
                'ficha_prices text_actual_no_bid',
                'hidden' => $lote_actual->open_price > 0,
            ])>
                <p class="price_label">{{ trans('web.lot_list.no_bids') }}</p>
            </div>
        @endif

        @if ($showBidButton)
            {{-- <p class="small">{{ trans('web.lot.insert_max_puja_start') }}</p> --}}
            <div class="ficha_actions">
                <div class="input-group group-pujar-custom d-flex">
                    <input class="form-control input-lg control-number w-100" id="bid_modal_pujar" type="text"
                        value="" placeholder="">
                    <div class="input-group-btn w-100">
                        <button class="btn btn-color bold w-100 h-100" id="pujar_ordenes_w" data-from="modal"
                            type="button" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}"
                            codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">
                            {{ trans('web.lot.place_bid') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if ($showLiveButton)
            <div class="ficha_actions">
                <a class="btn btn-bold live-btn btn-color" href='{{ $liveURL }}' target="_blank">
                    {{ trans('web.lot.bid_live') }}
                </a>
            </div>
        @endif
    @endif

    <div class="ficha_shares align-items-start">
        <p>{{ $startSession }}</p>
        <div>
            <p>
                <span class="clock">
                    <i class="fa fa-clock-o"></i>
                    <span class="timer" data-countdown="{{ strtotime($lote_actual->start_session) - getdate()[0] }}"
                        data-format="{!! Tools::down_timer($lote_actual->start_session) !!}" data-closed="{{ $lote_actual->cerrado_asigl0 }}">
                    </span>
                </span>
            </p>
            @include('includes.ficha.share')
        </div>
    </div>
</div>
