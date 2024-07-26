{{-- Facturas --}}
@php
    $anum = $isPayed ? $document->afra_cobro1 : $document->anum_pcob;
    $num = $isPayed ? $document->nfra_cobro1 : $document->num_pcob;
    $efec = $isPayed ? null : $document->efec_pcob;
    $fec = $isPayed ? $document->fec_cobro1 : $document->fec_pcob;
    $imp = $isPayed ? $document->imp_cobro1 : $document->total_price;
	$pending = $isPayed ? 0 : $document->imp_pcob;

    $url = "/factura/$anum-$num";

	$followUp = !empty($document->followUp) ? $document->followUp->idseg_dvc0seg : null;
	if(!$followUp && $isPayed) {
		$followUp = 1;
	}

	$state = match(true) {
		(in_array($followUp, [1, 2, 8])) => ['class' => 'warning', 'text' => trans("$theme-app.user_panel.estado_seg_$followUp")],
		(in_array($followUp, [4, 6, 7])) => ['class' => 'success', 'text' => trans("$theme-app.user_panel.estado_seg_$followUp")],
		default => ['class' => 'alert', 'text' => trans("$theme-app.user_panel.pending")]
	};

    $description = $document->des_sub ?? ($document->inf_fact['S'][0]->des_sub ?? '');
    $auctionNumber = fn($text, $codSub) => preg_match('/\b\d+\b/', $text, $matches) ? $matches[0] : $codSub;
@endphp

<div class="invoice-wrapper" data-type="pending-bill" data-id="{{ $id }}" data-anum="{{ $anum }}"
    data-num="{{ $num }}" data-efec="{{ $efec ?? '' }}" data-auction-wrapper>

    <div class="invoice-auction">
        <p>
            {{ date('d/m/Y', strtotime($fec)) }}
        </p>
        <p class="text-center text-md-start">
            <span class="visible-md visible-lg">{{ $description }}</span>
            <span class="hidden-md hidden-lg">
                {{ $auctionNumber($description, $document->cod_sub) }}
            </span>
        </p>
        <p class="visible-md visible-lg">{{ str_replace('-', '/', $id) }}</p>

		<p @class(['js-divisa fw-bold', 'visible-md visible-lg' => !$isPayed]) value="{{ $imp ?? 0 }}" style="font-size: 13px">
            {!! $currency->getPriceSymbol(2, $imp ?? 0) !!}
        </p>

        {{-- importe pendiente de pago --}}
        <p @class(['js-divisa fw-bold', 'visible-md visible-lg' => $isPayed]) value="{{ $pending ?? 0 }}" style="font-size: 13px">
            {!! $currency->getPriceSymbol(2, $pending ?? 0) !!}
        </p>

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
                <span class="badge badge-success">{{ trans("$theme-app.user_panel.paid_out") }}</span>
            @endif

            @if (!empty($document->factura) && file_exists($document->factura))
                <a class="panel-pdf-icon" href="{{ $url }}" target="_blank" download>
                    <img src="/themes/{{ $theme }}/assets/icons/file-pdf-solid.svg" alt="PDF file" width="18.75">
                </a>
            @endif
        </div>

        <div class="actions">
            {{-- <a class="btn btn-lb btn-lb-outline" data-toggle="tab" href="#auction-details-{{ $id }}"
                role="tab" aria-controls="settings">
                {{ trans("$theme-app.user_panel.see_detail") }}
            </a> --}}

			{{-- open Modal --}}
			<button type="button" class="btn btn-lb btn-lb-outline" data-toggle="modal" data-target="#myModal-{{ $id }}" data-id="{{ $id }}">
				{{ trans("$theme-app.user_panel.see_detail") }}
			</button>
        </div>
    </div>
</div>
