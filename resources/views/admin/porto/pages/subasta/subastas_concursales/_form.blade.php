<input type="hidden" name="force_overwritte" value="1">
<div class="col-xs-12 col-md-2">
	<img id="img_subasta" src="/img/load/subasta_large/AUCTION_{{ $fgSub->emp_sub }}_{{ $fgSub->cod_sub }}.jpg"
		width="96" height="auto" alt="" style="border:1px solid black">

</div>
<div class="col-xs-12 col-md-10">
	<label for="imagen">{{ trans("admin-app.title.image") }}</label>
	<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
		data-placement="right" data-original-title="{{ trans("admin-app.help_fields.imagen_sub") }}"></i>
	{!! $formulario->imagen['imagen_sub'] !!}
</div>

<div class="col-xs-12 col-md-12 mb-3 mt-3">
	<fieldset>
		<legend>{{ trans("admin-app.title.name_description") }}</legend>

		@foreach ($formulario->textos as $field => $input)
		<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
		<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
			data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
		{!! $input !!}
		@endforeach
	</fieldset>
</div>

<div class="col-xs-12 col-md-4">
	<fieldset>
		<legend>{{ trans("admin-app.title.options") }}</legend>
		@foreach ($formulario->estados as $field => $input)
		<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
		<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
			data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
		{!! $input !!}
		@endforeach
	</fieldset>
</div>

<div class="col-xs-12 col-md-8">
	<fieldset>
		<legend>{{ trans("admin-app.title.dates") }}</legend>
		<div class="row d-flex flex-wrap">
			@foreach ($formulario->fechas as $field => $input)

			<div class="col-xs-12 col-sm-6">
				<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
				<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
					data-toggle="tooltip" data-placement="right"
					data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
				{!! $input !!}
			</div>

			@endforeach
	</fieldset>
</div>

<div class="col-xs-12 col-md-12 mb-2 mt-3">
	<fieldset>
		<legend>{{ trans("admin-app.title.seo") }}</legend>

		@foreach ($formulario->seo as $field => $input)
		<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
		<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
			data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
		{!! $input !!}
		@endforeach
	</fieldset>
</div>

