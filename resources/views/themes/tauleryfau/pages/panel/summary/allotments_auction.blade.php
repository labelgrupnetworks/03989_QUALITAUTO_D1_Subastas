@php
    $totalInvoice = $document->himp_csub + $document->base_csub + $document->base_csub_iva;
    $state = ['class' => 'alert', 'text' => 'Pendiente'];
@endphp

<tr>
	<td>
		{{ date('d/m/Y', strtotime($document->fecha_csub)) }}
	</td>
	<td>
		<p class="max-line-2">
			{{ $document->name ?? '' }}
		</p>
	</td>
	<td class="hidden-xs">
		{{ str_replace('-', '/', $id) }}
	</td>
	<td>
		<p class="js-divisa" value="{{ $document->total_imp_invoice ?? 0  }}">
			{!! $currency->getPriceSymbol(2, $document->total_imp_invoice ?? 0 ) !!}
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
