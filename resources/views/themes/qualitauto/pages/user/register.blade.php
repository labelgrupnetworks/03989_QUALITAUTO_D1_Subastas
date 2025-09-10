@extends('layouts.default')

@push('scripts')
    <script defer src="{{ Tools::urlAssetsCache(public_default_path('js/register.js')) }}"></script>
    <script defer src="{{ Tools::urlAssetsCache("/themes/$theme/js/register.js") }}"></script>
@endpush

@section('title')
    {{ trans('web.head.title_app') }}
@stop


@section('content')

    @php
        $newsletters = (new \App\Models\Newsletter())->getNewslettersNames();
    @endphp

    <main>
        <section class="container create-account">

            <div class="mb-3">
                <h1>{{ trans('web.login_register.crear_cuenta') }}</h1>
                <p>{{ trans('web.login_register.all_fields_are_required') }}</p>
            </div>

            <form id="registerForm" action="{{ route('user.register_data') }}">
                @csrf

                <input name="captcha_token" data-sitekey="{{ config('app.captcha_v3_public') }}" type="hidden" value="">

                <div class="row mb-5">
                    <div class="col-md-4">
                        <h4>{{ trans('web.user_panel.personal_info') }}</h4>
                    </div>
                    <div class="col-md-8">
                        <div class="row gy-1">
                            <div class="col-12">
                                <label class="form-label register-particular">
                                    {{ trans('web.login_register.nombre') }}
                                    {!! $formulario->usuario !!}
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="form-label register-particular">
                                    {{ trans('web.login_register.apellidos') }}
                                    {!! $formulario->last_name !!}
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="form-label">
                                    {{ trans('web.login_register.phone') }}
                                    {!! $formulario->telefono !!}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-4">
                        <h4>{{ trans('web.login_register.files') }}</h4>
                    </div>
                    <div class="col-md-8">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label">
                                    Certificado de alta en IAE (epígrafe 654.1 - Comercio menor de vehículos terrestres)
                                    <input class="form-control" id="register_IAE" name="files[IAE]" type="file"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="form-label">
                                    DNI del apoderado
                                    <input class="form-control" id="register_dni_apoderado" name="files[dni_apoderado]"
                                        type="file" accept=".pdf,.jpg,.jpeg,.png">
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="form-label">
                                    Escritura de constitución y apoderamiento
                                    <input class="form-control" id="register_escritura_apoderamiento"
                                        name="files[escritura_apoderamiento]" type="file" accept=".pdf,.jpg,.jpeg,.png">
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="form-label">
                                    Tarjeta CIF / NIF de la empresa
                                    <input class="form-control" id="register_tarjeta_cif" name="files[tarjeta_cif]"
                                        type="file" accept=".pdf,.jpg,.jpeg,.png">
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="form-label">
                                    Certificados de estar al corriente TCSS/AEAT (Seguridad Social y Agencia Tributaria)
                                    <input class="form-control" id="register_certificados" name="files[certificados]"
                                        type="file" accept=".pdf,.jpg,.jpeg,.png">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-4">
                        <h4>{{ trans('web.login_register.cuenta') }}</h4>
                    </div>
                    <div class="col-md-8">
                        <div class="row gy-1">
                            <div class="col-md-6">
                                <label class="form-label">
                                    {{ trans('web.login_register.email') }}
                                    {!! $formulario->email !!}
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    {{ trans('web.login_register.email_confirm') }}
                                    {!! $formulario->confirm_email !!}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-4">
                        <h4>{{ trans('web.login_register.conditions') }}</h4>
                    </div>
                    <div class="col-md-8">
                        <div class="row gy-1">
                            <div class="col-12">
                                <label class="form-check-label">
                                    {!! $formulario->newsletter !!}
                                    {{ trans('web.login_register.recibir_newsletter') }}
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="form-check-label">
                                    {!! $formulario->condiciones !!}
                                    <span class="fw-bold text-danger">*</span>
                                    <span>
                                        {!! trans('web.login_register.read_conditions') !!}
                                        (<a href='{{ Routing::translateSeo('pagina') . trans('web.links.term_condition') }}'
                                            target="_blank">{{ trans('web.login_register.more_info') }}
                                        </a>)
                                    </span>
                                </label>
                            </div>

                            <div class="col-12">
                                <p class="captcha-terms">
                                    {!! trans('web.global.captcha-terms') !!}
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-4"></div>
                    <div class="col-md-8">
                        <button class="btn btn-lb-primary submitButton" type="submit">
                            <span class="text">{{ trans('web.login_register.register') }}</span>
                            <div class="spinner spinner-1 m-auto"></div>
                        </button>
                    </div>
                </div>

                @if (!empty($formulario->subalia))
                    {!! $formulario->subalia !!}
                    {!! $formulario->info !!}
                @endif
            </form>

        </section>


        @if (!empty($formulario->subalia))
            <form id="formToSubalia" method="post"
                action="{{ Config::get('app.subalia_URL', 'https://subalia.es') }}/registerclicli">
                <input id="info_sent" name="info" type="hidden" value="">
                <input id="cod_auchouse_sent" name="cod_auchouse" type="hidden" value="">
                <input id="redirect_sent" name="redirect" type="hidden" value="">
            </form>
        @endif

    </main>
@stop
