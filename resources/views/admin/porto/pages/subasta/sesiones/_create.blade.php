<form name="save_session" action="{{ route('subastas.sesiones.store', ['subasta' => $fgSub->cod_sub]) }}" method="POST"
	id="save_session" enctype="multipart/form-data">
	@csrf

	<div class="row">
		@include('admin::pages.subasta.sesiones._form', compact('formulario', 'fgSub', 'aucSession'))
	</div>
</form>

<script>
	$('[data-toggle="tooltip"]').tooltip();
	$('#modalSessionAccept').on('click', function(event){

		var formData = new FormData(save_session);

		$.ajax ({
			url: save_session.action,
			type: "POST",
			data: formData,
			enctype: 'multipart/form-data',
			contentType: false,
			processData: false,

			success: function(result) {
				$('#modalCreateSession').modal('hide');
				$('#subasta_sesiones').html(result);

				//por alguna razón no se esta eliminando la clase modal-open
				//al cerrar el modal, por lo que se elimina manualmente
				//si se consigue una solución mejor, se puede eliminar esta linea
				$('body').removeClass('modal-open');
			},
			error: function(error) {
				error(error);
			}
		});
	});
</script>
