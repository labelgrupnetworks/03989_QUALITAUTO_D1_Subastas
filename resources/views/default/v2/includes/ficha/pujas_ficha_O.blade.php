<div class="ficha-pujas ficha-pujas-o" id="reload_inf_lot">

    @php
        $myBestBid = 0;
		if(Session::has('user')) {
			$myBestBid = max($data['js_item']['user']['ordenMaxima'], $data['js_item']['user']['pujaMaxima']?->imp_asigl1 ?? 0);
		}
    @endphp

    {{-- Precio salida --}}
    <p class="price salida-price">
        <span>{{ trans($theme . '-app.lot.lot-price') }}</span>
        <span class="value">
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
            <span class="value">{{ Tools::moneyFormat($lote_actual->imptash_asigl0) }}
                {{ trans($theme . '-app.subastas.euros') }}
                @if (\Config::get('app.exchange'))
                    | <span class="exchange" id="estimateExchange_JS"> </span>
                @endif
            </span>
        </p>
    @endif

    {{-- Puja actual --}}
    <h4 id="text_actual_max_bid" @class([
        'price bid-price',
        'hidden' => count($lote_actual->pujas) == 0,
    ])>
        <span>{{ trans($theme . '-app.lot.puja_actual') }}</span>
        <span id="actual_max_bid" @class([
            'value',
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

            @if (config('app.exchange'))
                | <span class="exchange" id="actualBidExchange_JS"> </span>
            @endif
        </span>
    </h4>

    {{-- Sin pujas --}}
    <h5 id="text_actual_no_bid" @class(['hidden' => count($lote_actual->pujas) > 0])>
        {{ trans($theme . '-app.lot_list.no_bids') }}
    </h5>

    {{-- Reserva alcanzada  --}}
    <p @class([
        'price price_minim_reached',
        'hidden' => empty($lote_actual->impres_asigl0),
    ])>
        <span>{{ trans($theme . '-app.subastas.price_minim') }}</span>
        <span
            class="precio_minimo_alcanzado hidden">{{ trans($theme . '-app.subastas.reached') }}</span>
        <span
            class="precio_minimo_no_alcanzado hidden">{{ trans($theme . '-app.subastas.no_reached') }}</span>
    </p>

    {{-- Siguiente puja --}}
    <p class="price next-price">
        <span>{{ $hay_pujas ? trans($theme . '-app.lot.next_min_bid') : trans($theme . '-app.lot.min_puja') }}</span>
        <span class="value">
			<span>
				<span class="siguiente_puja"></span>
				<span>{{ trans($theme . '-app.subastas.euros') }}</span>
			</span>

            @if (\Config::get('app.exchange'))
                <span>|</span>
				<span class="exchange ml-1" id="nextBidExchange_JS"> </span>
            @endif

        </span>
    </p>

    {{-- inputs pujar --}}
    @if ($start_session || $subasta_abierta_P)
        @include('includes.ficha.inputs_pujar_O')
    @endif

    {{-- mi orden máxima --}}
    <p @class([
        'hist_new',
        'hidden' => empty($myBestBid),
    ])>
        {{ trans($theme . '-app.lot.max_puja') }}
        <strong>
            <span id="tuorden">
                @if (!empty($myBestBid))
                	{{ $myBestBid }}
                @endif
            </span>
            {{ trans($theme . '-app.subastas.euros') }}
            @if (\Config::get('app.exchange'))
                | <span class="exchange" id="yourOrderExchange_JS"> </span>
            @endif
        </strong>
    </p>

    {{-- Packengers --}}
    @if (config('app.urlToPackengers'))
        @php
            $lotFotURL = $lote_actual->cod_sub . '-' . $lote_actual->ref_asigl0;
            $urlCompletePackengers = \Config::get('app.urlToPackengers') . $lotFotURL;
        @endphp

        <div class="mt-3">
            <a class="d-block btn btn-outline-lb-secondary" href="{{ $urlCompletePackengers }}" target="_blank">
                <svg class="bi" width="16" height="16" fill="currentColor">
                    <use xlink:href="/bootstrap-icons.svg#truck"></use>
                </svg>
                {{ trans("$theme-app.lot.packengers_ficha") }}
            </a>
        </div>
    @endif

</div>

<?php //solo se debe recargar la fecha en las subatsas tipo Online, ne las abiertas tipo P no se debe ejecutar
?>
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
