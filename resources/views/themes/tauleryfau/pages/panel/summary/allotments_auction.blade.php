@php
    $totalInvoice = $document->himp_csub + $document->base_csub + $document->base_csub_iva;
    $state = ['class' => 'alert', 'text' => 'Pendiente'];
    $link = route('panel.allotment-bills', ['lang' => config('app.locale')]) . "#auction-details-{$id}";
	$auctionNumber = fn($text, $codSub) => preg_match('/\b\d+\b/', $text, $matches) ? $matches[0] : $codSub;
@endphp

<tr>
    <td>
        <a href="{{ $link }}">
            {{ date('d/m/Y', strtotime($document->fecha_csub)) }}
        </a>
    </td>
    <td>
        <a href="{{ $link }}">
            <p class="max-line-2">
                <span class="visible-md visible-lg">{{ $document->name ?? '' }}</span>
                <span class="hidden-md hidden-lg">
					{{ $auctionNumber($document->name, $document->cod_sub) }}
				</span>
            </p>
        </a>
    </td>
    <td class="hidden-xs">
        <a href="{{ $link }}">
            {{ str_replace('-', '/', $id) }}
        </a>
    </td>
    <td>
        <a href="{{ $link }}">
            <p class="js-divisa fw-bold" value="{{ $document->total_imp_invoice ?? 0 }}">
                {!! $currency->getPriceSymbol(2, $document->total_imp_invoice ?? 0) !!}
            </p>
        </a>
    </td>
    <td>
        <a href="{{ $link }}">
            <span class="badge badge-{{ $state['class'] }}">{{ $state['text'] }}</span>
        </a>
    </td>
</tr>
