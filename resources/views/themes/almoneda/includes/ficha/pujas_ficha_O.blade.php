@php
	$depositComision = $lote_actual->impsalhces_asigl0 * $lote_actual->comlhces_asigl0 * 0.01;
@endphp
<div id="reload_inf_lot" class="col-xs-12 info-ficha-buy-info no-padding">
    <div class="col-xs-12">
        <div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima']) ? '' : 'hidden' ?> ">
            {{ trans(\Config::get('app.theme') . '-app.lot.max_puja') }}
            <strong>

                <span id="tuorden">
                    @if (!empty($data['js_item']['user']['ordenMaxima']))
                        @if (!empty($lote_actual->max_puja) && $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'])
                            {{ $lote_actual->formatted_actual_bid }}
                        @else
                            {{ $data['js_item']['user']['ordenMaxima'] }}
                        @endif
                    @endif
                </span>
                {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
                @if (\Config::get('app.exchange'))
                    | <span id="yourOrderExchange_JS" class="exchange"> </span>
                @endif
            </strong><br><br>

        </div>
    </div>

	{{-- Valoración --}}
    <div class="col-xs-12 no-padding info-ficha-buy-info-price {{-- d-flex --}}">
        {{-- @if (!empty($lote_actual->imptash_asigl0)) --}}
        <div class="pre">
            <p class="pre-title title-font row">
                <span class="col-xs-6">{{ trans(\Config::get('app.theme') . '-app.lot.valoration') }}</span>
                <span class="pre-price col-xs-6">
                    {{ \Tools::moneyFormat($lote_actual->imptash_asigl0) }}
                    {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
                </span>
            </p>
        </div>
        {{-- @endif --}}
    </div>

	{{-- Precio de salida --}}
    <div class="col-xs-12 no-padding info-ficha-buy-info-price {{-- d-flex --}}">
        <div class="pre">
            <p class="pre-title title-font row">
                <span class="col-xs-6">{{ trans(\Config::get('app.theme') . '-app.lot.minimum_rate') }}</span>
                <span class="pre-price col-xs-6">
                    {{ $lote_actual->formatted_impsalhces_asigl0 }}
                    {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
                    @if (\Config::get('app.exchange'))
                        <span id="startPriceExchange_JS" class="exchange"> </span>
                    @endif
                </span>
            </p>
        </div>
    </div>

	{{-- Comisión --}}
    <div class="col-xs-12 no-padding info-ficha-buy-info-price {{-- d-flex --}}">
        <div class="pre">
            <p class="pre-title title-font row">
                <span class="col-xs-6">{{ trans(\Config::get('app.theme') . '-app.lot.deposit') }}</span>
                <span class="pre-price col-xs-6">
                    {{ \Tools::moneyFormat($depositComision) }}
                    {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
                </span>
            </p>
        </div>
    </div>

    <div class=" col-xs-12 no-padding info-ficha-buy-info-price">

        {{-- Última puja --}}
        <div id="text_actual_max_bid"
            class="{{-- d-flex --}} pre-price {{-- price-title-principal --}} <?= count($lote_actual->pujas) > 0 ? '' : 'hidden' ?>">
            <div class="pre pre-actual_max_bid lot-O-rows-align">
                <p class="pre-title title-font row">
                    <span
                        class="col-xs-6">{{ trans(\Config::get('app.theme') . '-app.lot.highest_bid') }}</span>
                    <span class="pre-price col-xs-6">
                        {{-- aparecera en rojo(clase other) si no eres el ganador y en verde si loeres (clase mine) , si no estas logeado no se modifica el color --}}
                        @if (Session::has('user'))
                            @php($class = !empty($lote_actual->max_puja) && $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'] ? 'mine' : 'other')
                        @else
                            @php($class = '')
                        @endif
                        <span id="actual_max_bid"
                            class="{{ $class }}">{{ $lote_actual->formatted_actual_bid }}
                            {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}</span>
                        @if (\Config::get('app.exchange'))
                            | <span id="actualBidExchange_JS" class="exchange"> </span>
                        @endif
                    </span>
                </p>
            </div>

            <div class="pre">
                @if (isset($lote_actual->impres_asigl0) && $lote_actual->impres_asigl0 > 0 && Session::has('user'))
                    <div class="pre_min">

                        <p class='pre-title'> {{ trans(\Config::get('app.theme') . '-app.subastas.price_minim') }}:
                        </p>
                        <strong>
                            <span
                                class="precio_minimo_alcanzado mine hidden">{{ trans(\Config::get('app.theme') . '-app.subastas.reached') }}</span>
                            <span
                                class="precio_minimo_no_alcanzado other hidden">{{ trans(\Config::get('app.theme') . '-app.subastas.no_reached') }}</span>
                        </strong>

                    </div>
                @endif
            </div>
        </div>
    </div>



    <div class="col-xs-12 no-padding info-ficha-buy-info-price border-top-bottom">
        <div class="pre d-flex mt-2 mb-2 ">
            <div id="text_actual_no_bid"
                class="price-title-principal pre col-xs-12 col-sm-3 no-padding <?= count($lote_actual->pujas) > 0 ? 'hidden' : '' ?>">
                {{ trans(\Config::get('app.theme') . '-app.lot_list.no_bids') }}
            </div>

            <div class="col-xs-12 col-sm-9 no-padding">
                @if ($hay_pujas)
                    <p class='explanation_bid t_insert pre-title'>
                        {{ trans(\Config::get('app.theme') . '-app.lot.next_min_bid') }} </p>
                @else
                    <p class='explanation_bid t_insert pre-title'>
                        {{ trans(\Config::get('app.theme') . '-app.lot.min_puja') }} </p>
                @endif
                <strong><span class="siguiente_puja">
                    </span>{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
                    @if (\Config::get('app.exchange'))
                        | <span id="nextBidExchange_JS" class="exchange"> </span>
                    @endif
                </strong>
            </div>

        </div>
    </div>

    @if ($start_session || $subasta_abierta_P)
        <div class="insert-bid-input col-lg-10 col-lg-offset-1 d-flex justify-content-center flex-column">

            <div class="input-group d-block group-pujar-custom ">
                <div>
                    <div class="insert-bid insert-max-bid mb-1">
                        {{ trans(\Config::get('app.theme') . '-app.lot.insert_max_puja') }}</div>
                </div>
                <div class="d-flex mb-2">
                    <input id="bid_amount" placeholder="{{ $data['precio_salida'] }}"
                        class="form-control control-number" type="text" value="{{ $data['precio_salida'] }}">
                    <div class="input-group-btn">

						@if(Session::has('user') && !$deposito)
							<button type="button" data-from="modal" class="lot-action_pujar_no_deposit ficha-btn-bid ficha-btn-bid-height button-principal <?= Session::has('user')?'add_favs':''; ?>" type="button">{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</button>
						@else
							<button type="button" data-from="modal" class="lot-action_pujar_on_line ficha-btn-bid ficha-btn-bid-height button-principal <?= Session::has('user')?'add_favs':''; ?>" type="button" ref="{{ $lote_actual->ref_asigl0 }}" ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}" >{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</button>
						@endif

                    </div>
                </div>

            </div>
        </div>
    @endif

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
</div>
