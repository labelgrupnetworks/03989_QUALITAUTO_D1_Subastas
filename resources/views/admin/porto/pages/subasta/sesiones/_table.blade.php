<div class="row">
	<div class="col-xs-12">

		<a id="create_session" class="btn btn-primary btn-sm pull-right">
			{{ trans("admin-app.button.new_fem") }}
			{{ trans_choice("admin-app.title.session", 0) }}
		</a>

		<table id="session" class="table table-striped table-condensed table-responsive" style="width:100%">
			<thead>
				<tr>

					<th class="col-xs-1" style="width: 5%">{{ trans("admin-app.title.img") }}</th>

					<th>{{ trans("admin-app.fields.id_auc_sessions") }}</th>
					<th>{{ trans("admin-app.fields.reference") }}</th>
					<th>{{ trans("admin-app.fields.name") }}</th>
					<th>{{ trans("admin-app.fields.init_lot") }}</th>
					<th>{{ trans("admin-app.fields.end_lot") }}</th>
					<th>{{ trans("admin-app.fields.start") }}</th>
					<th>{{ trans("admin-app.fields.end") }}</th>

					<th>
						<span>{{ trans("admin-app.fields.actions") }}</span>
					</th>

				</tr>
			</thead>

			<tbody>

				@forelse ($aucSessions as $aucSession)

				<tr id="fila_{{$aucSession->reference}}" style="max-height: 60px; overflow: hidden;">
					<td><img src="{{\Tools::url_img_session('subasta_medium',$aucSession->auction, $aucSession->reference)}}"
							width="100%">
					</td>

					<td class="js-id_auc_sessions">{{ $aucSession->id_auc_sessions }}</td>
					<td class="js-reference">{{ $aucSession->reference }}</td>
					<td class="js-name">{{ $aucSession->name }}</td>
					<td class="js-init_lot">{{ $aucSession->init_lot }}</td>
					<td class="js-end_lot">{{ $aucSession->end_lot }}</td>
					<td class="js-start_fotmat">{{ $aucSession->start_format }}</td>
					<td class="js-end_format">{{ $aucSession->end_format }}</td>

					<td class="js-auc_edit">
						<a title="{{ trans("admin-app.button.edit") }}" class="btn btn-success btn-sm js_edit_session" data-action="{{ route('subastas.sesiones.edit', ['cod_sub' => $aucSession->auction, 'reference' => $aucSession->reference]) }}">
							<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								{{ trans("admin-app.button.edit") }}
						</a>
						@if ($loop->index)
						<a title="{{ trans("admin-app.button.delete") }}" class="btn btn-danger btn-sm" data-reference="{{ $aucSession->reference }}" data-toggle="modal" data-target="#deleteSesionModal">
							<i class="fa fa-trash" aria-hidden="true"></i>
								{{ trans("admin-app.button.delete") }}
						</a>
						@endif

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
					data-action="{{ route('subastas.sesiones.destroy', ['subasta' => $aucSession->auction, 'reference' => 0]) }}"
					class="btn btn-danger">Borrar
				</a>

            </div>

        </div>
    </div>
</div>

<script>

	$('.js_edit_session').on('click', function(e){

		e.preventDefault();
		if(!e.target.dataset.action){
			return;
		}

		$('.js_edit_session').addClass("disabled");

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
				$('.js_edit_session').removeClass("disabled");
			}
		});
	});


	$('#create_session').on('click', function(){

		$.ajax ({
			url: "{{ route('subastas.sesiones.create', ['cod_sub' => $cod_sub]) }}",
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
