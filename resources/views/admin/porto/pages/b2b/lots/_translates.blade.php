<div class="mb-3 mt-3">
    <fieldset>
        <legend>Traducciones</legend>

        @foreach ($formulario->translates as $lang => $fields)
            <h4>{{ config("app.locales.$lang") }}</h4>

            {!! FormLib::Hidden('lang[]', 1, mb_strtoupper($lang)) !!}

            @foreach ($fields as $field => $input)
                <div>
                    <label class="mt-1" for="{{ $field }}">{{ trans("admin-app.fields.$field") }}</label>
                    <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right"
                        data-original-title="{{ trans("admin-app.help_fields.$field") }}" aria-hidden="true"
                        style="cursor: pointer; margin-left: 3px"></i>
                    {!! $input !!}
                </div>
            @endforeach
        @endforeach
    </fieldset>
</div>
