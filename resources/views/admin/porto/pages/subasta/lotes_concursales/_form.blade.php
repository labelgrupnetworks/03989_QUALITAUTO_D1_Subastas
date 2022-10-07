@foreach ($formulario->hiddens as $field => $input)
{!! $input !!}
@endforeach

<div class="col-xs-12 col-md-2">
	<img id="img_subasta" src="{{\Tools::url_img('lote_medium', $fgAsigl0->num_hces1, $fgAsigl0->lin_hces1)}}"
		width="96" height="auto" alt="" style="border:1px solid black">
</div>

<div class="col-xs-12 col-md-12 mb-3 mt-3">
	<fieldset>
		<legend>{{ trans("admin-app.title.reference_lot") }}</legend>
		<div class="row d-flex flex-wrap">
			@foreach ($formulario->id as $field => $input)
				@continue($field === 'other_id' && !in_array("OTHERS", explode(',', config("app.showEditLotOptions"))))

				<div class="col-xs-12 col-sm-6">
					<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
					<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
						data-toggle="tooltip" data-placement="right"
						data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
					{!! $input !!}
				</div>
			@endforeach
		</div>
	</fieldset>
</div>

<div class="col-xs-12 col-md-12 mb-3 mt-3">
	<fieldset>
		<legend>{{ trans("admin-app.title.info") }}</legend>
		@foreach ($formulario->info as $field => $input)
		<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
		<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
			data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
		{!! $input !!}
		@endforeach
	</fieldset>
</div>

<div class="col-xs-12 col-md-4 mb-3 mt-3">
	<fieldset>
		<legend>{{ trans("admin-app.title.states") }}</legend>
		@foreach ($formulario->estados as $field => $input)
		<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
		<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
			data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
		{!! $input !!}
		@endforeach
	</fieldset>
</div>

<div class="col-xs-12 col-md-8 mb-3 mt-3">
	<fieldset>
		<legend>Fechas</legend>
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

<div class="col-xs-12 mb-3 mt-3">
	<fieldset>
		<legend>Precios</legend>
		<div class="row d-flex flex-wrap">
			@foreach ($formulario->precios as $field => $input)

			<div class="col-xs-12 col-sm-6 col-md-4 @if(in_array($field, ['biddercommission', 'ownercommission'])){{'d-flex flex-wrap'}}@endif">
				<div>
				<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.concursal_$field") }}</label>
				<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
					data-toggle="tooltip" data-placement="right"
					data-original-title="{{ trans("admin-app.help_fields.concursal_$field") }}"></i>
				{!! $input !!}
				</div>
				@if($field == 'biddercommission')
				<div>
				<label class="mt-1">{{ trans("admin-app.help_fields.concursal_{$field}_amount") }}</label>
				<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
					data-toggle="tooltip" data-placement="right"
					data-original-title="{{ trans("admin-app.help_fields.concursal_{$field}_amount") }}"></i>

					<input type="text" class="form-control text-center effect-16" id="biddercommission_importe" value="{{ $fgAsigl0->impsalhces_asigl0 * $fgAsigl0->comlhces_asigl0 * 0.01 }}" autocomplete="off">
				</div>
				@elseif($field == 'ownercommission')
				<div>
					<label class="mt-1">{{ trans("admin-app.help_fields.concursal_{$field}_amount") }}</label>
					<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
						data-toggle="tooltip" data-placement="right"
						data-original-title="{{ trans("admin-app.help_fields.concursal_{$field}_amount") }}"></i>

						<input type="text" class="form-control text-center effect-16" id="ownercommission_importe" value="{{ $fgAsigl0->impsalhces_asigl0 * $fgAsigl0->comphces_asigl0 * 0.01 }}" autocomplete="off">
					</div>
				@endif
			</div>

			@endforeach
	</fieldset>
</div>

<script>
$(document).on('ready', function () {
	for (const element of document.querySelectorAll('input[type=time]')) {
		if (element.value.length < 6) {
			element.value += ':00';
    	}
	}
});
</script>
