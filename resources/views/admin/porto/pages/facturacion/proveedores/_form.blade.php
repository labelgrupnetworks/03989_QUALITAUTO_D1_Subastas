<div class="col-xs-12 col-md-9 mb-3 mt-3">
		<div class="row d-flex flex-wrap">
			@foreach ($formulario as $field => $input)
			<div class="col-xs-12 col-sm-4">
				<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
				<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
					data-toggle="tooltip" data-placement="right"
					data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
				{!! $input !!}
			</div>
			@endforeach

		</div>
		<div class="row">
			<div class="col-xs-12 col-md-3">
				<input type="submit" class="btn btn-success mt-2" value="Guardar">
			</div>
		</div>
</div>
