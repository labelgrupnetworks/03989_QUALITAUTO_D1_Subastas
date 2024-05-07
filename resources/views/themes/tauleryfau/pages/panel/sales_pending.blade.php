@extends('layouts.panel')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
    use App\libs\Currency;
    $currency = new Currency();
    $divisa = Session::get('user.currency', 'EUR');
    $divisas = $currency->setDivisa($divisa)->getAllCurrencies();
@endphp

@section('content')
    <script>
        var currency = @JSON($divisas);
        var divisa = @JSON($divisa);
        var replaceZeroDecimals = true;
    </script>

    <section class="sales-page">
        <div class="">
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
                        {{ trans("$theme-app.user_panel.assignment") }}
                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="sales-filter-toogle">
                        <form action="">
                            @foreach ($numsHces as $numHces)
                                <li>
                                    <div class="checkbox">
                                        <label>
                                            <input name="sheets[]" type="checkbox" value="{{ $numHces }}"
                                                @checked(in_array($numHces, $sheetsSelected))>{{ $numHces }}
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                            <li class="divider" role="separator"></li>
                            <li>
                                <button class="btn btn-lb btn-lb-primary"
                                    type="submit">{{ trans("$theme-app.global.filter") }}</button>
                            </li>
                        </form>
                    </ul>
                </div>
            </div>

            <div class="sales-menu">
                <a class="btn btn-lb btn-lb-primary btn-large"
                    href="{{ route('panel.sales.pending-assign', ['lang' => config('app.locale')]) }}">
                    <span class="visible-md visible-lg">{{ trans("$theme-app.user_panel.pending_auction") }}</span>
                    <span class="hidden-md hidden-lg">{{ trans("$theme-app.user_panel.pendings") }}</span>
                </a>
                <a class="btn btn-lb btn-lb-outline btn-large"
                    href="{{ route('panel.sales.active', ['lang' => config('app.locale')]) }}">
                    <span class="visible-md visible-lg">{{ trans("$theme-app.user_panel.active_auctions") }}</span>
                    <span class="hidden-md hidden-lg">{{ trans("$theme-app.user_panel.active") }}</span>
                </a>
                <a class="btn btn-lb btn-lb-outline btn-large"
                    href="{{ route('panel.sales.finish', ['lang' => config('app.locale')]) }}">
                    <span class="visible-md visible-lg">{{ trans("$theme-app.user_panel.auctions_completed") }}</span>
                    <span class="hidden-md hidden-lg">{{ trans("$theme-app.user_panel.finished") }}</span>
                </a>
            </div>

            <div class="sales-summary">
                <div class="sales-summary_detail">
                    <span class="js-divisa sales-counter" id="impsalPrice" value="{{ $lots->sum('impsal_hces1') }}">
                        0
                    </span>
                    <p>{{ trans("$theme-app.user_panel.starting_price") }}</p>
                </div>
                <div class="sales-summary_detail">
                    <div class="number-wrapper">
                        <span class="js-divisa sales-counter" id="imptasPrice" value="{{ $lots->sum('imptas_hces1') }}">
                            0
                        </span>
                    </div>
                    <p>{{ trans("$theme-app.user_panel.estimate_price") }}</p>
                </div>
                <div class="sales-summary_detail">
                    <div class="number-wrapper">
                        <span class="sales-counter" id="countLots" value="{{ $lots->count() }}">
                            0
                        </span>
                    </div>
                    <p>{{ trans("$theme-app.user_panel.pending_lots") }}</p>
                </div>
            </div>
        </div>


        <div class="panel-lots pending-lots">
            <div class="panel-lots_header-wrapper">
                <div class="table-grid_header panel-lots_header">
                    <p></p>
                    <p>{{ trans("$theme-app.user_panel.assignment") }}</p>
                    <p>{{ trans("$theme-app.user_panel.line") }}</p>
                    <p>{{ trans("$theme-app.user_panel.description") }}</p>
                    <p>{{ trans("$theme-app.user_panel.starting_price") }}</p>
                    <p>{{ trans("$theme-app.user_panel.estimated") }}</p>
                </div>
            </div>

            @foreach ($lots as $lot)
                <div class="panel-lot-wrapper">
                    <div class="panel-lot sales-lot" data-type="pending">
                        <div class="panel-lot_img">
                            <img class="img-responsive"
                                src="{{ Tools::url_img('lote_medium', $lot->num_hces1, $lot->lin_hces1) }}" alt=""
                                loading="lazy">
                        </div>
                        <div class="panel-lot_sheet">
                            <p>
                                <span class="panel-lot_label">{{ trans("$theme-app.user_panel.assignment") }}</span>
                                {{ $lot->num_hces1 }}
                            </p>
                        </div>
                        <div class="panel-lot_line-sheet">
                            <p>
                                <span class="panel-lot_label">{{ trans("$theme-app.user_panel.line") }}</span>
                                {{ $lot->lin_hces1 }}
                            </p>
                        </div>
                        <div class="panel-lot_desc">
                            <p>{!! $lot->descweb_hces1 !!}</p>
                        </div>
                        <div class="panel-lot_label label-price-salida">
                            <span>{{ trans("$theme-app.user_panel.starting_price_min") }}</span>
                        </div>
                        <div class="panel-lot_price-salida">
                            <p class="js-divisa" value="{{ $lot->impsal_hces1 }}">
                                {!! $currency->getPriceSymbol(2, $lot->impsal_hces1) !!}
                            </p>
                        </div>
                        <div class="panel-lot_label label-price-estimate">
                            <span>
                                {{ trans("$theme-app.user_panel.estimate") }}
                            </span>
                        </div>
                        <div class="panel-lot_estimate-price">
                            <p class="js-divisa" value="{{ $lot->imptas_hces1 }}">
                                {!! $currency->getPriceSymbol(2, $lot->imptas_hces1) !!}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </section>
@stop
