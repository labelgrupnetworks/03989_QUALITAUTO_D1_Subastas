<div class="col-xs-12 col-md-12">
    <div class="row d-flex flex-wrap">
        @foreach ($formulario as $field => $input)
            <div class="col-xs-12 col-sm-6">
                <label class="mt-1" for="{{ $field }}">{{ trans("admin-app.fields.$field") }}</label>
                <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right"
                    data-original-title="{{ trans("admin-app.help_fields.$field") }}" aria-hidden="true"
                    style="cursor: pointer; margin-left: 3px"></i>
                {!! $input !!}
            </div>
        @endforeach
    </div>
</div>
