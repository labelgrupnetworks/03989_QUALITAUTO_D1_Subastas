<div class="row">
	<div class="col-xs-12">

		<a id="create_session" class="btn btn-primary btn-sm pull-right">
			Nuevo archivo
		</a>

		<table id="session" class="table table-striped table-condensed table-responsive" style="width:100%">
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

			<tbody>

				@forelse ($files as $file)

				<tr id="fila_">
					<td>{{ $file->id_hces1_files }}</td>
					<td>{{ $file->name_hces1_files }}</td>
					<td>{{ $file->order_hces1_files }}</td>
					<td>{{ $file->is_active_hces1_files }}</td>
					<td>{{ $file->permission_hces1_files }}</td>

					<td>
						<a title="{{ trans("admin-app.button.edit") }}" class="btn btn-success btn-sm" data-action="">
							<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								{{ trans("admin-app.button.edit") }}
						</a>

						<a title="{{ trans("admin-app.button.delete") }}" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#">
							<i class="fa fa-trash" aria-hidden="true"></i>
								{{ trans("admin-app.button.delete") }}
						</a>

					</td>
				</tr>

				@empty

				<tr>
					<td colspan="6">
						<h3 class="text-center">{{ trans("admin-app.title.without_results") }}</h3>
					</td>
				</tr>

				@endforelse
			</tbody>
		</table>

	</div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" id="modalCreateSession">
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
				<button type="button" class="btn btn-primary" id="modalSessionAccept">Save changes</button>
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

	$('.edit').on('click', function(e){

		e.preventDefault();
		if(!e.target.dataset.action){
			return;
		}

		$('.edit').addClass("disabled");

		$.ajax ({
			url: e.target.dataset.action,
			type: "get",

			success: function(result) {
				$('#modal-create-body').html(result);
				$('#modalCreateSession').modal('show');
			},
			error: function(error) {
				console.log(error);
			},
			complete: function() {
				$('.edit').removeClass("disabled");
			}
		});
	});


	$('#create_session').on('click', function(){

		$.ajax ({
			url: "",
			type: "get",

			success: function(result) {
				$('#modal-create-body').html(result);
				$('#modalCreateSession').modal('show');
			},
			error: function() {
				error();
			}
		});
	});

	$('#deleteSesionModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var reference = button.data('reference');
            var action = $('#formDelete').attr('data-action').slice(0, -1) + reference;
			$('#formDelete').attr('data-action', action);

            var modal = $(this);
            modal.find('.modal-title').text('Vas a borrar la sesión ' + reference);

			$('#formDelete').on('click', function(){
				deleteSession(action);
			});
    });

	function deleteSession(url){
		$.ajax ({
			url: url,
			type: "delete",
			success: function(result) {
				$('#subasta_sesiones').html(result);
			},
			error: function() {
				error();
			}
		});
	}

</script>
