@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
    //query que mira si hay alguna subasta activa actualmente, si la hay no se pueden registrar 5/1440 es para bloquearlo 5 minutos antes de que empieze
    $sql = "select count(asigl0.ref_asigl0) as cuantos  from \"auc_sessions\" auc
join fgsub sub   on sub.emp_sub=auc.\"company\" and  sub.cod_sub = auc.\"auction\"
join fgasigl0 asigl0 on asigl0.emp_asigl0=auc.\"company\" and asigl0.sub_asigl0 = sub.cod_sub
where
auc.\"company\" = :emp and
sub.subc_sub in ('S') and
sub.tipo_sub = 'W' and

asigl0.ref_asigl0 >= auc.\"init_lot\"    AND
asigl0.ref_asigl0 <=  auc.\"end_lot\" and
 asigl0.cerrado_asigl0 = 'N' and
(auc.\"start\" - (5/1440))
< sysdate  and auc.\"end\" > sysdate";

    $bindings = [
        'emp' => Config::get('app.emp'),
    ];
    $active_lots = DB::select($sql, $bindings);

    $families = [
        2 => trans("$theme-app.user_panel.newsletter_2"),
		6 => trans("$theme-app.user_panel.newsletter_6"),
        4 => trans("$theme-app.user_panel.newsletter_4"),
        3 => trans("$theme-app.user_panel.newsletter_3"),
        5 => trans("$theme-app.user_panel.newsletter_5"),
    ];
@endphp

@section('content')
    @if (!empty($active_lots) && $active_lots[0]->cuantos > 0)
        <section class="principal-bar no-principal body-auctions2">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="princiapl-bar-wrapper">
                            <div class="principal-bar-title ">
                                <h3 class="titlePage" style="text-align:center;">
                                    {{ trans($theme . '-app.login_register.register_blocked') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else

        <script>
            const prefix = @json($jsitem->prefix);
        </script>

        <section class="register-page create-account">
            <div class="container register-container">

                <h1>{{ trans("$theme-app.login_register.register_on") }}</h1>

                <p class="text-center">
                    {{ trans("$theme-app.login_register.fill_out_registration") }}
                </p>

                <form id="registerForm" method="post" action="javascript:submit_register_form()">
                    <input class="form-control" name="_token" type="hidden" value="{{ csrf_token() }}">
					<input type="hidden" data-sitekey="{{ config('app.captcha_v3_public') }}" name="captcha_token" value="">
                    <input class="form-control" id="sexo" name="sexo" type="hidden" value="H">

                    <fieldset>
                        <legend>{{ trans("$theme-app.user_panel.billing_address") }}</legend>

                        <div class="form-group">
                            <label>
                                {{ trans($theme . '-app.login_register.pais') }}
                                {!! $formulario->pais !!}
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                {{ trans("$theme-app.user_panel.user_type") }}
                                <select class="form-control" id="pri_emp" name="pri_emp">
                                    <option value="F">
                                        {{ trans("$theme-app.login_register.particular") }}
                                    </option>
                                    <option value="J">
                                        {{ trans("$theme-app.login_register.empresa") }}
                                    </option>
                                </select>
                            </label>
                        </div>


                        <div class="form-group registerParticular nombre">
                            <label>
                                {{ trans($theme . '-app.login_register.nombre') }}
                                {!! $formulario->usuario !!}
                            </label>
                        </div>
                        <div class="form-group registerParticular apellidos">
                            <label>
                                {{ trans($theme . '-app.login_register.apellidos') }}
                                {!! $formulario->last_name !!}
                            </label>
                        </div>
                        <div class="form-group registerEnterprise rsoc_cli">
                            <label>
                                {{ trans($theme . '-app.login_register.company') }}
                                {!! $formulario->rsoc_cli !!}
                            </label>
                        </div>
                        <div class="form-group registerEnterprise contact">
                            <label>
                                {{ trans($theme . '-app.login_register.contact') }}
                                {!! $formulario->contact !!}
                            </label>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label>

                                            <span class="nif labelDni"
												@if(Config::get('app.locale') != 'es') style="display: none" @endif>
                                                {{ trans("$theme-app.login_register.dni") }}
                                            </span>

                                            <span class="cif labelDni" style="display: none">
                                                {{ trans($theme . '-app.login_register.cif') }}
                                            </span>

                                            <span class="passport labelDni"
											@if(Config::get('app.locale') == 'es') style="display: none" @endif>
                                                {{ trans("$theme-app.login_register.passport") }}
                                            </span>

                                            <span class="vat labelDni" style="display: none">
                                                {{ trans($theme . '-app.login_register.vat') }}
                                            </span>
                                            {!! $formulario->cif !!}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <label>
                                        {{ trans("$theme-app.user_panel.date_birthday") }}
                                        {!! $formulario->fecha_nacimiento !!}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>
                                        {{ trans($theme . '-app.login_register.via') }}
                                        {!! $formulario->vias !!}
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-9">
                                <div class="form-group">
                                    <label>
                                        {{ trans("$theme-app.login_register.direccion") }}
                                        {!! $formulario->direccion !!}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>
                                        {{ trans("$theme-app.login_register.cod_postal") }}
                                        {!! $formulario->cpostal !!}
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-9">
                                <div class="form-group">
                                    <label>
                                        {{ trans("$theme-app.login_register.ciudad") }}
                                        {!! $formulario->poblacion !!}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                {{ trans("$theme-app.login_register.provincia") }}
                                {!! $formulario->provincia !!}
                            </label>
                        </div>

                    </fieldset>

                    @if (!Config::get('app.delivery_address', false))
                        {!! $formulario->clid !!}
                        {!! $formulario->clid_pais !!}
                        {!! $formulario->clid_cpostal !!}
                        {!! $formulario->clid_provincia !!}
                        {!! $formulario->clid_codigoVia !!}
                        {!! $formulario->clid_direccion !!}
                    @else
                        <fieldset>
                            <legend>{{ trans("$theme-app.login_register.title_direccion_envio") }}</legend>

                            <div>
                                <div class="checkbox">
                                    <label>
                                        <input name="shipping_address" type="checkbox" value="1"
                                            checked="true" />
                                        {{ trans("$theme-app.login_register.utilizar_direcc_direccenv") }}
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="shipping_address" value="2"
                                            type="checkbox" />
                                        {{ trans("$theme-app.login_register.add_addres") }}
                                    </label>
                                </div>
                            </div>

                            <div class="collapse" id="collapse_d" aria-expanded="true">

                                {!! $formulario->clid !!}
                                {!! \FormLib::Hidden('usuario_clid', 0) !!}

                                <div class="form-group">
                                    <label>
                                        Alias
                                        {!! FormLib::Text('rsoc2_clid', 0, $address->rsoc2_clid ?? '', 'maxlength="40"') !!}
                                    </label>
                                </div>

                                <div class="form-group nombre">
                                    <label>
                                        {{ trans($theme . '-app.login_register.nombre') }}
                                        {!! \FormLib::Text('name_clidTemp', 0) !!}
                                    </label>
                                </div>

                                <div class="form-group apellidos">
                                    <label>
                                        {{ trans($theme . '-app.login_register.apellidos') }}
                                        {!! \FormLib::Text('lastName_clidTemp', 0) !!}
                                    </label>
                                </div>

                                <div class="row">
                                    <div class="col-xs-3 clid-via"
                                        style="@if (\Config::get('app.locale') != 'es') display: none; @endif">
                                        <div class="form-group">
                                            <label>
                                                {{ trans($theme . '-app.login_register.via') }}
                                                {!! $formulario->clid_codigoVia !!}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="form-group">
                                            <label>
                                                {{ trans("$theme-app.login_register.direccion") }}
                                                {!! $formulario->clid_direccion !!}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>
                                                {{ trans("$theme-app.login_register.cod_postal") }}
                                                {!! $formulario->clid_cpostal !!}
                                            </label>
                                        </div>

                                    </div>
                                    <div class="col-xs-9">
                                        <div class="form-group">
                                            <label>
                                                {{ trans("$theme-app.login_register.ciudad") }}
                                                {!! $formulario->clid_poblacion !!}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label>
                                                {{ trans("$theme-app.login_register.provincia") }}
                                                {!! $formulario->clid_provincia !!}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label>{{ trans($theme . '-app.login_register.pais') }}
                                                {!! $formulario->clid_pais !!}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row phone_address">
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>
                                                {{ trans("$theme-app.login_register.prefix") }}
                                                {!! \FormLib::Int('preftel_clid', 0, '') !!}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="form-group">
                                            <label>
                                                {{ trans("$theme-app.login_register.phone") }}
                                                {!! \FormLib::Int('tele_clid', 0, '') !!}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>{{ trans("$theme-app.login_register.contact") }}</legend>

                            <div class="row">
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label>
                                            {{ trans("$theme-app.login_register.prefix") }}
                                            {!! $formulario->prefix !!}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-9">
                                    <div class="form-group">
                                        <label>
                                            {{ trans("$theme-app.login_register.phone") }}
                                            {!! $formulario->telefono !!}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>
                                    {{ trans("$theme-app.user_panel.email") }}
                                    {!! $formulario->email !!}
                                </label>
                            </div>
                            <div class="form-group">
                                <label>
                                    {{ trans($theme . '-app.login_register.email_confirmacion') }}
                                    {!! $formulario->confirm_email !!}
                                </label>
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>{{ trans("$theme-app.login_register.preferences") }}</legend>

                            <div class="form-group moneda">
                                <label>
                                    {{ trans($theme . '-app.login_register.currency') }}
                                    {!! $formulario->divisa !!}
                                </label>
                            </div>

                            <div class="form-group idioma">
                                <label>
                                    {{ trans($theme . '-app.login_register.language') }}
                                    {!! $formulario->language !!}
                                </label>
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>{{ trans("$theme-app.user_panel.preferred_auction") }}</legend>

                            @foreach ($families as $key => $item)
                                <div class="checkbox">
                                    <label>
                                        <input name="families[]" type="checkbox" value="{{ $key }}" @checked($key == 2)>
                                        {{ $item }}
                                    </label>
                                </div>
                            @endforeach
                        </fieldset>

                        <fieldset>
                            <legend>{{ trans($theme . '-app.login_register.observacion') }} *</legend>

                            <div class="form-group observaciones">
                                {!! $formulario->obscli !!}
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>{{ trans($theme . '-app.login_register.cuenta') }} *</legend>

                            <div class="form-group has-feedback">
                                <label>
                                    {{ trans($theme . '-app.login_register.password') }}

                                    <div class="input-group">
                                        {!! $formulario->password !!}
                                    </div>
                                </label>
                            </div>

                            <div class="form-group has-feedback">
                                <label>
                                    {{ trans($theme . '-app.login_register.confirm_password') }}
									<div class="input-group">
                                    {!! $formulario->confirm_password !!}
									</div>
                                </label>
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>{{ trans("$theme-app.login_register.others") }}</legend>

                            <div class="checkbox">
                                <label>
                                    <input id="bool_0_newsletter" name="newsletter" type="checkbox" checked="true">
                                    {{ trans("$theme-app.login_register.i_wish_receive_newsletter") }}
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input id="bool__1__condiciones" name="condiciones" type="checkbox" checked="true">
                                    <span>{!! trans($theme . '-app.login_register.read_conditions') !!}
                                        (<a href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.term_condition') }}"
                                            target="_blank">{{ trans($theme . '-app.login_register.more_info') }}</a>)</span>
                                </label>
                            </div>
                        </fieldset>

                        <div>
                            <noscript>
                                <p class="error-javascript">
                                    {{ trans("$theme-app.login_register.enable-javascript") }}</p>
                            </noscript>

                            <div class="mt-1 mb-2">
								<p class="captcha-terms">
									{!! trans("$theme-app.global.captcha-terms") !!}
								</p>
							</div>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-primary submitButton" type="submit">
                                {{ trans($theme . '-app.login_register.register') }}
                            </button>
                        </div>

                        @if (!empty($formulario->subalia))
                            <fieldset>
                                <legend>Subalia</legend>
                                {!! $formulario->subalia !!}
                                {!! $formulario->info !!}
                            </fieldset>
                        @endif

                    @endif
                </form>

                @if (!empty($formulario->subalia))
                    @if (\Config::get('app.locale') == 'en')
                        <form id="formToSubalia" method="post" action="https://subalia.es/registerclicli">
                        @else
                            <form id="formToSubalia" method="post" action="https://subalia.es/registerclicli">
                    @endif
                    <input id="info_sent" name="info" type="hidden" value="">
                    <input id="cod_auchouse_sent" name="cod_auchouse" type="hidden" value="">
                    <input id="redirect_sent" name="redirect" type="hidden" value="">
                    </form>
                @endif


            </div>
        </section>
    @endif

@stop
