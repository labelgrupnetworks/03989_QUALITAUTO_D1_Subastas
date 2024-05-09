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
    </script>

    <section class="allotments-page">
        <div class="panel-title">
            <h1>{{ trans("$theme-app.user_panel.my_pending_bills") }}</h1>

            <select id="actual_currency">
                @foreach ($divisas as $divisaOption)
                    <option value='{{ $divisaOption->cod_div }}' @selected($divisaOption->cod_div == $divisa)>
                        {{ $divisaOption->cod_div }}
                    </option>
                @endforeach
            </select>

            <div class="dropdown sales-filter">
                <span>{{ trans("$theme-app.user_panel.filters") }}</span>
                <button class="custom-select" id="sales-filter-toogle" data-toggle="dropdown" type="button" aria-haspopup="true"
                    aria-expanded="false">
                    {{ trans("$theme-app.user_panel.year") }}
                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="sales-filter-toogle">
                    <form action="">
                        @foreach ($data['yearsAvailables'] as $year)
                            <li>
                                <div class="checkbox">
                                    <label>
                                        <input name="years[]" type="checkbox" value="{{ $year }}"
                                            @checked(in_array($year, $data['yearsSelected']))>{{ $year }}
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


        <div class="allotments-auctions-block">
            <div class="alltoments-auctions">

                <div class="alltoments-header-wrapper">
                    <div class="table-grid_header alltoments-auctions_header">
                        <p>{{ trans("$theme-app.user_panel.date") }}</p>
                        <p>{{ trans("$theme-app.user_panel.auction") }}</p>
                        <p class="visible-md visible-lg">{{ trans("$theme-app.user_panel.no_invoice") }}</p>
                        <p class="allotment-auctions_header-imp">{{ trans("$theme-app.user_panel.total_bill") }}</p>
                        <p class="visible-md visible-lg">{{ trans("$theme-app.user_panel.status") }}</p>
                        <p></p>
                        <p></p>
                        <p></p>
                    </div>
                </div>

                @foreach ($data['profomaInvoicesPendings'] as $proformaId => $profomaInvoice)
                    @include('pages.panel.adjudicaciones.auction', [
                        'id' => $proformaId,
                        'document' => $profomaInvoice->first(),
                        'isPayed' => false,
                    ])
                @endforeach

                @foreach ($data['billsPending'] as $bill)
                    @include('pages.panel.adjudicaciones.bill', [
                        'id' => "$bill->anum_pcob-$bill->num_pcob",
                        'document' => $bill,
                        'isPayed' => false,
                    ])
                @endforeach

                @foreach ($data['profomaInvoicesPayeds'] as $proformaId => $profomaInvoice)
                    @include('pages.panel.adjudicaciones.auction', [
                        'id' => $proformaId,
                        'document' => $profomaInvoice->first(),
                        'isPayed' => true,
                    ])
                @endforeach

                @foreach ($data['billsPayeds'] as $bill)
                    @include('pages.panel.adjudicaciones.bill', [
                        'id' => "$bill->afra_cobro1-$bill->nfra_cobro1",
                        'document' => $bill,
                        'isPayed' => true,
                    ])
                @endforeach

                @if (
                    $data['profomaInvoicesPendings']->isEmpty() &&
                        $data['billsPending']->isEmpty() &&
                        $data['profomaInvoicesPayeds']->isEmpty() &&
                        $data['billsPayeds']->isEmpty())

					<div class="invoice-wrapper empty-auction">
						<div class="invoice-auction">
                            <p>{{ trans("$theme-app.user_panel.no_purchases_during_selecteds") }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>


        <section class="tab-content" id="auction-details">


            @foreach ($data['profomaInvoicesPendings'] as $proformaId => $profomaInvoiceLots)
                @php
                    $title = $profomaInvoiceLots->first()->des_sub ?? 'proforma pendiente sin lotes';
                @endphp
                <x-panel.adjudicaciones_details :title="$title" :id="$proformaId">
                    @foreach ($profomaInvoiceLots as $lot)
                        @include('pages.panel.adjudicaciones.lot', [
                            'num' => $lot->num_hces1,
                            'lin' => $lot->lin_hces1,
                            'ref' => $lot->ref_asigl0,
                            'title' => $lot->titulo_hces1,
                            'description' => $lot->descweb_hces1 ?? $lot->desc_hces1,
                            'imp_sal' => $lot->impsalhces_asigl0,
                            'imp_award' => $lot->himp_csub,
                            'isPayed' => false,
                        ])
                    @endforeach
                </x-panel.adjudicaciones_details>
            @endforeach

            @foreach ($data['billsPending'] as $bill)
                @php
                    $lots = !empty($bill->inf_fact['S']) ? $bill->inf_fact['S'] : [];
                    $title = !empty($lots) ? $lots[0]->des_sub : 'factura pendiente sin lotes';
                    $id = "$bill->anum_pcob-$bill->num_pcob";
                @endphp
                <x-panel.adjudicaciones_details :title="$title" :id="$id">
                    @foreach ($lots as $lot)
                        @include('pages.panel.adjudicaciones.lot', [
                            'num' => $lot->numhces_dvc1l,
                            'lin' => $lot->linhces_dvc1l,
                            'ref' => $lot->ref_dvc1l,
                            'title' => $lot->titulo_hces1,
                            'description' => $lot->descweb_hces1 ?? $lot->desc_hces1,
                            'imp_sal' => $lot->impsalhces_asigl0,
                            'imp_award' => $lot->padj_dvc1l,
                            'isPayed' => false,
                        ])
                    @endforeach
                </x-panel.adjudicaciones_details>
            @endforeach

            @foreach ($data['profomaInvoicesPayeds'] as $proformaId => $profomaInvoiceLots)
                @php
                    $title = $profomaInvoiceLots->first()->des_sub ?? 'proforma pagada sin lotes';
                @endphp
                <x-panel.adjudicaciones_details :title="$title" :id="$proformaId">
                    @foreach ($profomaInvoiceLots as $lot)
                        @include('pages.panel.adjudicaciones.lot', [
                            'num' => $lot->num_hces1,
                            'lin' => $lot->lin_hces1,
                            'ref' => $lot->ref_asigl0,
                            'title' => $lot->titulo_hces1,
                            'description' => $lot->descweb_hces1 ?? $lot->desc_hces1,
                            'imp_sal' => $lot->impsalhces_asigl0,
                            'imp_award' => $lot->himp_csub,
                            'isPayed' => true,
                        ])
                    @endforeach
                </x-panel.adjudicaciones_details>
            @endforeach

            @foreach ($data['billsPayeds'] as $bill)
                @php
                    $lots = !empty($bill->inf_fact['S']) ? $bill->inf_fact['S'] : [];
                    $title = !empty($lots) ? $lots[0]->des_sub : 'factura sin lotes';
                    $id = "$bill->afra_cobro1-$bill->nfra_cobro1";
                @endphp
                <x-panel.adjudicaciones_details :title="$title" :id="$id">
                    @foreach ($lots as $lot)
                        @include('pages.panel.adjudicaciones.lot', [
                            'num' => $lot->numhces_dvc1l,
                            'lin' => $lot->linhces_dvc1l,
                            'ref' => $lot->ref_dvc1l,
                            'title' => $lot->titulo_hces1,
                            'description' => $lot->descweb_hces1 ?? $lot->desc_hces1,
                            'imp_sal' => $lot->impsalhces_asigl0,
                            'imp_award' => $lot->padj_dvc1l,
                            'isPayed' => true,
                        ])
                    @endforeach
                </x-panel.adjudicaciones_details>
            @endforeach
        </section>

    </section>

    <script>
        $(function() {

            $('.js-btn-certificate').on('click', function() {
                let data = {
                    '_token': '{{ csrf_token() }}',
                    'cod_sub': this.dataset.codsub,
                    'ref_asigl0': this.dataset.ref
                };

                $.ajax({
                    type: "POST",
                    url: "{{ route('panel.allotment.certifiacte', ['lang' => Config::get('app.locale')]) }}",
                    data: data,
                    success: function(response) {
                        var a = document.createElement("a");
                        a.href = response;
                        a.setAttribute("download", `${data.cod_sub}-${data.ref_asigl0}`);
                        a.click();
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });

            });
        });
    </script>


@stop
