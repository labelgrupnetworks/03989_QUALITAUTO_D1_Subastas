<div class="col-xs-12">
	<fieldset>
		<legend>{{ trans("admin-app.title.videos") }}</legend>
		<div class="row mt-2">

			<div class="col-xs-12 col-md-7">

				<div class="row">
					<div class="col-xs-12 col-md-7">
						<label>{{ trans("admin-app.fields.files") }}</label>
						{!! $formulario->videos['files'] !!}
					</div>
				</div>

			</div>

			<div class="col-xs-12 col-md-5">

				<label>{{ trans("admin-app.fields.files_asigned") }}</label>
				<table class="table table-striped table-files table-condensed">
					<tr>
						<th>{{ trans("admin-app.fields.name") }}</th>
						<th>{{ trans("admin-app.fields.actions") }}</th>
					</tr>

					@foreach ($videos as $file)
					<tr id="tr-{{$loop->index}}">

						@php
							$filePath = "/files/videos/".Config::get('app.emp')."/$fgAsigl0->num_hces1/$fgAsigl0->lin_hces1/$file";
						@endphp
						<td>
							<a style="text-decoration: none;" href="{{ $filePath }}" target="_blank">
								<span class="mt-3">{{ $file }}</span>
							</a>
						</td>
						<td>
							<a
								onclick="javascript:deleteVideo('{{$fgAsigl0->num_hces1}}', '{{$fgAsigl0->lin_hces1}}', '{{$file}}', '{{$loop->index}}')"
								class="btn btn-danger">
								<b>{{ mb_strtoupper(trans("admin-app.button.x_symbol")) }}</b>
							</a>
						</td>

					</tr>
					@endforeach

				</table>
			</div>



		</div>
	</fieldset>
</div>

<script>
	function deleteVideo(num_hces1, lin_hces1, nameFile, position){

	token = $("[name='_token']").val();
	bootbox.confirm(`¿Estás seguro que quieres borrar el archivo ${nameFile}?`, function (result) {

		if (result) {

			data = {
				_token: token,
				num_hces1: num_hces1,
				lin_hces1: lin_hces1,
				file: nameFile
			}

			$.post("/admin/lote/deletevideo", data, function (response) {

				$(`#tr-${position}`).remove();
				showMessage("Archivo eliminado");

			});

		}

	});
}


</script>
