<div class="row well">
	<div class="col-xs-12 table-responsive">

		<a id="addFile" class="btn btn-primary btn-sm pull-right" data-url="{{ route('subastas.lotes.files.create', ['num_hces1' => $fgAsigl0->num_hces1, 'lin_hces1' => $fgAsigl0->lin_hces1 ]) }}">
			+ Nuevo archivo
		</a>

		<table id="session" class="table table-striped table-condensed" style="width:100%">
			<thead>
				<tr>
					<th>{{ trans("admin-app.fields.id_hces1_files") }}</th>
					<th>{{ trans("admin-app.fields.name_hces1_files") }}</th>
					<th>{{ trans("admin-app.fields.order_hces1_files") }}</th>
					<th>{{ trans("admin-app.fields.is_active_hces1_files") }}</th>
					<th>{{ trans("admin-app.fields.permission_hces1_files") }}</th>

					<th>
						<span>{{ trans("admin-app.fields.actions") }}</span>
					</th>

				</tr>
			</thead>

			<tbody id="lotFilesRows">
				@include('admin::pages.subasta.lot_files._table_rows', ['files' => $files])
			</tbody>
		</table>

	</div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" id="addFileModal">
	<div class="modal-dialog modal-lg" role="document">

		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="modal-create-body"></div>

			<div class="modal-footer">
				<button type="submit" form="save_lot_file" class="btn btn-primary" id="modalSessionAccept">Save changes</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>

		</div>

	</div>
</div>

<div class="modal fade" id="deleteSesionModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>¿Seguro que desea borrar el registro seleccionado?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<a id="formDelete"
					data-action=""
					class="btn btn-danger">Borrar
				</a>

            </div>

        </div>
    </div>
</div>

<script>

	function editFile(button) {
		const url = button.dataset.action;

		$.ajax ({
			url: url,
			type: "get",

			success: function(result) {
				$('#modal-create-body').html(result);
				$('#addFileModal').modal('show');
			},
			error: function() {
				error();
			}
		});
	}

	function removeFile(button) {
		const url = button.dataset.action;

		$.ajax ({
			url: url,
			type: "delete",

			success: function(result) {
				$('#lotFilesRows').html(result);
				saved('Archivo borrado correctamente');
			},
			error: function() {
				error();
			}
		});
	}


	$('#addFile').on('click', function(){

		$.ajax ({
			url: $(this).data('url'),
			type: "get",

			success: function(result) {
				$('#modal-create-body').html(result);
				$('#addFileModal').modal('show');
			},
			error: function() {
				error();
			}
		});
	});

</script>
