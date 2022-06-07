@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

<?php


$nombre1 = "";
$nombre2 = "";
$email_cli = "";
$nom_cli ="";
$cif_cli = "";
$codpais_cli = "";
$cp_cli = "0";
$pob_cli = "";
$pro_cli = "";
$dir_cli = "";
$sg_cli = "";
$fecnac_cli = "";
$sexo_cli = "H";
$fisjur_cli = "F";

$tel1_cli = "";



if (isset($data['userFields'])) {

    $userFields = $data['userFields'];

    if (isset($userFields->nombre1)) {
        $nombre1 = ucwords($userFields->nombre1);
    }

    if (isset($userFields->nombre2)) {
        $nombre2 = ucwords($userFields->nombre2);
    }

    if (isset($userFields->email_cli)) {
        $email_cli = $userFields->email_cli;
    }

    if (isset($userFields->nom_cli)) {
        $nom_cli = ucwords($userFields->nom_cli);
    }

    if (isset($userFields->tel1_cli)) {
        $tel1_cli = $userFields->tel1_cli;
    }

    if (isset($userFields->cif_cli)) {
        $cif_cli = $userFields->cif_cli;
    }

    if (isset($userFields->codpais_cli)) {
        $codpais_cli = $userFields->codpais_cli;
    }

    if (isset($userFields->cp_cli)) {
        $cp_cli = $userFields->cp_cli;
    }

    if (isset($userFields->pob_cli)) {
        $pob_cli = $userFields->pob_cli;
    }

    if (isset($userFields->pro_cli)) {
        $pro_cli = $userFields->pro_cli;
    }

    if (isset($userFields->dir_cli)) {
        $dir_cli = $userFields->dir_cli;
    }

    if (isset($userFields->sg_cli)) {
        $sg_cli = $userFields->sg_cli;
    }

    if (isset($userFields->fecnac_cli)) {
        $fecnac_cli = date('Y-m-d', strtotime($userFields->fecnac_cli));
    }

    if (isset($userFields->sexo_cli)) {
        $sexo_cli = $userFields->sexo_cli;
    }

    if (isset($userFields->fisjur_cli)) {
        $fisjur_cli = $userFields->fisjur_cli;
    }
}

?>


@section('content')

