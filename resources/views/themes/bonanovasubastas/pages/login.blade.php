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
{{-- Login no se usa, redirigimos a register al entrar --}}
<script>
    window.location.replace("/{{ \App::getLocale() }}/register");
</script>
<form method="post" class="frmLogin" id="frmRegister-adv">
    <input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <div class="crear">
                    <div class="tit_page">
                        <h1 class="step">
                            {{ trans(\Config::get('app.theme').'-app.login_register.crear_cuenta') }}
                        </h1>
                        <p class="error-form-validation">
                            {{ trans(\Config::get('app.theme').'-app.login_register.all_fields_are_required') }}
                        </p>
                    </div>
                    <div class="col-xs-12">
                        <div class="item-perfil">
                            <div class="input-group-custom">
                                <div class="input-group-addon-custom form-group">
                                    <input
                                        class="form-control  change_job"
                                        style="display: none;"
                                        id="inlineCheckbox1"
                                        name="pri_emp"
                                        value="F"
                                        @if ($fisjur_cli=='F') checked="checked" @endif
                                        type="radio"
                                    />
                                    <label class="input-check-custom " for="inlineCheckbox1">{{ trans(\Config::get('app.theme').'-app.login_register.particular') }}</label>
                                </div>
                                <div class="form-group input-group-addon-custom ">
                                    <input class="form-control change_job" style="display: none;" id="inlineCheckbox2" name="pri_emp" value="J" type="radio" @if ($fisjur_cli=='J') checked="checked" @endif>
                                    <label class="input-check-custom" for="inlineCheckbox2">{{ trans(\Config::get('app.theme').'-app.login_register.empresa') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="item-contact-data col-md-8 col-md-offset-2" style="border: 1px solid #ccc; margin-bottom: 10px; padding-bottom:20px;">
                                <div class="tit">{{ trans(\Config::get('app.theme').'-app.login_register.personal_information') }}</div>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="input-group pull-right">
                                                <div class="form-group input-gener">
                                                    <input class="form-control" style="display: none;" id="inlineRadio1" value="H" name="sexo" @if ($sexo_cli=='H') checked="checked" @endif type="radio">
                                                    <label class="input-check-custom" for="inlineRadio1">{{ trans(\Config::get('app.theme').'-app.login_register.hombre') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="input-group">
                                                <div class="form-group input-gener">
                                                    <input class="form-control" style="display: none;" id="inlineRadio2" name="sexo" value="M" type="radio" @if ($sexo_cli=='M') checked="checked" @endif>
                                                    <label class="input-check-custom" for="inlineRadio2">{{ trans(\Config::get('app.theme').'-app.login_register.mujer') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <div class="row">
                                    <div class="col-xs-12 data-contact-container">
                                        <div class="col-xs-12 col-md-6">
                                            <div class="col-xs-12 col-md-12 pull-right">
                                                <div class="input-group date" data-provide="datepicker">
                                                    <label for="nombre">{{ trans(\Config::get('app.theme').'-app.user_panel.date_birthday') }}</label>
                                                    <div class="form-group" style="display: inline-table;">
                                                        <input name="date" class="form-control" type="date" id="datetimepicker" required value="{!! $fecnac_cli !!}">
                                                        <div class="input-group-addon icon-date">
                                                            <span class="glyphicon glyphicon-th"></span>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="form-group input-group name_client">
                                                    <label for="nombre">{{ trans(\Config::get('app.theme').'-app.login_register.nombre_apellido') }}</label>
                                                    <input class="form-control" id="nombre" name="usuario" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.nombre_apellido') }}" required="" type="text" value="{!! $nombre1 !!} {!! $nombre2 !!}">
                                                </div>
                                                <div class="form-group input-group hidden rsoc_cli">
                                                    <label for="">{{ trans(\Config::get('app.theme').'-app.login_register.company') }}</label>
                                                    <input class="form-control" name="rsoc_cli" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.company') }}" type="text" value="{!! $nombre1 !!}>
                                                </div>
                                                <div class="form-group input-group hidden rsoc_cli">
                                                    <label for="">{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}</label>
                                                    <input class="form-control" name="contact" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}" type="text" value="{!! $nom_cli !!}">
                                                </div>
                                                <div class="form-group input-group">
                                                    <label for="telefono">{{ trans(\Config::get('app.theme').'-app.login_register.phone') }}</label>
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


                                        <div  class="form-group input-group">
                                            <label for="nif" class="dni_txt">{{ trans(\Config::get('app.theme').'-app.login_register.dni') }}</label>
                                            <label for="nif" class="cif_txt hidden">{{ trans(\Config::get('app.theme').'-app.login_register.cif') }}</label>
                                            <input
                                                id="dni"
                                                placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.dni') }}"
                                                class="form-control dni"
                                                size="10"
                                                name="nif"
                                                title="Formato del NIF/NIE(12345678A/X1234567A)"
                                                required=""
                                                type="text"
                                                data-validation='validator-error'
                                                value="{!! $cif_cli !!}"
                                            />
                                        </div>

                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <div class="col-xs-12 col-md-12">
                                        <div class="form-group input-group">
                                            <label for="codigoVia">{{ trans(\Config::get('app.theme').'-app.login_register.via') }}</label>
                                            <select id="codigoVia" name="codigoVia" class="form-control">
                                                <option value="">---</option>
                                                @if (!empty($data) && !empty($data["via"]) )
                                                    @foreach ($data["via"] as $via)
                                                        <option value="{{ $via->cod_sg }}"@if ($sg_cli==$via->cod_sg) selected='selected' @endif>{{ $via->des_sg }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            </div>
                                            <div class="form-group input-group">
                                                <label for="direccion">{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}</label>
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

                                        <div class="input-group">
                                            <label for="country">{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</label>
                                            <select id="country" name="pais" class="form-control notranslate" required>
                                                <option value="">---</option>
                                                @if (!empty($data) && !empty($data["countries"]) )
                                                    @foreach ($data["countries"] as $country)
                                                        <option value="{{ $country->cod_paises }}" @if ($codpais_cli==$country->cod_paises) selected='selected' @endif>{{ $country->des_paises }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group input-group">
                                        <label for="codigo_postal">{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}</label>
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
                                        <div class="form-group input-group">
                                        <label for="Ciudad">{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}</label>
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
                                    <div class="form-group input-group">
                                        <label for="provincia">{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
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
                                        <div class="form-group input-group <?= count(Config::get('app.locales'))==1?'hidden':''; ?> ">
                                            <label>{{ trans(\Config::get('app.theme').'-app.login_register.language') }}</label>
                                                <select name="language" class="form-control" required>
                                                    @foreach( Config::get('app.locales') as $key => $value)
                                                        <option value="{{strtoupper($key)}}">{{$value}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if(!empty(Config::get('app.delivery_address')) && Config::get('app.delivery_address') == 1){ ?>
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2" style="border: 1px solid #ccc; margin-bottom: 10px; padding-bottom:20px;">
                    <div class="col-xs-12 ">
                        <div class="sub_page">
                            <div class="tit">{{ trans(\Config::get('app.theme').'-app.login_register.title_direccion_envio') }}</div>
                        </div>
                    <div class="ship-check">
                        <input
                            id="shipping_address"
                            class="form-control"
                            name="shipping_address"
                            type="checkbox"
                        />
                        <label for="shipping_address">{{ trans(\Config::get('app.theme').'-app.login_register.utilizar_direcc_direccenv') }}</label>
                    </div>
                </div>
            <div class="col-xs-12 data-address">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="col-sm-12 col-md-12">
                    <div class="form-group input-group">
                    <label for="codigoVia">{{ trans(\Config::get('app.theme').'-app.login_register.via') }}</label>
                        <select id="clid_codigoVia" name="clid_codigoVia" class="form-control">
                            <option value="">---</option>
                            @if (!empty($data) && !empty($data["via"]) )
                                @foreach ($data["via"] as $via)
                                    <option value="{{ $via->cod_sg }}">{{ $via->des_sg }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group input-group">
                        <label for="country_envio">{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</label>
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

                    <div class="form-group input-group">
                        <label >{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
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
            </div>
            <div class="col-xs-12 col-sm-6 col-sm-6">
                <div class="col-sm-12 col-md-12">
                    <div class="form-group input-group">
                        <label for="direccion_envio">{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}</label>
                        <input
                            name="clid_direccion"
                            class="form-control"
                            id="direccion_envio"
                            placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}"
                            required=""
                            maxlength="60"
                            type="text" />
                    </div>
                    <div class="form-group input-group">
                        <label for="cpostal_envio">{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}</label>
                        <input
                            id="codigo_postal"
                            name="clid_cpostal"
                            class="form-control"
                            placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}"
                            required=""
                            maxlength="10"
                            type="text" />
                    </div>
                     <div class="form-group input-group">
                        <label for="Ciudad_envio">{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}</label>
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

            </div>
        </div>

    </div>

</div>
            <?php } ?>
<div class="row">
    <div class="col-xs-12 col-md-8 col-md-offset-2" style="border: 1px solid #ccc; margin-bottom: 10px; padding-bottom:20px;">
        <div class="sub_page">
            <div class="tit">{{ trans(\Config::get('app.theme').'-app.login_register.cuenta') }}</div>
        </div>
        <div class="col-xs-12 col-sm-12 col-sm-offset-0">
            <div class="form-group form-group-custom">
                <label for="email">{{ trans(\Config::get('app.theme').'-app.login_register.email') }}</label>
                 <label id="erroremail" class="hidden text-danger"></label>

                <input
                    class="form-control"
                    id="email"
                    placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.email') }}"
                    name="email"
                    required=""
                    type="email"
                    value="{!! $email_cli !!}"
                />

            </div>
            <div class="form-group form-group-custom">
                <label for="emailconfirm">{{ trans(\Config::get('app.theme').'-app.login_register.email_confirmacion') }}</label>
                <input data-match="#email"
                    class="form-control"
                    id="emailconfirm"
                    placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.email_confirmacion') }}"
                    required=""
                    type="email"
                    value="{!! $email_cli !!}"
                />
            </div>
            <div class="form-group form-group-custom">

                <label for="contrasena">{{ trans(\Config::get('app.theme').'-app.login_register.password') }}</label>
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
            <div class="form-group form-group-custom">
                <label for="confirmcontrasena">{{ trans(\Config::get('app.theme').'-app.login_register.confirm_password') }}</label>
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
        <small id="confirm-error" class="hidden text-danger col-xs-12">{{ trans(\Config::get('app.theme').'-app.login_register.email_pass_confirm') }}</small>
        <p id="error-form-validation" class="hidden text-danger" style="font-size: 18px;">{{ trans(\Config::get('app.theme').'-app.login_register.all_fields_are_required') }}</p>
                <div class="col-xs-12">
            <div class="checkbox">
                <input
                     checked="checked"
                     name="newsletter"
                     type="checkbox"
                     class="form-control"
                     id="i-want-news"
                />
                <label for="i-want-news">{{ trans(\Config::get('app.theme').'-app.login_register.recibir_newsletter') }}</label>
            </div>
            <div class="checkbox">
                <input
                    name="condiciones"
                    required
                    type="checkbox"
                    class="form-control"
                    id="recibir-newletter"
                />
                <label for="recibir-newletter">
                    {{ trans(\Config::get('app.theme').'-app.login_register.read_conditions') }} (<a href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.term_condition') ?>" target="_blank">{{ trans(\Config::get('app.theme').'-app.login_register.more_info') }}</a>)
                </label>
            </div>
        </div>


    </div>

</div>


                    <div class="input-button-register">
                        <p id="error-form-validation" class="text-danger" style="font-size: 18px;"></p>
                        <button class="btn btn-registro">{{ trans(\Config::get('app.theme').'-app.login_register.finalizar') }}</button>
                    </div>
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
	</div>
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
<p class="error-form-validation hidden">{{ trans(\Config::get('app.theme').'-app.login_register.all_fields_are_required') }}</p>

<script>
$( document ).ready(function() {

    $('.icon-date').click(function(){

        $('#datetimepicker').focus();
    });
});
</script>
@stop
