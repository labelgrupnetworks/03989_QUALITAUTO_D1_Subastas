<form name="createFeatures" action="{{ route('admin.features.store') }}" method="POST">
    @csrf

    <div class="row">
        @foreach ($formulario as $index => $item)
            <div class="col-xs-12 mb-1">
                <label class="mt-1" for="{{ $index }}">{{ trans("admin-app.fields.$index") }}</label>
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
