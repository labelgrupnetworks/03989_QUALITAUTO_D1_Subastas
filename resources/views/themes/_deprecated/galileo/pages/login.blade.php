@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop




@section('content')
<div class="create-account color-letter">
    <div class="container">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-lg-8 col-md-9 col-xs-12 general-container">
                    <div id="register-button-back" class="button-back" style="display: none"><</div>
                <div class="create-account-container">
                        <form method="post" class="frmLogin" id="frmRegister-adv" data-toggle="validator">
                        <input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="create-account-title d-flex justify-content-space-between">
                        <span>{{ trans(\Config::get('app.theme').'-app.login_register.crear_cuenta') }}</span>
                        <div class="inputs-type-person d-flex justify-content-space-between">
                            <div class="input-group-addon-custom form-group">
                                <input 
                                    class="form-control  change_job" 
                                    style="display: none;" 
                                    id="inlineCheckbox1" 
                                    name="pri_emp" 
                                    value="F" 
                                    checked="checked" 
                                    type="radio" 
                                /> 
                                <label class="input-check-custom" for="inlineCheckbox1">{{ trans(\Config::get('app.theme').'-app.login_register.particular') }}</label>
                            </div>
                            <div class="form-group input-group-addon-custom ">
                                <input class="form-control change_job" style="display: none;" id="inlineCheckbox2" name="pri_emp" value="J" type="radio"> 
                                <label class="input-check-custom" for="inlineCheckbox2">{{ trans(\Config::get('app.theme').'-app.login_register.empresa') }}</label>                 
                            </div>
                        </div>
                    </div>
                    <div class="create-account-stepper">
                        <div class="stepper-container d-flex align-items-center justify-content-space-around">
                            <div id="step1" class="number actual d-flex align-items-center justify-content-center">1</div>     
                                <div class="divider-stepper"></div>     
                                    <div id="step2" class="number d-flex align-items-center justify-content-center">2</div>     
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
                                        <input name="date" class="form-control input-custom-group" type="date" required="" id="datetimepicker">
                                    </div>           
                            </div>
                            <div class="inputs-custom-group d-flex justify-content-space-between flex-wrap">
                                <div class="form-group input-group name_client col-xs-12 col-sm-6">
                                    <label class="" for="nombre">{{ trans(\Config::get('app.theme').'-app.login_register.nombre') }}</label>
                                    <input  onkeypress="return noComa(event)" class="form-control" id="nombre" name="usuario" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.nombre') }}" required="" type="text">
                                </div>
                                 <div class="form-group input-group name_client col-xs-12 col-sm-6">
                                     <label class="" for="nombre">{{ trans(\Config::get('app.theme').'-app.login_register.apellidos') }}</label>
                                    <input  onkeypress="return noComa(event)" class="form-control" id="last_name" name="last_name" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.apellidos') }}" required="" type="text">
                                </div>
                                <div class="form-group input-group hidden rsoc_cli col-xs-12 col-sm-6">
                                    <input class="form-control" name="rsoc_cli" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.company') }}" type="text">
                                    <label class="" for="">{{ trans(\Config::get('app.theme').'-app.login_register.company') }}</label>
                                </div>
                                <div class="form-group input-group hidden rsoc_cli col-xs-12 col-sm-6">
                                    <label class="" for="">{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}</label>
                                    <input class="form-control" name="contact" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}" type="text">
                                </div>
                                <div class="form-group input-group col-xs-12 col-sm-4">
                                    <label class="" for="telefono">{{ trans(\Config::get('app.theme').'-app.login_register.phone') }}</label>
                                    <input 
                                    name="telefono" 
                                    class="form-control" 
                                    placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.phone') }}" 
                                    required="" 
                                    maxlength="40" 
                                    type="text"
                                    />
                                </div>      
                                <div  class="form-group input-group col-xs-12 col-sm-8">
                                    <label for="nif" class="dni_txt ">{{ trans(\Config::get('app.theme').'-app.login_register.dni') }}</label>
                                    <label for="nif" class="cif_txt">{{ trans(\Config::get('app.theme').'-app.login_register.cif') }}</label>

                                    <input 
                                    id="dni" 
                                    placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.dni') }}" 
                                    class="form-control dni" 
                                    size="10" 
                                    name="nif" 
                                    title="Formato del NIF/NIE(12345678A/X1234567A)" 
                                    required="" 
                                    type="text" 
                                    />
                                    </div>
                                </div>
                                

                                <div class="inputs-custom-group inputs-account personal-data-inputs flex-wrap">
                                        <div class="new-user-title col-xs-12">Crea tu usuario</div>
                                        <label id="erroremail" class="erroremail text-danger col-xs-12 hidden" ></label>
                                         <label id="error-confirm-email" class="error-confirm text-danger col-xs-12" style="display: none">{{ trans(\Config::get('app.theme').'-app.msg_error.email_confirm') }}</label>
                                    <div class="form-group form-group-custom col-xs-12 col-sm-6">
                                        <label class="" for="email">{{ trans(\Config::get('app.theme').'-app.login_register.email') }}</label>
                                        <input 
                                        class="form-control" 
                                        id="email" 
                                        placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.email') }}" 
                                        name="email" 
                                        required="" 
                                        type="text"
                                        />
                                    </div>
                                    <div class="form-group form-group-custom col-xs-12 col-xs-6">
                                        <label class="" for="emailconfirm">{{ trans(\Config::get('app.theme').'-app.login_register.email_confirmacion') }}</label>
                                        <input data-match="#email"
                                        class="form-control" 
                                        id="emailconfirm" 
                                        placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.email_confirmacion') }}" 
                                        required="" 
                                        type="text" 
                                        />
                                    </div>
                                    <div class="form-group form-group-custom col-xs-12 col-xs-6">
                                        <label class="" for="contrasena">{{ trans(\Config::get('app.theme').'-app.login_register.password') }}</label>
                                        <input 
                                        maxlength="20" 
                                        id="password" 
                                        name="password" 
                                        class="form-control" 
                                        placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.password') }}" 
                                        data-minlength="5" 
                                        required="" 
                                        type="password" 
                                        />
                                    </div>
                                    <div class="form-group form-group-custom col-xs-12 col-xs-6">
                                        <label class="" for="confirmcontrasena">{{ trans(\Config::get('app.theme').'-app.login_register.confirm_password') }}</label>
                                        <input 
                                        maxlength="20" 
                                        name="confirm_password" 
                                        class="form-control" 
                                        data-match="#password" 
                                        id="confirmcontrasena" 
                                        placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.confirm_password') }}" 
                                        required="" 
                                        type="password"
                                        >
                                    </div>
                                    <small id="error-confirm-pass" class="error-confirm text-danger" style="display: none">{{ trans(\Config::get('app.theme').'-app.msg_error.pass_confirm') }}</small>
                                </div>
                            </div>
                            <div class="create-account-personal-info create-account-address" style="display: none">
                                <div class="create-account-address-container" style="position: relative">

                                    <div class="create-account-principal-address">
                                        <div class="inputs-custom-group  d-flex justify-content-space-between flex-wrap">
                                            <div class="form-group input-group col-xs-12 col-xs-6 col-sm-8 col-md-10">
                                                    <label class="" for="codigoVia">{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</label>

                                                <select id="country" name="pais" class="form-control notranslate" required>
                                                    <option value="">{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</option>
                                                    @if (!empty($data) && !empty($data["countries"]) )
                                                        @foreach ($data["countries"] as $country)
                                                            <option value="{{ $country->cod_paises }}">{{ $country->des_paises }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group input-group col-xs-12 col-sm-4 col-md-2">
                                                    <label class="" for="codigo_postal">{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}</label>
                                                <input 
                                                id="cpostal" 
                                                name="cpostal" 
                                                class="form-control" 
                                                placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}" 
                                                required="" 
                                                maxlength="10" 
                                                type="text" 
                                                />
                                            </div>
                                            <div class="form-group input-group col-xs-12 col-sm-6">
                                                <label class=""  for="Ciudad">{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}</label>
                                                <input 
                                                name="poblacion" 
                                                class="form-control" 
                                                id="Ciudad" 
                                                placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}" 
                                                required="" 
                                                maxlength="30" 
                                                type="text" 
                                                />
                                                </div>
                                                <div class="form-group input-group col-xs-12 col-sm-6">
                                                    <label class="" for="provincia">{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
                                                    <input 
                                                    name="provincia" 
                                                    class="form-control" 
                                                    id="provincia" 
                                                    placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}" 
                                                    maxlength="30" 
                                                    type="text" 
                                                    />
                                                </div>
                                                <div class="form-group input-group col-xs-12 col-sm-3">
                                                        <label class="" for="codigoVia">{{ trans(\Config::get('app.theme').'-app.login_register.via') }}</label>

                                                    <select id="codigoVia" name="codigoVia" class="form-control">
                                                        <option value="">{{ trans(\Config::get('app.theme').'-app.login_register.via') }}</option>
                                                        @if (!empty($data) && !empty($data["via"]) )
                                                            @foreach ($data["via"] as $via)
                                                                <option value="{{ $via->cod_sg }}">{{ $via->des_sg }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                
                                                <div class="form-group input-group col-xs-12 col-sm-9">
                                                    <label class="" for="direccion">{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}</label>
                                                    <input 
                                                    name="direccion" 
                                                    class="form-control" 
                                                    id="direccion" 
                                                    placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}" 
                                                    required="" 
                                                    maxlength="60" 
                                                    type="text" 
                                                    />
                                                    </div> 
                                                    @if(!empty(Config::get('app.delivery_address')) && Config::get('app.delivery_address') == 1)
                                                    <label for="second-address" class="d-flex aling-item-center second-address col-xs-12">
                                                            <input 
                                                                required 
                                                                type="checkbox"
                                                                id="second-address"
                                                                checked
                                                            />
                                                            <span>{{ trans(\Config::get('app.theme').'-app.login_register.utilizar_direcc_direccenv') }}</span>
                                                    </label>
                                                    @endif
                                                </div>
                                            </div>
                                            @if(!empty(Config::get('app.delivery_address')) && Config::get('app.delivery_address') == 1)
                                            <div id="second-address-content" class="create-account-second-address" style="display: none; position: relative">
                                                <div class="inputs-custom-group  d-flex justify-content-space-between flex-wrap">
                                                    <div class="col-xs-12 no-padding title-second-address">Direccion de envio</div>
                                                    <div class="form-group input-group col-xs-8 col-md-10">
                                                        <label for="country_envio">{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</label>
                                                        <select 
                                                        data-like="#country"
                                                            id="country_envio" 
                                                            name="clid_pais" 
                                                            onchange="" 
                                                            class="form-control" 
                                                            required 
                                                        >
                                                            <option value="">{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</option>
                                                                @if (!empty($data) && !empty($data["countries"]) )
                                                                    @foreach ($data["countries"] as $country)
                                                                        <option value="{{ $country->cod_paises }}">{{ $country->des_paises }}</option>
                                                                    @endforeach
                                                                @endif
                                                        </select>
                                                    </div>
                                                    <div class="form-group input-group col-xs-4 col-md-2">
                                                        <label for="cpostal_envio">{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}</label>
                                                        <input
                                                        data-like="#cpostal" 
                                                        id="codigo_postal" 
                                                        name="clid_cpostal" 
                                                        class="form-control" 
                                                        placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}" 
                                                        required="" 
                                                        maxlength="10" 
                                                        type="text" />
                                                    </div>
                                                    <div class="form-group input-group col-xs-6">
                                                        <label for="Ciudad_envio">{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}</label>
                                                        <input 
                                                        data-like="#Ciudad" 

                                                        name="clid_poblacion" 
                                                        class="form-control" 
                                                        id="clid_poblacion" 
                                                        placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}" 
                                                        required="" 
                                                        maxlength="30" 
                                                        type="text" />
                                                    </div>
                                                        <div class="form-group input-group col-xs-6">
                                                                <label >{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
                                                                
                                                                <input id="clid_provincia"
                                                                data-like='#provincia'
                                                                    name="clid_provincia" 
                                                                    class="form-control" 
                                                                    id="provincia" 
                                                                    maxlength="30" 
                                                                    type="text" 
                                                                    placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}"
                                                                />
                                                            </div>
                                                            <div class="form-group input-group col-xs-3">
                                                                <label for="codigoVia">{{ trans(\Config::get('app.theme').'-app.login_register.via') }}</label>
                                                                <select data-like="#codigoVia" id="clid_codigoVia" name="clid_codigoVia" class="form-control">
                                                                    <option value="">---</option>
                                                                    @if (!empty($data) && !empty($data["via"]) )
                                                                    @foreach ($data["via"] as $via)
                                                                    <option value="{{ $via->cod_sg }}">{{ $via->des_sg }}</option>
                                                                    @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <div class="form-group input-group col-xs-9">
                                                                <label for="direccion_envio">{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}</label>
                                                                <input 
                                                                data-like="#direccion"
                                                                name="clid_direccion" 
                                                                class="form-control" 
                                                                id="direccion_envio" 
                                                                placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}" 
                                                                required="" 
                                                                maxlength="60" 
                                                                type="text" />
                                                            </div>
                                                            <small id="error-address text-danger" style="display:none"></small>
                                                        </div>
                                                    </div>
                                                @endif
                                                </div>
                                            </div>

                                            <div class="buttons-login-content-form-back principal-button col-xs-12">
                                                <div class="create-account-accept-condition">
                                                    
