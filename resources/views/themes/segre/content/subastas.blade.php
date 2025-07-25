@php
	$codSubs = collect($data['auction_list'])->pluck('cod_sub')->unique()->values();
	$descriptions = App\Models\V5\FgSub::query()
		->select('cod_sub')
		->whereIn('cod_sub', $codSubs)
		->joinlangSub()
		->get();

	$auctions = collect($data['auction_list'])->filter(fn($auction) => $auction->reference == '001')
		->map(function ($auction) use ($descriptions) {
			$description = $descriptions->where('cod_sub', $auction->cod_sub)->first();
			$auction->description_det = $description ? $description->descdet_sub : '';
			return $auction;
		});
@endphp

<div class="auctions-wrapper my-5">
    <div class="container">
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 gy-3 mb-3 align-items-stretch">

            @foreach ($auctions as $subasta)
                <div class="col">
                    @include('includes.subasta', ['subasta' => $subasta])
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="modal fade" id="documentsModal" aria-hidden="true" aria-labelledby="documentsModalLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentsModal">{{ trans("$theme-app.subastas.documentacion") }}</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button class="btn btn-lb-primary" data-bs-dismiss="modal"
                    type="button">{{ trans("$theme-app.head.close") }}</button>
            </div>
        </div>
    </div>
</div>
