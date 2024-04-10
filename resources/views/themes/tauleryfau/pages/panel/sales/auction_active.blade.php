<div class="sales-auction-wrapper" data-type="active" data-sub="{{ $cod_sub }}">
    <div class="sales-auction">
        <p>
            {{ date('d/m/Y', strtotime($auctions->first()->start)) }}
        </p>
        <p>
            <span class="visible-md visible-lg">{{ $auctions->first()->des_sub }}</span>
            <span class="hidden-md hidden-lg">{{ $auctions->first()->cod_sub }}</span>

        </p>
        <p>{{ $auctionStatistics['consigned_lots'] }}</p>
        <p class="js-divisa visible-md visible-lg" value="{{ $auctionStatistics['starting_price'] }}">
            {!! $currency->getPriceSymbol(2, $auctionStatistics['starting_price']) !!}
        </p>
        <p class="js-divisa visible-md visible-lg" value="{{ $auctionStatistics['estimate_price'] }}">
            {!! $currency->getPriceSymbol(2, $auctionStatistics['estimate_price']) !!}
        </p>
        <p class="js-divisa" value="{{ $auctionStatistics['actual_price'] }}">
            {!! $currency->getPriceSymbol(2, $auctionStatistics['actual_price']) !!}
        </p>
        <div class="actions">
            <a class="btn btn-lb btn-lb-outline" data-toggle="tab" href="#auction-details-{{ $cod_sub }}"
                role="tab" aria-controls="settings">Ver detalle</a>
        </div>
    </div>
</div>
