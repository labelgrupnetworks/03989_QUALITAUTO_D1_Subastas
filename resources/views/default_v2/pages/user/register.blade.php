@extends('layouts.default')

@push('scripts')
    <script defer src="{{ Tools::urlAssetsCache('/default_v2/js/register.js') }}"></script>
@endpush

@section('title')
    {{ trans("$theme-app.head.title_app") }}
@stop


@section('content')

    @php
        $newsletters = (new \App\Models\Newsletter())->getNewslettersNames();
    @endphp

    <script src="https://www.google.com/recaptcha/api.js?hl={{ config('app.locale') }}" async defer></script>

    <section class="container create-account">

        <div class="mb-3">
            <h1>{{ trans("$theme-app.login_register.crear_cuenta") }}</h1>
            <p>{{ trans("$theme-app.login_register.all_fields_are_required") }}</p>
        </div>

        <form id="registerForm" action="{{ route('send_register') }}">
            @csrf

            <div class="row mb-5">
                <div class="col-md-4">
                    <h4>{{ trans("$theme-app.user_panel.personal_info") }}</h4>
                </div>
                <div class="col-md-8">
                    <div class="row gy-1">
                        <div class="col-12">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.particular") }}
                                <select class="form-select" name="pri_emp" onchange="changeRegisterType(this)">
                                    <option value="F" selected>{{ trans("$theme-app.login_register.particular") }}
                                    </option>
                                    <option value="J">{{ trans("$theme-app.login_register.empresa") }}</option>
                                </select>
                            </label>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.genre") }}
                                <select class="form-select" name="sexo">
                                    <option value="H" selected>{{ trans("$theme-app.login_register.genre_mr") }}</option>
                                    <option value="M">{{ trans("$theme-app.login_register.genre_mrs") }}</option>
                                </select>
                            </label>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label register-particular">
                                {{ trans("$theme-app.login_register.nombre") }}
                                {!! $formulario->usuario !!}
                            </label>
                            <label class="form-label register-empresa d-none">
                                {{ trans("$theme-app.login_register.contact") }}
                                {!! $formulario->contact !!}
                            </label>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label register-particular">
                                {{ trans("$theme-app.login_register.apellidos") }}
                                {!! $formulario->last_name !!}
                            </label>
                            <label class="form-label register-empresa d-none">
                                {{ trans("$theme-app.login_register.company") }}
                                {!! $formulario->rsoc_cli !!}
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                {{ trans("$theme-app.user_panel.date_birthday") }}
                                {!! $formulario->fecha_nacimiento !!}
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.language") }}
                                {!! $formulario->language !!}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-md-4">
                    <h4>{{ trans("$theme-app.login_register.personal_information") }}</h4>
                </div>
                <div class="col-md-8">
                    <div class="row gy-1">
                        <div class="col-md-6">
                            <label class="form-label">
                                <span class="register-particular">{{ trans("$theme-app.login_register.dni") }}</span>
                                <span class="register-empresa d-none">{{ trans("$theme-app.login_register.cif") }}</span>
                                {!! $formulario->cif !!}
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.phone") }}
                                {!! $formulario->telefono !!}
                            </label>
                        </div>
                        {{-- <div class="col-md-6">
							<label class="form-label">
								{{ trans("$theme-app.login_register.mobile") }}
								{!! $formulario->movil !!}
							</label>
						</div> --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.pais") }}
                                {!! $formulario->pais !!}
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.cod_postal") }}
                                {!! $formulario->cpostal !!}
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.provincia") }}
                                {!! $formulario->provincia !!}
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.ciudad") }}
                                {!! $formulario->poblacion !!}
                            </label>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.via") }}
                                {!! $formulario->vias !!}
                            </label>
                        </div>
                        <div class="col-md-10">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.direccion") }}
                                {!! $formulario->direccion !!}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Por el momento oculto hasta saber que hacer con estos campos --}}
            <div class="row mb-5 d-none">
                <div class="col-md-4">
                    <h4>Otros</h4>
                </div>
                <div class="col-md-8">
                    <div class="row gy-1">
                        <div class="col-md-6">
                            <label class="form-label">
                                {!! $formulario->condiciones2 !!}
                                {!! trans("$theme-app.login_register.read_conditions2") !!}
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.currency") }}
                                {!! $formulario->divisa !!}
                            </label>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.observacion") }}
                                {!! $formulario->obscli !!}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div @class([
                'row mb-5',
                'd-none' => !config('app.delivery_address', false),
            ])>
                <div class="col-md-4">
                    <h4>{{ trans("$theme-app.login_register.title_direccion_envio") }}</h4>


                </div>
                <div class="col-md-8">
                    <label class="form-check-label">
                        <input class="form-check-input" name="shipping_address" type="checkbox"
                            onchange="handleCheckedAddressShipping(this)" @checked(!config('app.delivery_address', false)) />
                        {{ trans("$theme-app.login_register.utilizar_direcc_direccenv") }}
                    </label>
                    <div class="row mt-2 gy-1" id="js-shipping_address">
                        {!! $formulario->clid !!}

                        <div class="col-md-6">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.pais") }}
                                {!! $formulario->clid_pais !!}
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.cod_postal") }}
                                {!! $formulario->clid_cpostal !!}
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.provincia") }}
                                {!! $formulario->clid_provincia !!}
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.ciudad") }}
                                {!! $formulario->clid_poblacion !!}
                            </label>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.via") }}
                                {!! $formulario->clid_codigoVia !!}
                            </label>
                        </div>
                        <div class="col-md-10">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.direccion") }}
                                {!! $formulario->clid_direccion !!}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-md-4">
                    <h4>{{ trans("$theme-app.login_register.cuenta") }}</h4>
                </div>
                <div class="col-md-8">
                    <div class="row gy-1">
                        <div class="col-md-6">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.email") }}
                                {!! $formulario->email !!}
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.email_confirm") }}
                                {!! $formulario->confirm_email !!}
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.password") }}
                                {!! $formulario->password !!}
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                {{ trans("$theme-app.login_register.confirm_password") }}
                                {!! $formulario->confirm_password !!}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            @if (count($newsletters) > 0)
                <div class="row mb-5">
                    <div class="col-md-4">
                        <h4>{{ trans("$theme-app.login_register.recibir_newsletter") }}</h4>
                    </div>
                    <div class="col-md-8">
                        <div class="row gy-1">

                            @foreach ($newsletters as $id_newsletters => $name_newsletters)
                                <div class="col-12">
                                    <label class="form-check-label">
                                        <input class="form-check-input" id="register_newsletter_{{ $id_newsletters }}"
                                            name="families[{{ $id_newsletters }}]" type="checkbox"
                                            value="{{ $id_newsletters }}">
                                        {{ $name_newsletters }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="row mb-5">
                <div class="col-md-4">
                    <h4>{{ trans("$theme-app.login_register.conditions") }}</h4>
                </div>
                <div class="col-md-8">
                    <div class="row gy-1">
                        <div class="col-12">
                            <label class="form-check-label">
                                {!! $formulario->newsletter !!}
                                {{ trans("$theme-app.login_register.recibir_newsletter") }}
                            </label>
                        </div>
                        <div class="col-12">
                            <label class="form-check-label">
                                {!! $formulario->condiciones !!}
                                {!! trans("$theme-app.login_register.read_conditions") !!}
                                (<a href='{{ Routing::translateSeo('pagina') . trans("$theme-app.links.term_condition") }}'
                                    target="_blank">{{ trans("$theme-app.login_register.more_info") }}
                                </a>)
                            </label>
                        </div>
                        <div class="col-12">
                            <div class="g-recaptcha" data-sitekey="{{ config('app.codRecaptchaEmailPublico') }}"
                                data-callback="onSubmit"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-md-4"></div>
                <div class="col-md-8">
                    <button class="btn btn-lb-primary submitButton" type="submit">
                        <span class="text">{{ trans("$theme-app.login_register.register") }}</span>
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
        <form id="formToSubalia" method="post" action="https://subalia.es/registerclicli">
            <input id="info_sent" name="info" type="hidden" value="">
            <input id="cod_auchouse_sent" name="cod_auchouse" type="hidden" value="">
            <input id="redirect_sent" name="redirect" type="hidden" value="">
        </form>
    @endif

@stop
