@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.foot.faq') }}
@stop

@section('content')

<?php 

    $bread[] = array("name" => trans(\Config::get('app.theme').'-app.foot.contact') );


$data['departamentos'] = array();

$data['departamentos'][0] = [
    'name' => 'Información General',
    'mail' => 'info@alcalasubastas.es',
    'info' => 'Información'
];
$data['departamentos'][1] = [
    'name' => 'Pintura Antigua',
    'mail' => 'caritina@alcalasubastas.es',
    'info' => 'Caritina Martínez de la Rasilla'
];
$data['departamentos'][2] = [
    'name' => 'Pintura ff.S.XIX-pp.S.XX',
    'mail' => 'yolanda@alcalasubastas.es',
    'info' => 'Yolanda Muñoz Franco'
];
$data['departamentos'][3] = [
    'name' => 'Arte Contemporáneo',
    'mail' => 'eduardo@alcalasubastas.es',
    'info' => 'Eduardo Bobillo'
];
$data['departamentos'][4] = [
    'name' => 'Artes Decorativas y Muebles',
    'mail' => 'ana@alcalasubastas.es',
    'info' => 'Ana Torres'
];
$data['departamentos'][5] = [
    'name' => 'Joyas',
    'mail' => 'raquel@alcalasubastas.es',
    'info' => 'Raquel Moreno'
];
$data['departamentos'][6] = [
    'name' => 'Departamento de Administración',
    'mail' => 'anam@alcalasubastas.es',
    'info' => 'Ana Mayán'
];


?>



<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>

    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 text-center color-letter">
                <h1 class="titlePage">{{trans(\Config::get('app.theme').'-app.foot.contact') }}</h1>
                @include('includes.breadcrumb')
            </div>
        </div>
    </div>

  
    <div class="container">

	

        
        

