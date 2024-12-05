@foreach ($formulario->hiddens as $field => $input)
    {!! $input !!}
@endforeach

<div>
    <img id="img_subasta" src="{{ Tools::url_img('lote_medium', $fgAsigl0->num_hces1, $fgAsigl0->lin_hces1) }}"
		style="border:1px solid black" width="96" height="auto">
</div>

<div class="mb-3 mt-3">
    <fieldset>
        <legend>{{ trans('admin-app.title.reference_lot') }}</legend>
        <div class="row d-flex flex-wrap">
            @foreach ($formulario->id as $field => $input)
                <div class="col-xs-12 col-sm-6">
                    <label class="mt-1" for="{{ $field }}">{{ trans("admin-app.fields.$field") }}</label>
                    <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right"
                        data-original-title="{{ trans("admin-app.help_fields.$field") }}" aria-hidden="true"
                        style="cursor: pointer; margin-left: 3px"></i>
                    {!! $input !!}
                </div>
            @endforeach
        </div>
    </fieldset>
</div>

<div class="mb-3 mt-3">
    <fieldset>
        <legend>{{ trans('admin-app.title.info') }}</legend>
        @foreach ($formulario->info as $field => $input)
            @if (!in_array('noLot' . $field, Config::get('app.config_menu_admin')) && !empty($input))
                <label class="mt-1" for="{{ $field }}">{{ trans("admin-app.fields.$field") }}</label>
                <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right"
                    data-original-title="{{ trans("admin-app.help_fields.$field") }}" aria-hidden="true"
                    style="cursor: pointer; margin-left: 3px"></i>
                {!! $input !!}
            @endif
        @endforeach
    </fieldset>
</div>


<div class="mb-3 mt-3 @if (!in_array('DATES', explode(',', config('app.HideEditLotOptions')))) col-md-6 @else col-md-12 @endif">
    <fieldset>
        <legend>{{ trans('admin-app.title.states') }}</legend>
        <div class="row d-flex flex-wrap">
            @foreach ($formulario->estados as $field => $input)
                {{-- No mostramos los campos que se han definido como no mostrar en el config --}}
                @if (!in_array('noLot' . $field, Config::get('app.config_menu_admin')))
                    <div class="col-xs-12 col-sm-6">
                        <label class="mt-1" for="{{ $field }}">{{ trans("admin-app.fields.$field") }}</label>
                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right"
                            data-original-title="{{ trans("admin-app.help_fields.$field") }}" aria-hidden="true"
                            style="cursor: pointer; margin-left: 3px"></i>
                        {!! $input !!}
                    </div>
                @endif
            @endforeach
        </div>
    </fieldset>
</div>

<div class="row">
    @if (!in_array('DATES', explode(',', config('app.HideEditLotOptions'))))
        <div class="col-xs-12 col-md-6 mb-3 mt-3">
            <fieldset>
                <legend>{{ trans('admin-app.title.dates') }}</legend>
                <div class="row d-flex flex-wrap">
                    @foreach ($formulario->fechas as $field => $input)
                        {{-- No mostramos los campos que se han definido como no mostrar en el config --}}
                        @if (!in_array('noLot' . $field, Config::get('app.config_menu_admin')))
                            <div class="col-xs-12 col-sm-6">
                                <label class="mt-1"
                                    for="{{ $field }}">{{ trans("admin-app.fields.$field") }}</label>
                                <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right"
                                    data-original-title="{{ trans("admin-app.help_fields.$field") }}"
                                    aria-hidden="true" style="cursor: pointer; margin-left: 3px"></i>
                                {!! $input !!}
                            </div>
                        @endif
                    @endforeach
                </div>
            </fieldset>
        </div>
    @endif

    @if (!in_array('PRICE', explode(',', config('app.HideEditLotOptions'))))
        <div class="col-xs-12 mb-3 mt-3">
            <fieldset>
                <legend>{{ trans('admin-app.title.prices') }}</legend>
                <div class="row d-flex flex-wrap">
                    @foreach ($formulario->precios as $field => $input)
                        {{-- No mostramos los campos que se han definido como no mostrar en el config --}}
                        @if (!in_array('noLot' . $field, Config::get('app.config_menu_admin')))
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <label class="mt-1"
                                    for="{{ $field }}">{{ trans("admin-app.fields.$field") }}</label>
                                <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right"
                                    data-original-title="{{ trans("admin-app.help_fields.$field") }}"
                                    aria-hidden="true" style="cursor: pointer; margin-left: 3px"></i>
                                {!! $input !!}
                            </div>
                        @elseif($field == 'startprice')
                            <input name="startprice" type="hidden" value=0 />
                        @endif
                    @endforeach
            </fieldset>
        </div>
    @else
        <input name="startprice" type="hidden" value=0 />
    @endif
</div>

@if (in_array('OTHERS', explode(',', config('app.showEditLotOptions'))))
    <div class="mb-3 mt-3">
        <fieldset>
            <legend>{{ trans('admin-app.title.others') }}</legend>
            <div class="row d-flex flex-wrap">
                @foreach ($formulario->otros as $field => $input)
                    {{-- No mostramos los campos que se han definido como no mostrar en el config --}}
                    @if (!in_array('noLot' . $field, Config::get('app.config_menu_admin')))
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <label class="mt-1"
                                for="{{ $field }}">{{ trans("admin-app.fields.$field") }}</label>
                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right"
                                data-original-title="{{ trans("admin-app.help_fields.$field") }}" aria-hidden="true"
                                style="cursor: pointer; margin-left: 3px"></i>
                            {!! $input !!}
                        </div>
                    @elseif($field == 'startprice')
                        <input name="startprice" type="hidden" value=0 />
                    @endif
                @endforeach
        </fieldset>
    </div>
@endif

<script>
    $(document).on('ready', function() {
        for (const element of document.querySelectorAll('input[type=time]')) {
            if (element.value.length < 6) {
                element.value += ':00';
            }
        }
    });
</script>
