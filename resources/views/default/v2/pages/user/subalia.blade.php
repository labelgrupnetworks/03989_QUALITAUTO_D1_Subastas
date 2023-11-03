@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')


<link rel="stylesheet" type="text/css" href="/css/user/subalia.css">
<div class="landing">

    <div class="d-flex flex-column ">

        <img class="img-responsive banner-landing mt-5" src="/themes/{{\Config::get('app.theme')}}/img/banner-landing-registro.jpg"  alt="{{(\Config::get( 'app.theme' ))}}" onerror="this.style.display='none'">

        <p class="mt-3 text-center">{{ trans(\Config::get('app.theme').'-app.login_register.subalia_info') }}</p>
        <div class="first_line mr-3 ml-3"></div>

        @if(!Session::has('user'))
        <p class="mt-2 text-center">{{ trans(\Config::get('app.theme').'-app.login_register.subalia_hasUser') }} {{(\Config::get( 'app.name' ))}}</p>

        <form id="formSubalia" method="post" action="javascript:landingLogin();">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="mt-5 formsLanding">
                {!!$formulario->email!!}
            </div>
            <div class="mt-2 mb-5 formsLanding">
                {!!$formulario->password!!}
            </div>
            <div class="mt-2 mb-5 formsLanding">
                {!!$formulario->submit!!}
            </div>

        </form>
        <p class="mt-5 text-center">{{ trans(\Config::get('app.theme').'-app.login_register.subalia_notHasUser') }} <a href="/{{\Config::get('app.locale') }}/login">{{ trans(\Config::get('app.theme').'-app.login_register.here') }}</a></p>

        @else
        <p class="mt-2 text-center">{{ trans(\Config::get('app.theme').'-app.login_register.share_data') }}</p>

        <div class="mt-2 formsLanding">
                {!!$formulario->submit!!}
        </div>
        <div class="mt-1 mb-5">
            <a title="" href="/{{ \Config::get("app.locale")}}"><button class="btn" style="background-color: transparent; min-width: 150px;color:black;" id="cancelarSubalia">{{ trans(\Config::get('app.theme').'-app.login_register.cancel') }}</button></a>
        </div>

        <form id="formUserLogin" method="post" action="javascript:enviarDatos();">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="mt-5">
                {!!$formulario->email!!}
            </div>
        </form>
        @endif


        <form id="formEnvio" method="post" action="{{\Config::get("app.subalia_URL", "https://subalia.es")}}/registercomplete">

            <input type="hidden" name="info" id="info" value="">
            <input type="hidden" name="cod_ambassador" id="member" value="">
        </form>


        <br><br>

    </div>

</div>

<script src="{{ URL::asset('js/user/subalia.js') }}"></script>
@stop
