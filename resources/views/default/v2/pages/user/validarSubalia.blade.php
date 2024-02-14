@extends('layouts.default')

@section('title')
{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')

<link rel="stylesheet" type="text/css" href="/css/user/subalia.css">

<div class="landing">

    <div class="d-flex flex-column ">


        @if(!Session::has('user'))
        <p class="mt-2 text-center">{{ trans($theme.'-app.login_register.subalia_hasUser') }} {{(\Config::get( 'app.name' ))}}</p>

        <form id="formUserNoLogin" method="post" action="javascript:validarLogin(this);">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="mt-5 formsLanding">
                {!!$formulario->email!!}
            </div>
            <div class="mt-2 mb-5 formsLanding">
                {!!$formulario->password!!}
            </div>
        @else
        <p class="mt-2 text-center">{{ trans($theme.'-app.login_register.share_data') }}</p>
        <form id="formUserLogin" method="post" action="javascript:construirDatos();">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @endif
            <div class="mt- mb-5">
                {!!$formulario->check!!}
                <label for="chek" style="margin-left: 5px;"> Acepto terminos y condiciones</label>
            </div>
            {!!$formulario->cod_cli!!}
            {!!$formulario->redirect!!}
            <div class="mt-2 mb-5 formsLanding">
                {!!$formulario->submit!!}
            </div>
        </form>
        <div class="mt-1 mb-5">
                <a title="" href="{{ $redirect }}"><button class="btn" style="background-color: transparent; min-width: 150px;color:black;" id="cancelarSubalia">{{ trans($theme.'-app.login_register.cancel') }}</button></a>
        </div>


       
            <form id="formToSubalia" method="post" action="{{\Config::get("app.subalia_URL", "https://subalia.es")}}/registerclicli">

                <input type="hidden" name="info" id="info" value="">
                <input type="hidden" name="cod_auchouse" id="cod_auchouse" value="">
                <input type="hidden" name="redirect" id="redirectH" value="">
            </form>


            <br><br>

            </div>

            </div>

            <script src="{{ URL::asset('js/user/validarSubalia.js') }}"></script>
            @stop
