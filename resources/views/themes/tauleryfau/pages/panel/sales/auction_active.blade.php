@php
	$auctionNumber = fn($text, $codSub) => preg_match('/\b\d+\b/', $text, $matches) ? $matches[0] : $codSub;
@endphp

<div class="sales-auction-wrapper" data-type="active" data-sub="{{ $auction['sub_asigl0'] }}" data-id="{{ $auction['sub_asigl0'] }}" data-auction-wrapper>
    <div class="sales-auction">
        <p>
            {{ date('d/m/Y', strtotime($auction['start'])) }}
        </p>
        <p class="sales-auctions_description text-center text-md-start">
            <span class="visible-md visible-lg">{{ $auction['des_sub'] }}</span>
            <span class="hidden-md hidden-lg">
				{{ $auctionNumber($auction['des_sub'], $auction['sub_asigl0']) }}
			</span>

        </p>
        <p class="text-center text-md-start">{{ $auction['total_lots'] }}</p>
        <p class="js-divisa visible-md visible-lg" value="{{ $auction['total_impsalhces'] }}">
            {!! $currency->getPriceSymbol(2, $auction['total_impsalhces']) !!}
        </p>
        <p class="js-divisa visible-md visible-lg" value="{{ $auction['total_imptas'] }}">
            {!! $currency->getPriceSymbol(2, $auction['total_imptas']) !!}
        </p>
        <p class="js-divisa fw-bold" value="{{ $auction['total_award'] }}">
            {!! $currency->getPriceSymbol(2, $auction['total_award']) !!}
        </p>
        <div class="actions">
			{{-- tab --}}
            {{-- <a class="btn btn-lb btn-lb-outline" data-toggle="tab" href="#auction-details-{{ $auction['sub_asigl0'] }}"
                role="tab" aria-controls="settings">{{ trans("$theme-app.user_panel.see_detail") }}</a> --}}

			{{-- open Modal --}}
			<button type="button" class="btn btn-lb btn-lb-outline" data-toggle="modal" data-target="#myModal-{{ $auction['sub_asigl0'] }}" data-id="{{ $auction['sub_asigl0'] }}">
				{{ trans("$theme-app.user_panel.see_detail") }}
			</button>
        </div>
    </div>
</div>
