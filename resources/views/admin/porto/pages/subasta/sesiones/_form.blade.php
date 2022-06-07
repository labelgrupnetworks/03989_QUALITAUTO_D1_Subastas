<div class="col-xs-12 col-md-2">
	<img id="img_session" src="{{\Tools::url_img_session('subasta_medium',$fgSub->cod_sub, $aucSession->reference)}}"
		width="96" height="auto" alt="" style="border:1px solid black">

</div>

<div class="col-xs-12 col-md-10">
	<label for="imagen">{{ trans("admin-app.title.image") }}</label>
	<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
		data-placement="right" data-original-title="{{ trans("admin-app.help_fields.imagen_sub") }}"></i>
	{!! $formulario->imagen['image_session'] !!}
</div>

<div class="col-xs-12 col-md-12">
	<fieldset>
		<legend>{{ trans("admin-app.title.options") }}</legend>

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


			@foreach ($formulario->lotes as $field => $input)
			<div class="col-xs-12 col-sm-6">
				<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
				<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
					data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
				{!! $input !!}
			</div>
			@endforeach
		</div>

	</fieldset>
</div>

<div class="col-xs-12 col-md-12 mb-3 mt-3">
	@foreach ($formulario->textos as $field => $input)
		<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
		<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
			data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
		{!! $input !!}
	@endforeach
</div>

@foreach ($formulario->traducciones ?? [] as $shortLang => $inputs)
<div class="col-xs-12 @if(count($formulario->traducciones) > 1) col-md-6 @else col-md-12 @endif mb-2 mt-2">
	<fieldset>
		<legend>{{ \Config::get('app.locales')[$shortLang] }}</legend>

		@foreach ($inputs as $field => $input)
		<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
		<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
			data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
		{!! $input !!}

		@endforeach
	</fieldset>
</div>
@endforeach


