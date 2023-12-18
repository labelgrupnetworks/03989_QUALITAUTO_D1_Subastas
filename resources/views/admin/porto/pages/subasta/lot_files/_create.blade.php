<form name="save_lot_file" action="{{ route('subastas.lotes.files.store', ['num_hces1' => $num_hces1, 'lin_hces1' => $lin_hces1]) }}" method="POST"
	id="save_lot_file" enctype="multipart/form-data">
	@csrf

	<div class="alert alert-warning alert-dismissible show" role="alert" id="alertMultipleFiles">
		<strong>Atención!</strong> En caso de subir varios archivos, el nombre utilizado será el que tenga el propio archivo.
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">×</span>
		</button>
	</div>

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
				saved('Archivo creado correctamente');
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
