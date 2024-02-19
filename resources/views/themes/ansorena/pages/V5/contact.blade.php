@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.foot.faq') }}
@stop

@section('framework-css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/5.2.0/css/bootstrap.min.css') }}">
@endsection

@section('framework-js')
    <script src="{{ URL::asset('vendor/bootstrap/5.2.0/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>
@endsection

@section('custom-css')
    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/global.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Tools::urlAssetsCache("/themes/$theme/css/style.css") }}" rel="stylesheet" type="text/css">

    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/header.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')

    <main class="contact-page gray-page pb-5">

        <h1 class="ff-highlight fs-32-40 text-center">{{ trans("$theme-app.foot.contact") }}</h1>

        <p class="text-center contact-subtitle">
			{!! trans("$theme-app.faq.frequently_asked_questions") !!}
        </p>

        <section class="contact-form container">
            <div class="row justify-content-around">
                <div class="col-lg-5 mb-5">
                    <form name="contactForm" id="contactForm" method="post" action="javascript:sendContact()">

                        {!! $data['formulario']['_token'] !!}

                        <div class="form-floating mb-3">
                            {!! $data['formulario']['nombre'] !!}
                            <label for="floatingInput">
                                {{ trans("$theme-app.login_register.contact") }}
                            </label>
                        </div>
                        <div class="form-floating mb-3">
                            {!! $data['formulario']['email'] !!}
                            <label for="floatingInput">
                                {{ trans("$theme-app.foot.newsletter_text_input") }}
                            </label>
                        </div>
                        <div class="form-floating mb-3">
                            {!! $data['formulario']['telefono'] !!}
                            <label for="floatingInput">
                                {{ trans("$theme-app.user_panel.phone") }}
                            </label>
                        </div>
                        <div class="form-floating mb-3">
                            {!! $data['formulario']['comentario'] !!}
                            <label for="floatingInput">
                                {{ trans("$theme-app.global.coment") }}
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" name="condiciones" value="on"
                                id="bool__1__condiciones" autocomplete="off">
                            <label class="form-check-label" for="bool__1__condiciones">
                                {!! trans("$theme-app.emails.privacy_conditions") !!}
                            </label>
                        </div>

                        <div class="mb-3">
                            <div class="g-recaptcha" data-sitekey="{{ \Config::get('app.codRecaptchaEmailPublico') }}"
                                data-callback="onSubmit">
                            </div>
                        </div>

                        <div class="text-center text-lg-end">
                            {!! $data['formulario']['SUBMIT'] !!}
                        </div>
                    </form>

                </div>
                <div class="col-lg-5 contact-address">
                    {!! $data['content'] !!}
                </div>
            </div>

        </section>

    </main>

	<div class="container">
		@include('includes.work_section')
	</div>
@stop
