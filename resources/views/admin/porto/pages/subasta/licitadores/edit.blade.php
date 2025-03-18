<form name="createLicits" action="{{ route('admin.licit.store') }}" method="POST">
    @csrf

    <div class="row">
        @foreach ($formulario as $index => $item)
            <div class="col-xs-12 mb-1">

                <label class="mt-1" for="{{ $index }}">{{ trans("admin-app.fields.$index") }}</label>
                <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right"
                    data-original-title="{{ trans("admin-app.help_fields.$index") }}" aria-hidden="true"
                    style="cursor: pointer; margin-left: 3px"></i><br>
                <div>
                    {!! $item !!}
                </div>

            </div>
        @endforeach

        <div class="col-xs-12">
            <input class="btn btn-success" type="submit" value="Guardar" style="margin-top: 1rem">
        </div>
    </div>
</form>
