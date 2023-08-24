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
                        <label>Imagen</label>
                        {!! $formulario[strtoupper($lang)]['imagen'] !!}
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>Imagen mobile</label>
                        {!! $formulario[strtoupper($lang)]['imagen_mobile'] !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-8">
                        <label>Texto</label>
                        {!! $formulario[strtoupper($lang)]['texto2'] !!}
                    </div>
                </div>
            @elseif ($tipo == 'texto')
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <label>Texto</label>
                        {!! $formulario[strtoupper($lang)]['texto2'] !!}
                    </div>
                </div>
            @elseif ($tipo == 'video')
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <label> URL Video Youtube</label>
                        {!! $formulario[strtoupper($lang)]['texto'] !!}
                    </div>
                </div>
            @endif

            <br>
            @if ($tipo != 'video')
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <label>Url</label>
                        {!! $formulario[strtoupper($lang)]['url'] !!}
                    </div>
                    <div class="col-xs-12 col-md-3 text-center">
                        <label>Abrir url en ventana nueva</label>
                        {!! $formulario[strtoupper($lang)]['ventana_nueva'] !!}
                    </div>
                </div>
            @endif

        </div>
    @endforeach

    <br><br>

    <center><button id="btnGuardarItem" data-lang="formLenguaje{{ strtoupper($lang) }}"
            class="btn btn-success">Guardar</button></center>

</form>
