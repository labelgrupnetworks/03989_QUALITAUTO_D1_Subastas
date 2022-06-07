<div class="form-authorize">
<h3>{{ trans(\Config::get('app.theme').'-app.lot.authorization') }}{{ $ref }}</h3>

<input type="hidden" name="cod_sub" value="{{ $cod_sub }}">
<input type="hidden" name="ref" value="{{ $ref }}">

@foreach ($formulario as $key =>$item)
<div class="row mt-2 d-flex align-items-center">
	<div class="col-xs-12 col-md-6">
		<label>{{$key}}</label>
	</div>
	<div class="col-xs-12 col-md-6">
		{!! $item !!}
	</div>
</div>
@endforeach

<p class="text-left mt-2">{{ trans(\Config::get('app.theme').'-app.login_register.include_documents') }}</p>
<div class="row">
	<div class="col-xs-12">
		<input id="files" name="files[]" type="file" class="file form-control" data-show-upload="false" data-show-caption="true"
			multiple>
		<p class="text-left"><small>{{ trans(\Config::get('app.theme').'-app.lot.upload_document_size') }}</small></p>
	</div>
</div>
<p class="text-left mt-2 mb-2">{{ trans(\Config::get('app.theme').'-app.login_register.contest_rules') }}</p>
</div>