<section id="content" class="contacto">
    <br>

        <h3>{{trans(\Config::get('app.theme').'-app.global.informacion_contacto') }}</h3>
        <hr>

    <div class="panel-group" id="accordion">
        
        
        <div class="bloque-princi">

            <!-- DEPARTAMETOS -->
            <div class="panel-heading heading">
                
                <h5 class="panel-title">
                    <a onclick="javascript:$('#departamentos').toggle('blind');" id="link_departamento">
                        {{trans(\Config::get('app.theme').'-app.global.departamentos') }}<span>+</span>
                    </a>
                </h5>
                
                <div id="departamentos" class="collapse">
                    
                    <div class="panel-body bloque-contacto">
                        
                        <p>¿Desea contactar con el responsable de algún Departamento?</p>
                        <br>
                            
                        @foreach ($data['departamentos'] as $index_departamentos => $value_departametos)

                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <a class="color-letter" onclick="javascript:seleciona_departamento('{{$value_departametos['mail']}}','{{$value_departametos['info']}}','{{$value_departametos['name']}}')" id="department{{$index_departamentos}}">{{$value_departametos['name']}}</a>
                            </div>

                        @endforeach
                    
                    </div>
                    <br>
                    <div class="row">
                    
                        <div class="col-xs-12 col-md-4">
                            <div style="padding: 20px">
                                <h4>{{trans(\Config::get('app.theme').'-app.global.departamento') }}</h4>
                                <hr>
                                <span id="selected_department"></span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-8 contact-page-form">
                            <div style="padding: 20px 20px 0 0">
                                <h4>{{trans(\Config::get('app.theme').'-app.global.form_contact') }}</h4>
                                <hr>
                                <form name="contactForm" id="contactForm" method="post" action="javascript:sendContact()">

                                    {!! $data['formulario']['_token'] !!}
                                    <div class="form-group">
                                        <div class="input-effect col-xs-12">
                                            <label>{{trans(\Config::get('app.theme').'-app.login_register.contact') }}</label>
                                            {!! $data['formulario']['nombre'] !!}
                                        </div>

                                        <div class="input-effect col-xs-12">
                                            <label>{{trans(\Config::get('app.theme').'-app.foot.newsletter_text_input') }}</label>
                                            {!! $data['formulario']['email'] !!}
                                        </div>

                                        <div class="input-effect col-xs-12">
                                            <label>{{trans(\Config::get('app.theme').'-app.user_panel.phone') }}</label>
                                            {!! $data['formulario']['telefono'] !!}
                                        </div>

                                        <div class="input-effect col-xs-12">
                                            <label>{{trans(\Config::get('app.theme').'-app.global.coment') }}</label>
                                            {!! $data['formulario']['comentario'] !!}
                                        </div>
                                        <input type="hidden" name="email_to" value="" id="email_to">

                                        <div class="check_term row">
                                            <div class="col-xs-2 col-sm-1">
                                                <input type="checkbox" class="newsletter" name="condiciones" value="on" id="bool__1__condiciones" autocomplete="off">
                                            </div>
                                            <div class="col-xs-10 col-md-11">
                                                <label for="accept_new"><?= trans(\Config::get('app.theme') . '-app.emails.privacy_conditions') ?></label>
                                            </div>
                                        </div>
                                        
                                        <br>
                                        <div class="g-recaptcha col-xs-12 col-md-6"
                                             data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"
                                             data-callback="onSubmit"
                                             >
                                        </div>
                                        <div class="col-xs-12 col-md-6 text-center first_check">
                                            <br>
                                            {!! $data['formulario']['SUBMIT'] !!}
                                            <br><br>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                </form>
                            </div>
                        </div>
                                       
                            
                    </div>

                        
                    
                </div>
            
            </div>






            <!-- MAPA -->
            <div class="panel-heading heading">

                <h5 class="panel-title">
                     <a onclick="javascript:$('#mapa').toggle('blind');">{{trans(\Config::get('app.theme').'-app.global.mapa') }}<span>+</span></a>
                </h5>
                
                <div id="mapa" class="collapse">
                    <br>
                    <div class="row">
                        <div class="col-xs-12 col-md-9">
                            <section class="google-maps">
                                <div class="maps">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d20433.25822126205!2d-3.7011964683341376!3d40.421653456978916!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd42289858996375%3A0xe297f850255d1e9c!2sAlcal%C3%A1+Subastas!5e0!3m2!1ses!2ses!4v1555424136919!5m2!1ses!2ses" width="100%" height="350" frameborder="0" style="border:0" allowfullscreen=""></iframe>
                                </div>
                            </section>
                        </div>
                        <div class="info-box col-xs-12 col-md-3">
                            <div class="map-location">
                                <p><img src="https://www.alcalasubastas.es/assets/web/img/maps.png" alt=""><a href="https://goo.gl/maps/pdCK6ZBg9bQ2" class="ml-10" target="_blank" style="color: black;"><strong>Alcalá Subastas</strong></a></p>

                            </div>
                            <p>C/ Núñez de Balboa, 9<br>28001 Madrid<br>España</p>
                            <p><strong>{{trans(\Config::get('app.theme').'-app.global.metro') }}:</strong><br>Retiro (L2)<br>Príncipe de Vergara (Líneas 2 y 9)</p>
                            <p><strong>{{trans(\Config::get('app.theme').'-app.global.autobuses') }}:</strong><br>
                                1 - 9 - 19 - 51 - 74 C/ Velázquez - C/ Villanueva<br>
                                29 C/ Jorge Juan - C/ Príncipe de Vergara<br>
                                15 - 146 - 152 C/ Alcalá - C/ Príncipe de Vergara</p>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
        
        
    </div>
    
    
    <!-- ORIGINAL -->
    <br><br>

    <div class="col-xs-12 col-md-6">
        
        <h3>Información General</h3>
        <hr>
        <div class="contact-info bott-27">
            <p><span>Dirección: </span><strong>Nuñez de Balboa 9</strong></p>
            <p><strong>28001 Madrid-Spain</strong></p>
            <p><span>Teléfono: </span><strong>+34 91 577 87 97</strong></p>
            <p><span>Móvil: </span><strong>+34 717 79 17 39</strong></p>
            <p><span>WhatsApp: </span><strong>+34 616 095 044</strong></p>
            <p><span>Fax: </span><strong>+34 91 432 47 55</strong></p>
            <p><span>Email: </span><strong><a href="mailto:info@alcalasubastas.es">info@alcalasubastas.es</a></strong></p>
        </div>
    </div>

    <div class="col-xs-12 col-md-6">
        <h3>Newsletter</h3>
        <hr>
        @include('includes.newsletter')
        
    </div>
    
</section>

    </div>
<br><br>


<script>

var departamento = getParameterByName("dpto");
    

    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }


    function seleciona_departamento(email, info, name) {

        html = "";
        html = html + '<p>'+info+'</p>';
        html = html + '<p>'+name+'</p>';
        html = html + '<p>Email: <span>'+email+'</span></p>';
        
        $("#selected_department").html(html);

        $("#email_to").val(email);
    }
    

    if(departamento!="") {
        $("#departamentos").show();
        $("#department"+departamento)[0].click();
    }
    else {
        seleciona_departamento('{{$data['departamentos'][0]['mail']}}','{{$data['departamentos'][0]['info']}}','{{$data['departamentos'][0]['name']}}');    
    }
                                            
</script>

@stop

