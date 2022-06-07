<div class="row">

	<div class="col-xs-12 col-lg-12 mt-2">
		<fieldset>
			<legend>{{ trans("admin-app.title.add_files") }}</legend>
			<div class="row d-flex flex-wrap">
				@foreach ($formulario->archivos as $field => $input)

				<div class="col-xs-12 @if($field != 'auc_sessions_files' && $field != 'img') col-md-4 @endif">
					<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
					<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
						data-toggle="tooltip" data-placement="right"
						data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
					{!! $input !!}
				</div>

				@endforeach
		</fieldset>
	</div>

	<div class="col-xs-12 col-lg-12 mt-3">
		<fieldset>
			<legend>{{ trans("admin-app.fields.files_asigned") }}</legend>

			<table class="table table-striped table-files">
				<tr>
					<th>{{ trans("admin-app.title.type") }}</th>
					<th>{{ trans("admin-app.fields.reference") }}</th>
					<th>{{ trans("admin-app.fields.name") }}</th>
					<th>{{ trans("admin-app.title.order") }}</th>
					<th>{{ trans("admin-app.fields.actions") }}</th>
				</tr>
				@foreach ($aucSessionsFiles as $files)
				<tr>
					<form method="POST" action="/admin/sesion/deletefile">
						@csrf
						<input type="hidden" name="auction" value="{{$files->auction}}">
						<input type="hidden" name="reference" value="{{$files->reference}}">
						<input type="hidden" name="idFile" value="{{$files->id}}">
						<td style="width: 5%"><img
								src="{{ \App\Models\V5\AucSessionsFiles::PATH_ICONS[$files->type] }}" width="100%"></td>
						<td style="width: 10%">{{ $files->reference }}</td>
						<td><a style="text-decoration: none;" title="{{ $files->description }}" target="_blank"
								href="/files{{ $files->path }}">{{ $files->description }}</a>
						</td>
						<td style="width: 10%">{{ $files->order }}</td>
						<td><button type="submit" class="btn btn-danger"><b>X</b></button></td>
					</form>
				</tr>
				@endforeach

			</table>
		</fieldset>
	</div>
</div>

