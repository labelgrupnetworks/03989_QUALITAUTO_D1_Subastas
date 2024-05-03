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
		(empty($document->followUp)) => ['class' => 'alert', 'text' =>  trans("$theme-app.user_panel.pending")],
		($document->followUp->idseg_dvc0seg == 1) => ['class' => 'success', 'text' => trans("$theme-app.user_panel.estado_seg_1")],
		($document->followUp->idseg_dvc0seg == 2) => ['class' => 'warning', 'text' => 'Tramitando exportación'],
		($document->followUp->idseg_dvc0seg == 4) => ['class' => 'success', 'text' => 'Recogido en tienda'],
	};

	$description = $document->des_sub ?? $document->inf_fact['S'][0]->des_sub ?? '';
@endphp

<tr>
	<td>
		{{ date('d/m/Y', strtotime($fec)) }}
	</td>
	<td>
		<p class="max-line-2">
			{{ $description }}
		</p>
	</td>
	<td class="hidden-xs">
		{{ str_replace('-', '/', $id) }}
	</td>
	<td>
		<p class="js-divisa" value="{{ $document->total_price ?? 0  }}">
			{!! $currency->getPriceSymbol(2, $document->total_price ?? 0 ) !!}
		</p>
	</td>
	<td>
		<span class="badge badge-{{ $state['class'] }}">{{ $state['text'] }}</span>
	</td>
	<td>
		<a
			href="{{ route('panel.allotment-bills', ['lang' => config('app.locale')]) . "#auction-details-{$id}" }}">
			<i class="fa fa-eye"></i>
		</a>
	</td>
</tr>
