<div class="row well">
    <div class="col-xs-12 mb-1 pt-1 pb-1" style="background-color: #ffe7e7">

        <div class="d-flex align-items-center" style="gap:1rem;">

            <div class="mr-auto d-inline-flex align-items-center" style="gap:1rem;">
                <label class="">
                    Subasta:
                </label>
                <select class="form-control form-control-sm" name="auction">
                    @foreach ($auctions as $codSub => $nameSub)
                        <option
							@selected($auctionSelected == $codSub)
								value="{{ $codSub }}">
								{{ "$codSub - $nameSub" }}
						</option>
                    @endforeach
                </select>

            </div>

            <a class="btn btn-sm btn-primary" href="{{ route('admin.licit.export', ['auction' => $auctionSelected]) }}">
                {{ trans('admin-app.button.export') }}
                {{ mb_strtolower(trans('admin-app.title.licits')) }}
            </a>

            <button class="btn btn-sm btn-primary"
				onclick="createLicit()"
			>
                {{ trans('admin-app.button.new') }}
                {{ trans('admin-app.title.licit') }}
            </button>
        </div>
    </div>

    <div class="col-xs-12 table-responsive">
        <table class="table table-striped table-condensed table-responsive" id="tableLicits" data-order-name="order"
            style="width:100%">
            <thead>
                <th>{{ trans('admin-app.fields.cod_cli') }}</th>
                <th>{{ trans('admin-app.fields.idorigincli') }}</th>
                <th>{{ trans('admin-app.fields.cod_licit') }}</th>
                <th>{{ trans('admin-app.fields.rsoc_cli') }}</th>
            </thead>

            <tbody>
                @foreach ($licits as $licit)
                    <tr>
                        <td>{{ $licit->cli_licit }}</td>
                        <td>{{ $licit->cod2_cli }}</td>
                        <td>{{ $licit->cod_licit }}</td>
                        <td>{{ $licit->rsoc_licit }}</td>
                    </tr>
                @endforeach

        </table>


    </div>

</div>

{{-- modal --}}
<div class="modal fade" id="modalCreateLicit" role="dialog" aria-labelledby="modalCreateLicitLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					{{ trans('admin-app.button.new') }}
					{{ trans("admin-app.title.licit") }}
				</h5>
				<button class="close" data-dismiss="modal" type="button" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body"></div>
		</div>
	</div>
</div>



<script>
	$('select[name="auction"]').on('change', function() {
		window
			.location = window.location.pathname + '?auction=' + $(this).val();
	});

	function createLicit() {
		$('#modalCreateLicit').modal('show');
		$.ajax({
			url: '{{ route('admin.licit.create', ['auction' => $auctionSelected]) }}',
			type: 'GET',
			success: function(data) {
				$('#modalCreateLicit .modal-body').html(data);
			}
		});
	}
</script>
