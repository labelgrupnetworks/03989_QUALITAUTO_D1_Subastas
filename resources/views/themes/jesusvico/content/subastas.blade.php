@php
	$isHistoric = $data['subc_sub'] == App\Models\V5\FgSub::SUBC_SUB_HISTORICO;

	$auctionsForYears = collect($data['auction_list']);

	if ($isHistoric) {
		$auctionsForYears = $auctionsForYears->sortByDesc('session_start');
	} else {
		$auctionsForYears = $auctionsForYears->sortBy('session_start');
	}

	$auctionsForYears = $auctionsForYears->groupBy(fn($auction) => (new Carbon\Carbon($auction->session_start))->year);

	$auctionsForYears = $auctionsForYears->sortKeys();
@endphp

<div class="auctions-wrapper mt-3">
	<div class="container">
		<ul class="nav nav-tabs" id="yearAuctionTabs" role="tablist">
			@foreach ($auctionsForYears as $year => $auctions)
				<li class="nav-item" role="presentation">
					@if ($isHistoric)
						<button class="nav-link p-nav-items-auc {{ $loop->last ? 'active' : '' }}" id="tab-{{ $year }}" data-bs-toggle="tab"
							data-bs-target="#tab-panel-{{ $year }}" type="button" role="tab"
							aria-controls="tab-panel-{{ $year }}" aria-selected="{{ $loop->last ? 'true' : 'false' }}">
							<h2>{{ $year }}</h2>
						</button>
					@else
						<div class="year-border m-nav-items-auc">
							<h2>{{ $year }}</h2>
						</div>
					@endif
				</li>
			@endforeach
		</ul>
		<div class="tab-content" id="yearAuctionTabsContent">
			@foreach ($auctionsForYears as $year => $auctions)
				<div class="tab-pane fade {{ $isHistoric ? ($loop->last ? 'show active' : '') : 'show active' }}" id="tab-panel-{{ $year }}" role="tabpanel"
					aria-labelledby="tab-{{ $year }}" tabindex="0">
					<div
						class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 row-cols-xl-6 gy-3 {{-- mb-3 --}} align-items-stretch">
						@foreach ($auctions as $subasta)
							<div class="col">
								@include('includes.subasta', ['subasta' => $subasta, 'year' => $year])
							</div>
						@endforeach
					</div>
				</div>
			@endforeach
		</div>
	</div>
</div>
