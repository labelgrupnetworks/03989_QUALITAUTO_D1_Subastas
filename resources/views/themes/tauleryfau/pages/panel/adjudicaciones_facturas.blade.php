@extends('layouts.panel')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
    use App\libs\Currency;

    $currency = new Currency();
    $divisa = Session::get('user.currency', 'EUR');
    $divisas = $currency->setDivisa($divisa)->getAllCurrencies();

    //temporales
    $invoicesYearsAvailables = [];
    $yearSelected = 2024;
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
                <span>Filtros</span>
                <button class="custom-select" id="sales-filter-toogle" data-toggle="dropdown" type="button" aria-haspopup="true"
                    aria-expanded="false">
                    Año
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
                            <button class="btn btn-lb btn-lb-primary" type="submit">Filtrar</button>
                        </li>
                    </form>
                </ul>
            </div>
        </div>


        <div class="allotments-auctions-block">
            <div class="alltoments-auctions">

                <div class="alltoments-header-wrapper">
                    <div class="alltoments-auctions_header">
                        <p>Fecha</p>
                        <p>Subasta</p>
                        <p class="visible-md visible-lg">Nº Factura</p>
                        <p class="allotment-auctions_header-imp">Total Factura</p>
                        <p class="visible-md visible-lg">Estado</p>
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
                        'isPayed' => true
                    ])
                @endforeach
            </div>
        </div>
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
