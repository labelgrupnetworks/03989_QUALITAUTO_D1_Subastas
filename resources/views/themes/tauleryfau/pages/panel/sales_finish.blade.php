@extends('layouts.panel')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
    use App\libs\Currency;
    $currency = new Currency();
    $divisa = Session::get('user.currency', 'EUR');
    $divisas = $currency->setDivisa($divisa)->getAllCurrencies();

    $statistics = [];
    $statistics['total'] = [
        'total_lots' => $auctionsResults->sum('total_lots'),
        'total_awarded_lots' => $auctionsResults->sum('total_awarded_lots'),
        'total_award' => $auctionsResults->sum('total_award'),
        'total_impsalhces' => $auctionsResults->sum('total_impsalhces'),
        'total_liquidation' => $auctionsResults->sum('total_liquidation'),
    ];

    $statistics['auction'] = $auctionsResults->keyBy('sub_asigl0');
@endphp

@section('content')

    <script>
        var currency = @JSON($divisas);
        var divisa = @JSON($divisa);
        var replaceZeroDecimals = true;
        const statistics = @JSON($statistics);
    </script>

    <section class="sales-page">
        <div class="sticky-section">
            <div class="panel-title">
                <h1>{{ trans("$theme-app.user_panel.my_assignments") }}</h1>

                <select id="actual_currency">
                    @foreach ($divisas as $divisaOption)
                        <option value='{{ $divisaOption->cod_div }}' @selected($divisaOption->cod_div == $divisa)>
                            {{ $divisaOption->cod_div }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sales-menu">
                <a class="btn btn-lb btn-lb-outline btn-large" href="">
                    <span class="visible-md visible-lg">Pendientes de subastar</span>
                    <span class="hidden-md hidden-lg">Pendientes</span>
                </a>
                <a class="btn btn-lb btn-lb-outline btn-large" href="#">
                    <span class="visible-md visible-lg">Subastas activas</span>
                    <span class="hidden-md hidden-lg">Activas</span>
                </a>
                <a class="btn btn-lb btn-lb-primary btn-large" href="">
                    <span class="visible-md visible-lg">Subastas Finalizadas</span>
                    <span class="hidden-md hidden-lg">Finalizadas</span>
                </a>
            </div>

            <div class="sales-summary">
                <div class="sales-summary_detail">
                    <span class="js-divisa sales-counter" id="settlementPrice"
                        value="{{ $statistics['total']['total_liquidation'] }}">
                        0
                    </span>
                    <p>Total Liquidación</p>
                </div>
                <div class="sales-summary_detail">
                    <div class="number-wrapper">
                        <span class="sales-counter" id="percentageAwards"
                            value="{{ ($statistics['total']['total_awarded_lots'] / max($statistics['total']['total_lots'], 1)) * 100 }}">
                            0
                        </span>
                        <span>%</span>
                    </div>
                    <p>Adjudicado</p>
                </div>
                <div class="sales-summary_detail">
                    <div class="number-wrapper">
                        <span class="sales-counter" id="revaluation"
                            value="{{ ($statistics['total']['total_award'] / max($statistics['total']['total_impsalhces'], 1)) * 100 }}">
                            0
                        </span>
                        <span>%</span>
                    </div>
                    <p>Revalorización</p>
                </div>
                <div class="sales-summary_detail sales-summary_detail_lots">
                    <span class="sales-counter" id="consignedLots" value="{{ $statistics['total']['total_lots'] }}">
                        0
                    </span>
                    <p>Lotes consignados</p>
                </div>
                <div class="sales-summary_detail sales-summary_detail_lots">
                    <span class="sales-counter" id="awardedLots"
                        value="{{ $statistics['total']['total_awarded_lots'] }}">0</span>
                    <p>Lotes Vendidos</p>
                </div>
            </div>
        </div>

        <div class="sales-auctions-block">

            <div class="sales-auctions sales-finish-auctions">

                <div class="sales-header-wrapper">
                    <div class="sales-auctions_header">
                        <p>Fecha</p>
                        <p>Subasta</p>
                        <p>Nº Factura</p>

                        <p class="visible-md visible-lg">Total Liquidación</p>
                        <p class="visible-md visible-lg">Total pendiente</p>
                        <p>Estado</p>
                    </div>
                </div>

                @foreach ($auctionsWithoutInvoice as $auctions)
                    @include('pages.panel.sales.auction_finish', [
                        'invoicesAuctions' => $auctions,
                    ])
                @endforeach

                @foreach ($ownerInvoices as $invoiceId => $invoices)
                    @include('pages.panel.sales.invoice_finish', [
                        'invoiceId' => str_replace('/', '-', $invoiceId),
                        'invoicesAuctions' => $invoices,
                    ])
                @endforeach

            </div>

        </div>

        <section class="tab-content" id="auction-details">
            @foreach ($auctionsWithoutInvoice as $lots)
                @include('pages.panel.sales.auction_details', [
                    'id' => $lots->first()->sub_asigl0,
                    'title' => $lots->first()->des_sub,
                    'lotes' => $lots,
                ])
            @endforeach

            @foreach ($ownerInvoices as $invoiceId => $lots)
                @include('pages.panel.sales.auction_details', [
                    'id' => str_replace('/', '-', $invoiceId),
                    'title' => $lots->first()->des_sub,
                    'lotes' => $lots,
                ])
            @endforeach
        </section>

    </section>
@stop
