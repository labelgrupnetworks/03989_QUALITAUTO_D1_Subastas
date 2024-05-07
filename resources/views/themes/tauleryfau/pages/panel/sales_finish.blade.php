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

                <div class="dropdown sales-filter">
                    <span>{{ trans("$theme-app.user_panel.filters") }}</span>
                    <button class="custom-select" id="sales-filter-toogle" data-toggle="dropdown" type="button"
                        aria-haspopup="true" aria-expanded="false">
                        {{ trans("$theme-app.user_panel.year") }}
                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="sales-filter-toogle">
                        <form action="">
                            @foreach ($invoicesYearsAvailables as $year)
                                <li>
                                    <div class="checkbox">
                                        <label>
                                            <input name="years[]" type="checkbox" value="{{ $year }}"
                                                @checked(in_array($year, $yearSelected))>{{ $year }}
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                            <li class="divider" role="separator"></li>
                            <li>
                                <button class="btn btn-lb btn-lb-primary" type="submit">{{ trans("$theme-app.global.filter") }}</button>
                            </li>
                        </form>
                    </ul>
                </div>
            </div>

            <div class="sales-menu">
                <a class="btn btn-lb btn-lb-outline btn-large" href="{{ route('panel.sales.pending-assign', ['lang' => config('app.locale')]) }}">
                    <span class="visible-md visible-lg">{{ trans("$theme-app.user_panel.pending_auction") }}</span>
                    <span class="hidden-md hidden-lg">{{ trans("$theme-app.user_panel.pendings") }}</span>
                </a>
                <a class="btn btn-lb btn-lb-outline btn-large" href="{{ route('panel.sales.active', ['lang' => config('app.locale')]) }}">
                    <span class="visible-md visible-lg">{{ trans("$theme-app.user_panel.active_auctions") }}</span>
                    <span class="hidden-md hidden-lg">{{ trans("$theme-app.user_panel.active") }}</span>
                </a>
                <a class="btn btn-lb btn-lb-primary btn-large" href="{{ route('panel.sales.finish', ['lang' => config('app.locale')]) }}">
                    <span class="visible-md visible-lg">{{ trans("$theme-app.user_panel.auctions_completed") }}</span>
                    <span class="hidden-md hidden-lg">{{ trans("$theme-app.user_panel.finished") }}</span>
                </a>
            </div>

            <div class="sales-summary">
                <div class="sales-summary_detail">
                    <span class="js-divisa sales-counter" id="settlementPrice"
                        value="{{ $statistics['total']['total_liquidation'] }}">
                        0
                    </span>
                    <p>{{ trans("$theme-app.user_panel.total_liquidation") }}</p>
                </div>
                <div class="sales-summary_detail">
                    <div class="number-wrapper">
                        <span class="sales-counter" id="percentageAwards"
                            value="{{ ($statistics['total']['total_awarded_lots'] / max($statistics['total']['total_lots'], 1)) * 100 }}">
                            0
                        </span>
                        <span>%</span>
                    </div>
                    <p>{{ trans("$theme-app.user_panel.awarded") }}</p>
                </div>
                <div class="sales-summary_detail">
                    <div class="number-wrapper">
                        <span class="sales-counter" id="revaluation"
                            value="{{ ($statistics['total']['total_award'] / max($statistics['total']['total_impsalhces'], 1)) * 100 }}">
                            0
                        </span>
                        <span>%</span>
                    </div>
                    <p>{{ trans("$theme-app.user_panel.revaluation") }}</p>
                </div>
                <div class="sales-summary_detail sales-summary_detail_lots">
                    <span class="sales-counter" id="consignedLots" value="{{ $statistics['total']['total_lots'] }}">
                        0
                    </span>
                    <p>{{ trans("$theme-app.user_panel.consigned_lots") }}</p>
                </div>
                <div class="sales-summary_detail sales-summary_detail_lots">
                    <span class="sales-counter" id="awardedLots"
                        value="{{ $statistics['total']['total_awarded_lots'] }}">0</span>
                    <p>{{ trans("$theme-app.user_panel.lots_sold") }}</p>
                </div>
            </div>
        </div>

        <div class="sales-auctions-block">

            <div class="sales-auctions sales-finish-auctions">

                <div class="sales-header-wrapper">
                    <div class="table-grid_heder header sales-auctions_header">
                        <p>{{ trans("$theme-app.user_panel.date") }}</p>
                        <p>{{ trans("$theme-app.user_panel.auction") }}</p>
                        <p class="visible-md visible-lg">{{ trans("$theme-app.user_panel.no_invoice") }}</p>
                        <p>{{ trans("$theme-app.user_panel.total_liquidation") }}</p>
                        <p>Total {{ trans("$theme-app.user_panel.pending") }}</p>
                        <p class="visible-md visible-lg">{{ trans("$theme-app.user_panel.status") }}</p>
                    </div>
                </div>

                @foreach ($auctionsWithoutInvoice as $auctions)
                    @include('pages.panel.sales.auction_finish', [
                        'invoicesAuctions' => $auctions,
                    ])
                @endforeach

                @foreach ($ownerInvoices as $invoiceId => $invoices)
                    @include('pages.panel.sales.invoice_finish', [
                        'invoiceId' => $invoiceId,
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
