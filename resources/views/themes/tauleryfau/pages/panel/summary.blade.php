@extends('layouts.panel')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')
    <script>
        var currency = @JSON($divisas);
        var divisa = @JSON($divisa);
    </script>

    <section class="summary-page">
        <div class="panel-title">
            <h1>{{ trans("$theme-app.user_panel.summary") }}</h1>

            <select id="actual_currency">
                @foreach ($divisas as $divisaOption)
                    <option value='{{ $divisaOption->cod_div }}' @selected($divisaOption->cod_div == $divisa)>
                        {{ $divisaOption->cod_div }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="summary_body">
            <div class="summary-allotments">
                <h4 class="summary-subtitle">{{ trans("$theme-app.user_panel.my_pending_bills") }}</h4>
                <div class="loader-box">
                    <div class="loading-wrapper">
                        <div class="loader-a">
                            <div class="loader-inner">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="summary-allotments_table"></div>
            </div>

            <div class="summary-sales">
                <div class="loader-box">
                    <div class="loading-wrapper">
                        <div class="loader-a">
                            <div class="loader-inner">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="summary-sales_header">
                    <h4 class="summary-subtitle">{{ trans("$theme-app.user_panel.my_assignments") }}</h4>
                    <div class="sales-menu">
                        <a class="btn btn-lb btn-lb-outline" onclick="getPendingSales(this)">
                            <span>{{ trans("$theme-app.user_panel.pendings") }}</span>
                        </a>
                        <a class="btn btn-lb btn-lb-primary" onclick="getSales(this)">
                            <span>{{ trans("$theme-app.user_panel.active_auctions") }}</span>
                        </a>
                        <a class="btn btn-lb btn-lb-outline" onclick="getFinishSales(this)">
                            <span>{{ trans("$theme-app.user_panel.auctions_completed") }}</span>
                        </a>
                    </div>
                </div>
                <div id="summary-sales"></div>
            </div>

            <div class="summary-orders">
                <h4 class="summary-subtitle">{{ trans("$theme-app.user_panel.orders") }} | {{ trans("$theme-app.user_panel.favorites") }}</h4>
                <div class="loader-box">
                    <div class="loading-wrapper">
                        <div class="loader-a">
                            <div class="loader-inner">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="summary-favorites"></div>
            </div>
        </div>

    </section>

    <script>
        getAllotmentsAndBills();
        getSales();
        getFavorites();
    </script>
@stop
