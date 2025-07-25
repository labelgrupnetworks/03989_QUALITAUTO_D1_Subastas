@extends('layouts.default')

@push('scripts')
    <script defer src="{{ Tools::urlAssetsCache(public_default_path('js/register.js')) }}"></script>
    <script defer src="{{ Tools::urlAssetsCache("/themes/$theme/js/register.js") }}"></script>
@endpush

@section('title')
    {{ trans("$theme-app.head.title_app") }}
@stop


@section('content')

    @php
        $bread[] = ['name' => 'Registro'];
        $newsletters = (new \App\Models\Newsletter())->getNewslettersNames();
    @endphp

    <main class="register-page">

        {!! BannerLib::bannerWithView('contact-page', 'hero', [
            'title' => 'Registro',
            'breadcrumb' => view('includes.breadcrumb', ['bread' => $bread])->render(),
        ]) !!}

        <section class="container create-account">

            <div class="my-5 py-md-5 text-center">
                <h1 class="register-title">{{ trans("$theme-app.login_register.crear_cuenta") }}</h1>
                <p class="register-subtitle">{{ trans("$theme-app.login_register.all_fields_are_required") }}</p>
            </div>

            <div class="row justify-content-around">
                <div class="col-lg-3 order-2 order-lg-1">
                    <div class="d-flex flex-lg-column flex-wrap gap-3">
                        <div class="flex-grow-1 d-flex gap-3 mb-3 mb-md-5">
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

                        <div class="flex-grow-1 d-flex gap-3 mb-3 mb-md-5">
                            <h4>
                                <x-icon.boostrap icon="telephone-fill" />
                            </h4>
                            <div>
                                <h4 class="fw-500">Telf.</h4>
                                <a class="opacity-50" href="tel:+34915159584">91 515 95 84</a>
                            </div>
                        </div>

                        <div class="flex-grow-1 d-flex gap-3 mb-3 mb-md-5">
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

                        <div class="flex-grow-1 d-flex gap-3 mb-3 mb-md-5">
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
                </div>

                <div class="col-lg-5 order-1 order-lg-2">

                    <form id="registerForm" action="{{ route('send_register') }}">
                        @csrf

                        <input name="captcha_token" data-sitekey="{{ config('app.captcha_v3_public') }}" type="hidden"
                            value="">
                        <input class="form-control" id="sexo" name="sexo" type="hidden" value="H">
                        <input id="select__0__language" name="language" type="hidden"
                            value="{{ strtoupper(\Config::get('app.locale')) }}">


                        <div class="row gy-1">
                            <div class="col-12 mb-3 pb-md-3">
                                <label class="form-label">
                                    {{ trans("$theme-app.login_register.particular") }}
                                    <select class="form-select" name="pri_emp" onchange="changeRegisterType(this)">
                                        <option value="F" selected>
                                            {{ trans("$theme-app.login_register.particular") }}
                                        </option>
                                        <option value="J">{{ trans("$theme-app.login_register.empresa") }}
                                        </option>
                                    </select>
                                </label>
                            </div>

                            <div class="col-12 mb-3 pb-md-3">
                                <label class="form-label register-particular">
                                    {{ trans("$theme-app.login_register.nombre") }}
                                    {!! $formulario->usuario !!}
                                </label>
                                <label class="form-label register-empresa d-none">
                                    {{ trans("$theme-app.login_register.contact") }}
                                    {!! $formulario->contact !!}
                                </label>
                            </div>

                            <div class="col-12 mb-3 pb-md-3">
                                <label class="form-label register-particular">
                                    {{ trans("$theme-app.login_register.apellidos") }}
                                    {!! $formulario->last_name !!}
                                </label>
                                <label class="form-label register-empresa d-none">
                                    {{ trans("$theme-app.login_register.company") }}
                                    {!! $formulario->rsoc_cli !!}
                                </label>
                            </div>

                        </div>

                        <div class="row gy-1">
                            <div class="mb-3 pb-md-3">
                                <label class="form-label">
                                    <span class="register-particular">{{ trans("$theme-app.login_register.dni") }}</span>
                                    <span
                                        class="register-empresa d-none">{{ trans("$theme-app.login_register.cif") }}</span>
                                    {!! $formulario->cif !!}
                                </label>
                            </div>
                            <div class="mb-3 pb-md-3">
                                <label class="form-label">
                                    {{ trans("$theme-app.login_register.phone") }}
                                    {!! $formulario->telefono !!}
                                </label>
                            </div>

                            <div class="mb-3 pb-md-3">
                                <label class="form-label">
                                    {{ trans("$theme-app.login_register.pais") }}
                                    {!! $formulario->pais !!}
                                </label>
                            </div>
                            <div class="mb-3 pb-md-3">
                                <label class="form-label">
                                    {{ trans("$theme-app.login_register.cod_postal") }}
                                    {!! $formulario->cpostal !!}
                                </label>
                            </div>
                            <div class="mb-3 pb-md-3">
                                <label class="form-label">
                                    {{ trans("$theme-app.login_register.provincia") }}
                                    {!! $formulario->provincia !!}
                                </label>
                            </div>
                            <div class="mb-3 pb-md-3">
                                <label class="form-label">
                                    {{ trans("$theme-app.login_register.ciudad") }}
                                    {!! $formulario->poblacion !!}
                                </label>
                            </div>
                            <div class="col-md-3 mb-3 pb-md-3">
                                <label class="form-label">
                                    {{ trans("$theme-app.login_register.via") }}
                                    {!! $formulario->vias !!}
                                </label>
                            </div>
                            <div class="col-md-9 mb-3 pb-md-3">
                                <label class="form-label">
                                    {{ trans("$theme-app.login_register.direccion") }}
                                    {!! $formulario->direccion !!}
                                </label>
                            </div>
                        </div>

                        <div @class([
                            'row mb-5',
                            'd-none' => !config('app.delivery_address', false),
                        ])>
                            <div>
                                <label class="form-check-label">
                                    <input class="form-check-input" name="shipping_address" type="checkbox"
                                        onchange="handleCheckedAddressShipping(this)" checked />
                                    {{ trans("$theme-app.login_register.utilizar_direcc_direccenv") }}
                                </label>
                                <div class="row mt-2 gy-1" id="js-shipping_address">
                                    {!! $formulario->clid !!}

                                    <div class="mb-3 pb-md-3">
                                        <label class="form-label">
                                            {{ trans("$theme-app.login_register.pais") }}
                                            {!! $formulario->clid_pais !!}
                                        </label>
                                    </div>
                                    <div class="mb-3 pb-md-3">
                                        <label class="form-label">
                                            {{ trans("$theme-app.login_register.cod_postal") }}
                                            {!! $formulario->clid_cpostal !!}
                                        </label>
                                    </div>
                                    <div class="mb-3 pb-md-3">
                                        <label class="form-label">
                                            {{ trans("$theme-app.login_register.provincia") }}
                                            {!! $formulario->clid_provincia !!}
                                        </label>
                                    </div>
                                    <div class="mb-3 pb-md-3">
                                        <label class="form-label">
                                            {{ trans("$theme-app.login_register.ciudad") }}
                                            {!! $formulario->clid_poblacion !!}
                                        </label>
                                    </div>
                                    <div class="col-md-3 mb-3 pb-md-3">
                                        <label class="form-label">
                                            {{ trans("$theme-app.login_register.via") }}
                                            {!! $formulario->clid_codigoVia !!}
                                        </label>
                                    </div>
                                    <div class="col-md-9 mb-3 pb-md-3">
                                        <label class="form-label">
                                            {{ trans("$theme-app.login_register.direccion") }}
                                            {!! $formulario->clid_direccion !!}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row gy-1">
                            <div class="mb-3 pb-md-3">
                                <label class="form-label">
                                    {{ trans("$theme-app.login_register.email") }}
                                    {!! $formulario->email !!}
                                </label>
                            </div>
                            <div class="mb-3 pb-md-3">
                                <label class="form-label">
                                    {{ trans("$theme-app.login_register.email_confirm") }}
                                    {!! $formulario->confirm_email !!}
                                </label>
                            </div>
                            <div class="mb-3 pb-md-3">
                                <label class="form-label">
                                    {{ trans("$theme-app.login_register.password") }}
                                    {!! $formulario->password !!}
                                </label>
                            </div>
                            <div class="mb-3 pb-md-3">
                                <label class="form-label">
                                    {{ trans("$theme-app.login_register.confirm_password") }}
                                    {!! $formulario->confirm_password !!}
                                </label>
                            </div>
                        </div>

                        @if (count($newsletters) > 0)
                            <div class="row gy-1">
                                @foreach ($newsletters as $id_newsletters => $name_newsletters)
                                    <div class="mb-3 pb-md-3">
                                        <label class="form-check-label">
                                            <input class="form-check-input"
                                                id="register_newsletter_{{ $id_newsletters }}"
                                                name="families[{{ $id_newsletters }}]" type="checkbox"
                                                value="{{ $id_newsletters }}">
                                            {{ $name_newsletters }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="row gy-1">
                            <div class="mb-3">
                                <label class="form-check-label">
                                    {!! $formulario->newsletter !!}
                                    {{ trans("$theme-app.login_register.recibir_newsletter") }}
                                </label>
                            </div>
                            <div class="mb-3">
                                <label class="form-check-label">
                                    {!! $formulario->condiciones !!}
                                    <span class="fw-bold text-danger">*</span>
                                    <span>
                                        {!! trans("$theme-app.login_register.read_conditions") !!}
                                        (<a href='{{ Routing::translateSeo('pagina') . trans("$theme-app.links.term_condition") }}'
                                            target="_blank">{{ trans("$theme-app.login_register.more_info") }}
                                        </a>)
                                    </span>
                                </label>
                            </div>

                            <div class="mb-3 pb-md-3">
                                <p class="captcha-terms">
                                    {!! trans("$theme-app.global.captcha-terms") !!}
                                </p>
                            </div>
                        </div>

                        <div class="mb-5">
                            <button class="btn btn-lb-primary px-md-5 submitButton" type="submit">
                                <span class="text">{{ trans("$theme-app.login_register.register") }}</span>
                                <div class="spinner spinner-1 m-auto"></div>
                            </button>
                        </div>

                        @if (!empty($formulario->subalia))
                            {!! $formulario->subalia !!}
                            {!! $formulario->info !!}
                        @endif
                    </form>

                </div>
            </div>

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
