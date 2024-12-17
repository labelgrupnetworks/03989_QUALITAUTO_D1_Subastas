<div class="mb-3 mt-3">
    <fieldset>
        <legend>Datos de la empresa</legend>

        @if (!empty($user->image))
            <div>
                <img id="companyImage" src="{{ $user->image }}" style="border:1px solid black" width="96"
                    height="auto">
            </div>
        @endif

        <div class="row d-flex flex-wrap">
            @foreach ($formulario->company_image as $field => $input)
                <div class="col-xs-12">
                    <label class="mt-1" for="{{ $field }}">{{ trans('admin-app.fields.image') }}</label>
                    {!! $input !!}
                </div>
            @endforeach

            @foreach ($formulario->info as $field => $input)
                <div class="col-xs-12 col-sm-6">
                    <label class="mt-1" for="{{ $field }}">{{ trans("admin-app.fields.$field") }}</label>
                    {!! $input !!}
                </div>
            @endforeach
        </div>
    </fieldset>
</div>

<div class="mb-3 mt-3">
    <fieldset>
        <legend>Información de la subasta</legend>

        @if (!empty($auction->image))
            <div>
                <img id="auctionImage" src="{{ $auction->image }}" style="border:1px solid black" width="96"
                    height="auto">
            </div>
        @endif

        <div class="row d-flex flex-wrap">
            @foreach ($formulario->auction_image as $field => $input)
                <div class="col-xs-12">
                    <label class="mt-1" for="{{ $field }}">{{ trans('admin-app.fields.image') }}</label>
                    {!! $input !!}
                </div>
            @endforeach

            @foreach ($formulario->auction as $field => $input)
                <div class="col-xs-12">
                    <label class="mt-1" for="{{ $field }}">{{ trans("admin-app.fields.$field") }}</label>
                    {!! $input !!}
                </div>
            @endforeach
        </div>
    </fieldset>
</div>
