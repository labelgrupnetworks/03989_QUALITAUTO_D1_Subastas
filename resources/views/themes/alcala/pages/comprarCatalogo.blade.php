@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.foot.faq') }}
@stop

@section('content')

<?php 

    $bread[] = array("name" => trans(\Config::get('app.theme').'-app.foot.comprar_catalogo') );

?>



<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>
  
    <div class="container">
	
    	<h1 class="color-letter">{{trans(\Config::get('app.theme').'-app.foot.comprar_catalogo') }}</h1>

        <section id="content" class="contacto">
        <br>

        <h3>{{trans(\Config::get('app.theme').'-app.global.informacion_contacto') }}</h3>
        <hr>

        
        <div class="row contact-page-form ">

            <div class="col-xs-12 col-md-8">
                    <form name="contactForm" id="contactForm" method="post" action="javascript:sendCatalogoContact()">

                        {!! $data['formulario']['_token'] !!}
                        
                            <div class="input-effect col-xs-12">
                                {!! $data['formulario']['nombre'] !!}
                                <label>{{trans(\Config::get('app.theme').'-app.user_panel.name') }}</label>
                            </div>

                            <div class="input-effect col-xs-12">
                                {!! $data['formulario']['apellidos'] !!}
                                <label>{{trans(\Config::get('app.theme').'-app.user_panel.surname') }}</label>
                            </div>

                            <div class="input-effect col-xs-12">
                                {!! $data['formulario']['nif'] !!}
                                <label>{{trans(\Config::get('app.theme').'-app.user_panel.nif') }}</label>
                            </div>

                            <div class="input-effect col-xs-12">
                                {!! $data['formulario']['profesion'] !!}
                                <label>{{trans(\Config::get('app.theme').'-app.user_panel.profesion') }}</label>
                            </div>

                            <div class="input-effect col-xs-12">
                                {!! $data['formulario']['direccion'] !!}
                                <label>{{trans(\Config::get('app.theme').'-app.user_panel.direccion') }}</label>
                            </div>

                            <div class="input-effect col-xs-12">
                                {!! $data['formulario']['poblacion'] !!}
                                <label>{{trans(\Config::get('app.theme').'-app.user_panel.poblacion') }}</label>
                            </div>

                            <div class="input-effect col-xs-12">
                                {!! $data['formulario']['provincia'] !!}
                                <label>{{trans(\Config::get('app.theme').'-app.user_panel.provincia') }}</label>
                            </div>

                            <div class="input-effect col-xs-12">
                                {!! $data['formulario']['cp'] !!}
                                <label>{{trans(\Config::get('app.theme').'-app.user_panel.cp') }}</label>
                            </div>

                            <div class="input-effect col-xs-12">
                                {!! $data['formulario']['pais'] !!}
                                <label>{{trans(\Config::get('app.theme').'-app.user_panel.pais') }}</label>
                            </div>

                            <div class="input-effect col-xs-12">
                                {!! $data['formulario']['email'] !!}
                                <label>{{trans(\Config::get('app.theme').'-app.user_panel.email') }}</label>
                            </div>

                            <div class="input-effect col-xs-12">
                                {!! $data['formulario']['telefono'] !!}
                                <label>{{trans(\Config::get('app.theme').'-app.user_panel.phone') }}</label>
                            </div>

                            <div class="clearfix"></div>
                           
                            <div style="border:#CCC 1px solid;background:#FAFAFA;padding:20px;margin:20px;">
                                <b>{{trans(\Config::get('app.theme').'-app.global.datos_envio') }}</b>
                                <br><br>
                                {{trans(\Config::get('app.theme').'-app.global.espana') }}  - 90 €<br>
                                {{trans(\Config::get('app.theme').'-app.global.europa') }} 150 €<br>
                                {{trans(\Config::get('app.theme').'-app.global.resto_paises') }} 200 €<br>
                            </div>
                            <div class="clearfix"></div>
                            <br>
                            <div class="g-recaptcha col-xs-12 col-md-6"
                                 data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"
                                 data-callback="onSubmit"
                                 >
                            </div>
                            <div class="col-xs-12 col-md-6 text-center">
                                <br>
                                {!! $data['formulario']['SUBMIT'] !!}
                                <br><br><br><br>
                            </div>

                        <div class="clearfix"></div>

                    </form>
                </div>
                <div class="col-xs-12 col-md-4">

                    {!! $data['content'] !!}

                </div>                        

            </div>
                            
        </div>


    
</section>

    </div>
<br><br>


@stop

