@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop


<?php
/*

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


*/
?>

@section('content')
<script>
    <?php // login es la págian antigua y si cierran sesion estando en una página que requiere estar logeado redirige a esta página  ?>
    window.location.replace("/{{ \App::getLocale() }}/register");

</script>
<?php
/*
//query que mira si hay alguna subasta activa actualmente, si la hay no se pueden registrar 5/1440 es para bloquearlo 5 minutos antes de que empieze
$sql="select count(asigl0.ref_asigl0) as cuantos  from \"auc_sessions\" auc
join fgsub sub   on sub.emp_sub=auc.\"company\" and  sub.cod_sub = auc.\"auction\"
join fgasigl0 asigl0 on asigl0.emp_asigl0=auc.\"company\" and asigl0.sub_asigl0 = sub.cod_sub
where
auc.\"company\" = :emp and
sub.subc_sub in ('S') and
sub.tipo_sub = 'W' and

asigl0.ref_asigl0 >= auc.\"init_lot\"    AND
asigl0.ref_asigl0 <=  auc.\"end_lot\" and
 asigl0.cerrado_asigl0 = 'N' and
(auc.\"start\" - (5/1440)) < sysdate  and auc.\"end\" > sysdate";


$bindings = array(
                    'emp'      => Config::get('app.emp')
                   );
$active_lots = DB::select($sql,$bindings);

?>
    @if(!empty($active_lots) && $active_lots[0]->cuantos > 0)
     <section class="principal-bar no-principal body-auctions2">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                    <div class="princiapl-bar-wrapper">
                            <div class="principal-bar-title ">
                                <h3 class="titlePage" style="text-align:center;"> {{ trans($theme.'-app.login_register.register_blocked') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else

        <section class="principal-bar no-principal">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                    <div class="princiapl-bar-wrapper">
                            <div class="principal-bar-title">
                                <h3 class="titlePage"> {{ trans($theme.'-app.login_register.crear_cuenta') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="form-register">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <p class="error-form-validation">
                            {{ trans($theme.'-app.login_register.all_fields_are_required') }}
                        </p>
                    </div>
                    <form method="post" class="frmLogin" id="frmRegister-adv" data-toggle="validator">
                        <input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-xs-12">
                            <div class="register-form">
                                <div class="perfil flex valign">
                                    <div class="form-group">
                                        <label class="input-check-custom " for="inlineCheckbox1">

                                            <input
                                            class="form-control change_job"
                                            style="display:none;"
                                            id="inlineCheckbox1"
                                            name="pri_emp"
                                            value="F"
                                            checked="checked"
                                            @if ($fisjur_cli=='F') checked="checked" @endif
                                            type="radio"
                                        />
                                            <div class="modern-button"></div>
                                            <p>{{ trans($theme.'-app.login_register.particular') }}</p>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-check-custom " for="inlineCheckbox2">
                                            <input
                                            class="form-control change_job"
                                            style="display: none;"
                                            id="inlineCheckbox2"
                                            name="pri_emp"
                                            value="J"
                                            @if ($fisjur_cli=='J') checked="checked" @endif
                                            type="radio"
                                        />
                                            <p>{{ trans($theme.'-app.login_register.empresa') }}</p>
                                            <div class="modern-button"></div>
                                        </label>
                                    </div>
                                </div>

                                <div class="contact-data">
                                    <div class="titles-form-login">
                                        <h3>{{ trans($theme.'-app.login_register.personal_information') }}</h3>
                                    </div>

                                    <div class="genere hidden flex flex-row">
                                        <div class="input-group">
                                        <div class="form-group input-gener">
                                            <input class="form-control" style="display: none;" id="inlineRadio1" value="H" name="sexo" @if ($sexo_cli=='H') checked="checked" @endif type="radio">
                                            <label class="input-check-custom" for="inlineRadio1"><i class="fa fa-2x fa-male"></i>{{ trans($theme.'-app.login_register.hombre') }}</label>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <div class="form-group input-gener">
                                            <input class="form-control" style="display: none;" id="inlineRadio2" name="sexo" value="M" @if ($sexo_cli=='M') checked="checked" @endif type="radio">
                                            <label class="input-check-custom" for="inlineRadio2"><i class="fa fa-2x fa-female"></i>{{ trans($theme.'-app.login_register.mujer') }}</label>
                                        </div>
                                    </div>
                                    </div>


                                    <div class="data-wrapper flex">
                                        <div class="block-divider">
                                            <div class="form-group input-group name_client">
                                                <label for="nombre">{{ trans($theme.'-app.login_register.nombre_apellido') }}</label>
                                                <input class="form-control" id="nombre" name="usuario" value="{!! $nombre1 !!} {!! $nombre2 !!}" placeholder="{{ trans($theme.'-app.login_register.nombre_apellido') }}" required="" type="text">
                                            </div>
                                            <div class="form-group input-group hidden rsoc_cli">
                                                <label for="">{{ trans($theme.'-app.login_register.company') }}</label>
                                                <input class="form-control" name="rsoc_cli" value="{!! $nom_cli !!}" placeholder="{{ trans($theme.'-app.login_register.company') }}" type="text">
                                            </div>
                                            <div class="form-group input-group hidden rsoc_cli">
                                                <label for="">{{ trans($theme.'-app.login_register.contact') }}</label>
                                                <input class="form-control" name="contact" placeholder="{{ trans($theme.'-app.login_register.contact') }}" type="text" value="{!! $nombre1 !!}">
                                            </div>

                                            <div class="form-group input-group">
                                                <label for="telefono">{{ trans($theme.'-app.login_register.phone') }}</label>
                                                <input
                                                    name="telefono"
                                                    class="form-control"
                                                    placeholder="{{ trans($theme.'-app.login_register.phone') }}"
                                                    required=""
                                                    maxlength="40"
                                                    type="text"
                                                    value="{!! $tel1_cli!!}"
                                                />
                                            </div>
                                            <div  class="form-group input-group">
                                                <label class="dni_txt" for="nif">{{ trans($theme.'-app.login_register.dni') }}</label>
                                                <label for="nif" class="cif_txt hidden">{{ trans($theme.'-app.login_register.cif') }}</label>

                                                <input
                                                    id="dni"
                                                    placeholder="{{ trans($theme.'-app.login_register.dni') }}"
                                                    class="form-control dni"
                                                    size="10"
                                                    name="nif"
                                                    title="Formato del NIF/NIE(12345678A/X1234567A)"
                                                    required=""
                                                    type="text"
                                                    value="{!! $cif_cli !!}"
                                                />
                                            </div>
                                            <div  class="form-group input-group">
                                                <label>{{ trans($theme.'-app.login_register.observacion') }}</label>
                                                <textarea style="background: #f1ece6;  max-width: 100%; max-height:100px;"  maxlength="200" class="form-control" name="obscli"  placeholder="{{ trans($theme.'-app.login_register.observacion_holder') }}"></textarea>
                                            </div>
                                            <div class="form-group input-group <?= count(Config::get('app.locales'))==1?'hidden':''; ?> ">
                                                <label>{{ trans($theme.'-app.login_register.language') }}</label>
                                                <select name="language" class="form-control" required>
                                                    @foreach( $data['language'] as $key => $value)
                                                        <option value="{{strtoupper($key)}}">{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group input-group">
                                                <label>{{ trans($theme.'-app.login_register.currency') }}</label>
                                                <select name="divisa" class="form-control" required>
                                                    @foreach( $data['divisa'] as $key => $value)
                                                        <option value="{{$value->cod_div}}">{{$value->cod_div}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="block-divider">
                                            <div class="form-group input-group">
                                                <label for="codigoVia">{{ trans($theme.'-app.login_register.via') }}</label>
                                                <select id="codigoVia" name="codigoVia" class="form-control">
                                                    <option value="">---</option>
                                                    @if (!empty($data) && !empty($data["via"]) )
                                                        @foreach ($data["via"] as $via)
                                                            <option value="{{ $via->cod_sg }}" @if ($sg_cli==$via->cod_sg) selected='selected' @endif>{{ $via->des_sg }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group input-group">
                                                <label for="direccion">{{ trans($theme.'-app.login_register.direccion') }}</label>
                                                <input
                                                name="direccion"
                                                    class="form-control"
                                                    id="direccion"
                                                    placeholder="{{ trans($theme.'-app.login_register.direccion') }}"
                                                    required=""
                                                    maxlength="60"
                                                    type="text"
                                                    value="{!! $dir_cli !!}"
                                                />
                                            </div>
                                            <div class="form-group input-group">
                                                <label for="country">{{ trans($theme.'-app.login_register.pais') }}</label>
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
                                                <label for="codigo_postal">{{ trans($theme.'-app.login_register.cod_postal') }}</label>
                                                <input
                                                    id="cpostal"
                                                    name="cpostal"
                                                    class="form-control"
                                                    placeholder="{{ trans($theme.'-app.login_register.cod_postal') }}"
                                                    required=""
                                                    maxlength="10"
                                                    type="text"
                                                    value="{!! $cp_cli !!}"
                                                />
                                            </div>
                                            <div class="form-group input-group">
                                                <label for="Ciudad">{{ trans($theme.'-app.login_register.ciudad') }}</label>
                                                <input
                                                    name="poblacion"
                                                    class="form-control"
                                                    id="Ciudad"
                                                    placeholder="{{ trans($theme.'-app.login_register.ciudad') }}"
                                                    required=""
                                                    maxlength="30"
                                                    type="text"
                                                    value="{!! $pob_cli !!}"
                                                />
                                            </div>
                                            <div class="form-group input-group">
                                                <label for="provincia">{{ trans($theme.'-app.login_register.provincia') }}</label>
                                                <input
                                                    name="provincia"
                                                    class="form-control"
                                                    id="provincia"
                                                    placeholder="{{ trans($theme.'-app.login_register.provincia') }}"
                                                    maxlength="30"
                                                    type="text"
                                                    value="{!! $pro_cli!!}"
                                                />
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <?php if(!empty(Config::get('app.delivery_address')) && Config::get('app.delivery_address') == 1){ ?>
                                <div class="divider-prices"></div>
                                    <div class="address-data">
                                        <div class="titles-form-login">
                                            <h3>{{ trans($theme.'-app.login_register.title_direccion_envio') }}</h3>
                                        </div>
                                        <div class="ship-check">
                                            <input
                                                id="shipping_address"
                                                class="form-control filled-in"
                                                name="shipping_address"
                                                type="checkbox"
                                            />
                                            <label for="shipping_address">{{ trans($theme.'-app.login_register.utilizar_direcc_direccenv') }}</label>
                                        </div>
                                        <div class="data-wrapper flex">
                                            <div class="block-divider">
                                                <div class="form-group input-group">
                                                    <label for="country_envio">{{ trans($theme.'-app.login_register.pais') }}</label>
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
                                                    <label >{{ trans($theme.'-app.login_register.provincia') }}</label>
                                                    <input id="clid_provincia"
                                                        name="clid_provincia"
                                                        class="form-control"
                                                        id="provincia"
                                                        maxlength="30"
                                                        type="text"
                                                        placeholder="{{ trans($theme.'-app.login_register.provincia') }}"
                                                    />
                                                </div>

                                                <div class="form-group input-group">
                                                    <label for="codigoVia">{{ trans($theme.'-app.login_register.via') }}</label>
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
                                            <div class="block-divider">
                                                <div class="form-group input-group">
                                                    <label for="cpostal_envio">{{ trans($theme.'-app.login_register.cod_postal') }}</label>
                                                    <input id="codigo_postal"
                                                        name="clid_cpostal"
                                                        class="form-control"
                                                        placeholder="{{ trans($theme.'-app.login_register.cod_postal') }}"
                                                        required=""
                                                        maxlength="10"
                                                        type="text"
                                                    />
                                                </div>

                                                <div class="form-group input-group">
                                                    <label for="nombre">{{ trans($theme.'-app.login_register.ciudad') }}</label>
                                                    <input id="clid_poblacion" type="text" name="clid_poblacion" class="form-control" required required maxlength="30" placeholder="{{ trans($theme.'-app.login_register.ciudad') }}">
                                                </div>

                                                <div class="form-group input-group">
                                                    <label for="direccion_envio">{{ trans($theme.'-app.login_register.direccion') }}</label>
                                                    <input
                                                        name="clid_direccion"
                                                        class="form-control"
                                                        id="direccion_envio"
                                                        placeholder="{{ trans($theme.'-app.login_register.direccion') }}"
                                                        required=""
                                                        maxlength="60"
                                                        type="text"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="divider-prices"></div>
                                <div class="account-data">
                                    <div class="titles-form-login">
                                        <h3>{{ trans($theme.'-app.login_register.cuenta') }}</h3>
                                    </div>
                                    <div class="data-wrapper flex">
                                        <div class="account-container flex">
                                            <div class="form-group form-group-custom" style="margin-bottom: 20px;">
                                                <label for="email">{{ trans($theme.'-app.login_register.email') }}</label>
                                                <span id="erroremail" class="text-danger"></span>
                                                <input
                                                    class="form-control"
                                                    id="email"
                                                    placeholder="{{ trans($theme.'-app.login_register.email') }}"
                                                    name="email"
                                                    required=""
                                                    type="text"
                                                    value="{!! $email_cli !!}"
                                                />
                                                <label id="erroremail" class="hidden text-danger">dddd</label>

                                            </div>
                                            </div>
                                        <div class="account-container flex">
                                             <div class="form-group form-group-custom">
                                                <label for="emailconfirm">{{ trans($theme.'-app.login_register.email_confirmacion') }}</label>
                                                <input data-match="#email"
                                                    class="form-control"
                                                    id="emailconfirm"
                                                    placeholder="{{ trans($theme.'-app.login_register.email_confirmacion') }}"
                                                    required=""
                                                    type="text"
                                                    value="{!! $email_cli !!}"
                                                />
                                            </div>
                                            </div>

                                        <div class="account-container flex">
                                            <div class="form-group form-group-custom">
                                                <label for="contrasena">{{ trans($theme.'-app.login_register.password') }}</label>
                                                <input
                                                    maxlength="20"
                                                    id="password"
                                                    name="password"
                                                    class="form-control"
                                                    placeholder="{{ trans($theme.'-app.login_register.password') }}"
                                                    data-minlength="5"
                                                    required=""
                                                    type="password" />
                                                <small class="" style="color: #5bc0de">{{ trans($theme.'-app.login_register.max_characters') }}</small>
                                            </div>
                                               </div>
                                    <div class="account-container flex">
                                            <div class="form-group form-group-custom">
                                                <label for="confirmcontrasena">{{ trans($theme.'-app.login_register.confirm_password') }}</label>
                                                <input
                                                    maxlength="20"
                                                    name="confirm_password"
                                                    class="form-control"
                                                    data-match="#password"
                                                    id="confirmcontrasena"
                                                    placeholder="{{ trans($theme.'-app.login_register.confirm_password') }}"
                                                    required=""
                                                    type="password">
                                            </div>
                                 </div>

                                    </div>
                            </div>
                        </div>
                            <div class="col-xs-12">
                                <p>{{ trans($theme.'-app.login_register.more_addres_user_panel') }}</p>
                    <div class="checkbox">
                        <input
                            name="condiciones"
                            required
                            type="checkbox"
                            class="form-control filled-in"
                            id="recibir-newletter"
                        />
                        <label for="recibir-newletter">
                            {{ trans($theme.'-app.login_register.read_conditions') }} (<a href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.term_condition') ?>" target="_blank">{{ trans($theme.'-app.login_register.more_info') }}</a>)
                        </label>
                            </div>
                        <div class="col-xs-12 text-center">
                            <div class="input-button-register">
                                <p id="error-form-validation" class="text-danger" style="font-size: 18px;"></p>
                                <button type="submit" class="btn btn-registro btn-color">{{ trans($theme.'-app.login_register.finalizar') }}</button>
                            </div>
                                                <div class="col-xs-12 col-sm-12 text-center">
                                <div id="regCallback" class="alert alert-danger"></div>
                            </div>
                             <div class="confirmacion">
                            <div class="tit_page">
                                <h1 class="step"><span class="col_reg_form"></span></h1>
                            </div>
                                 <p class="error-form-validation hidden">{{ trans($theme.'-app.login_register.all_fields_are_required') }}</p>

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
                </div>
            </div>

        </section>

	@endif

	*/
	?>
@stop