<section class="login-register">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <form data-toggle="validator" id="accerder-user-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="login_desktop_title">
                            <h1>
                                <?= trans(\Config::get('app.theme').'-app.login_register.login') ?>
                            </h1>
                            
                        </div>
                        <div class="input-group form-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>                            
                            <input class="form-control" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.user') }}" type="email" name="email" type="text">
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input class="form-control" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.contraseña') }}" type="password" name="password" maxlength="20">
                        </div>
                        <p class="text-center">
                            <a onclick="cerrarLogin();" class="c_bordered" data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery" data-title="{{ trans(\Config::get('app.theme').'-app.login_register.forgotten_pass_question')}}" href="javascript:;" data-toggle="modal" data-target="#modalAjax" >{{ trans(\Config::get('app.theme').'-app.login_register.forgotten_pass_question')}}</a>
                        </p>
                        <h5 class="message-error-log text-danger"></h5></p>
                        <button id="accerder-user" class="btn btn-login-desktop" type="button">{{ trans(\Config::get('app.theme').'-app.login_register.acceder') }}</button>
                        @if(!empty(\Config::get('app.coregistroSubalia')) && \Config::get('app.coregistroSubalia'))
                        <br>
                        <p style="margin-top:1rem;"><a class="subalia-button" href="/{{\Config::get('app.locale')}}/login/subalia">{{ trans(\Config::get('app.theme').'-app.login_register.register_subalia') }} {{ trans(\Config::get('app.theme').'-app.login_register.here') }}</a></p>
                        <br>
                        @endif

                </form>
            </div>
            <div class="col-lg-6">
                <form method="post" class="frmLogin" id="frmRegister-adv" data-toggle="validator">
                    <input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">
    
                                <div class="crear">
                                    <div class="tit_page">
                                        <h1 class="step">
                                            {{ trans(\Config::get('app.theme').'-app.login_register.crear_cuenta') }}
                                        </h1>
                                        <p class="error-form-validation">
                                            {{ trans(\Config::get('app.theme').'-app.login_register.all_fields_are_required') }}
                                        </p>
                                    </div>
                                        <div class="item-perfil">                
                                            <div class="input-group-custom">
                                                <div class="input-group-addon-custom form-group">
                                                    <input 
                                                        class="form-control  change_job" 
                                                        
                                                        id="inlineCheckbox1" 
                                                        name="pri_emp" 
                                                        value="F" 
                                                        checked="checked" 
                                                        @if ($fisjur_cli=='F') checked="checked" @endif
                                                        type="radio" 
                                                    /> 
                                                    <label role="button" class="input-check-custom " for="inlineCheckbox1">
                                                      {{ trans(\Config::get('app.theme').'-app.login_register.particular') }}
                                                    </label>
                                                </div>
                                                <div class="form-group input-group-addon-custom ">
                                                    <input class="form-control change_job" id="inlineCheckbox2" name="pri_emp" value="J" type="radio" @if ($fisjur_cli=='J') checked="checked" @endif> 
                                                    <label role="button" class="input-check-custom" for="inlineCheckbox2">{{ trans(\Config::get('app.theme').'-app.login_register.empresa') }}</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tit">{{ trans(\Config::get('app.theme').'-app.login_register.personal_information') }}</div>
                                        <div class="genero">
                                                <div class="col-xs-3">                                    
                                                    <div class="input-group">
                                                        <div class="form-group input-gener">
                                                            <input class="form-control" style="" id="inlineRadio1" value="H" name="sexo" @if ($sexo_cli=='H') checked="checked" @endif type="radio"> 
                                                            <label role="button" class="input-check-custom" for="inlineRadio1">{{ trans(\Config::get('app.theme').'-app.login_register.hombre') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-9">
                                                    <div class="input-group">
                                                        <div class="form-group input-gener">
                                                            <input class="form-control" style="" id="inlineRadio2" name="sexo" value="M" type="radio" @if ($sexo_cli=='M') checked="checked" @endif> 
                                                            <label role="button" class="input-check-custom" for="inlineRadio2">{{ trans(\Config::get('app.theme').'-app.login_register.mujer') }}</label>     
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                        <div class="contact-data col-xs-12">
                                            <div class="input-group col-xs-12 date" data-provide="datepicker">
                                                <label for="nombre">{{ trans(\Config::get('app.theme').'-app.user_panel.date_birthday') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                    <input name="date" class="form-control" type="date" required="" id="datetimepicker" value="{!! $fecnac_cli !!}">
                                                </div>
                                            </div>

                                            <div class="input-group col-xs-12" >
                                                <label for="nombre">{{ trans(\Config::get('app.theme').'-app.login_register.nombre_apellido') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                                    <input class="form-control" id="nombre" name="usuario" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.nombre_apellido') }}" required="" type="text" value="{!! $nombre1 !!}">
                                                </div>
                                            </div>   
                                            <div class="input-group col-xs-12 name_client">
                                                    <label for="apellido">{{ trans(\Config::get('app.theme').'-app.login_register.apellidos') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                                    <input class="form-control" id="apellido" name="last_name" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.apellidos') }}" required="" type="text" value="{!! $nombre2 !!}">
                                                </div>
                                            </div>
                                            <div class="input-group col-xs-12 hidden rsoc_cli">
                                                    <label for="">{{ trans(\Config::get('app.theme').'-app.login_register.company') }}</label>
                                                    <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                                    <input class="form-control" name="rsoc_cli" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.company') }}" type="text" value="{!! $nombre1 !!}">
                                                </div>
                                            </div>
                                            <div class="input-group col-xs-12 hidden rsoc_cli">
                                                    <label for="">{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}</label>
                                                    <div class="form-group input-group">
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-phone-alt"></i></span>
                                                        <input class="form-control" name="contact" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}" type="text" value="{!! $nom_cli !!}">
                                                    </div>
                                            </div>
                                            <div class="input-group col-xs-12">
                                                <label for="telefono">{{ trans(\Config::get('app.theme').'-app.login_register.phone') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                                                    <input 
                                                        name="telefono" 
                                                        class="form-control" 
                                                        placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.phone') }}" 
                                                        required="" 
                                                        maxlength="40" 
                                                        type="text"
                                                        value="{!! $tel1_cli !!}"
                                                    />                                                    
                                                </div>
                                            </div>
                                            <div class="input-group col-xs-12">
                                                <label for="nif" class="dni_txt">{{ trans(\Config::get('app.theme').'-app.login_register.dni') }}</label>
                                                <label for="nif" class="cif_txt hidden">{{ trans(\Config::get('app.theme').'-app.login_register.cif') }}</label>
                                                <div class="form-group input-group">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
                                                    <input 
                                                        id="dni" 
                                                        placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.dni') }}" 
                                                        class="form-control dni" 
                                                        size="10" 
                                                        name="nif" 
                                                        title="Formato del NIF/NIE(12345678A/X1234567A)" 
                                                        required="" 
                                                        type="text" 
                                                        value="{!! $cif_cli !!}"
                                                    />
                                                </div>
                                            </div>
                                            <div class="input-group col-xs-12">
                                                <label for="codigoVia">{{ trans(\Config::get('app.theme').'-app.login_register.via') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                                        <select id="codigoVia" name="codigoVia" class="form-control">
                                                                <option value="">---</option>
                                                                @if (!empty($data) && !empty($data["via"]) )
                                                                    @foreach ($data["via"] as $via)
                                                                        <option value="{{ $via->cod_sg }}" @if ($sg_cli==$via->cod_sg) selected='selected' @endif>{{ $via->des_sg }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>                                                
                                                </div>
                                            </div>
                                            <div class="input-group col-xs-12">
                                                                <label for="direccion">{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                                                    <input 
                                                     name="direccion" 
                                                     class="form-control" 
                                                     id="direccion" 
                                                     placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}" 
                                                     required="" 
                                                     maxlength="60" 
                                                     type="text" 
                                                     value="{!! $dir_cli !!}"
                                                 />                                    
                                                </div>
                                            </div>
                                                                                        <div class="input-group col-xs-12">
                                                            <label for="country">{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                                    <select id="country" name="pais" class="form-control notranslate" required>
                                                                <option value="">---</option>
                                                                @if (!empty($data) && !empty($data["countries"]) )
                                                                    @foreach ($data["countries"] as $country)
                                                                        <option value="{{ $country->cod_paises }}" @if ($codpais_cli==$country->cod_paises) selected='selected' @endif>{{ $country->des_paises }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>             
                                                </div>
                                            </div>
                                            
                                            
                                            
                                            
                                                <div class="input-group col-xs-12">
                                                        <label for="codigo_postal">{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                                    <input 
                                                            id="cpostal" 
                                                            name="cpostal" 
                                                            class="form-control" 
                                                            placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}" 
                                                            required="" 
                                                            maxlength="10" 
                                                            type="text" 
                                                            value="{!! $cp_cli !!}"
                                                        />
                                                </div>
                                            </div>
                                            
                                            <div class="input-group col-xs-12">
                                                        <label for="Ciudad">{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                                    <input 
                                                            name="poblacion" 
                                                            class="form-control" 
                                                            id="Ciudad" 
                                                            placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}" 
                                                            required="" 
                                                            maxlength="30" 
                                                            type="text" 
                                                            value="{!! $pob_cli !!}"
                                                        />
                                                </div>
                                            </div>
                                            
                                            
                                                                                        <div class="input-group col-xs-12">
                                                        <label for="provincia">{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                                    <input 
                                                            name="provincia" 
                                                            class="form-control" 
                                                            id="provincia" 
                                                            placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}" 
                                                            maxlength="30" 
                                                            type="text" 
                                                            value="{!! $pro_cli!!}"
                                                        />
                                                </div>
                                            </div>
                                            <div class="input-group col-xs-12">
                                                <label>{{ trans(\Config::get('app.theme').'-app.login_register.language') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                                    <select name="language" class="form-control" required>
                                                        @foreach( Config::get('app.locales') as $key => $value)
                                                            <option value="{{strtoupper($key)}}">{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            
                                            
                                            <?php if(!empty(Config::get('app.delivery_address')) && Config::get('app.delivery_address') == 1){ ?>
                                                
                                            
                                            <div class="tit">{{ trans(\Config::get('app.theme').'-app.login_register.personal_information') }}</div>
                                            <div class="input-group col-xs-12">
                                                
                                                <div class="ship-check col-xs-1">
                                                <input 
                                                    id="shipping_address" 
                                                    class="form-control"
                                                    name="shipping_address" 
                                                    type="checkbox" 
                                                />
                                                </div>
                                                <label for="shipping_address" class="col-xs-10">{{ trans(\Config::get('app.theme').'-app.login_register.utilizar_direcc_direccenv') }}</label>
                                            </div>
                                            
                                            
                                            <div class="input-group col-xs-12">
                                    <label for="codigoVia">{{ trans(\Config::get('app.theme').'-app.login_register.via') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                                    <select id="clid_codigoVia" name="clid_codigoVia" class="form-control">
                                            <option value="">---</option>
                                            @if (!empty($data) && !empty($data["via"]) )
                                                @foreach ($data["via"] as $via)
                                                    <option value="{{ $via->cod_sg }}">{{ $via->des_sg }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                                </div>
                                            </div>
                                            
                                                                                        
                                            <div class="input-group col-xs-12">
                                        <label for="country_envio">{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                                    <select 
                                            id="country_envio" 
                                            name="clid_pais" 
                                            onchange="" 
                                            class="form-control" 
                                            required 
                                        >
                                            <option value="">---</option>
                                                @if (!empty($data) && !empty($data["countries"]) )
                                                    @foreach ($data["countries"] as $country)
                                                        <option value="{{ $country->cod_paises }}">{{ $country->des_paises }}</option>
                                                    @endforeach
                                                @endif
                                        </select>
                                                </div>
                                            </div>
                                            
                                            
                                                                                        <div class="input-group col-xs-12">
                                        <label >{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                                    <input id="clid_provincia"
                                            name="clid_provincia" 
                                            class="form-control" 
                                            id="provincia" 
                                            maxlength="30" 
                                            type="text" 
                                            placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}"
                                        />
                                                </div>
                                            </div>
                                            
                                            
                                            
                                                                                     <div class="input-group col-xs-12">
                                        <label for="direccion_envio">{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                                                    <input 
                                            name="clid_direccion" 
                                            class="form-control" 
                                            id="direccion_envio" 
                                            placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}" 
                                            required="" 
                                            maxlength="60" 
                                            type="text" />
                                                </div>
                                            </div>
                                            
                                            
                                                   <div class="input-group col-xs-12">
                                        <label for="cpostal_envio">{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                                   <input 
                                            id="codigo_postal" 
                                            name="clid_cpostal" 
                                            class="form-control" 
                                            placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}" 
                                            required="" 
                                            maxlength="10" 
                                            type="text" />
                                                </div>
                                            </div>
                                            
                                            
                                                                                               <div class="input-group col-xs-12">
                                        <label for="Ciudad_envio">{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                                   <input 
                                            name="clid_poblacion" 
                                            class="form-control" 
                                            id="clid_poblacion" 
                                            placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}" 
                                            required="" 
                                            maxlength="30" 
                                            type="text" />
                                                </div>
                                            </div>
                                            <?php } ?>
                                            
                                            <div class="sub_page">
                            <div class="tit">{{ trans(\Config::get('app.theme').'-app.login_register.cuenta') }}</div>
                        </div>

                                               <div class="input-group col-xs-12">
                                <label for="email">{{ trans(\Config::get('app.theme').'-app.login_register.email') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                                  <input 
                                    class="form-control" 
                                    id="email" 
                                    placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.email') }}" 
                                    name="email" 
                                    required="" 
                                    type="text"
                                    value="{!! $email_cli !!}"
                                />
                                                </div>
                                            </div>
                                            <div class="input-group col-xs-12">
                                <label for="emailconfirm">{{ trans(\Config::get('app.theme').'-app.login_register.email_confirmacion') }}</label>
                                                                 <label id="erroremail" class="hidden text-danger"></label>

                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                                 <input data-match="#email"
                                    class="form-control" 
                                    id="emailconfirm" 
                                    placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.email_confirmacion') }}" 
                                    required="" 
                                    type="text" 
                                    value="{!! $email_cli !!}"
                                />
                                                </div>
                                            </div>
                                            
                                            
                                            

                                            
                                            
                                             <div class="input-group col-xs-12">
                                <label for="contrasena">{{ trans(\Config::get('app.theme').'-app.login_register.password') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
                                                 <input 
                                    maxlength="20" 
                                    id="password" 
                                    name="password" 
                                    class="form-control" 
                                    placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.password') }}" 
                                    data-minlength="5" 
                                    required="" 
                                    type="password" />
                                                </div>
                                            </div>
                                            
                                            <div class="input-group col-xs-12">
                                <label for="confirmcontrasena">{{ trans(\Config::get('app.theme').'-app.login_register.confirm_password') }}</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
                                                <input 
                                    maxlength="20" 
                                    name="confirm_password" 
                                    class="form-control" 
                                    data-match="#password" 
                                    id="confirmcontrasena" 
                                    placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.confirm_password') }}" 
                                    required="" 
                                    type="password">
                                                </div>
                                            </div>
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            <div class="input-group col-xs-12">
                                                
                                                <div class="ship-check col-xs-1">
                                                <input 
                                     checked="checked" 
                                     name="newsletter" 
                                     type="checkbox"
                                     class="form-control"
                                     id="i-want-news"
                                /> 
                                                </div>
                                <label for="i-want-news">{{ trans(\Config::get('app.theme').'-app.login_register.recibir_newsletter') }}</label>
                                            </div>
                                            

                                    <div class="input-group col-xs-12">
                                                
                                                <div class="ship-check col-xs-1">
                                               <input 
                                    name="condiciones" 
                                    required 
                                    type="checkbox"
                                    class="form-control" 
                                    id="recibir-newletter"
                                />
                                                </div>
 <label for="recibir-newletter">
                                    {{ trans(\Config::get('app.theme').'-app.login_register.read_conditions') }} (<a href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.term_condition') ?>" target="_blank">{{ trans(\Config::get('app.theme').'-app.login_register.more_info') }}</a>)                                
                                </label>                                            </div>
                                            
                                            
                                            <div class="input-group col-xs-12">
                                                <div class="input-button-register">
                                        <p id="error-form-validation" class="text-danger" style="font-size: 18px;"></p>
                                        <button type="submit" class="btn btn-registro">{{ trans(\Config::get('app.theme').'-app.login_register.finalizar') }}</button>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 text-center">
                                        <div id="regCallback" class="alert alert-danger"></div>
                                    </div>
                                </div>
                                <div class="confirmacion">
                                    <div class="tit_page">
                                        <h1 class="step"><span class="col_reg_form"></span></h1>
                                    </div>
                                </div>
                                            </div>
                                            
                            



<p class="error-form-validation hidden">{{ trans(\Config::get('app.theme').'-app.login_register.all_fields_are_required') }}</p>
                                </div>


                        

                            
                @if(!empty($data['formulario']->subalia))
                        {!!$data['formulario']->subalia!!}
                        {!!$data['formulario']->info!!}
                    @endif
                    </form>
                    @if(!empty($data['formulario']->subalia))
                    <form id="formToSubalia" method="post" action="https://subalia.es/registerclicli">
                        <input type="hidden" name="info" id="info_sent" value="">
                        <input type="hidden" name="cod_auchouse" id="cod_auchouse_sent" value="">
                        <input type="hidden" name="redirect" id="redirect_sent" value="">
                    </form>
                    @endif
                
            </div>
        </div>
    </div>
</section>


                    
      




<script>
$( document ).ready(function() {
    
    $('.icon-date').click(function(){
        
        $('#datetimepicker').focus();    
    });
});    
</script>
@stop
