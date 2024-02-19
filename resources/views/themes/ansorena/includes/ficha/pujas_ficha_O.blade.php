@php
    $hasMaxOrderUser = !empty($data['js_item']['user']['ordenMaxima']);

    $maxActualUserBid = $data['js_item']['user']['ordenMaxima'] ?? 0;
    if ($hasMaxOrderUser && $data['js_item']['user']['pujaMaxima'] && intval($data['js_item']['user']['ordenMaxima']) <= intval($data['js_item']['user']['pujaMaxima']->imp_asigl1)) {
        $maxActualUserBid = $lote_actual->formatted_actual_bid;
    }

    $hasBids = count($lote_actual->pujas) > 0;

@endphp

<div id="reload_inf_lot" class="d-flex flex-column gap-3">
    <p class="ff-highlight ficha-lot-price {{ $hasMaxOrderUser ? '' : 'hidden' }} info_single_title hist_new">
        {{ trans("$theme-app.lot.max_puja") }}
        <span id="tuorden">
            @if ($hasMaxOrderUser)
                {{ $maxActualUserBid }}
            @endif
        </span>

        {{ trans("$theme-app.subastas.euros") }}

        @if (\Config::get('app.exchange'))
            |<span id="yourOrderExchange_JS" class="exchange"> </span>
        @endif
    </p>

    <p class="ff-highlight ficha-lot-price">
        {{ trans("$theme-app.lot.lot-price") . ' ' . $lote_actual->formatted_impsalhces_asigl0 . ' ' . trans("$theme-app.subastas.euros") }}
        @if (Config::get('app.exchange'))
            | <span id="startPriceExchange_JS" class="exchange"> </span>
        @endif
    </p>

    @if (!empty($lote_actual->imptash_asigl0))
        <p class="ff-highlight ficha-lot-estimate">
            {{ trans("$theme-app.lot.lot-estimate") . ' ' . $lote_actual->imptash_asigl0 . ' ' . trans("$theme-app.subastas.euros") }}
            @if (Config::get('app.exchange'))
                | <span id="estimateExchange_JS" class="exchange"> </span>
            @endif
        </p>
    @endif

    {{-- puja actual --}}
    <p id="text_actual_max_bid" class="ff-highlight ficha-lot-price {{ $hasBids ? '' : 'hidden' }}">
        {{ trans("$theme-app.lot.puja_actual") }}

        <span id="actual_max_bid" class="">{{ $lote_actual->formatted_actual_bid }}
            {{ trans("$theme-app.subastas.euros") }}</span>
        @if (\Config::get('app.exchange'))
            | <span id="actualBidExchange_JS" class="exchange"> </span>
        @endif
    </p>

    {{-- precio de reserva alcanzado/no alcanzado --}}
    @if (isset($lote_actual->impres_asigl0) && $lote_actual->impres_asigl0 > 0 && Session::has('user'))
        <p class="ficha-lot-price ff-highlight">
            {{ trans("$theme-app.subastas.price_minim") }}:
            <span class="precio_minimo_alcanzado hidden">{{ trans("$theme-app.subastas.reached") }}</span>
            <span class="precio_minimo_no_alcanzado hidden">{{ trans("$theme-app.subastas.no_reached") }}</span>
        </p>
    @endif

    {{-- sin puja --}}
    <p class="ficha-lot-price ff-highlight {{ !$hasBids ? '' : 'hidden' }}">
        {{ trans("$theme-app.lot_list.no_bids") }}
    </p>

    {{-- siguiente puja --}}
    <p class="ff-highlight ficha-lot-price">
        <span class="explanation_bid t_insert pre-title">
            {{ $hay_pujas ? trans("$theme-app.lot.next_min_bid") : trans("$theme-app.lot.min_puja") }}
        </span>
        <span class="siguiente_puja"> </span>
        {{ trans("$theme-app.subastas.euros") }}

        @if (\Config::get('app.exchange'))
            | <span id="nextBidExchange_JS" class="exchange"> </span>
        @endif
    </p>

    @if ($subasta_abierta_P && $cerrado_N && $fact_N && $start_session && !$end_session)
        <div class="ficha-live-btn-content">
            <a class="ficha-live-btn-link secondary-button"
                href='{{ \Tools::url_real_time_auction($data['subasta_info']->lote_actual->cod_sub, $data['subasta_info']->lote_actual->name, $data['subasta_info']->lote_actual->id_auc_sessions) }}'>
                <div class="bid-online"></div>
                <div class="bid-online animationPulseRed"></div>
                <?= trans($theme . '-app.lot.bid_live') ?>
            </a>
        </div>
    @endif

    @if ($inicio_pujas || $subasta_abierta_P)
        <div class="ficha-actions mt-lg-4">

            @if (Session::has('user') && Session::get('user.admin'))
                <div class="d-block w-100 mb-3">
                    <input id="ges_cod_licit" name="ges_cod_licit" class="form-control" type="text" value=""
                        type="text" style="border: 1px solid red;" placeholder="Código de licitador">
                    @if ($subasta_abierta_P)
                        <input type="hidden" id="tipo_puja_gestor" value="abiertaP">
                    @endif
                </div>
            @endif

            {{-- Si el lote es NFT y el usuario está logeado pero no tiene wallet --}}
            @if ($lote_actual->es_nft_asigl0 == 'S' && !empty($data['usuario']) && empty($data['usuario']->wallet_cli))
                <p class="require-wallet">{!! trans($theme . '-app.lot.require_wallet') !!}</p>
            @else
                <p class="insert-bid insert-max-bid mb-1">
                    {{ trans("$theme-app.lot.insert_max_puja") }}
                </p>

                <div class="ficha-buttons">
                    <div class="position-relative ficha-insert-bid">
                        <input type="text" class="form-control control-number" id="bid_amount"
                            placeholder="{{ $data['precio_salida'] }}"
                            aria-label="{{ trans("$theme-app.lot.insert_max_puja") }}"
                            value="{{ $data['precio_salida'] }}">

                        <button type="button"
                            class="btn btn-lb-primary btn-medium ficha-btn-bid lot-action_pujar_on_line {{ Session::has('user') ? 'add_favs' : '' }}"
                            ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}">
                            {{ trans("$theme-app.lot.pujar") }}
                        </button>
                    </div>
                </div>

                {{--  OJO revisar que exita en el node la funcion cancel_order_user,  bloqueo la condicion con 1==2 por si acaso --}}
                @if (1 == 2 && Session::has('user') && !empty(\Config::get('app.DeleteOrdersAnyTime')))
                    <div class="d-flex mb-2">
                        <button style="width:100%" id="cancelarOrdenUser"
                            class="ficha-btn-bid-height button-principal  @if (empty($data['js_item']['user']['ordenMaxima'])) hidden @endif"
                            type="button" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}"
                            sub="{{ $data['subasta_info']->lote_actual->cod_sub }}">
                            {{ trans("$theme-app.user_panel.delete_orden") }}
                        </button>
                    </div>
                @endif
            @endif

            @if (\Config::get('app.urlToPackengers'))
                @php
                    $lotFotURL = $lote_actual->cod_sub . '-' . $lote_actual->ref_asigl0;
                    $urlCompletePackengers = \Config::get('app.urlToPackengers') . $lotFotURL;
                @endphp
                <div class="packengers-container-button-ficha">
                    <a class="packengers-button-ficha" href="{{ $urlCompletePackengers }}" target="_blank">
                        <i class="fa fa-truck" aria-hidden="true"></i>
                        {{ trans("$theme-app.lot.packengers_ficha") }}
                    </a>
                </div>
            @endif

        </div>
    @else
        <div class="">
            <p>{{ trans("$theme-app.lot.can_place_bids") }}{{ Tools::getDateFormat($lote_actual->fini_asigl0, 'Y-m-d H:i:s', 'd/m/Y H:i:s') }}
            </p>
        </div>
    @endif
</div>

{{-- solo se debe recargar la fecha en las subatsas tipo Online, ne las abiertas tipo P no se debe ejecutar --}}
@if ($subasta_online)
<script>
	$(document).ready(function() {

		$("#actual_max_bid").bind('DOMNodeInserted', function(event) {
			if (event.type == 'DOMNodeInserted') {

				$.ajax({
					type: "GET",
					url: "/lot/getfechafin",
					data: {
						cod: cod_sub,
						ref: ref
					},
					success: function(data) {

						if (data.status == 'success') {
							$(".timer").data('ini', new Date().getTime());
							$(".timer").data('countdownficha', data.countdown);
							//var close_date = new Date(data.close_at * 1000);
							// $("#cierre_lote").html(close_date.toLocaleDateString('es-ES') + " " + close_date.toLocaleTimeString('es-ES'));
							$("#cierre_lote").html(format_date_large(new Date(data
								.close_at * 1000), ''));
						}

					}
				});
			}
		});
	});
</script>
@endif
