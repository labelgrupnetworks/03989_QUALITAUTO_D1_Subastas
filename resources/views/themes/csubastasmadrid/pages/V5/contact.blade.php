@extends('layouts.default')

@section('title')
    {{ trans('web.foot.faq') }}
@stop

@section('content')

    @php
        $bread[] = ['name' => trans('web.foot.contact')];
    @endphp

    <main class="page-static">
        <div class="contenido">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 titlePage">
                        <h1>{{ trans('web.foot.contact') }}</h1>
                        <p class="mini-underline"></p>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="info_contact">
                            <div class="contact-address-info">
                                <span class="brown-color-1 contact-icon-size">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </span>
                                <address>
                                    Esparteros, 1 - 2ª planta <br>
                                    28018, Madrid
                                </address>
                            </div>
                            <p class="contact-lineup contact-telf-info">
                                <span class="brown-color-1 contact-icon-size"><i class="fa fa-phone fa-flip-horizontal"
                                        aria-hidden="true"></i></span> +34 915 21 65 68
                            </p>
                            <p class="contact-lineup contact-mail-info">
                                <span class="brown-color-1 contact-icon-size"><i class="fa fa-envelope"
                                        aria-hidden="true"></i></span><a href="mailto:csm@csubastasmadrid.com"
                                    title="csm@csubastasmadrid.com">csm@csubastasmadrid.com</a>
                            </p>

                        </div>
                        <div class="map-contact">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1806.1985782896143!2d-3.706446018971147!3d40.41656346902126!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd42287e5c7906a5%3A0x6a0812ac7915bc0b!2sCasa%20de%20Subastas%20de%20Madrid!5e0!3m2!1ses!2ses!4v1652956200858!5m2!1ses!2ses"
                                style="border:0;" allowfullscreen="" width="100%" height="350" frameborder="0"></iframe>
                        </div>
                    </div>
                    <div class="col-sm-1 hidden-xs hidden-sm"></div>
                    <div class="col-xs-12 col-sm-6 col-md-5">

                        <div class="contact-form-container">
                            <div class="contact-title-container">
                                <h2 class="contact-title">{{ trans("web.login_register.any_questions") }}</h2>
                                <p class="mini-underline"></p>
                            </div>

                            <form id="contactForm" name="contactForm" method="post" action="javascript:sendContact()">
                                @csrf

                                <input name="captcha_token" data-sitekey="{{ config('app.captcha_v3_public') }}"
                                    type="hidden" value="">

                                <div class="form-group">
                                    <label class="form-label" for="nombre">
                                        <strong>{{ trans('web.global.nomApell') }} *</strong>
                                    </label>
                                    <input class="form-control form-input-contact" name="nombre" type="text"
                                        aria-required="true" placeholder="" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="email">
                                        <strong>{{ trans('web.foot.newsletter_text_input') }} *</strong>
                                    </label>
                                    <input class="form-control form-input-contact" name="email" type="text"
                                        aria-required="true" placeholder="" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="telefono">
                                        <strong>{{ trans('web.user_panel.phone') }} *</strong>
                                    </label>
                                    <input class="form-control form-input-contact" name="telefono" type="text"
                                        aria-required="true" placeholder="" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="comentario">
                                        <strong>{{ trans('web.global.coment') }} *</strong>
                                    </label>
                                    <textarea class="form-control form-input-contact" id="" name="comentario" aria-required="true"
                                        style="resize: none;" required cols="30" rows="4"></textarea>
                                </div>

                                <div class="checkbox">
                                    <label>
                                        <input name="condiciones" type="checkbox" required="">
                                        {!! trans('web.login_register.read_conditions_politic') !!}
                                    </label>
                                </div>

                                <p>* {{ trans('web.login_register.all_fields_are_required') }}</p>


                                <p class="captcha-terms">
                                    {!! trans('web.global.captcha-terms') !!}
                                </p>

                                <button class="btn btn-4" type="submit" id="buttonSend">
                                    {{ trans('web.valoracion_gratuita.send') }}
                                </button>


                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

@stop
