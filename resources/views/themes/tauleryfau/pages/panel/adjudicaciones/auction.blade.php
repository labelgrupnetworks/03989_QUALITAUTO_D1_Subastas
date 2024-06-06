@php
    $totalInvoice = $document->himp_csub + $document->base_csub + $document->base_csub_iva;
    $state = ['class' => 'alert', 'text' => 'Pendiente'];
	$auctionNumber = fn($text, $codSub) => preg_match('/\b\d+\b/', $text, $matches) ? $matches[0] : $codSub;
@endphp

<div class="invoice-wrapper" data-type="pending" data-id="{{ $id }}">
    <div class="invoice-auction">
        <p>
            {{ date('d/m/Y', strtotime($document->fecha_csub)) }}
        </p>
        <p class="allotment-invoice_cod text-center text-md-start">
            <span class="visible-md visible-lg">{{ $document->name ?? '' }}</span>
            <span class="hidden-md hidden-lg">
				{{ $auctionNumber($document->name, $document->cod_sub) }}
			</span>
        </p>
        <p class="visible-md visible-lg">{{ str_replace('-', '/', $id) }}</p>

        <p class="js-divisa fw-bold" value="{{ $document->total_imp_invoice ?? 0 }}" style="font-size: 13px;">
            {!! $currency->getPriceSymbol(2, $document->total_imp_invoice ?? 0) !!}
        </p>
        <p class="allotment-invoice_state">
            <span class="badge badge-{{ $state['class'] }}">{{ $state['text'] }}</span>
        </p>
        <p class="allotment-invoice_pay-buttons">
            @if ($document->compraweb_sub == 'S')

                <a class="btn btn-lb btn-lb-secondary"
                    href="{{ route('panel.allotment.proforma', ['apre' => $document->apre_csub, 'npre' => $document->npre_csub, 'lang' => Config::get('app.locale')]) }}"
                    cod_sub="{{ $document->cod_sub }}">{{ trans($theme . '-app.user_panel.pay_now') }}</a>

                @if (!empty($document->prefactura))
                    <a class="panel-pdf-icon" href="/prefactura/{{ $document->cod_sub }}" target="_blank" download>
                        <img src="/themes/{{ $theme }}/assets/icons/file-pdf-solid.svg" alt="PDF file" width="18.75">
                    </a>
                @endif
            @endif
        </p>
        <div class="actions">
            <a class="btn btn-lb btn-lb-outline" data-toggle="tab" href="#auction-details-{{ $id }}"
                role="tab" aria-controls="settings">
                {{ trans("$theme-app.user_panel.see_detail") }}
            </a>
        </div>
    </div>
</div>
