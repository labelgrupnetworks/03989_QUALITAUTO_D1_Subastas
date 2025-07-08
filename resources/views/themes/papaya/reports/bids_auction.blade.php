@extends('reports.layout.layout')

@section('content')

    <div>
        <h1>{{ $reportTitle }}</h1>

        <table class="table-bordered" style="width: 100%">
            <tr>
                <td class="td-title">{{ trans("$theme-app.reports.prop_hces1") }}</td>
                <td>{{ $owner->rsoc_cli ?? '' }}</td>
                <td class="td-title">{{ trans("$theme-app.reports.lote_aparte") }}</td>
                <td>{{ $owner->loteaparte_hces1 ?? '' }}</td>
            </tr>
            <tr>
                <td class="td-title">{{ trans("$theme-app.reports.auction_code") }}</td>
                <td>{{ $auction->cod_sub ?? '' }}</td>
                <td class="td-title">{{ trans("$theme-app.reports.lots_code") }}</td>
                <td>{{ $lotsRange ?? '' }}</td>
            </tr>
            <tr>
                <td class="td-title">{{ trans("$theme-app.reports.date_start") }}</td>
                <td>{{ Tools::getDateFormat($auction->start, 'Y-m-d H:i:s', 'd/m/Y') }}</td>
                <td class="td-title">{{ trans("$theme-app.reports.hour_start") }}</td>
                <td>{{ Tools::getDateFormat($auction->start, 'Y-m-d H:i:s', 'H:i:s') }}</td>
            </tr>
            <tr>
                <td class="td-title">{{ trans("$theme-app.reports.date_end") }}</td>
                <td>{{ Tools::getDateFormat($auction->end, 'Y-m-d H:i:s', 'd/m/Y') }}</td>
                <td class="td-title">{{ trans("$theme-app.reports.hour_end") }}</td>
                <td>{{ Tools::getDateFormat($auction->end, 'Y-m-d H:i:s', 'H:i:s') }}</td>
            </tr>
        </table>

        @if (!empty($bids))
            <h2>{{ $titleTable }}</h2>

            <table class="table-bordered table-content" style="width: 100%; max-width: 100%;">

                <thead>
                    <tr>
                        <th>{{ trans("$theme-app.reports.lot_name") }}</th>
                        <th>{{ trans("$theme-app.reports.licit") }}</th>
                        <th>{{ trans("$theme-app.reports.cli_name") }}</th>
                        <th>{{ trans("$theme-app.reports.imp_asigl1") }}</th>
                        <th>{{ trans("$theme-app.reports.bid_date") }}</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($bids as $bid)
                        <tr>
                            <td>{{ $bid->ref_asigl1 }}</td>

                            @if ($bid->isBid)
                                <td>{{ $bid->cod_licit . ' - ' . $bid->cli_licit }}</td>
                                <td>
									@if(!empty($bid->nom_representados))
										{{ substr($bid->nom_representados, 0, 25) }}
									@elseif($bid->fisjur_cli == 'J')
										{{ substr($bid->rsoc_cli, 0, 25) }}
									@else
										{{ substr($bid->nom_cli, 0, 25) }}
									@endif
								</td>
                                <td>{{ Tools::moneyFormat($bid->imp_asigl1) . ' €' }}</td>
                                <td>{{ Tools::getDateFormat($bid->fec_asigl1, 'Y-m-d H:i:s', 'd/m/Y H:i:s') }}</td>
                            @else
                                <td colspan="4">{{ mb_strtoupper(trans("$theme-app.lot_list.no_bids")) }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@stop
