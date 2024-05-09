@php
	$auctionData = $invoicesAuctions->first();
    $date = $auctionData->fecha_dvc0;
    $dateValue = date('d/m/Y', strtotime($date));

	$totalSettlement = $invoicesAuctions->sum('implic_hces1') - $auctionData->total_dvc0;
	$totalPending = abs($invoicesAuctions->sum('imp_pending'));

    $state = match (true) {
        ($totalPending != 0) => ['class' => 'warning', 'text' => 'En curso'],
        default => ['class' => 'success', 'text' => 'Pagado'],
    };
@endphp

<div class="sales-auction-wrapper" data-type="finish" data-sub="{{ $auctionData->sub_asigl0 }}">
    <div class="sales-auction">
        <p>
            {{ $dateValue }}
        </p>
        <p>
            <span class="visible-md visible-lg">{{ $auctionData->des_sub }}</span>
            <span class="hidden-md hidden-lg">{{ $auctionData->sub_asigl0 }}</span>
        </p>
        <p class="visible-md visible-lg">
            {{ str_replace('-', '/', $invoiceId) }}
        </p>
        <p class="js-divisa" value="{{ $totalSettlement }}">
            {!! $currency->getPriceSymbol(2, $totalSettlement ?? 0) !!}
        </p>
        <p class="js-divisa" value="{{ $totalPending  ?? 0 }}">
            {!! $currency->getPriceSymbol(2, $totalPending  ?? 0) !!}
        </p>
        <p>
            <span class="badge badge-{{ $state['class'] }}">{{ $state['text'] }}</span>

        </p>
        <p class="sales-auction_invoice">
			<a class="panel-pdf-icon" target="_blank" href="/factura/{{ $invoiceId }}">
				<i class="fas fa-file-pdf fa-2x"></i>
			</a>
        </p>
        <div class="actions">
            <a class="btn btn-lb btn-lb-outline" data-toggle="tab" href="#auction-details-{{ $invoiceId }}"
                role="tab" aria-controls="settings">{{ trans("$theme-app.user_panel.see_detail") }}</a>
        </div>
    </div>
</div>
