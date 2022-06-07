@foreach ($formulario->traducciones as $shortLang => $inputs)
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
