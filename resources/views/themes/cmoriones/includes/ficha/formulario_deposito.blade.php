<form class="form-authorize">
    <h3>{{ trans("$theme-app.lot.authorization") }}{{ $ref }}</h3>

    <input name="cod_sub" type="hidden" value="{{ $cod_sub }}">
    <input name="ref" type="hidden" value="{{ $ref }}">

	@foreach ($formulario as $key => $item)
		<div class="form-group mb-2 row">
			<label class="col-sm-4 col-form-label">
				{{ $key }}
			</label>
			<div class="col-sm-8">
				{!! $item !!}
			</div>
		</div>
	@endforeach


    <p class="mt-2">{{ trans("$theme-app.login_register.include_documents") }}</p>
    <div class="row">
        <div class="col-12">
            <input class="file form-control" id="files" name="files[]" data-show-upload="false"
                data-show-caption="true" type="file" multiple>
            <p><small>{{ trans("$theme-app.lot.upload_document_size") }}</small>
            </p>
        </div>
    </div>
    <p class="mt-2 mb-2">{{ trans("$theme-app.login_register.contest_rules") }}</p>
</form>
