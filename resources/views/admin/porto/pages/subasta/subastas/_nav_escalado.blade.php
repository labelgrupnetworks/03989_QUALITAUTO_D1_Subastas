<div class="col-xs-12 col-md-12 mb-2">

	<fieldset id="fgpujassubs">

		<legend>{{ trans("admin-app.title.scaled") }}</legend>


		@foreach ($formulario->escalado as $index => $fgPujasSubs)

		<div class="row">
			@foreach ($fgPujasSubs as $field => $input)

			<div class="col-xs-6 mb-2">

				@if ($loop->parent->first)
				<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
				<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
					data-toggle="tooltip" data-placement="right"
					data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
				@endif

				{!! $input !!}

			</div>

			@endforeach
		</div>
		@endforeach

	</fieldset>
	<div class="row">
		<div class="col-xs-12 text-right">
			<input type="button" id="addEscalado" class="btn btn-sm btn-outline-primary" value="add">

		</div>
		<div class="col-xs-12 text-center">
			<a href="/preciofueraescalado/{{$fgSub->cod_sub}}" class="btn btn-info" target="_blank">{{ trans("admin-app.button.check_scale") }}</a>
		</div>
	</div>
</div>
