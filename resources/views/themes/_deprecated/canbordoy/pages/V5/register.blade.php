@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop




@section('content')

<?php
$data['families'] = array();
$data['families'][2] = "Próximas subastas";
$data['families'][3] = "Abanicos";
$data['families'][4] = "Alfombras";
$data['families'][5] = "Cerámica";
$data['families'][6] = "Contemporáneo";
$data['families'][7] = "Cristal";
$data['families'][8] = "Escultura";
$data['families'][9] = "Joyas";
$data['families'][10] = "Lámparas";
$data['families'][11] = "Miniaturas";
$data['families'][12] = "Muebles";
$data['families'][13] = "Oriental";
$data['families'][14] = "Pintura";
$data['families'][15] = "Plata";
$data['families'][16] = "Porcelana";
$data['families'][17] = "Relojes";
$data['families'][18] = "Tapices";
$data['families'][19] = "Varios";
?>

<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>

<div class="create-account color-letter">
    <div class="container register pb-5">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-xs-12 general-container">

                <div class="title-register">
                    <p class="text-center">{{ trans(\Config::get('app.theme').'-app.login_register.crear_cuenta') }}</p>
                </div>

                <div class="col-xs-12 contact-page-form">
                    <form method="post" id="registerForm" data-toggle="validator" action="javascript:submit_register_form()">


                        <div class="create-account-title d-flex justify-content-center">
                            <div class="inputs-type-person d-flex justify-content-space-between">
                                <div class="input-group-addon-custom form-group">
                                    <input class="form-control tipo_empresa" style="display: none;" id="inlineCheckbox1" name="pri_emp" value="F" checked="checked" type="radio"> 
                                    <label class="input-check-custom" for="inlineCheckbox1">{{ trans(\Config::get('app.theme').'-app.login_register.particular') }}</label>
                                </div>
                                <div class="form-group input-group-addon-custom ">
                                    <input class="form-control tipo_empresa" style="display: none;" id="inlineCheckbox2" name="pri_emp" value="J" type="radio"> 
                                    <label class="input-check-custom" for="inlineCheckbox2">{{ trans(\Config::get('app.theme').'-app.login_register.empresa') }}</label>                 
                                </div>
                            </div>
                        </div>


                        <input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">
                        {!!$data['formulario']->language!!}

                        <div class="subtitle-register">
                            <p>{{ trans(\Config::get('app.theme').'-app.login_register.info_personal') }}</p>
                        </div>

                        <div id="registerParticular" class="form-group">
                            <div class="input-effect col-xs-12 col-md-6">
                                {!!$data['formulario']->usuario!!}
                                <label for="nombre">{{ trans(\Config::get('app.theme').'-app.login_register.nombre') }}</label>

                            </div>
                            <div class="input-effect col-xs-12 col-md-6">
                                {!!$data['formulario']->last_name!!}
                                <label for="apellido">{{ trans(\Config::get('app.theme').'-app.login_register.apellidos') }}</label>
                            </div>
                        </div>
                        <div id="registerEnterprise" class="form-group">

                            <div class="input-effect col-xs-12 col-md-6">
                                {!!$data['formulario']->rsoc_cli!!}
                                <label for="">{{ trans(\Config::get('app.theme').'-app.login_register.company') }}</label>
                            </div>

                            <div class="input-effect col-xs-12 col-md-6">
                                {!!$data['formulario']->contact!!}
                                <label for="">{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}</label>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="form-group" id="personalinfo">

                            <div class="input-effect col-xs-12 col-md-3">
                                {!!$data['formulario']->sexo!!}
                            </div>

                            <div class="input-effect col-xs-12 col-md-3">
                                {!!$data['formulario']->telefono!!}
                                <label for="telefono">{{ trans(\Config::get('app.theme').'-app.login_register.phone') }}</label>
                            </div>

                            <div class="input-effect col-xs-12 col-md-3">
                                {!!$data['formulario']->cif!!}
                                <label for="cif">{{ trans(\Config::get('app.theme').'-app.login_register.dni') }}</label>
                            </div>

                            <div class="input-effect col-xs-12 col-md-3">
                                {!!$data['formulario']->fecha_nacimiento!!}


                            </div>

                        </div>

                        <div class="clearfix"></div>
                        <br>

                        <div class="subtitle-register">
                            <p>{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}</p>
                        </div>

                        <div class="form-group">

                            <div class="input-effect col-xs-12 col-md-6">
                                {!!$data['formulario']->pais!!}
                            </div>
                            <div class="input-effect col-xs-12 col-md-6">
                                {!!$data['formulario']->cpostal!!}
                                <label for="cpostal">{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}</label>
                            </div>

                            <div class="input-effect col-xs-12 col-md-6">
                                {!!$data['formulario']->poblacion!!}
                                <label for="poblacion">{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}</label>
                            </div>
                            <div class="input-effect col-xs-12 col-md-6">
                                {!!$data['formulario']->provincia!!}
                                <label for="provincia">{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
                            </div>

                            <div class="input-effect col-xs-12 col-md-3">
                                {!!$data['formulario']->vias!!}
                            </div>
                            <div class="input-effect col-xs-12 col-md-9">
                                <label for="direccion">{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}</label>
                                {!!$data['formulario']->direccion!!}
                            </div>


                        </div>

                        <div class="clearfix"></div>
                        <br>


                        <!-- Bloque para dirección de envio -->

                        @if(empty(\Config::get('app.shipping_address')) || !\Config::get('app.shipping_address'))

                        {!!$data['formulario']->clid!!}
                        {!!$data['formulario']->clid_pais!!}
                        {!!$data['formulario']->clid_cpostal!!}
                        {!!$data['formulario']->clid_provincia!!}
                        {!!$data['formulario']->clid_codigoVia!!}
                        {!!$data['formulario']->clid_direccion!!}

                        @else

                        <div class="subtitle-register">
                            <p>{{ trans(\Config::get('app.theme').'-app.login_register.title_direccion_envio') }}</p>
                        </div>

                        <div class="ship-check mt-2">
                            <input 
                                id="shipping_address" 
                                class="form-control filled-in"
                                name="shipping_address" 
                                type="checkbox"
                                checked="ture"
                                />
                            <label for="shipping_address">{{ trans(\Config::get('app.theme').'-app.login_register.utilizar_direcc_direccenv') }}</label>
                        </div>

                        <div class="form-group collapse" id="collapse_direccion" aria-expanded="true">
                            {!!$data['formulario']->clid!!}
                            <div class="input-effect col-xs-12 col-md-6">
                                {!!$data['formulario']->clid_pais!!}
                            </div>
                            <div class="input-effect col-xs-12 col-md-6">
                                {!!$data['formulario']->clid_cpostal!!}
                                <label for="cpostal">{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}</label>
                            </div>

                            <div class="input-effect col-xs-12 col-md-6">
                                {!!$data['formulario']->clid_poblacion!!}
                                <label for="poblacion">{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}</label>
                            </div>
                            <div class="input-effect col-xs-12 col-md-6">
                                {!!$data['formulario']->clid_provincia!!}
                                <label for="provincia">{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
                            </div>

                            <div class="input-effect col-xs-12 col-md-3">
                                {!!$data['formulario']->clid_codigoVia!!}
                            </div>
                            <div class="input-effect col-xs-12 col-md-9">
                                {!!$data['formulario']->clid_direccion!!}
                                <label for="direccion">{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}</label>
                            </div>


                        </div>
                        <div class="clearfix"></div>
                        <br>
                        @endif


                        <div class="subtitle-register">
                            <p>{{ trans(\Config::get('app.theme').'-app.login_register.credentials') }}</p>
                        </div>

                        <div class="form-group">

                            <div class="input-effect col-xs-12 col-md-6">
                                {!!$data['formulario']->email!!}
                                <label for="email">{{ trans(\Config::get('app.theme').'-app.login_register.email') }}</label>
                            </div>
                            <div class="input-effect col-xs-12 col-md-6">
                                {!!$data['formulario']->confirm_email!!}
                                <label for="email">{{ trans(\Config::get('app.theme').'-app.login_register.email_confirm') }}</label>
                            </div>


                            <input style="display:none" type="password">

                            <div class="input-effect col-xs-12 col-md-6" >
                                {!!$data['formulario']->password!!}
                                <label for="contrasena">{{ trans(\Config::get('app.theme').'-app.login_register.password') }}</label>
                            </div>

                            <div class="input-effect col-xs-12 col-md-6" >
                                {!!$data['formulario']->confirm_password!!}
                                <label for="confirmcontrasena">{{ trans(\Config::get('app.theme').'-app.login_register.confirm_password') }}</label>
                            </div>


                        </div>

                        <div class="clearfix"></div>
                        <br>
                        <div class="subtitle-register"></div>
                        <br><br>

                        <!-- con categorias newsletter -->
                        @if(!empty($data['families']) && count($data['families']) > 0)
                        <div class="subtitle-register mb-2">
                            <p>Newsletter - Recibir información de:</p>
                        </div>

                        <fieldset>

                            @for ($t=2; $t <= count($data['families'])+1 ;$t++) 

                            <div class="ship-check col-xs-6 col-md-3">
                                <input type="checkbox" class="form-control filled-in" name="families[]" value="1" id="{{$t}}"> 
                                <label>{{ $data['families'][$t] }}</label>
                            </div>

                            @endfor


                        </fieldset>


                        <div class="clearfix"></div>
                        <br>
                        <div class="subtitle-register"></div>
                        <br><br>
                        @endif


                        <div class="col-xs-12 col-md-8">

                            <!-- sin categorias newsletter -->
                            @if(empty($data['families']))
                            <div class="row">
                                <div class="col-xs-1 text-center">{!! $data['formulario']->newsletter!!}</div>
                                <div class="col-xs-11">
                                    <label for="bool__0__newsletter">{{ trans(\Config::get('app.theme').'-app.login_register.recibir_newsletter') }}</label>
                                    <br><br>
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-xs-1 text-center">{!! $data['formulario']->condiciones!!}</div>
                                <div class="col-xs-11">
                                    <label for="bool__1__condiciones">{{ trans(\Config::get('app.theme').'-app.login_register.read_conditions') }} (<a href="<?php echo Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.term_condition') ?>" target="_blank">{{ trans(\Config::get('app.theme').'-app.login_register.more_info') }}</a>)
                                    </label>
                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="g-recaptcha"
                                 data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"
                                 data-callback="onSubmit"
                                 >
                            </div>

                        </div>

                        <div class="clearfix"></div>

                        <br>
                        <div class="subtitle-register"></div>
                        <br><br>

                        @if (\Config::get("app.coregistroSubalia"))
                        <div class="row">
                            <div class="col-xs-12 col-sm-3 text-center">
                                <img src="<?= URL::asset('/themes/' . $theme . '/assets/img/subalia.png') ?>" width="75%">
                            </div>
                            <div class="col-xs-12 col-sm-9">
                                {{ trans(\Config::get('app.theme').'-app.login_register.coregistroSubalia') }}
                            </div>
                        </div>

                        <br><br>
                        <div class="row">
                            <div class="col-xs-1 text-center">{!! $data['formulario']->condicionesSubalia!!}</div>
                            <div class="col-xs-11">
                                <label for="bool__1__condicionesSubalia">{{ trans(\Config::get('app.theme').'-app.login_register.read_conditions_subalia') }} (<a href="http://www.subalia.test<?php echo Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.term_condition') ?>" target="_blank">{{ trans(\Config::get('app.theme').'-app.login_register.more_info') }}</a>)
                                </label>

                                <br>
                            </div>
                        </div>

                        <div class="subtitle-register"></div>
                        <br><br>

                        @endif

                        <div class="col-xs-12 text-center">
                            <button type="submit" class="button-principal  submitButton">{{ trans(\Config::get('app.theme').'-app.login_register.register') }}</button>
                        </div>

                        <div class="clearfix"></div>

                        <br><br><br><br><br><br>


                    </form>
                </div>
            </div>
        </div>
    </div>

    @stop
