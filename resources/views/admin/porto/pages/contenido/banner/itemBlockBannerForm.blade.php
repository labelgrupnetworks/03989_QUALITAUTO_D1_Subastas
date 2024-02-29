@foreach (\Config::get('app.locales') as $lang => $textLang)
    <a onclick="change_lang('{{ strtoupper($lang) }}');" class="btn btn-primary" id="{{ strtoupper($lang) }}button"
        style="cursor:pointer;color:#FFF">{{ $textLang }}</a>
    &nbsp;&nbsp;&nbsp;
@endforeach
<br><br>
<form id="formLenguaje" method="POST" action="/admin/newbanner/guardaItemBloque" enctype="multipart/form-data">

    {!! $formulario[strtoupper($lang)]['token'] !!}
    {!! $formulario[strtoupper($lang)]['key'] !!}

    @foreach (\Config::get('app.locales') as $lang => $textLang)
        <div class="langs lenguaje{{ strtoupper($lang) }}" style="display: none">

            <h3>{{ $textLang }}</h3>
            {!! $formulario[strtoupper($lang)]['id'] !!}
            {!! $formulario[strtoupper($lang)]['id_web_banner'] !!}
            {!! $formulario[strtoupper($lang)]['lenguaje'] !!}
            {!! $formulario[strtoupper($lang)]['bloque'] !!}
            <br>
            @if ($tipo == 'imagen' || $tipo == 'imgSingle' || $tipo == 'imgBlock')
                <div class="row">
                    <div class="col-xs-12 col-md-4">
                        <label>{{ trans("admin-app.fields.image") }}</label>
                        {!! $formulario[strtoupper($lang)]['imagen'] !!}
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>{{ trans("admin-app.fields.mobile_image") }}</label>
                        {!! $formulario[strtoupper($lang)]['imagen_mobile'] !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-8">
                        <label>{{ trans("admin-app.fields.text") }}</label>
                        {!! $formulario[strtoupper($lang)]['texto2'] !!}
                    </div>
                </div>
            @elseif ($tipo == 'texto' || $tipo == 'iframe')
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <label>{{ trans("admin-app.fields.text") }}</label>
                        {!! $formulario[strtoupper($lang)]['texto2'] !!}
                    </div>
                </div>
            @elseif ($tipo == 'video')
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <label>{{ trans("admin-app.fields.url_yt_video") }}</label>
                        {!! $formulario[strtoupper($lang)]['texto'] !!}
                    </div>
                </div>
            @endif

            <br>
            @if ($tipo != 'video')
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <label>{{ trans("admin-app.fields.url_abbreviated") }}</label>
                        {!! $formulario[strtoupper($lang)]['url'] !!}
                    </div>
                    <div class="col-xs-12 col-md-3 text-center">
                        <label>{{ trans("admin-app.fields.open_url_new_windows") }}</label>
                        {!! $formulario[strtoupper($lang)]['ventana_nueva'] !!}
                    </div>
                </div>
            @endif

        </div>
    @endforeach

    <br><br>

    <center><button id="btnGuardarItem" data-lang="formLenguaje{{ strtoupper($lang) }}"
            class="btn btn-success">{{ trans("admin-app.button.save") }}</button></center>

</form>
