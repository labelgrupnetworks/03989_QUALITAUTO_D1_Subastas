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
            <p class="max-line-2 text-center text-md-start">
                <span class="visible-md visible-lg">{{ $document->name ?? '' }}</span>
                <span class="hidden-md hidden-lg">
                    {{ $auctionNumber($document->name, $document->cod_sub) }}
                </span>
            </p>
        </a>
    </td>
    <td class="hidden-xs">
        <a href="{{ $link }}">
			@if (!empty($document->apre_csub))
            	{{ str_replace('-', '/', $id) }}
			@else
				-
			@endif
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
        @if ($document->compraweb_sub == 'S')
			@if (!empty($document->apre_csub))
				<a class="btn btn-lb btn-lb-secondary"
					href="{{ route('panel.allotment.proforma', ['apre' => $document->apre_csub, 'npre' => $document->npre_csub, 'lang' => Config::get('app.locale')]) }}"
					cod_sub="{{ $document->cod_sub }}">
					{{ trans($theme . '-app.user_panel.pay_now') }}
				</a>
				@else
				<a class="btn btn-lb btn-lb-secondary"
					href="{{ route('panel.allotment.sub', ['cod_sub' => $document->cod_sub, 'lang' => Config::get('app.locale')]) }}"
					cod_sub="{{ $document->cod_sub }}">{{ trans($theme . '-app.user_panel.pay_now') }}</a>
				@endif
        @endif
    </td>

    <td class="summary-icons">
        <img src="/themes/{{ $theme }}/assets/icons/eye-regular.svg" alt="go to" style="display: block"
            width="20.25">
    </td>
</tr>
