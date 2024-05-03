@php
    use App\libs\Currency;
    $currency = new Currency();
    $divisa = Session::get('user.currency', 'EUR');
    $divisas = $currency->setDivisa($divisa)->getAllCurrencies();
@endphp

<div class="summary-allotments-auctions-block">

    <table class="table table-striped table-hover">

        <thead>
            <tr>
                <th scope="col">{{ trans("$theme-app.user_panel.date") }}</th>
                <th scope="col">{{ trans("$theme-app.user_panel.auction") }}</th>
                <th scope="col" class="hidden-xs">{{ trans("$theme-app.user_panel.no_invoice") }}</th>
                <th scope="col">{{ trans("$theme-app.user_panel.total_bill") }}</th>
                <th scope="col">{{ trans("$theme-app.user_panel.status") }}</th>
                <th scope="col"></th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data['profomaInvoicesPendings'] as $proformaId => $profomaInvoice)
                @include('pages.panel.summary.allotments_auction', [
                    'id' => $proformaId,
                    'document' => $profomaInvoice->first(),
                    'isPayed' => false,
                ])
            @endforeach

            @foreach ($data['billsPending'] as $bill)
                @include('pages.panel.summary.allotments_bill', [
                    'id' => "$bill->anum_pcob-$bill->num_pcob",
                    'document' => $bill,
                    'isPayed' => false,
                ])
            @endforeach

            @foreach ($data['profomaInvoicesPayeds'] as $proformaId => $profomaInvoice)
                @include('pages.panel.summary.allotments_auction', [
                    'id' => $proformaId,
                    'document' => $profomaInvoice->first(),
                    'isPayed' => true,
                ])
            @endforeach

            @foreach ($data['billsPayeds'] as $bill)
                @include('pages.panel.summary.allotments_bill', [
                    'id' => "$bill->afra_cobro1-$bill->nfra_cobro1",
                    'document' => $bill,
                    'isPayed' => true,
                ])
            @endforeach
        </tbody>
    </table>
</div>
