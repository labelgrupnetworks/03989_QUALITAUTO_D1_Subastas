@inject('auctionService', App\Services\Auction\AuctionService::class)

@php
    $url = $auctionService->getNextLiveSessionUrlByAuction($codSub);
@endphp

@if ($url)
<a class="btn btn-lb-danger btn-pill text-white w-100 mt-2"
    href="{{ $url }}">
	{{ trans('web.lot.bid_live') }}
</a>
@endif
