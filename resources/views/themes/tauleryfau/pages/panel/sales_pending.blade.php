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
                    <span>Filtros</span>
                    <button class="custom-select" id="sales-filter-toogle" data-toggle="dropdown" type="button"
                        aria-haspopup="true" aria-expanded="false">
                        Cesión
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
                                <button class="btn btn-lb btn-lb-primary" type="submit">Filtrar</button>
                            </li>
                        </form>
                    </ul>
                </div>
            </div>

            <div class="sales-menu">
                <a class="btn btn-lb btn-lb-primary btn-large"
                    href="{{ route('panel.sales.pending-assign', ['lang' => config('app.locale')]) }}">
                    <span class="visible-md visible-lg">Pendientes de subastar</span>
                    <span class="hidden-md hidden-lg">Pendientes</span>
                </a>
                <a class="btn btn-lb btn-lb-outline btn-large"
                    href="{{ route('panel.sales', ['lang' => config('app.locale')]) }}">
                    <span class="visible-md visible-lg">Subastas activas</span>
                    <span class="hidden-md hidden-lg">Activas</span>
                </a>
                <a class="btn btn-lb btn-lb-outline btn-large"
                    href="{{ route('panel.sales.finish', ['lang' => config('app.locale')]) }}">
                    <span class="visible-md visible-lg">Subastas Finalizadas</span>
                    <span class="hidden-md hidden-lg">Finalizadas</span>
                </a>
            </div>

            <div class="sales-summary">
                <div class="sales-summary_detail">
                    <span class="js-divisa sales-counter" id="impsalPrice" value="{{ $lots->sum('impsal_hces1') }}">
                        0
                    </span>
                    <p>Precio de salida</p>
                </div>
                <div class="sales-summary_detail">
                    <div class="number-wrapper">
                        <span class="js-divisa sales-counter" id="imptasPrice" value="{{ $lots->sum('imptas_hces1') }}">
                            0
                        </span>
                    </div>
                    <p>Precio de estimación</p>
                </div>
                <div class="sales-summary_detail">
                    <div class="number-wrapper">
                        <span class="sales-counter" id="countLots" value="{{ $lots->count() }}">
                            0
                        </span>
                    </div>
                    <p>Lotes pendientes</p>
                </div>
            </div>
        </div>


        <div class="sales-lots-wrapper pending-lots">
            <div class="sales-lots-header-wrapper">
                <div class="sales-lots-header">
                    <p></p>
                    <p>Cesión</p>
                    <p>Línea</p>
                    <p>Descripción</p>
                    <p>Precio Salida</p>
                    <p>Estimado</p>
                </div>
            </div>

            @foreach ($lots as $lot)
                <div class="sales-lot-wrapper">
                    <div class="sales-lot" data-type="pending">
                        <div class="sales-lot_img">
                            <img class="img-responsive"
                                src="{{ Tools::url_img('lote_medium', $lot->num_hces1, $lot->lin_hces1) }}" alt="">
                        </div>
                        <div class="sales-lot_sheet">
                            <p>
                                <span class="sale-label">Cesión</span>
                                {{ $lot->num_hces1 }}
                            </p>
                        </div>
                        <div class="sales-lot_line-sheet">
                            <p>
                                <span class="sale-label">Línea</span>
                                {{ $lot->lin_hces1 }}
                            </p>
                        </div>
                        <div class="sales-lot_desc">
                            <p>{!! $lot->descweb_hces1 !!}</p>
                        </div>
                        <div class="sale-label label-price-salida">
                            <span>P. Salida</span>
                        </div>
                        <div class="sales-lot_price-salida">
                            <p class="js-divisa" value="{{ $lot->impsal_hces1 }}">
                                {!! $currency->getPriceSymbol(2, $lot->impsal_hces1) !!}
                            </p>
                        </div>
                        <div class="sale-label label-price-estimate">
                            <span>
                                Esimación
                            </span>
                        </div>
                        <div class="sales-lot_estimate-price">
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
