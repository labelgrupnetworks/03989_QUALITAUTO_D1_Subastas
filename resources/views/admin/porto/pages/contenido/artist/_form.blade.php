

<div class="col-xs-12">

	@foreach ($formulario as $field => $input)

	@if (!is_array($input))

	<label class="mt-2" for="{{$field}}">{{ trans("admin-app.fields.artist.$field") }}</label>
	{!! $input !!}

	@else


		@foreach ($input as $lang => $fields)

		<fieldset class="mt-3">
			<legend>{{ config('app.locales')[$lang] }}</legend>
			@foreach ($fields as $field => $input)
			<label class="mt-2" for="{{$field}}">{{ trans("admin-app.fields.artist.$field") }}</label>
			{!! $input !!}
			@endforeach
		</fieldset>

		@endforeach


	@endif

	@endforeach
	<br/>
	<input type="submit" class="btn btn-success" value="Guardar" style="margin-top: 1rem">


</div>