<small><i>“<b>RESPONSABLE:</b>  María de los Ángeles Benayas García / 02505153Q / 914461926 / C/ Donoso Cortés 38, 28015 Madrid | <b>FINALIDAD:</b> Gestionar su registro a nuestra plataforma | <b>DERECHOS:</b> Acceso, rectificación, supresión y portabilidad de sus datos, de limitación y oposición a su tratamiento, así como, a no ser objeto de decisiones basadas únicamente en el tratamiento automatizado de sus datos, cuando procedan. | <b>INFORMACIÓN ADICIONAL:</b> Puede consultar información adicional y detallada sobre nuestra Política de Privacidad en  <a href="https://www.subastasgalileo.es/es/pagina/politica-de-privacidad">https://www.subastasgalileo.es/es/pagina/politica-de-privacidad</a>”</i></small>
                                                    
                <br><br>

                <div class="check_term row">
                    <div class="col-xs-2 col-md-1">
                        <input type="checkbox" name="newsletter" value="on" id="i-want-news" checked="checked">
                    </div>
                    <div class="col-xs-10 col-md-11">
                        <label for="accept_new">{{ trans(\Config::get('app.theme').'-app.login_register.recibir_newsletter') }}</label>
                    </div>
                </div>

                <br>
                                        
                <div class="check_term row">
                    <div class="col-xs-2 col-md-1">
                        <input type="checkbox" name="condiciones" value="on" id="bool__1__condiciones1" required="required">
                    </div>
                    <div class="col-xs-10 col-md-11">
                        <label for="accept_new"><?= trans(\Config::get('app.theme') . '-app.emails.privacy_conditions') ?></label>
                    </div>
                </div>

                <br>
                
                <div class="check_term row">
                    <div class="col-xs-2 col-md-1">
                        <input type="checkbox" name="condiciones2" value="on" id="bool__1__condiciones2">
                    </div>
                    <div class="col-xs-10 col-md-11">
                        <label for="accept_new"><?= trans(\Config::get('app.theme') . '-app.emails.privacy_conditions2') ?></label>
                    </div>
                </div>
                
                <br><br>


            </div>
                <div class="buttons col-xs-12">
                    <a id="create-login-content-form" class="secondary-button">Siguiente</a>
                    <a id="create-user" class="button-principal" style="display: none">Finalizar</a>
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
@stop
