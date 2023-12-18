<form name="save_lot_file" action="{{ route('subastas.lotes.files.update', ['fgHces1File' => $id]) }}" method="POST"
	id="save_lot_file" enctype="multipart/form-data">
	@csrf

	<div class="row">
		@include('admin::pages.subasta.lot_files._form', ['formulario' => $formulario])
	</div>
</form>

<script>
	$('[data-toggle="tooltip"]').tooltip();
	$('#save_lot_file').on('submit', function(event){
		event.preventDefault();

		var formData = new FormData(save_lot_file);

		$.ajax ({
			url: save_lot_file.action,
			type: "POST",
			data: formData,
			enctype: 'multipart/form-data',
			contentType: false,
			processData: false,

			success: function(result) {
				$('#lotFilesRows').html(result);
				$('#addFileModal').modal('hide');
				saved('Archivo actualizado correctamente');
			},
			error: function(error) {
				if(error.responseJSON?.message) {
					globalThis.error(error.responseJSON.message);
					return;
				}
				globalThis.error('Error al crear el archivo');
			}
		});
	});
</script>
