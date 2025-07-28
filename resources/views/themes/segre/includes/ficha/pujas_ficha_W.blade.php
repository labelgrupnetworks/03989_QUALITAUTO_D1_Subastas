<div class="ficha-pujas ficha-pujas-w">

    {{-- Precio salida --}}
    <p class="price salida-price">
        <span>{{ trans($theme . '-app.lot.lot-price') }}</span>
        <span>
            {{ $lote_actual->formatted_impsalhces_asigl0 }} {{ trans($theme . '-app.subastas.euros') }}

            @if (config('app.exchange'))
                | <span class="exchange" id="startPriceExchange_JS"> </span>
            @endif
        </span>
    </p>

    {{-- Estimación --}}
    @if (!empty($lote_actual->imptash_asigl0))
        <p class="price estimacion-price">
            <span>{{ trans($theme . '-app.lot.estimate') }}</span>
            <span>
                {{ \Tools::moneyFormat($lote_actual->imptash_asigl0) }} {{ trans($theme . '-app.subastas.euros') }}
                @if (\Config::get('app.exchange'))
                    | <span class="exchange" id="estimateExchange_JS"> </span>
                @endif
            </span>
        </p>
    @endif

    {{-- Puja actual --}}
    @if ($subasta_web && $subasta_abierta_O && $cerrado_N)
        <h4 id="text_actual_max_bid" @class(['price bid-price', 'hidden' => $lote_actual->open_price == 0])>
            <span>{{ trans($theme . '-app.lot.puja_actual') }}</span>
            <span
                class="{{ count($data['ordenes']) > 0 && !empty($data['js_item']['user']) && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'] ? 'winner' : 'no_winner' }}"
                id="max_bid_color">
                <span id="actual_max_bid">
                    {{ \Tools::moneyFormat($lote_actual->open_price) }}
                </span>
                <span>{{ trans($theme . '-app.subastas.euros') }}</span>
            </span>

        </h4>

        <div id="text_actual_no_bid" @class(['hidden' => $lote_actual->open_price > 0])>
            <p>{{ trans($theme . '-app.lot_list.no_bids') }}</p>
        </div>
    @elseif ($subasta_web && $subasta_abierta_P && $cerrado_N)
        <h4 id="text_actual_max_bid" @class(['price bid-price', 'hidden' => $lote_actual->open_price == 0])>
            <span>{{ trans($theme . '-app.lot.puja_actual') }}</span>
            <span id="actual_max_bid" @class([
                'mine' =>
                    Session::has('user') &&
                    $lote_actual->max_puja &&
                    $lote_actual->max_puja?->cod_licit ==
                        $data['js_item']['user']['cod_licit'],
                'other' =>
                    Session::has('user') &&
                    $lote_actual->max_puja &&
                    $lote_actual->max_puja?->cod_licit !=
                        $data['js_item']['user']['cod_licit'],
            ])>

                {{ $lote_actual->formatted_actual_bid }} {{ trans($theme . '-app.subastas.euros') }}
            </span>
        </h4>
    @endif

    {{-- live button --}}
    @if ($cerrado_N && $fact_N && $start_session && !$end_session)
        <div class="mt-3">
            <a class="btn btn-outline-lb-primary w-100"
                href='{{ \Tools::url_real_time_auction($data['subasta_info']->lote_actual->cod_sub, $data['subasta_info']->lote_actual->name, $data['subasta_info']->lote_actual->id_auc_sessions) }}'>
                {{ trans($theme . '-app.lot.bid_live') }}
            </a>
        </div>
    @else
        {{-- las subastas abiertas tipo P se veran como W cuando empiece la subasta, pero controlamos que no sep uedan hacer ordenes (!$subasta_abierta_P) --}}
        @if ($cerrado_N && $fact_N && $start_orders && !$end_orders && !$subasta_abierta_P)

            {{-- Si el lote es NFT y el usuario está logeado pero no tiene wallet --}}
            @if ($lote_actual->es_nft_asigl0 == 'S' && !empty($data['usuario']) && empty($data['usuario']->wallet_cli))
                <p class="require-wallet">{!! trans($theme . '-app.lot.require_wallet') !!}</p>
            @else
                <p class="mt-2 insert-max-bid small text-muted">{{ trans($theme . '-app.lot.insert_max_puja_start') }}
                </p>
                <div class="pujas-w-inputs-wrapp">
                    <div class="input-group">
                        <input class="form-control control-number border-danger text-danger" id="bid_modal_pujar" type="text"
                            value="{{ $data['precio_salida'] }}" aria-describedby="pujar_ordenes_w"
                            placeholder="{{ $data['precio_salida'] }}">
                        {{-- <span class="input-group-text currency-input">{{trans($theme.'-app.subastas.euros')}}</span> --}}
                        <button class="btn btn-outline-danger" id="pujar_ordenes_w" data-from="modal" type="button"
                            ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}">
                            {{ trans($theme . '-app.lot.place_bid') }}
                        </button>
                    </div>

                    @if (!empty($lote_actual->ordentel_sub) && $lote_actual->ordentel_sub <= $lote_actual->impsalhces_asigl0)
                        <button class="btn btn-outline-danger w-100" id="pujar_orden_telefonica"
                            data-from="modal" type="button" ref="{{ $lote_actual->ref_asigl0 }}"
                            codsub="{{ $lote_actual->cod_sub }}">
                            {{ trans($theme . '-app.lot.puja_telefonica') }}
                        </button>
                        <input id="orderphone" type="hidden">
                    @endif


                </div>

				<div class="pujas-w-inputs-wrapp mt-4">
					@php
						$to = 'info@subastassegre.es';
						$dataToEmail = [
							'ref' => $lote_actual->ref_asigl0,
							'sub' => $lote_actual->cod_sub,
						];
						$subject = rawurlencode(trans("web.lot.request_information", $dataToEmail));
						$body = rawurlencode(trans("web.lot.request_information_text", $dataToEmail));
					@endphp
					<a class="btn btn-icon btn-lb-secondary w-100 justify-content-center" href="mailto:{{ $to }}?subject={{ $subject }}&body={{ $body }}">
						<x-icon.boostrap icon="envelope-fill" class="me-2" />
						{{ trans("web.lot.request_information_button") }}
					</a>
				</div>


            @endif

        @endif

        {{-- mi orden máxima --}}
        @if (!$fact_devuelta)
            <p @class([
                'hist_new mt-3',
                'hidden' => empty($data['js_item']['user']['ordenMaxima']),
            ])>
                {{ trans($theme . '-app.lot.max_puja') }}
                <strong>
                    <span id="tuorden">
                        @if (!empty($data['js_item']['user']['ordenMaxima']))
                            {{ \Tools::moneyFormat($data['js_item']['user']['ordenMaxima']) }}
                        @endif
                    </span>
                    {{ trans($theme . '-app.subastas.euros') }}
                    @if (\Config::get('app.exchange'))
                        | <span class="exchange" id="yourOrderExchange_JS"> </span>
                    @endif
                </strong>
            </p>
        @endif

    @endif
</div>
