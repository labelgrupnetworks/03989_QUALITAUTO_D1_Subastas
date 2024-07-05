{{-- Facturas --}}
@php
    $anum = $isPayed ? $document->afra_cobro1 : $document->anum_pcob;
    $num = $isPayed ? $document->nfra_cobro1 : $document->num_pcob;
    $efec = $isPayed ? null : $document->efec_pcob;
    $fec = $isPayed ? $document->fec_cobro1 : $document->fec_pcob;
    $imp = $isPayed ? $document->imp_cobro1 : $document->imp_pcob;

    $url = "/factura/$anum-$num";

    //los estados deben ser traducciones en user_panel.estado_seg_*
    //pero hasta aceptar el diseño, para no modificar los actuales se dejan en texto plano
    $states = [
		0 => ['es' => 'Preparando envío', 'en' => 'Preparing shipment'],
        1 => ['es' => 'Pagado', 'en' => 'Paid'],
        2 => ['es' => 'Tramitando exportación', 'en' => 'Processing export'],
		4 => ['es' => 'Enviado', 'en' => 'Shipped'],
        6 => ['es' => 'Recogido en tienda', 'en' => 'Picked up in store'],
    ];
	$locale = config('app.locale');
	$followUp = !empty($document->followUp) ? $document->followUp->idseg_dvc0seg : null;

	$state = match(true) {
		($followUp == '1') => ['class' => 'success', 'text' => $states[1][$locale]],
		($followUp == '2') => ['class' => 'warning', 'text' => $states[2][$locale]],
		($followUp == '4') => ['class' => 'success', 'text' => $states[4][$locale]],
		($followUp == '6') => ['class' => 'success', 'text' =>	$states[6][$locale]],
		(!$followUp && $isPayed) => ['class' => 'warning', 'text' => $states[0][$locale]],
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

		{{-- <p class="js-divisa fw-bold" value="{{ $document->total_price ?? 0 }}" style="font-size: 13px">
            {!! $currency->getPriceSymbol(2, $document->total_price ?? 0) !!}
        </p> --}}

        {{-- importe pendiente de pago --}}
        <p class="js-divisa fw-bold" value="{{ $imp ?? 0 }}" style="font-size: 13px">
            {!! $currency->getPriceSymbol(2, $imp ?? 0) !!}
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
