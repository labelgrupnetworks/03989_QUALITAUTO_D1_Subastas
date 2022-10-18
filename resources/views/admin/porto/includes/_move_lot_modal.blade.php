@php
$auctions = App\Models\V5\FgSub::select("cod_sub", "des_sub", "tipo_sub")->where("subc_sub","!=","C")->orderby('hfec_sub')->get();
@endphp


<div class="modal fade" id="moveLotModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id=" modalLabel"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<p>{{ trans("admin-app.title.select_auction_to_clone") }}</p>

				<div class="mt-2 mb-2">
					<form id="formDuplicateLot" action="{{ $routeToClone }}"
						data-action="{{ $routeToClone }}" method="POST">
						@csrf
						<div class="mt-2 mb-2">
							<select name="newAuction" id="auctionToDuplicateLot">
								@foreach ($auctions as $auction)
									@if ($cod_sub != $auction->cod_sub )
										<option value="{{ $auction->cod_sub }}">{{ $auction->des_sub }}</option>
									@endif
								@endforeach
							</select>
							<input type="hidden" name="auctionSource" id="auctionSource" value="">
							<input type="hidden" name="lotToDuplicate" id="lotToDuplicate" value="">
						</div>
						<button type="button" class="btn btn-secondary"
							data-dismiss="modal">{{ trans("admin-app.button.close") }}</button>
						<button type="submit" class="btn btn-succes">{{ trans("admin-app.button.clone") }}</button>
					</form>
				</div>
			</div>

			<div class="modal-footer">

			</div>

		</div>
	</div>
</div>
