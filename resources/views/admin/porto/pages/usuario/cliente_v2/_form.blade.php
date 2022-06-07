<div class="col-xs-12 mb-3 mt-3">

	<div class="row">
		<div class="col-xs-12 col-md-6 mb-3 mt-3">
			<fieldset>
				<legend>{{ trans("admin-app.title.identificacion") }}</legend>
				@foreach ($formulario->identificacion as $field => $input)
				<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
				<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
					data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
				{!! $input !!}
				@endforeach
			</fieldset>
		</div>

		<div class="col-xs-12 col-md-6 mb-3 mt-3">
			<fieldset>
				<legend>{{ trans("admin-app.title.clienteweb") }}</legend>
				@foreach ($formulario->clienteweb as $field => $input)
				<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
				<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
					data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
				{!! $input !!}
				@endforeach
			</fieldset>
		</div>

	</div>


</div>

<div class="col-xs-12 col-md-6">
	<fieldset>
		<legend>{{ trans("admin-app.title.datosPersonales") }}</legend>
		@foreach ($formulario->datosPersonales as $field => $input)
		<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
		<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
			data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
		{!! $input !!}
		@endforeach
	</fieldset>
</div>

<div class="col-xs-12 col-md-6">
	<fieldset>
		<legend>{{ trans("admin-app.title.direccionFacturacion") }}</legend>
			@foreach ($formulario->direccionFacturacion as $field => $input)
				<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
				<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
			data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
				{!! $input !!}
			@endforeach
	</fieldset>
</div>

<div class="col-xs-12 col-md-12 mb-2 mt-3">
	<fieldset>
		<legend>{{ trans("admin-app.title.direccionEnvio") }}</legend>

		@foreach ($formulario->direccionEnvio as $field => $input)
		<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
		<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
			data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
		{!! $input !!}
		@endforeach
	</fieldset>
</div>

<div class="col-xs-12 col-md-12 mb-2 mt-3">
	<fieldset>
		<legend>{{ trans("admin-app.title.newsletters") }}</legend>

		<div class="row">
			@foreach ($formulario->newsletters as $field => $input)
			<div class="col-xs-6 col-sm-3">
				<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.newsletter$field") }}</label>
				{!! $input !!}
			</div>
			@endforeach
		</div>

	</fieldset>
</div>

<div class="col-xs-12 col-md-6">
	<fieldset>
		<legend>{{ trans("admin-app.title.additional") }}</legend>
		@foreach ($formulario->additional as $field => $input)
		<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
		<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
			data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
		{!! $input !!}
		@endforeach
	</fieldset>
</div>
