@extends('layouts.default')

@section('title')
    {{ trans('web.head.title_app') }}
@stop

@section('content')
    @php
        $totalLotes = 0;
        $totalPsalida = 0;
        $totalPremate = 0;
        $lotsSold = 0;
        $lotsInAuction = 0;
    @endphp

    <div class="container user-panel-page sales-page">

        <div class="row">
            <div class="col-lg-3">
                @include('pages.panel.menu_micuenta')
            </div>

            <div class="col-lg-9">
                <h1>{{ trans("web.user_panel.my_lots") }}</h1>

                <div class="accordion">
                    @foreach ($subastas as $cod_sub => $lotes)
                        <div>
                            <h2 class="accordion-item accordion-header" id="{{ $cod_sub }}-heading">
                                <button class="accordion-button" data-bs-toggle="collapse"
                                    data-bs-target="#{{ $cod_sub }}-collapse" type="button" aria-expanded="true"
                                    aria-controls="{{ $cod_sub }}-collapse">
                                    {{ $lotes[0]->name }}
                                </button>
                            </h2>

                            <div class="accordion-collapse collapse show" id="{{ $cod_sub }}-collapse"
                                aria-labelledby="#{{ $cod_sub }}-heading">
                                <div class="accordion-body p-0">
                                    <div class="table-to-columns">
                                        <table class="table table-sm align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th></th>
                                                    <th>{{ trans("web.user_panel.lot") }}</th>
                                                    <th style="max-width: 300px">{{ trans("web.user_panel.title") }}</th>
                                                    <th>{{ trans("web.user_panel.status") }}</th>
                                                    <th>{{ trans("web.lot.lot-price") }}</th>
                                                    <th>{{ trans("web.lot.puja_actual") }}</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($lotes as $lote)
                                                    @php
                                                        $url_friendly = str_slug($lote->desc_hces1);
                                                        $url_friendly = \Routing::translateSeo('lote') . $lote->cod_sub . '-' . str_slug($lote->name) . '-' . $lote->id_auc_sessions . '/' . $lote->ref_asigl0 . '-' . $lote->num_hces1 . '-' . $url_friendly;
                                                        $hay_pujas = !empty($lote->implic_hces1);
                                                        $maxPuja = \Tools::moneyFormat($lote->implic_hces1);
                                                        $cerrado = $lote->cerrado_asigl0 == 'S';
                                                        $devuelto = $lote->fac_hces1 == 'D' || $lote->fac_hces1 == 'R' || $lote->cerrado_asigl0 == 'D';
                                                        $desadjudicado = $lote->desadju_asigl0 == 'S';

                                                        $totalLotes++;
                                                        $totalPsalida += $lote->impsalhces_asigl0;
                                                        $totalPremate += $lote->implic_hces1;

                                                        $status = '';
                                                        if ($hay_pujas) {
                                                            $status = trans("web.subastas.buy");
                                                            $lotsSold++;
                                                        } elseif (strtotime($lote->end) < time()) {
                                                            $status = trans("web.user_panel.closed");
                                                        } elseif (strtotime($lote->orders_start) > time()) {
                                                            $status = trans("web.user_panel.soon");
                                                        } elseif (strtotime($lote->orders_start) < time()) {
                                                            $status = trans("web.sheet_tr.in_auction");
                                                            $lotsInAuction++;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td class="td-img">
                                                            <a href="{{ $url_friendly }}">
                                                                <img class="img-fluid"
                                                                    src="{{ \Tools::url_img('lote_small', $lote->num_hces1, $lote->lin_hces1) }}">
                                                            </a>
                                                        </td>

                                                        <td data-title="{{ trans("web.user_panel.lot") }}">
                                                            {{ $lote->ref_asigl0 }}
                                                        </td>
                                                        <td class="td-title" data-title="{{ trans("web.user_panel.title") }}">
                                                            <span class="max-line-2">{!! $lote->desc_hces1 !!}</span>
                                                        </td>
                                                        <td data-title="{{ trans("web.user_panel.status") }}">
                                                            {{ $status }}
                                                        </td>
                                                        <td data-title="{{ trans("web.lot.lot-price") }}">
                                                            {{ Tools::moneyFormat($lote->impsalhces_asigl0, trans("web.subastas.euros")) }}
                                                        </td>
                                                        <td data-title="{{ trans("web.lot.puja_actual") }}">
                                                            {{ Tools::moneyFormat($lote->implic_hces1, trans("web.subastas.euros")) }}
                                                        </td>

                                                        <td>
                                                            <div class="btn-group">
                                                                <button
                                                                    class="btn btn-sm d-flex align-items-center p-2 rounded-circle"
                                                                    data-bs-toggle="dropdown" type="button"
                                                                    aria-expanded="false">
                                                                    <svg class="bi" width="16" height="16"
                                                                        fill="currentColor">
                                                                        <use
                                                                            xlink:href="/bootstrap-icons.svg#three-dots-vertical" />
                                                                    </svg>
                                                                </button>

                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item" href="{{ $url_friendly }}"
                                                                            target="_blank">{{ trans("web.user_panel.see_lot") }}</a></li>
                                                                    {{-- <li><hr class="dropdown-divider"></li> --}}
                                                                </ul>
                                                            </div>
                                                        </td>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach

                    <hr>

                    <div>
                        <h2 class="accordion-item accordion-header" id="totals-heading">
                            <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#totals-collapse"
                                type="button" aria-expanded="true" aria-controls="totals-collapse">
                                Totales
                            </button>
                        </h2>

                        <div class="accordion-collapse collapse show" id="totals-collapse"
                            aria-labelledby="#totals-heading">
                            <div class="accordion-body p-0">
                                <div class="table-to-columns">
                                    <table class="table table-sm align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>{{ trans("web.lot_list.lots") }}</th>
                                                <th>{{ trans("web.lot_list.award_filter") }}</th>
                                                <th>{{ trans("web.sheet_tr.in_auction") }}</th>
                                                <th>{{ trans("web.lot.lot-price") }}</th>
                                                <th>{{ trans("web.user_panel.price") }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td data-title="{{ trans("web.lot_list.lots") }}">
                                                    {{ $totalLotes }}</td>
                                                <td data-title="{{ trans("web.lot_list.award_filter") }}">
                                                    {{ $lotsSold }}</td>
                                                <td data-title="{{ trans("web.sheet_tr.in_auction") }}">
                                                    {{ $lotsInAuction }}</td>
                                                <td data-title="{{ trans("web.lot.lot-price") }}">
                                                    {{ Tools::moneyFormat($totalPsalida, trans("web.subastas.euros")) }}
                                                </td>
                                                <td data-title="{{ trans("web.user_panel.price") }}">
                                                    {{ Tools::moneyFormat($totalPremate, trans("web.subastas.euros")) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop
