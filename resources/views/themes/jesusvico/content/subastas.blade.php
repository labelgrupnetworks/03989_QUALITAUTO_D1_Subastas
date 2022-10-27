@php
    $auctionsForYears = collect($data['auction_list'])->groupBy(fn($auction) => (new Carbon\Carbon($auction->session_start))->year);
@endphp

<div class="auctions-wrapper">
    <div class="container">
        @foreach ($auctionsForYears as $year => $auctions)

			<div class="year-border">
				<h2>{{ $year }}</h2>
				<hr>
			</div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 row-cols-lg-6 gy-3 mb-3 align-items-stretch">
                @foreach ($auctions as $subasta)
                    <div class="col">
                        @include('includes.subasta', ['subasta' => $subasta])
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
