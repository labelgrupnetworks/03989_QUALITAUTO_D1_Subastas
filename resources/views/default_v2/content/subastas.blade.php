<div class="auctions-wrapper">
	<div class="container">
		<div class="row row-cols-1 row-cols-xl-2 gy-3 mb-3 align-items-stretch">

			@foreach ($data['auction_list'] as $subasta)

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
			  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary-custom" data-bs-dismiss="modal">{{ trans("$theme-app.head.close") }}</button>
			</div>
		  </div>
	</div>
</div>
