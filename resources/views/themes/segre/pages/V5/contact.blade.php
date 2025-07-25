@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.foot.faq') }}
@stop

@section('content')

    @php
        $bread[] = ['name' => trans($theme . '-app.foot.contact')];
    @endphp

    <main class="contact-page">

        {!! BannerLib::bannerWithView('contact-page', 'hero', [
            'title' => trans("$theme-app.foot.contact"),
            'breadcrumb' => view('includes.breadcrumb', ['bread' => $bread])->render(),
        ]) !!}

        <div class="container mt-5">

            <div class="text-center m-auto" style="max-width: 800px;">
                <h2 class="mt-5 mb-5">
                    {{ trans("web.pages.contact_over_20_years") }}
                </h2>
                <p class="opacity-75">
					{{ trans("web.pages.address_contact") }}
                </p>
            </div>

            <div class="row mt-3 mt-lg-5 pt-lg-5 gy-5">
                <div class="col-md-6">

                    <div class="d-flex gap-3 mb-3 mb-md-5">
                        <h4>
                            <x-icon.boostrap icon="geo-alt-fill" />
                        </h4>
                        <div>
                            <h4 class="fw-500">{{ trans("web.login_register.direccion") }}</h4>
                            <p class="opacity-50">
                                Segre, 18,<br>
                                28002 Madrid
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-3 mb-md-5">
                        <h4>
                            <x-icon.boostrap icon="telephone-fill" />
                        </h4>
                        <div>
                            <h4 class="fw-500">Telf.</h4>
                            <a class="opacity-50" href="tel:+34915159584">91 515 95 84</a>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-3 mb-md-5">
                        <h4>
                            <x-icon.boostrap icon="envelope-fill" />
                        </h4>

                        <div>
                            <h4 class="fw-500">Email</h4>
                            <a class="opacity-50" href="mailto:info@subastassegre.es">
                                info@subastassegre.es
                            </a>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-3 mb-md-5">
                        <h4>
                            <x-icon.boostrap icon="clock-fill" />
                        </h4>
                        <div>
                            <h4 class="fw-500">{{ trans("web.login_register.schedule") }}</h4>
                            <p class="opacity-50">
								{!! trans("web.login_register.working_hours") !!}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 contact-page-form">

                    <form id="contactForm" name="contactForm" novalidate>
                        @csrf

                        <input name="captcha_token" data-sitekey="{{ config('app.captcha_v3_public') }}" type="hidden"
                            value="">

                        <div class="mb-3 pb-3">
                            <label class="form-label fw-500" for="texto__1__nombre">
                                {{ trans("$theme-app.login_register.contact") }}</label>
                            {!! $data['formulario']['nombre'] !!}
                        </div>
                        <div class="mb-3 pb-3">
                            <label class="form-label fw-500"
                                for="email__1__email">{{ trans("$theme-app.foot.newsletter_text_input") }}</label>
                            {!! $data['formulario']['email'] !!}
                        </div>
                        <div class="mb-3 pb-3">
                            <label class="form-label fw-500"
                                for="texto__1__telefono">{{ trans("$theme-app.user_panel.phone") }}</label>
                            {!! $data['formulario']['telefono'] !!}
                        </div>
                        <div class="mb-3 pb-3">
                            <label class="form-label fw-500"
                                for="textogrande__1__comentario">{{ trans("$theme-app.global.coment") }}</label>
                            {!! $data['formulario']['comentario'] !!}
                        </div>

                        <div class="form-check mb-3 pb-3">
                            <input class="form-check-input" id="bool__1__condiciones" name="condiciones" type="checkbox"
                                value="" value="on" autocomplete="off" required>
                            <label class="form-check-label" for="bool__1__condiciones">
                                {!! trans("$theme-app.emails.privacy_conditions") !!}
                            </label>

                        </div>

                        <p class="captcha-terms">
                            {!! trans("$theme-app.global.captcha-terms") !!}
                        </p>

                        <button class="btn btn-lb-primary px-md-5 mt-3" type="submit">
							{{ trans("web.global.enviar") }}
						</button>

                    </form>
                </div>

            </div>

        </div>
    </main>

@stop
