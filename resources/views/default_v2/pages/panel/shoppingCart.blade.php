@extends('layouts.default')

@section('content')

    <div class="container user-panel-page shopping-page">
        <div class="row">
            <div class="col-lg-3">
                @include('pages.panel.menu_micuenta')
            </div>

            <div class="col-lg-9">
                <h1>{{ trans("$theme-app.shopping_cart.myCart") }}</h1>

                <h2>
                    @if (!Session::has('user'))
                        {!! trans("$theme-app.shopping_cart.mustLoginShippingCart") !!}
                    @elseif(count($auctions) == 0)
                        {{ trans("$theme-app.shopping_cart.noLots") }}
                    @else
                        {{ trans("$theme-app.shopping_cart.text_reserve") }}
                    @endif
                </h2>

                @foreach ($auctions as $codSub => $auction)
                    @if (count($auctions) > 1)
                        <h3>{{ trans("$theme-app.shopping_cart.myCart") }} {{ head($auction)->des_sub }}</h3>
                    @endif

                    <form id="pagar_lotes_{{ $codSub }}" autocomplete="off">
                        @csrf
                        <div class="table-to-columns">
                            <table class="table table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th></th>
                                        <th>{{ trans("$theme-app.user_panel.lot") }}</th>
                                        <th style="max-width: 300px">{{ trans("$theme-app.user_panel.description") }}</th>
                                        <th>{{ trans("$theme-app.user_panel.units") }}</th>
                                        <th>{{ trans("$theme-app.user_panel.unit_price") }}</th>
                                        <th>{{ trans("$theme-app.user_panel.price_clean") }}</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($auction as $lot)
                                        @include('includes.shoppingcart.lot')
                                    @endforeach
                                </tbody>


                            </table>
                        </div>

                        @php
                            $pagar = $totalLotes[$codSub];

                            if ($gastosEnvio > 1) {
                                $pagar += $gastosEnvio;
                            }
                        @endphp

                        <input id="totalLotes_{{ $codSub }}_JS" type="hidden" value="{{ $totalLotes[$codSub] }}">
                        <input id="gastosEnvio_{{ $codSub }}_JS" type="hidden" value="{{ $gastosEnvio }}">
                        <input id="seguro_{{ $codSub }}_JS" type="hidden" value="{{ $totalSeguro[$codSub] }}">

                        <div class="row row-cols-1 row-cols-md-2 gx-5 gy-3 gy-md-0 mb-3">

                            @if (!empty(Config::get('app.web_gastos_envio')) || !empty(Config::get('app.direccion_envio')))
                                <div class="d-flex flex-column gap-3">

                                    <label class="form-label">
                                        {{ trans("$theme-app.user_panel.direccion-facturacion") }}
                                        <select class="form-select change_address_carrito_js"
                                            id="clidd_carrito_{{ $codSub }}" name="clidd_carrito"
                                            data-sub="{{ $codSub }}" aria-label="select address">
                                            @foreach ($address as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </label>

                                    <label class="form-label" for="comments">
                                        {!! trans("$theme-app.shopping_cart.comment") !!}
                                    </label>
                                    <textarea class="w-100" id="comments" name="comments form-control" rows="5"> </textarea>

                                </div>
                            @endif

                            @if (!empty(Config::get('app.web_gastos_envio')))
                                <div class="gastos_envio d-flex flex-column gap-3">
                                    <p class="fw-bold">{{ trans("$theme-app.user_panel.envio_agencia") }}</p>

                                    <div id="envioPosible_carrito_{{ $codSub }}_js" @class(['d-none' => $gastosEnvio == -1])>
                                        <div class="form-check">
                                            <input class="form-check-input change_envio_carrito_js"
                                                id="envio_agencia_carrito_{{ $codSub }}_js" name="envio_carrito"
                                                data-sub="{{ $codSub }}" type="radio" value="1"
                                                @checked($gastosEnvio != '-1')>

                                            <label class="form-check-label"
                                                for="envio_agencia_carrito_{{ $codSub }}_js">
                                                {{ trans("$theme-app.user_panel.gastos_envio") }} :
                                                <span id="coste-envio-carrito_{{ $codSub }}_js">
                                                    {{ Tools::moneyFormat($gastosEnvio, trans("$theme-app.lot.eur"), 2) }}
                                                </span>
                                            </label>
                                        </div>

                                        @if (!empty(Config::get('app.porcentaje_seguro_envio')))
                                            <div class="form-check">
                                                <input class="form-check-input check_seguro_js"
                                                    id="seguro_carrito_{{ $codSub }}_js" name="seguro_carrito"
                                                    data-sub="{{ $codSub }}" type="checkbox" value="1">

                                                <label class="form-check-label"
                                                    for="seguro_carrito_{{ $codSub }}_js">
                                                    {{ trans("$theme-app.user_panel.seguro_envio") }} :
                                                    <span id="coste-seguro-carrito_js">
                                                        {{ Tools::moneyFormat($totalSeguro[$codSub], trans("$theme-app.lot.eur"), 2) }}
                                                    </span>
                                                </label>
                                            </div>
                                        @endif
                                    </div>

                                    <div id="envioNoDisponible_carrito_{{ $codSub }}_js"
                                        @class(['d-none' => $gastosEnvio != '-1'])>
                                        {{ trans("$theme-app.user_panel.envio_no_disponible") }}
                                    </div>

                                    <p class="fw-bold">{{ trans("$theme-app.user_panel.recogida_producto") }} </p>
                                    <div class="form-check">
                                        <input class="form-check-input change_envio_carrito_js"
                                            id="recogida_almacen_carrito_js" name="envio_carrito"
                                            data-sub="{{ $codSub }}" type="radio" value="0"
                                            @checked($gastosEnvio == '-1')>

                                        <label class="form-check-label" for="recogida_almacen_carrito_js">
                                            {{ trans("$theme-app.user_panel.sala_almacen") }}
                                        </label>
                                    </div>

                                </div>
                            @endif
                        </div>

                        <div class="">

                            {{-- En este caso se envia la información pero no se calculan gastos ya que será informativo --}}
                            @if (!empty(Config::get('app.SeguroCarrito')))
                                <div class="form-check">
                                    <input class="form-check-input" id="seguro_carrito_info" name="seguro_carrito_info"
                                        data-sub="{{ $codSub }}" type="checkbox" value="1"
                                        @checked($gastosEnvio != '-1')>

                                    <label class="form-check-label" for="seguro_carrito_info">
                                        {{ trans("$theme-app.user_panel.seguro_envio") }}
                                    </label>
                                </div>
                            @endif

                            <p>
                                <span class="fw-bold">{{ trans("$theme-app.shopping_cart.total_articles") }}</span>
                                <span>{{ Tools::moneyFormat($totalLotes[$codSub], trans("$theme-app.subastas.euros"), 2) }}</span>
                            </p>

                            <p>
                                <span class="fw-bold">{{ trans("$theme-app.shopping_cart.total_pay") }}</span>
                                <span class="precio_final_carrito_{{ $codSub }}">
                                    {{ Tools::moneyFormat($pagar, trans("$theme-app.subastas.euros"), 2) }}
                                </span>
                            </p>

                            @if (Config::get('app.PayBizum') || Config::get('app.PayTransfer'))
                                <div class="form-check mt-3">
                                    <input class="form-check-input" id="paycreditcard" name="paymethod" type="radio"
                                        value="creditcard" checked="checked">

                                    <label class="form-check-label" for="paycreditcard">
                                        @include('components.boostrap_icon', ['icon' => 'credit-card'])
                                        {{ trans("$theme-app.user_panel.pay_creditcard") }}
                                    </label>
                                </div>

                                @if (Config::get('app.PayBizum'))
                                    <div class="form-check">
                                        <input class="form-check-input" id="paybizum" name="paymethod" type="radio"
                                            value="bizum">

                                        <label class="form-check-label" for="paybizum">
                                            <img src="/default/img/logos/bizum-blue.png" height="20">
                                            {{ trans("$theme-app.user_panel.pay_bizum") }}
                                        </label>
                                    </div>
                                @endif

                                @if (Config::get('app.PayTransfer'))
                                    <div class="form-check">
                                        <input class="form-check-input" id="paytransfer" name="paymethod" type="radio"
                                            value="transfer">

                                        <label class="form-check-label" for="paytransfer">
                                            {{ trans("$theme-app.user_panel.pay_transfer") }}
                                        </label>
                                    </div>
                                @endif
                            @else
                                <input id="paytransfer" name="paymethod" type="hidden" value="creditcard">
                            @endif

                            {{-- en principio solo duran tiene el check el resto que lo use se marcara siempre y quedará oculto --}}
                            @if (Config::get('app.checkPayCart'))
                                <div class="form-check mt-3">
                                    <input class="form-check-input" id="acceptCheck" name="acceptCheck" type="checkbox"
                                        value="1">

                                    <label class="form-check-label" for="acceptCheck">
                                        {!! trans("$theme-app.shopping_cart.check") !!}
                                    </label>
                                </div>

                                <p>{!! trans("$theme-app.shopping_cart.text_condition") !!}</p>
                            @else
                                <input class="hidden" id="acceptCheck" name="acceptCheck" type="checkbox" value=1
                                    checked="checked">
                            @endif
                            <button class="btn btn-lb-primary submitShoppingCart_JS mt-3" class="btn btn-step-reg"
                                type="button" cod_sub="{{ $codSub }}">
								<span class="text">{{ trans("$theme-app.user_panel.pay") }}</span>
								<div class="spinner spinner-1 m-auto"></div>
                            </button>

                        </div>

                    </form>
                @endforeach

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            //No eliminar
            @foreach ($auctions as $codSub => $auction)
                calcShippingCosts("{{ $codSub }}");
            @endforeach

        });
    </script>
@stop
