@php
    $ocultarPs = $lote_actual->ocultarps_asigl0 === 'S';
    $ordenes = App\Models\V5\FgOrlic::select('count(ref_orlic) as cuantos')
        ->where('sub_orlic', $data['subasta_info']->lote_actual->cod_sub)
        ->where('ref_orlic', $data['subasta_info']->lote_actual->ref_asigl0)
        ->where('tipop_orlic', 'T')
        ->first();

    //Mónica ha pedido que las pujas telefónicas se permitan hasta las 14:00 h del día de la subasta
    $inTime = strtotime(date('Y-m-d 14:00:00', strtotime($data['subasta_info']->lote_actual->start_session))) > time();
    //no puede haber más de 10 pujas telefónica y el preci odebe ser mayor de 300
    $overMiniumPriceForPhoneBid = $ordenes->cuantos < 10 && $lote_actual->impsalhces_asigl0 >= 300;

	$phoneBidIsActive = $inTime && $overMiniumPriceForPhoneBid;
@endphp

<div class="ficha-lot-prices">
    @if (!$ocultarPs)
        <p class="ff-highlight ficha-lot-price">
            {{ trans("$theme-app.lot.lot-price") . ' ' . $lote_actual->formatted_impsalhces_asigl0 . ' ' . trans("$theme-app.subastas.euros") }}
            @if (Config::get('app.exchange'))
                | <span id="startPriceExchange_JS" class="exchange"> </span>
            @endif
        </p>
    @endif

    @if (!empty($lote_actual->imptash_asigl0))
        <p class="ff-highlight ficha-lot-estimate">
            {{ trans("$theme-app.lot.lot-estimate") . ' ' . $lote_actual->imptash_asigl0 . ' ' . trans("$theme-app.subastas.euros") }}
            @if (Config::get('app.exchange'))
                | <span id="estimateExchange_JS" class="exchange"> </span>
            @endif
        </p>
    @endif
</div>

@if ($subasta_abierta_O)
    <div class="info-ficha-buy-info-prices">
        <div id="text_actual_max_bid"
            class="price-title-principal pre {{ $lote_actual->open_price > 0 ? '' : 'hidden' }}">
            <p class="pre-title">{{ trans("$theme-app.lot.puja_actual") }}</p>
            <div id="max_bid_color"
                class="pre-price <?= count($data['ordenes']) > 0 && !empty($data['js_item']['user']) && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'] ? 'winner' : 'no_winner' ?>">
                <span id="actual_max_bid">
                    {{ \Tools::moneyFormat($lote_actual->open_price) }}
                </span> {{ trans("$theme-app.subastas.euros") }}
            </div>

        </div>

        <div id="text_actual_no_bid" class="pre <?= $lote_actual->open_price > 0 ? 'hidden' : '' ?>">
            <p class="pre-title-principal">{{ trans($theme . '-app.lot_list.no_bids') }}</p>
        </div>
    </div>
@endif

@if ($subasta_abierta_P)
    @php
        //aparecera en rojo(clase other) si no eres el ganador y en verde si loeres (clase mine) , si no estas logeado no se modifica el color
        $class = '';
        if (Session::has('user')) {
            $class = !empty($lote_actual->max_puja) && $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'] ? 'mine' : 'other';
        }
    @endphp
    <div class="info-ficha-buy-info-prices">
        <div id="text_actual_max_bid"
            class="pre-price price-title-principal <?= count($lote_actual->pujas) > 0 ? '' : 'hidden' ?>">
            <p class="pre-title">{{ trans("$theme-app.lot.puja_actual") }}</p>
            <strong>
                <span id="actual_max_bid" class="{{ $class }}">{{ $lote_actual->formatted_actual_bid }}
                    {{ trans("$theme-app.subastas.euros") }}</span>

            </strong>
        </div>
    </div>
@endif

@php
    $myMaxOrder = $data['js_item']['user']['ordenMaxima'] ?? null;
@endphp

{{-- las clases hist_new y tuorden se utilizan en js --}}
@if (!$fact_devuelta)
    <div class="ficha-my-order hist_new @if (!$myMaxOrder) hidden @endif">
        {{ trans("$theme-app.lot.max_puja") }}
        <strong>
            <span id="tuorden">
                {{ $myMaxOrder }}
            </span>
            {{ trans("$theme-app.subastas.euros") }}
            @if (\Config::get('app.exchange'))
                | <span id="yourOrderExchange_JS" class="exchange"> </span>
            @endif
        </strong>
    </div>
@endif

<div class="ficha-actions mt-lg-4">
    @if ($fact_N && $start_session && !$end_session)
        <a class="btn btn-outline-lb-primary btn-medium" target="_blank"
            href='{{ Tools::url_real_time_auction($lote_actual->cod_sub, $lote_actual->name, $lote_actual->id_auc_sessions) }}'>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="12" fill="#ED2F2F"></circle>
            </svg>
            {{ trans("$theme-app.lot.bid_live") }}
        </a>
    @elseif ($fact_N && $start_orders && !$end_orders && !$subasta_abierta_P)
        {{-- las subastas abiertas tipo P se veran como W cuando empiece la subasta, pero controlamos que no sep uedan hacer ordenes (!$subasta_abierta_P) --}}
        <p class="insert-max-bid">{{ trans("$theme-app.lot.insert_max_puja_start") }}</p>

        <div class="ficha-buttons">

            <div class="position-relative ficha-insert-bid">
                <input type="text" class="form-control control-number" id="bid_modal_pujar"
                    placeholder="{{ $data['precio_salida'] }}"
                    aria-label="{{ trans("$theme-app.lot.insert_max_puja_start") }}"
                    value="{{ $data['precio_salida'] }}">

                <button type="button" class="btn btn-lb-primary btn-medium ficha-btn-bid"
                    ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}"
                    codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}"
                    id="pujar_ordenes_w_ansorena">{{ trans("$theme-app.lot.place_bid") }}</button>
            </div>

            @if ($phoneBidIsActive)
                <button id="pujar_orden_telefonica" data-from="modal" type="button" class="ficha-btn-telephone-bid btn btn-lb-primary btn-medium"
                    ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}"
                    codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">
					{{ trans("$theme-app.lot.puja_telefonica") }}
				</button>
                <input id="orderphone" type="hidden">
                <input id="userphone1" type="hidden" value="">
            @endif

        </div>
    @endif
</div>

@if (Config::get('app.urlToPackengers'))
	@php
		$lotReference = str_replace('.', '-', $lote_actual->ref_asigl0);
		$lotFotURL = "$lote_actual->cod_sub-$lotReference";
	@endphp
	<a class="btn btn-small btn-outline-lb-primary btn-packengers gap-2 w-100" href="{{ Config::get('app.urlToPackengers') . "/{$lotFotURL}?source=estimate" }}" target="_blank">
		<x-icon.boostrap icon="truck" />
		{{ trans("$theme-app.lot.packengers_ficha") }}
	</a>
@endif
