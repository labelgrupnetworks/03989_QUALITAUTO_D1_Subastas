@php
	$auctionData = $invoicesAuctions->first();

    $date = $auctionData->end;
    $dateValue = date('d/m/Y', strtotime($date));

	$totalSettlement = $invoicesAuctions->sum('imp_liquidacion');
	$totalPending = $invoicesAuctions->sum('imp_liquidacion');

    $state = ['class' => 'alert', 'text' => 'Provisional'];
	$auctionNumber = fn($text, $codSub) => preg_match('/\b\d+\b/', $text, $matches) ? $matches[0] : $codSub;
@endphp

<div class="sales-auction-wrapper" data-type="finish" data-sub="{{ $auctionData->sub_asigl0 }}">
    <div class="sales-auction">
        <p>
            {{ $dateValue }}
        </p>
        <p>
            <span class="visible-md visible-lg">{{ $auctionData->des_sub }}</span>
            <span class="hidden-md hidden-lg">
				{{ $auctionNumber($auctionData->des_sub, $auctionData->sub_asigl0) }}
			</span>
        </p>
        <p class="visible-md visible-lg">-</p>
        <p class="js-divisa" value="{{ $totalSettlement }}">
            {!! $currency->getPriceSymbol(2, $totalSettlement ?? 0) !!}
        </p>
        <p class="js-divisa" value="{{ $totalPending  ?? 0 }}">
            {!! $currency->getPriceSymbol(2, $totalPending  ?? 0) !!}
        </p>
        <p>
            <span class="badge badge-{{ $state['class'] }}">{{ $state['text'] }}</span>

        </p>
        <p></p>
        <div class="actions">
            <a class="btn btn-lb btn-lb-outline" data-toggle="tab" href="#auction-details-{{ $auctionData->sub_asigl0 }}"
                role="tab" aria-controls="settings">{{ trans("$theme-app.user_panel.see_detail") }}</a>
        </div>
    </div>
</div>
