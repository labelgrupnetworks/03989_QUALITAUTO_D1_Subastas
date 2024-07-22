@foreach (\Config::get('app.locales') as $lang => $textLang)
    <a class="btn btn-primary" id="{{ strtoupper($lang) }}button" style="cursor:pointer;color:#FFF"
        onclick="change_lang('{{ strtoupper($lang) }}');">{{ $textLang }}</a>
    &nbsp;&nbsp;&nbsp;
@endforeach
<br><br>
<form id="formLenguaje" method="POST" action="/admin/newbanner/guardaItemViewBloque" enctype="multipart/form-data">

    {!! $formulario[strtoupper($lang)]['token'] !!}
    {!! $formulario[strtoupper($lang)]['key'] !!}

    @foreach (\Config::get('app.locales') as $lang => $textLang)
        <div class="langs lenguaje{{ strtoupper($lang) }}" style="display: none">
            <div class="row">
                <div class="col-xs-12 col-md-8">
                    <h3>{{ $textLang }}</h3>
                    @foreach ($formulario[strtoupper($lang)] as $inputName => $form)
                        <div class="form-group">
                            <label>
                                {{ $inputName }}
                                {!! $form !!}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <br><br>

    <center><button class="btn btn-success" id="btnGuardarItem"
            data-lang="formLenguaje{{ strtoupper($lang) }}">{{ trans('admin-app.button.save') }}</button></center>

</form>
