{{-- Facturas --}}
@php
    //Tauler solo recibe facturas por pagar, pero me sirve como componente para otro cliente
    $anum = $isPayed ? $document->afra_cobro1 : $document->anum_pcob;
    $num = $isPayed ? $document->nfra_cobro1 : $document->num_pcob;
    $efec = $isPayed ? null : $document->efec_pcob;
    $fec = $isPayed ? $document->fec_cobro1 : $document->fec_pcob;
    $imp = $isPayed ? $document->imp_cobro1 : $document->imp_pcob;

    $url = "/factura/$anum-$num";

    $state = match (true) {
        empty($document->followUp) => ['class' => 'alert', 'text' => trans("$theme-app.user_panel.pending")],
        $document->followUp->idseg_dvc0seg == 1 => [
            'class' => 'success',
            'text' => trans("$theme-app.user_panel.estado_seg_1"),
        ],
        $document->followUp->idseg_dvc0seg == 2 => ['class' => 'warning', 'text' => 'Tramitando exportación'],
        $document->followUp->idseg_dvc0seg == 4 => ['class' => 'success', 'text' => 'Recogido en tienda'],
    };

    $description = $document->des_sub ?? ($document->inf_fact['S'][0]->des_sub ?? '');
    $link = route('panel.allotment-bills', ['lang' => config('app.locale')]) . "#auction-details-{$id}";
	$auctionNumber = fn($text, $codSub) => preg_match('/\b\d+\b/', $text, $matches) ? $matches[0] : $codSub;
@endphp

<tr>
    <td>
        <a href="{{ $link }}">
            {{ date('d/m/Y', strtotime($fec)) }}
        </a>
    </td>
    <td>
        <a href="{{ $link }}">
            <p class="max-line-2">
                <span class="visible-md visible-lg">{{ $description }}</span>
                <span class="hidden-md hidden-lg">
					{{ $auctionNumber($description, $document->cod_sub) }}
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
            <p class="js-divisa fw-bold" value="{{ $document->total_price ?? 0 }}">
                {!! $currency->getPriceSymbol(2, $document->total_price ?? 0) !!}
            </p>
        </a>
    </td>
    <td>
        <a href="{{ $link }}">
            <span class="badge badge-{{ $state['class'] }}">{{ $state['text'] }}</span>
        </a>
    </td>
</tr>
