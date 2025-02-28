@php
    $loteActual = $data['subasta_info']->lote_actual;
    $currencySimbol = $data['js_item']['subasta']['currency']->symbol;
	$position = $currencySimbol == "$" ? 'F' : 'R';
    $withBids = count($loteActual->pujas) > 0;
    $imWinner = !empty($data['js_item']['user']) && !empty($loteActual->max_puja) && $loteActual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'];
@endphp

<div class="tr_user_info">

    {{-- currency --}}
    @if (Config::get('app.exchange', 0))
        <div class="text-right info-currency">
			<label class="form-label w-100">
				{{ trans("web.lot.foreignCurrencies") }}
				<select id="currencyExchange" class="form-select">
					@foreach ($divisas as $divisa)
						@php
							//quieren que salgan los dolares por defecto (sin no hay nada o hay euros)
							$cod_div_cli = $data['js_item']['subasta']['cod_div_cli'];
							$isDivisaSelected = $cod_div_cli == $divisa->cod_div || ($divisa->cod_div == 'USD' && ($cod_div_cli == 'EUR' || $cod_div_cli == ''));
						@endphp
						<option value='{{ $divisa->cod_div }}' @selected($isDivisaSelected)>
							{{ $divisa->cod_div }}
						</option>
					@endforeach
				</select>
			</label>
        </div>
    @endif

    <div class="prices border p-1 mb-2">

        {{-- precio de estimado --}}
        @if (Config::get('app.tr_show_estimate_price', 0))
            <div class="precio_estimado block-price" id="precioestimado">

                <p>{{ trans("web.sheet_tr.estimate_price") }}</p>
                @if ($currencySimbol == "$")
                    <p>{{ $currencySimbol }}</p>
                @endif
                <span id="imptas">{{ $loteActual->formatted_imptas_asigl0 }} </span>
                -
                <span id="imptash"> {{ $loteActual->formatted_imptash_asigl0 }}</span>
                @if ($currencySimbol != "$")
                    <p>{{ $currencySimbol }}</p>
                @endif

            </div>
        @endif

        {{-- precio de salida --}}
        <div class="precioSalida salida block-price" id="precioSalida">
            <p>{{ trans("web.sheet_tr.start_price") }}</p>
            @if ($currencySimbol == "$")
                {{ $currencySimbol }}<span>{{ $loteActual->formatted_impsalhces_asigl0 }}</span>
            @else
                <span>{{ $loteActual->formatted_impsalhces_asigl0 }}</span> {{ $currencySimbol }}
            @endif
            @if (Config::get('app.exchange'))
                | <span class="exchange" id="startPriceExchange_JS"> </span>
            @endif
        </div>

        {{-- Mi Orden / puja máxima --}}
        <div class="tuorden block-price">
            <p>{{ trans("web.sheet_tr.your_actual_order") }}</p>
            @if ($currencySimbol == "$")
                {{ $currencySimbol }}
            @endif
            <span id="tuorden">
                {{ $data['js_item']['user']['maxOrden']->himp_orlic ?? ($data['js_item']['user']['maxPuja']->imp_asigl1 ?? '0') }}
            </span>
            @if ($currencySimbol != "$")
                {{ $currencySimbol }}
            @endif

            @if (Config::get('app.exchange'))
                | <span class="exchange" id="yourOrderExchange_JS"> </span>
            @endif

            @if (Session::has('user') && !empty(Config::get('app.DeleteOrdersAnyTime')))
                <input id="cancelarOrdenUser" type="button" value="{{ trans("web.user_panel.delete_orden") }}"
                    @class([
                        'btn',
                        'hidden' => empty($data['js_item']['user']['maxOrden']),
                    ]) ref="{{ $loteActual->ref_asigl0 }}" sub="{{ $loteActual->cod_sub }}">
            @endif
        </div>

        {{-- puja actual --}}
        <div class="pactual salida block-price">

            <p>
                <span id="text_actual_max_bid" @class(['hidden' => !$withBids])>
                    {{ trans("web.sheet_tr.max_actual_bid") }}
                </span>
                <span id="text_actual_no_bid" @class(['hidden' => $withBids])>
                    {{ trans("web.sheet_tr.pending_bid") }}
                </span>
            </p>

            <span id="actual_max_bid" @class([
                'mine' => $imWinner,
                'other' => !$imWinner,
                'none' => !Session::has('user'),
            ])>
                @if ($withBids)
                    {{ Tools::moneyFormat($loteActual->actual_bid, $currencySimbol, 0, $position) }}
                @endif
            </span>

            @if (Config::get('app.exchange'))
                | <span class="exchange" id="actualBidExchange_JS"> </span>
            @endif

        </div>
    </div>

    <!-- panel pujar -->
    <div class="pujar">
        <input id="tiempo_real" type="hidden" value="1" readonly>

        @if (Session::has('user'))
            <button class="add_next-bid btn btn-lb-primary w-100 mb-2">
                <h4>{{ trans("web.sheet_tr.place_bid") }}</h4>
                <h4 class="fw-bold" id="value-view">
                    {{ Tools::moneyFormat($loteActual->importe_escalado_siguiente, $currencySimbol, 0, $position) }}
                </h4>
            </button>
        @else
            <button class="btn btn-lb-secondary w-100 mb-2" onclick="clickLogin()">
				<h4>{{ trans("web.msg_error.login_required") }}</h4>
				<h4 class="fw-bold">{{ trans("web.msg_error.log_in") }}</h4>
            </button>
        @endif

        <?php //deshabilitamos el input para que el usuario no pueda cambiar de importe
        ?>
        <div class="input-puja input-group mb-2">
            <input class="form-control bid_amount_gestor" id="bid_amount" type="text"
                value="{{ $loteActual->importe_escalado_siguiente }}" autocomplete="off">
            <span class="input-group-text">
                {{ $currencySimbol }}
            </span>
            @if (Session::has('user'))
                <button class="add_bid btn btn-lb-primary">
                    <i class="fa fa-gavel"></i> {{ trans("web.sheet_tr.place_bid") }}
                </button>
                <input id="tiempo_real" type="hidden" value="1" readonly>
            @else
                <button class="btn button btn-lb-primary" onclick="initSesion();"><i class="fa fa-gavel"></i>
                    {{ trans("web.sheet_tr.place_bid") }}</button>
            @endif
        </div>

    </div>
    @if (Config::get('app.trAuctionConditions'))
        <div class="auction_conditions">{!! trans("web.sheet_tr.auctionConditions") !!}</div>
    @endif

</div>
