<div class="col-xs-12 col-md-12 mb-3 mt-3">
	<fieldset>
		<legend>Traducciones</legend>
		<div class="row d-flex flex-wrap">
			@foreach ($formulario->translates as $lang => $fields)
			<div class="col-xs-12">
				<h4>{{ config("app.locales.$lang") }}</h4>
			</div>

			{!! FormLib::Hidden('lang[]', 1,  mb_strtoupper($lang)) !!}

			@foreach ($fields as $field => $input)
			<div class="col-xs-12">
				<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
				<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
					data-toggle="tooltip" data-placement="right"
					data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
				{!! $input !!}
			</div>
			@endforeach

			@endforeach
		</div>
	</fieldset>
</div>
