@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.foot.faq') }}
@stop

@section('content')

    @php
        $bread[] = ['name' => trans($theme . '-app.foot.contact')];
    @endphp

    <main>
        <div class="container">
            @include('includes.breadcrumb')

            <h1>{{ trans($theme . '-app.foot.contact') }}</h1>
        </div>

        <div class="container">

            <div class="row gy-3">
                <div class="col-md-7 contact-page-form">

                    <form id="contactForm" name="contactForm" novalidate>
                        @csrf

                        <input name="captcha_token" data-sitekey="{{ config('app.captcha_v3_public') }}" type="hidden"
                            value="">

                        <div class="mb-3">
                            <label class="form-label"
                                for="texto__1__nombre">{{ trans("$theme-app.login_register.contact") }}</label>
                            {!! $data['formulario']['nombre'] !!}
                        </div>
                        <div class="mb-3">
                            <label class="form-label"
                                for="email__1__email">{{ trans("$theme-app.foot.newsletter_text_input") }}</label>
                            {!! $data['formulario']['email'] !!}
                        </div>
                        <div class="mb-3">
                            <label class="form-label"
                                for="texto__1__telefono">{{ trans("$theme-app.user_panel.phone") }}</label>
                            {!! $data['formulario']['telefono'] !!}
                        </div>
                        <div class="mb-3">
                            <label class="form-label"
                                for="textogrande__1__comentario">{{ trans("$theme-app.global.coment") }}</label>
                            {!! $data['formulario']['comentario'] !!}
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" id="bool__1__condiciones" name="condiciones" type="checkbox"
                                value="" value="on" autocomplete="off" required>
                            <label class="form-check-label" for="bool__1__condiciones">
                                {!! trans("$theme-app.emails.privacy_conditions") !!}
                            </label>

                        </div>

                        <p class="captcha-terms">
                            {!! trans("$theme-app.global.captcha-terms") !!}
                        </p>

                        <button class="btn btn-lb-primary" type="submit">Enviar</a>

                    </form>
                </div>

                <div class="col-md-5">
                    {!! $data['content'] !!}
                </div>
            </div>

        </div>

    </main>

@stop
