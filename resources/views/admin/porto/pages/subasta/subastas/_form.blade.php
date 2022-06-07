<input type="hidden" name="force_overwritte" value="0">
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
@if(!empty($formulario->provider))
<div class="col-xs-12 col-md-12 mb-3 mt-3">
	<fieldset>
		<legend>{{ trans_choice("admin-app.title.provider",1) }}</legend>
		@foreach ($formulario->provider as $field => $input)
		<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
		<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
			data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
		{!! $input !!}
		@endforeach
		@if($field=="autor")
			@if(isMultilanguage())
				<div class="col-xs-12 text-right">
					<a class="btn btn-success js-create-feature" data-feature="{{ \Config::get("app.ArtistCode") }}"><i class="fa fa-plus" aria-hidden="true" data-feature="{{ \Config::get("app.ArtistCode") }}"></i></a>
					<a class="btn btn-default js-edit-feature" data-feature="{{ \Config::get("app.ArtistCode") }}"><i style="color:black" class="fa fa-language" aria-hidden="true" data-feature="{{ \Config::get("app.ArtistCode") }}"></i></a>
				</div>

			@else
				<div class="col-xs-9">
					<input type="text" id="feature_input_{{\Config::get("app.ArtistCode")}}"  name="feature_input[{{\Config::get("app.ArtistCode")}}]" value="{{$valueFeature }}" class="form-control">
				</div>
				<div class="col-xs-3 text-right">
					<a class=" addAutor_JS btn btn-success" data-feature="{{\Config::get("app.ArtistCode")}}"> + </a>
				</div>
			@endif
		@endif

	</fieldset>
</div>
@endif
<div class="col-xs-12 col-md-4">
	<fieldset>
		<legend>{{ trans("admin-app.title.options") }}</legend>
		@foreach ($formulario->estados as $field => $input)
			@if(!in_array('noSub'.$field, Config::get('app.config_menu_admin')))
				<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
				<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
					data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
				{!! $input !!}
			@endif
		@endforeach
	</fieldset>
</div>

<div class="col-xs-12 col-md-8">
	<fieldset>
		<legend>{{ trans("admin-app.title.dates") }}</legend>
		<div class="row d-flex flex-wrap">
			@foreach ($formulario->fechas as $field => $input)
				@if(!in_array('noSub'.$field, Config::get('app.config_menu_admin')))
					<div class="col-xs-12 col-sm-6">
						<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
						<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
							data-toggle="tooltip" data-placement="right"
							data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
						{!! $input !!}
					</div>
				@endif
			@endforeach
	</fieldset>
</div>

@if(!empty($aucSessions))
<div class="col-xs-12 col-md-12 mb-2 mt-3">
	<fieldset>
		<legend class="">
			{{ trans_choice("admin-app.title.session", 2) }}
		</legend>

		<div id="subasta_sesiones">
			@include('admin::pages.subasta.sesiones._table', ['aucSessions' => $aucSessions, 'cod_sub' => $fgSub->cod_sub ])
		</div>

	</fieldset>
</div>
@endif

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



