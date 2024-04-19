@extends('layouts.panel')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
    $sub = new \App\Models\Subasta();
    $all_adj = [];
    foreach ($data['adjudicaciones'] as $temp_adj) {
        $all_adj[$temp_adj->cod_sub]['lotes'][] = $temp_adj;
    }
    foreach ($all_adj as $key_inf => $value) {
        $sub->cod = $key_inf;
        $all_adj[$key_inf]['inf'] = $sub->getInfSubasta();
    }

    use App\libs\Currency;
    $currency = new Currency();
    $divisas = $currency->getAllCurrencies();
    $divisa = Session::get('user.currency', 'EUR');

    $envioPorDefecto = collect($data['envio'])
        ->where('codd_clid', 'W1')
        ->first();

    $codPais_clid = $envioPorDefecto->codpais_clid ?? ($data['user']->codpais_cli ?? 'ES');
    $cod_sub = head($all_adj)['inf']->cod_sub;

    $iva = $data['js_item']['iva'];
@endphp

@section('content')

    <script>
        var info_lots = @json($data['js_item']);
        var currency = @json($divisas);
        var divisa = @JSON($divisa);
    </script>

    <section class="payment-page">
        <div class="panel-title">
            <h1>{{ trans("$theme-app.user_panel.my_invoice") }}</h1>

            <select id="actual_currency">
                @foreach ($divisas as $divisaOption)
                    <option value='{{ $divisaOption->cod_div }}' @selected($divisaOption->cod_div == $divisa)>
                        {{ $divisaOption->cod_div }}
                    </option>
                @endforeach
            </select>

            <a class="btn btn-lb btn-lb-outline" href="/es/user/panel/allotments">
                {{ trans("$theme-app.global.go_home") }}
            </a>
        </div>

        <form id="pagar_lotes_{{ $cod_sub }}">
            <section class="payment-body">

                <div class="payment_details">
                    @include('pages.panel.payment_gateway.details', [
                        'user' => $data['user'],
                        'countries' => $data['countries'],
                        'envio' => $data['envio'],
                        'cod_sub' => $cod_sub,
                    ])
                </div>

                <div class="payment_profoma-details">
                    @foreach ($all_adj as $key_sub => $all_inf)
                        @php
                            $total_remate = 0;
                            $total_base = 0;
                            $total_licencia_exportacion = 0;
                        @endphp

                        <div id="auction-details-{{ $all_inf['inf']->cod_sub }}">
                            <h4 class="auction-details_title">{{ $all_inf['inf']->name }}</h4>

                            <div class="panel-lots payment-lots">
                                <div class="panel-lots_header-wrapper">
                                    <div class="table-grid_header panel-lots_header">
                                        <p></p>
                                        <p>{{ trans("$theme-app.user_panel.lot") }}</p>
                                        <p>{{ trans("$theme-app.user_panel.description") }}</p>
                                        <p>Adjudicación</p>
                                        <p>{{ trans("$theme-app.user_panel.price_comision") }}</p>
                                        <p>Total</p>
                                    </div>
                                </div>

                                @foreach ($all_inf['lotes'] as $inf_lot)
                                    @php
                                        //Calculo total
                                        $total_remate += $inf_lot->himp_csub;
                                        $total_base += $inf_lot->base_csub;
                                        $total_licencia_exportacion += $inf_lot->licencia_exportacion;
                                    @endphp

                                    @include('pages.panel.payment_gateway.lot', [
                                        'cod_sub' => $inf_lot->sub_csub,
                                        'image' => "/img/load/lote_medium/$inf_lot->imagen",
                                        'ref' => $inf_lot->ref_csub,
                                        'description' => $inf_lot->desc_hces1,
                                        'imp_award' => $inf_lot->himp_csub,
                                        'imp_commision' => $inf_lot->base_csub,
                                        'pais_clid' => $codPais_clid,
                                    ])
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="payment_summary">
                    @foreach ($all_adj as $key_sub => $all_inf)
                        @include('pages.panel.payment_gateway.summary', [
                            'cod_sub' => $all_inf['inf']->cod_sub,
                            'title' => $all_inf['inf']->name,
                            'total_base' => $total_base,
                            'total_iva' => $total_base * ($iva / 100),
                            'total_remate' => $total_remate,
                            'total_licencia_exportacion' => $total_licencia_exportacion,
                            'isCompraWeb' => $all_inf['inf']->compraweb_sub == 'S',
                            'isCorrCli' => $data['user']->envcorr_cli == 'B',
							'lots_count' => count($all_inf['lotes']),
                        ])
                    @endforeach
                </div>

                <div class="payment_profoma-summary">
                    @foreach ($all_adj as $key_sub => $all_inf)
                        @include('pages.panel.payment_gateway.summary', [
                            'cod_sub' => $all_inf['inf']->cod_sub,
                            'title' => $all_inf['inf']->name,
                            'total_base' => $total_base,
                            'total_iva' => $total_base * ($iva / 100),
                            'total_remate' => $total_remate,
                            'total_licencia_exportacion' => $total_licencia_exportacion,
                            'isCompraWeb' => $all_inf['inf']->compraweb_sub == 'S',
                            'isCorrCli' => $data['user']->envcorr_cli == 'B',
							'lots_count' => count($all_inf['lotes']),
                        ])
                    @endforeach
                </div>

            </section>
        </form>

    </section>

    <script>
        $(document).ready(function() {
            reload_carrito();
        });
    </script>

@stop
