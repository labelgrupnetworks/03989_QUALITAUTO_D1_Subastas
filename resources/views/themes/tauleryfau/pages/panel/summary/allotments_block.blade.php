@php
    use App\libs\Currency;
    $currency = new Currency();
    $divisa = Session::get('user.currency', 'EUR');
    $divisas = $currency->setDivisa($divisa)->getAllCurrencies();
@endphp

<div class="summary-allotments-auctions-block">

    <table class="table table-striped table-hover table-allotments">

        <thead>
            <tr>
                <th class="allotments-date" scope="col">{{ trans("$theme-app.user_panel.date") }}</th>
                <th class="allotments-auction text-center text-md-start" scope="col">
                    {{ trans("$theme-app.user_panel.auction") }}</th>
                <th class="allotments-invoice hidden-xs" scope="col">{{ trans("$theme-app.user_panel.no_invoice") }}
                </th>
                <th class="allotments-total" scope="col">
                    {{ trans("$theme-app.user_panel.total") }}
                </th>
                <th class="allotments-staus" scope="col">{{ trans("$theme-app.user_panel.status") }}</th>
                <th class="summary-icons" scope="col"></th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data['profomaInvoicesPendings'] as $proformaId => $profomaInvoice)

				@php
					$sumInvoices = $profomaInvoice->sum('total_imp_invoice');
				@endphp

                @include('pages.panel.summary.allotments_auction', [
                    'id' => $proformaId,
                    'document' => $profomaInvoice->first(),
                    'isPayed' => false,
					'sumInvoices' => $sumInvoices
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
				@php
					$sumInvoices = $profomaInvoice->sum('total_imp_invoice');
				@endphp

                @include('pages.panel.summary.allotments_auction', [
                    'id' => $proformaId,
                    'document' => $profomaInvoice->first(),
                    'isPayed' => true,
					'sumInvoices' => $sumInvoices
                ])
            @endforeach

            @foreach ($data['billsPayeds'] as $bill)
                @include('pages.panel.summary.allotments_bill', [
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
                <tr>
                    <td class="text-center" colspan="6">
                        <p>{{ trans("$theme-app.user_panel.not_lots_purchased_last_year") }}</p>
                    </td>
                </tr>
            @endif

        </tbody>
    </table>
</div>
