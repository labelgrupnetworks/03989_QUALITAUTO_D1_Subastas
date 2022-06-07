@csrf

<fieldset>
	<input type="hidden" name="back" value="{{ url()->previous() }}">

@foreach($formulario as $index => $item)
<div class="col-xs-12 col-md-7 mb-1">

		<label class="mt-1" for="{{$index}}">{{ trans("admin-app.fields.$index") }}</label>
		<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
			data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$index") }}"></i><br>
		<div>
			{!! $item !!}
		</div>

</div>
@endforeach
</fieldset>

<div class="col-xs-12">
	<input type="submit" class="btn btn-success" value="Guardar" style="margin-top: 1rem">
</div>
