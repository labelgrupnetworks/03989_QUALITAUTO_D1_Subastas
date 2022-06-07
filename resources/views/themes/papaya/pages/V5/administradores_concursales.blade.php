@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.foot.faq') }}
@stop

@section('content')

<link href="/themes/papaya/css/page/administradores.css" rel="stylesheet" type="text/css"/>
<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>
<section class="all-aution-title title-content pb-1">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 h1-titl text-center">
                <h1 class="page-title mb-3">Administradores concursales</h1>
            </div>
        </div>
    </div>
</section>

<section class="all-aution-title pb-1 m-0">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 h1-titl text-center">
                <h2 class="page-title">Formulario de Contacto para Administradores Concursales</h2>
            </div>
        </div>
    </div>
</section>

<?php
$formulario = $data['formulario'];
?>


<div class="container">



    <div class="row">

        <div class="col-xs-12 col-md-2"></div>
        <div class="col-xs-12 col-md-8 contact-page-form">
            <form name="contactForm" id="contactForm" method="post" action="javascript:sendContact()">

                {!! $formulario['_token'] !!}
                <div class="create-account-personal-info personal-info">

                    <div class="inputs-custom-group d-flex flex-direction-column" style="align-items: center">
                        <div class="form-group input-group name_client registerParticular col-xs-12 col-sm-8">
                            <label class="" for="nombre">{{ trans(\Config::get('app.theme').'-app.login_register.nombre') }}</label>
                            {!!$formulario['nombre']!!}
                        </div>
                        <div class="form-group input-group col-xs-12 col-sm-8">
                            <label class="" for="telefono">{{ trans(\Config::get('app.theme').'-app.foot.newsletter_text_input') }}</label>
                            {!!$formulario['email']!!}
                        </div>
                        <div class="form-group input-group col-xs-12 col-sm-8">
                            <label class="" for="telefono">{{ trans(\Config::get('app.theme').'-app.user_panel.phone') }}</label>
                            {!!$formulario['telefono']!!}
                        </div>
                        <div class="form-group input-group col-xs-12 col-sm-8">
                            <label class="" for="telefono">{{ trans(\Config::get('app.theme').'-app.global.coment') }}</label>
                            {!!$formulario['comentario']!!}
                        </div>
                        <div class=" col-xs-12 col-sm-8 mb-2 p-0 checks-terms" style="padding: 0 4px">

                            <label for="accept_new" class="d-flex align-items-center">
                                <input name="accept_news" required type="checkbox"class="form-control" id="accept_new"/>
                                <span>{{ trans(\Config::get('app.theme').'-app.emails.accept_news') }}</span>
                            </label>
                            <label for="condiciones" class="d-flex align-items-center">
                                <input name="condiciones" required type="checkbox"class="form-control" id="accept_new_condiciones"/>
                                <span><?= trans(\Config::get('app.theme') . '-app.emails.privacy_conditions') ?></a></span>
                            </label>

                        </div>

                        <div class="g-recaptcha col-xs-12 col-sm-8 p-0"
                             data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"
                             data-callback="onSubmit"
                             >
                        </div>

                        <div class="col-xs-12 col-sm-8 contact-submit p-0">
                            <br>
                            {!! $formulario['SUBMIT'] !!}
                        </div>


                    </div>

                </div>
        </div>
        <div class="clearfix"></div>
        </form>

    </div>
    <div class="col-xs-12 col-md-2"></div>
</div>

</div>

<script>

    $('#button-map').click( function () {

    if($(this).hasClass('active')){
    $('.maps-house-auction').animate({left: '100%'}, 300)
    $(this)
    .removeClass('active')
    .find('i').addClass('fa-map-marker-alt').removeClass('fa-times')
    }else{
    $('.maps-house-auction').animate({left: 0}, 300)
    $(this)
    .addClass('active')
    .find('i').removeClass('fa-map-marker-alt').addClass('fa-times')
    }

    })



$(document).ready(function(){
    $('input[type="text"]').each(function(){

        $(this).attr('placeholder', $(this).siblings('label').text())
    })

    $('input[type="password"]').each(function(){

$(this).attr('placeholder', $(this).siblings('label').text())
})

})
</script>

@stop

