<div class="col-xs-12">
	<p>{!!trans("admin-app.help_fields.creditsub")!!}</p>
</div>

<div class="col-xs-12 col-md-6">
	@foreach ($formulario as $field => $input)
	<label style="margin-top: 1rem" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
	<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
		data-toggle="tooltip" data-placement="right"
		data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
	{!! $input !!}
	@endforeach

	{!! FormLib::Hidden('fecha_creditosub', 1, date("Y-m-d H:i:s")) !!}

	<input type="submit" class="btn btn-success" value="Guardar" style="margin-top: 1rem">
</div>
