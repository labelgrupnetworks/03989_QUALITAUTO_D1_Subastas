{{-- Facturas --}}
@php
    //Tauler solo recibe facturas por pagar, pero me sirve como componente para otro cliente
    $anum = $isPayed ? $document->afra_cobro1 : $document->anum_pcob;
    $num = $isPayed ? $document->nfra_cobro1 : $document->num_pcob;
    $efec = $isPayed ? null : $document->efec_pcob;
    $fec = $isPayed ? $document->fec_cobro1 : $document->fec_pcob;
    $imp = $isPayed ? $document->imp_cobro1 : $document->imp_pcob;

	$url = "/factura/$anum-$num";

	$state = match(true) {
		(empty($document->followUp)) => ['class' => 'alert', 'text' => 'Pendiente'],
		($document->followUp->idseg_dvc0seg == 1) => ['class' => 'success', 'text' => 'Pagado'],
		($document->followUp->idseg_dvc0seg == 2) => ['class' => 'warning', 'text' => 'Tramitando exportación'],
		($document->followUp->idseg_dvc0seg == 4) => ['class' => 'success', 'text' => 'Recogido en tienda'],
	};

	$description = $document->des_sub ?? $document->inf_fact['S'][0]->des_sub ?? '';
@endphp

{{-- @php
$totalBillsImport += $bill->imp_pcob;
if(!empty($bill->factura)){
	$pdfBills[] = "/factura/$bill->anum_pcob-$bill->num_pcob";
}
@endphp --}}


<div class="invoice-wrapper" data-type="pending-bill" data-id="{{ $id }}" data-anum="{{ $anum }}"
    data-num="{{ $num }}" data-efec="{{ $efec ?? '' }}">

    <div class="invoice-auction">
        <p>
            {{ date('d/m/Y', strtotime($fec)) }}
        </p>
        <p>
            <span class="visible-md visible-lg">{{ $description }}</span>
            <span class="hidden-md hidden-lg">{{ $document->cod_sub }}</span>
        </p>
        <p class="visible-md visible-lg">{{ str_replace('-', '/', $id) }}</p>
        <p class="js-divisa" value="{{ $document->total_price ?? 0 }}">
            {!! $currency->getPriceSymbol(2, $document->total_price ?? 0) !!}
        </p>

        {{-- importe pendiente de pago --}}
        {{-- <p class="js-divisa" value="{{ $document->imp ?? 0 }}">
            {!! $currency->getPriceSymbol(2, $document->imp ?? 0) !!}
        </p> --}}

        <p class="allotment-invoice_state">
            <span class="badge badge-{{ $state['class'] }}">{{ $state['text'] }}</span>
        </p>

        <div class="allotment-invoice_pay-buttons">
            @if (!$isPayed)
                @if (!empty($efec) && $document->compraweb_sub != 'N')
                    <form class="js-pay-bill w-100" action="/gateway/pagarFacturasWeb" method="POST">
                        <input name="factura[{{ $anum }}][{{ $num }}]" type="hidden" value="1">
                        <button class="btn btn-lb btn-lb-secondary"
                            type="submit">{{ trans("$theme-app.user_panel.pay_now") }}</button>
                    </form>
                @endif
            @else
                <span class="badge badge-success">Pagado</span>

				@if (!empty($document->factura) && file_exists($document->factura))
				<a class="panel-pdf-icon" target="_blank" href="/prefactura/{{ $url }}" download>
					<i class="fas fa-file-pdf fa-2x"></i>
				</a>
            	@endif
            @endif
        </div>

        <div class="actions">
            <a class="btn btn-lb btn-lb-outline" data-toggle="tab" href="#auction-details-{{ $id }}"
                role="tab" aria-controls="settings">
                Ver detalle
            </a>
        </div>
    </div>
</div>
