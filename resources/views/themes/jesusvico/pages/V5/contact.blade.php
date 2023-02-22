@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.foot.faq') }}
@stop

@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>
@endpush

@section('content')

    <main class="contact-page">
        <div class="container">
            <h1>{{ trans(\Config::get('app.theme') . '-app.foot.contact') }}</h1>
        </div>

        <div class="container">

            <div class="row gy-3">

                <div class="col-lg-5 pe-lg-5">
                    {{-- {!! $data['content'] !!} --}}

                    <section class="bg-lb-color-primary">
                        <h3 class="p-5 m-0" style="color: white;">{{ trans("$theme-app.foot.answer_your_questions") }}</h3>
                    </section>
                    <section class="bg-lb-color-backgorund-light p-5">
                        <div class="d-flex align-items-center pt-2 pb-2">
                            <svg class="m-2" xmlns="http://www.w3.org/2000/svg" width="21" height="15.545"
                                viewBox="0 0 21 15.545">
                                <g id="mail-outline" transform="translate(0.5 0.5)">
                                    <rect id="Rectángulo_152" data-name="Rectángulo 152" width="20" height="14.545"
                                        rx="2" fill="none" stroke="#b9b13c" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="1" />
                                    <path id="Trazado_119" data-name="Trazado 119" d="M112,160l6.62,5.927L125.24,160"
                                        transform="translate(-108.62 -157.502)" fill="none" stroke="#b9b13c"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="1" />
                                </g>
                            </svg>
                            <a class="static-link" href="mailto:info@jesusvico.com">info@jesusvico.com</a>
                        </div>
                        <div class="d-flex align-items-center pt-2 pb-2">
                            <svg class="m-2" xmlns="http://www.w3.org/2000/svg" width="20.963" height="20.919"
                                viewBox="0 0 20.963 20.919">
                                <g id="call-outline" transform="translate(0.519 0.444)">
                                    <path id="call-outline-2" data-name="call-outline"
                                        d="M67.37,63.685a17.644,17.644,0,0,0-3.51-2.344c-1.168-.588-1.264-.637-2.183.046-.613.455-1.02.862-1.737.709A9.3,9.3,0,0,1,56.3,59.721a9.61,9.61,0,0,1-2.429-3.677c-.153-.714.26-1.117.711-1.731.636-.865.588-1.01.044-2.178a15.978,15.978,0,0,0-2.351-3.5c-.826-.816-.826-.672-1.358-.45a7.7,7.7,0,0,0-1.242.662,3.721,3.721,0,0,0-1.495,1.574c-.3.638-.433,2.134,1.109,4.935a24.445,24.445,0,0,0,4.863,6.466,26.606,26.606,0,0,0,6.478,4.845c3.114,1.744,4.308,1.4,4.948,1.106a3.709,3.709,0,0,0,1.578-1.49,7.649,7.649,0,0,0,.663-1.24C68.043,64.51,68.187,64.51,67.37,63.685Z"
                                        transform="translate(-47.994 -48.012)" fill="none" stroke="#b9b13c"
                                        stroke-miterlimit="10" stroke-width="1" />
                                </g>
                            </svg>
                            <div>
                                <p>+34 914 318 807</p>
                                <p>+34 915 773 065</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center pt-2 pb-2">
                            <svg class="m-2" xmlns="http://www.w3.org/2000/svg" width="21" height="21"
                                viewBox="0 0 21 21">
                                <g id="print-outline" transform="translate(0.5 0.5)">
                                    <path id="Trazado_208" data-name="Trazado 208"
                                        d="M19.917,19.188h1.25a2.105,2.105,0,0,0,2.083-2.114V8.614A2.105,2.105,0,0,0,21.167,6.5H5.333A2.105,2.105,0,0,0,3.25,8.614v8.458a2.105,2.105,0,0,0,2.083,2.114h1.25"
                                        transform="translate(-3.25 -3.25)" fill="none" stroke="#b9b20e"
                                        stroke-linejoin="round" stroke-width="1" />
                                    <path id="Trazado_209" data-name="Trazado 209"
                                        d="M7.783,12.188H18.717A1.259,1.259,0,0,1,20,13.422v8.093a1.259,1.259,0,0,1-1.283,1.235H7.783A1.259,1.259,0,0,1,6.5,21.515V13.422a1.259,1.259,0,0,1,1.283-1.234Z"
                                        transform="translate(-3.25 -2.75)" fill="none" stroke="#b9b20e"
                                        stroke-linejoin="round" stroke-width="1" />
                                    <path id="Trazado_210" data-name="Trazado 210"
                                        d="M20,6.5V5.281A2.077,2.077,0,0,0,17.891,3.25H8.609A2.077,2.077,0,0,0,6.5,5.281V6.5"
                                        transform="translate(-3.25 -3.25)" fill="none" stroke="#b9b20e"
                                        stroke-linejoin="round" stroke-width="1" />
                                    <path id="Trazado_211" data-name="Trazado 211"
                                        d="M21.125,9.344A1.219,1.219,0,1,1,19.906,8.16,1.219,1.219,0,0,1,21.125,9.344Z"
                                        transform="translate(-2.75 -3.106)" fill="#b9b20e" />
                                </g>
                            </svg>
                            <p>+34 714 301 104</p>
                        </div>
                        <div class="d-flex align-items-center pt-2 pb-2">
                            <svg class="m-2" xmlns="http://www.w3.org/2000/svg" width="21" height="29.885"
                                viewBox="0 0 21 29.885">
                                <g id="location-outline" transform="translate(0.5 0.5)">
                                    <path id="Trazado_212" data-name="Trazado 212"
                                        d="M15.687,2.438a9.769,9.769,0,0,0-10,9.513c0,6.041,6.666,15.615,9.114,18.921a1.1,1.1,0,0,0,1.771,0C19.021,27.567,25.687,18,25.687,11.951a9.768,9.768,0,0,0-10-9.513Z"
                                        transform="translate(-5.687 -2.438)" fill="none" stroke="#b9b20e"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="1" />
                                    <path id="Trazado_213" data-name="Trazado 213"
                                        d="M15.438,9.75A2.438,2.438,0,1,1,13,7.312,2.438,2.438,0,0,1,15.438,9.75Z"
                                        transform="translate(-3 -0.11)" fill="none" stroke="#b9b20e"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="1" />
                                </g>
                            </svg>
                            <p>Calle Jorge Juan, 83 duplicado,<br>28009 Madrid, España</p>
                        </div>
                        <div class="d-flex align-items-center pt-2 pb-2">
                            <svg class="m-2" xmlns="http://www.w3.org/2000/svg" width="21" height="21"
                                viewBox="0 0 21 21">
                                <g id="time-outline" transform="translate(0.5 0.5)">
                                    <path id="Trazado_214" data-name="Trazado 214"
                                        d="M13.25,3.25a10,10,0,1,0,10,10A10,10,0,0,0,13.25,3.25Z"
                                        transform="translate(-3.25 -3.25)" fill="none" stroke="#b9b20e"
                                        stroke-miterlimit="10" stroke-width="1" />
                                    <path id="Trazado_215" data-name="Trazado 215" d="M13,6.5v7.313h4.875"
                                        transform="translate(-2.917 -3.116)" fill="none" stroke="#b9b20e"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="1" />
                                </g>
                            </svg>
                            <p>De lunes a viernes<br>de 9:30h a 18:00h</p>
                        </div>
                    </section>
                </div>
                <div class="col-lg-7 contact-page-form ps-xl-5 mt-5 mt-lg-0">

                    <form id="contactForm" name="contactForm" novalidate>
                        @csrf
                        <div class="mb-2">
                            <label class="form-label"
                                for="texto__1__nombre">{{ trans("$theme-app.login_register.contact") }}</label>
                            {!! $data['formulario']['nombre'] !!}
                        </div>
                        <div class="mb-2">
                            <label class="form-label"
                                for="email__1__email">{{ trans("$theme-app.foot.newsletter_text_input") }}</label>
                            {!! $data['formulario']['email'] !!}
                        </div>
                        <div class="mb-2">
                            <label class="form-label"
                                for="texto__1__telefono">{{ trans("$theme-app.user_panel.phone") }}</label>
                            {!! $data['formulario']['telefono'] !!}
                        </div>
                        <div class="mb-2">
                            <label class="form-label"
                                for="textogrande__1__comentario">{{ trans("$theme-app.global.coment") }}</label>
                            {!! $data['formulario']['comentario'] !!}
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" id="bool__1__condiciones" name="condiciones" type="checkbox"
                                value="" value="on" autocomplete="off" required>
                            <label class="form-check-label" for="bool__1__condiciones">
                                {!! trans("$theme-app.emails.privacy_conditions") !!}
                            </label>
                        </div>

                        <div class="mb-2">
                            <div class="g-recaptcha" data-sitekey="{{ \Config::get('app.codRecaptchaEmailPublico') }}"
                                data-callback="onSubmit"></div>
                        </div>

                        <button class="btn btn-lb-primary" type="submit">{{ trans("$theme-app.global.enviar") }}</a>

                    </form>
                </div>
            </div>

        </div>

        <section class="container-fluid p-0 map-contact mt-5 mb-5 pt-5">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6074.675997893583!2d-3.6826217223657953!3d40.42351398503582!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd4228a4d0c37133%3A0x826a8f1e0b894a8c!2sJes%C3%BAs%20Vico%20S.A.!5e0!3m2!1ses!2ses!4v1666859088845!5m2!1ses!2ses"
                style="border:0;" width="100%" height="500" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </section>
    </main>

    <script>
        $(document).ready(function() {
            $('#texto__1__nombre').addClass('input-text-style');
            $('#email__1__email').addClass('input-text-style');
            $('#texto__1__telefono').addClass('input-text-style');
            $('#textogrande__1__comentario').addClass('input-text-style');
            $('#textogrande__1__comentario').attr('rows', 5);
        });
    </script>

@stop
