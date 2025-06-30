@extends('layouts.default')

@push('scripts')
    <script defer src="{{ URL::asset('js/numeral.js') }}"></script>
@endpush

@section('content')
    <script>
        var pendientes = @json($data['pending'])
    </script>

    <section class="container user-panel-page bills-page">
        <div class="row">
            <div class="col-lg-3">
                @include('pages.panel.menu_micuenta')
            </div>

            <div class="col-lg-9">
                <h1>{{ trans('web.user_panel.pending_bills') }}</h1>

                <div class="accordion mb-2">
                    <h2 class="accordion-item accordion-header" id="bills-pending-heading">
                        <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#bills-pending-collapse"
                            type="button" aria-expanded="true" aria-controls="bills-pending-collapse">
                            {{ trans('web.user_panel.still_paid') }}
                        </button>
                    </h2>

                    <div class="accordion-collapse collapse show" id="bills-pending-collapse"
                        aria-labelledby="#bills-pending-heading">
                        <div class="accordion-body p-0">

                            <div class="table-to-columns">
                                <form id="pagar_fact">
                                    @csrf
                                    <table class="table table-sm align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th>{{ trans('web.user_panel.pending_bills') }}</th>
                                                <th>{{ trans('web.user_panel.date') }}</th>
                                                <th>{{ trans('web.user_panel.total_fact') }}</th>
                                                <th>{{ trans('web.user_panel.total_price_fact') }}</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>


                                            @foreach ($data['pending'] as $key_bill => $pendiente)
                                                @include('pages.panel.bills.bills', [
                                                    'anum' => $pendiente->anum_pcob,
                                                    'num' => $pendiente->num_pcob,
                                                    'efec' => $pendiente->efec_pcob,
                                                    'fec' => $pendiente->fec_pcob,
                                                    'imp' => $pendiente->imp_pcob,
                                                    'bill' => $pendiente,
                                                    'info_factura' => $data['inf_factura'],
                                                    'tipo_tv' => $data['tipo_tv'],
                                                ])
                                            @endforeach

                                        </tbody>
                                    </table>
                                </form>
                            </div>

                            @if (Config::get('app.pasarela_web'))
                                <div class="total-price checkout mb-2">

                                    <div class="importe_total adj">
                                        <h4>{{ trans('web.user_panel.total_price') }}</h4>
                                        <h4><span id="total_bills">00</span> {{ trans('web.subastas.euros') }}</h4>
                                    </div>
                                    @if (\Config::get('app.PayBizum') || \Config::get('app.PayTransfer') || \Config::get('app.paymentPaypal'))

                                        <div class="btn-group gap-1" role="group" aria-label="Pay method">

                                            @if (\Config::get('app.paymentUP2') || Config::get('app.paymentRedsys'))
                                                <input class="btn-check" id="paycreditcard" name="paymethod" type="radio"
                                                    value="creditcard" autocomplete="off" checked>
                                                <label class="btn btn-outline-lb-secondary" for="paycreditcard">
                                                    {{ trans('web.user_panel.pay_creditcard') }}
                                                </label>
                                            @endif

                                            @if (\Config::get('app.PayBizum'))
                                                <input class="btn-check" id="paybizum" name="paymethod" type="radio"
                                                    value="bizum" autocomplete="off">
                                                <label class="btn btn-outline-lb-secondary" for="paybizum">
                                                    <img src="/default/img/logos/bizum-blue.png"
                                                        style="height: 20px;margin: 0px 6px;">
                                                    {{ trans('web.user_panel.pay_bizum') }}
                                                </label>
                                            @endif

                                            @if (\Config::get('app.PayTransfer'))
                                                <input class="btn-check" id="paytransfer" name="paymethod" type="radio"
                                                    value="transfer" autocomplete="off">
                                                <label class="btn btn-outline-lb-secondary" for="paytransfer">
                                                    {{ trans('web.user_panel.pay_transfer') }}
                                                </label>
                                            @endif

                                            @if (\Config::get('app.paymentPaypal'))
                                                <input class="btn-check" id="paypaypal" name="paymethod" type="radio"
                                                    value="paypal" autocomplete="off">
                                                <label class="btn btn-outline-lb-secondary" for="paypaypal">
                                                    <i class="fa fa-paypal" aria-hidden="true"></i>
                                                    {{ trans('web.user_panel.pay_paypal') }}
                                                </label>
                                            @endif

                                        </div>
                                    @endif


                                    <div>

                                        <button class="btn btn-custom hidden" id="btoLoader" type="button">
                                            <div class="loader"></div>
                                        </button>

                                        <button class="btn btn-lb-primary hidden" id="submit_fact"
                                            type="button">{{ trans('web.user_panel.pay') }}</button>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>

                </div>

                <div class="accordion">
                    <h2 class="accordion-item accordion-header" id="bills-payed-heading">
                        <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#bills-payed-collapse"
                            type="button" aria-expanded="true" aria-controls="bills-payed-collapse">
                            {{ trans('web.user_panel.bills') }}
                        </button>
                    </h2>

                    <div class="accordion-collapse collapse show" id="bills-payed-collapse"
                        aria-labelledby="#bills-payed-heading">
                        <div class="accordion-body p-0">

                            <div class="table-to-columns">

                                <table class="table table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>{{ trans('web.user_panel.pending_bills') }}</th>
                                            <th>{{ trans('web.user_panel.date') }}</th>
                                            <th>{{ trans('web.user_panel.total_fact') }}</th>
                                            <th>{{ trans('web.user_panel.paid_out') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['bills'] as $key_bill => $bill)
                                            @include('pages.panel.bills.bills', [
                                                'anum' => $bill->afra_cobro1,
                                                'num' => $bill->nfra_cobro1,
                                                'fec' => $bill->fec_cobro1,
                                                'imp' => $bill->imp_cobro1,
                                                'bill' => $bill,
                                                'info_factura' => $data['inf_factura_pag'],
                                                'tipo_tv' => $data['tipo_tv_pag'],
                                            ])
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>

                </div>

            </div>
    </section>

    <script>
        $(document).ready(function() {
            reload_facturas();
        });
    </script>
@stop
