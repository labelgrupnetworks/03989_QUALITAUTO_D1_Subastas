@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
<?php
//AÑADIR TEXTOS DE PLACEHOLDERS
?>
<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>

<div class="create-account color-letter">

    <div class="container">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-lg-9 col-md-10 col-xs-12 general-container">

                <div id="register-button-back" class="button-back" style="display: none"></div>
                <div class="create-account-container">

                    <form method="post" id="registerForm" action="javascript:submit_register_form()">

                        <input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input class="form-control" type="hidden" name="sexo" id="sexo" value="H">
                        <input class="form-control" type="hidden" name="pri_emp" id="pri_emp" value="F">

                        <div class="create-account-title d-flex justify-content-space-between">
                            <span>{{ trans(\Config::get('app.theme').'-app.login_register.crear_cuenta') }}</span>

                            <div class="inputs-type-person d-flex justify-content-space-between">

                                <div class="input-group-addon-custom form-group particular">
                                    <input class="form-control" style="display: none;" id="inlineCheckbox1" type="radio" checked/>
                                    <label onclick="javascript:particular();"  class="input-check-custom" for="inlineCheckbox1">{{ trans(\Config::get('app.theme').'-app.login_register.particular') }}</label>
                                </div>
                                <div class="form-group input-group-addon-custom empresa">
                                    <input class="form-control" style="display: none;" id="inlineCheckbox2" type="radio" >
                                    <label onclick="javascript:empresa();" class="input-check-custom" for="inlineCheckbox2">{{ trans(\Config::get('app.theme').'-app.login_register.empresa') }}</label>
                                </div>

                            </div>
                        </div>

                        <div class="create-account-stepper">
                            <div class="stepper-container d-flex align-items-center justify-content-space-around">
                                <div id="step1" class="number actual d-flex align-items-center justify-content-center" style="display:none">1</div>
                                <div class="divider-stepper"></div>
                                <div style="display:none" id="step2" class="number d-flex align-items-center justify-content-center">2</div>
                            </div>
                        </div>

                        <div class="create-account-wrapper" style="position:relative">
                            <div class="create-account-personal-info personal-info">

                                <div class="gener-group">
                                    <div class="input-gener flex-wrap d-flex align-items-center justify-content-center form-group">
                                        <div>
                                            <input class="form-control" style="display: none;" id="inlineRadio1" value="H" name="sexo" checked="checked" type="radio">
                                            <label class="input-check-custom" for="inlineRadio1">{{ trans(\Config::get('app.theme').'-app.login_register.hombre') }} </label>
                                        </div>
                                        <div>
                                            <input class="form-control" style="display: none;" id="inlineRadio2" name="sexo" value="M" type="radio">
                                            <label class="input-check-custom" for="inlineRadio2">{{ trans(\Config::get('app.theme').'-app.login_register.mujer') }}</label>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 input-group flex-wrap date date-component d-flex justify-content-flex-end form-group" data-provide="datepicker">
                                        <label class="label-date" for="nombre">{{ trans(\Config::get('app.theme').'-app.user_panel.date_birthday') }}</label>
                                        {!!$formulario->fecha_nacimiento!!}
                                    </div>
                                </div>

                                <div class="inputs-custom-group d-flex justify-content-space-between flex-wrap">

                                    <div class="form-group input-group name_client registerParticular col-xs-12 col-sm-6">
                                        <label class="" for="nombre">{{ trans(\Config::get('app.theme').'-app.login_register.nombre') }}</label>
                                        {!!$formulario->usuario!!}
                                    </div>
                                    <div class="form-group input-group name_client registerParticular col-xs-12 col-sm-6">
                                        <label class="" for="nombre">{{ trans(\Config::get('app.theme').'-app.login_register.apellidos') }}</label>
                                        {!!$formulario->last_name!!}
									</div>
									<div class="form-group input-group name_client registerParticular col-xs-12 col-sm-6">
                                        <label class="" for="nombre">{{ trans(\Config::get('app.theme').'-app.login_register.representar') }}</label>
                                        <select data-placement="right" class="form-control select2" type="select" name="representar" id="select__1__representar"
											onblur="comprueba_campo(this)">
											<option value="N">{{ trans(\Config::get('app.theme').'-app.login_register.no') }}</option>
											<option value="S">{{ trans(\Config::get('app.theme').'-app.login_register.yes') }}</option>
										</select>
									</div>

                                    <div class="form-group input-group registerEnterprise col-xs-12 col-sm-6 mt-3">
                                        <label class="" for="">{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}</label>
                                        {!!$formulario->contact!!}
									</div>
									<div class="form-group input-group rsoc_inputgroup col-xs-12 col-sm-6">
                                        <label class="" for="">{{ trans(\Config::get('app.theme').'-app.login_register.company') }}</label>
                                        {!!$formulario->rsoc_cli!!}
									</div>

                                    <div class="form-group input-group col-xs-12 col-sm-4">
                                        <label class="" for="telefono">{{ trans(\Config::get('app.theme').'-app.login_register.phone') }}</label>
                                        {!!$formulario->telefono!!}
                                    </div>
                                    <div  class="form-group input-group col-xs-12 col-sm-8 datos_contacto">
                                        <label for="nif" class="nif dni_txt">{{ trans(\Config::get('app.theme').'-app.login_register.dni') }}</label>
                                        <label for="nif" class="cif cif_txt">{{ trans(\Config::get('app.theme').'-app.login_register.cif') }}</label>
                                        {!!$formulario->cif!!}
                                    </div>

                                    <div class="form-group input-group col-xs-12 col-sm-10">
                                        <label>{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</label>
                                        {!!$formulario->pais!!}
                                    </div>
                                    <div class="form-group input-group col-xs-12 col-sm-2">
                                        <label>{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}</label>
                                        {!!$formulario->cpostal!!}
                                    </div>

                                    <div class="form-group input-group col-xs-12 col-sm-6">
                                        <label>{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}</label>
                                        {!!$formulario->poblacion!!}
                                    </div>
                                    <div class="form-group input-group col-xs-12 col-sm-6">
                                        <label>{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
                                        {!!$formulario->provincia!!}
                                    </div>

                                    <div class="form-group input-group col-xs-12 col-sm-3">
                                        <label>{{ trans(\Config::get('app.theme').'-app.login_register.via') }}</label>
                                        {!!$formulario->vias!!}
                                    </div>
                                    <div class="form-group input-group col-xs-12 col-sm-9">
                                        <label>{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}</label>
                                        {!!$formulario->direccion!!}
                                    </div>

                                </div>


                                <div class="inputs-custom-group inputs-account personal-data-inputs flex-wrap">
                                    <div class="new-user-title col-xs-12">Crea tu usuario</div>
                                    <label id="erroremail" class="erroremail text-danger col-xs-12 hidden" ></label>
                                    <label id="error-confirm-email" class="error-confirm text-danger col-xs-12" style="display: none">{{ trans(\Config::get('app.theme').'-app.msg_error.email_confirm') }}</label>
                                    <div class="form-group form-group-custom col-xs-12 col-sm-6">
                                        <label class="" for="email">{{ trans(\Config::get('app.theme').'-app.login_register.email') }}</label>
                                        {!!$formulario->email!!}
                                    </div>
                                    <div class="form-group form-group-custom col-xs-12 col-sm-6">
                                        <label class="" for="emailconfirm">{{ trans(\Config::get('app.theme').'-app.login_register.email_confirmacion') }}</label>
                                        {!!$formulario->confirm_email!!}
                                    </div>
                                    <div class="form-group form-group-custom col-xs-12 col-sm-6">
                                        <label class="" for="contrasena">{{ trans(\Config::get('app.theme').'-app.login_register.password') }}</label>
                                        {!!$formulario->password!!}
                                    </div>
                                    <div class="form-group form-group-custom col-xs-12 col-sm-6">
                                        <label class="" for="confirmcontrasena">{{ trans(\Config::get('app.theme').'-app.login_register.confirm_password') }}</label>
                                        {!!$formulario->confirm_password!!}
                                    </div>
                                    <small id="error-confirm-pass" class="error-confirm text-danger" style="display: none">{{ trans(\Config::get('app.theme').'-app.msg_error.pass_confirm') }}</small>
                                </div>
                            </div>


                            <div class="buttons-login-content-form-back principal-button col-xs-12" style="padding: 0px 40px;">
                                <div class="create-account-accept-condition">

                                    <br>
                                    <div class="check_term">

                                        <div class="d-flex align-items-center">
                                            {!!$formulario->newsletter!!}
                                            <label for="bool__1__condiciones">{{ trans(\Config::get('app.theme').'-app.emails.accept_news') }}</label>
                                        </div>

                                        <div class="d-flex align-items-center">
                                            {!! $formulario->condiciones!!}
                                            <label for="bool__1__condiciones"><?= trans(\Config::get('app.theme') . '-app.emails.privacy_conditions') ?></label>
                                        </div>

                                    </div>

                                    <br>


                                </div>
                                <div class="col-xs-12 p-0">
                                    <div class="g-recaptcha" data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}" data-callback="onSubmit"></div>
                                </div>
                                <div class="buttons col-xs-12 p-0">
                                    <button type="submit" class="btn btn-primary submitButton">
                                        {{ trans(\Config::get('app.theme').'-app.login_register.finalizar') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</form>
<p class="error-form-validation hidden">{{ trans(\Config::get('app.theme').'-app.login_register.all_fields_are_required') }}</p>




<script>
$(document).ready(function(){
    $('input[type="text"]').each(function(){

        $(this).attr('placeholder', $(this).siblings('label').text())
    })

    $('input[type="password"]').each(function(){

$(this).attr('placeholder', $(this).siblings('label').text())
})

})
</script>

@stop
