@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

<?php
if($data['user']->fisjur_cli == 'F' || $data['user']->fisjur_cli == null){
	$name = explode(",", $data['user']->nom_cli);
	if(count($name)!= 2){
		$name[1] = $data['user']->nom_cli;
		$name[0] = '';
	}
}
?>

<section class="bread-new" style="margin-bottom: 40px;">
    <div class="container">
        <div class="row">
            <div class="col-xs-12" >
                			<h1 class="titlepageBig-bread">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h1>
            </div>
        </div>
    </div>
</section>

<div class="container panel">
    <div class="row">
        <div class="col-xs-12">
            <?php $tab="datos-personales";?>
                    @include('pages.panel.menu_micuenta')
        </div>
        <div class="col-xs-12 col-sm-8">
            <div class="content-tabs-height">

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active tabe-cust">
                                    <div class="sub_page">
                                            <div class="tit">{{ trans(\Config::get('app.theme').'-app.user_panel.datos_contacto') }}</div>
                                    </div>
                            <div class="col-lg-12 " >
                                <form method="post" class="frmLogin" id="frmUpdateUserInfoADV" data-toggle="validator">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" class="form-control">
                                    <div class="col_reg_form"></div>
                                        <div class="row">
                                            <div class="">
                                                @if($data['user']->fisjur_cli == 'J')
                                                    <div class="form-group-custom form-group">
                                                        <label >{{ trans(\Config::get('app.theme').'-app.login_register.company') }}</label>
                                                        <input type="text" class="form-control"  placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.company') }}" type="text" name="rsoc_cli" value="{{$data['user']->rsoc_cli}}" required>
                                                        <input type="hidden" class="form-control"  name="title_rsoc_cli" value="{{ trans(\Config::get('app.theme').'-app.login_register.company') }}">
                                                    </div>
                                                    <div class="form-group-custom form-group">
                                                        <label >{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}</label>
                                                        <input type="text" class="form-control"  placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}" type="text" name="usuario" value="{{$data['user']->nom_cli}}" required>
                                                        <input type="hidden" class="form-control"  name="title_contact" value="{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}">
                                                    </div>
                                                @else
                                                    <div class="form-group-custom form-group">
                                                        <label for="nombre">{{ trans(\Config::get('app.theme').'-app.login_register.nombre') }}</label>
                                                        <input type="text" class="form-control" name="usuario" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.nombre') }}" required value="<?= $name[1] ?>">
                                                        <input type="hidden" class="form-control" name="title_name" value="{{ trans(\Config::get('app.theme').'-app.login_register.nombre') }}">
													</div>
													<div class="form-group-custom form-group">
                                                        <label for="nombre">{{ trans(\Config::get('app.theme').'-app.login_register.apellidos') }}</label>
                                                        <input type="text" class="form-control" name="last_name" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.apellidos') }}" required value="<?= $name[0] ?>">
                                                        <input type="hidden" class="form-control" name="title_last_name" value="{{ trans(\Config::get('app.theme').'-app.login_register.apellidos') }}">
                                                    </div>
                                                @endif
                                                <div class="form-group-custom form-group">
                                                    <label for="email">{{ trans(\Config::get('app.theme').'-app.user_panel.email') }}</label>
                                                    <input type="text" class="form-control" id="email" placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.email') }}" type="email" disabled name="email" value="{{$data['user']->usrw_cliweb}}" required>
                                                    <input type="hidden" class="form-control"  name="title_email" value="{{ trans(\Config::get('app.theme').'-app.user_panel.email') }}">
                                                </div>
                                                <div class="form-group-custom form-group">
                                                    <label for="telefono">{{ trans(\Config::get('app.theme').'-app.user_panel.phone') }}</label>
                                                    <input type="text" name="telefono" class="form-control" placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.phone') }}" required="" maxlength="40" value="{{$data['user']->tel1_cli}}">
                                                    <input type="hidden" name="title_telefono" class="form-control" value="{{ trans(\Config::get('app.theme').'-app.user_panel.phone') }}">
                                                </div>
                                                <div class="form-group-custom form-group <?= count(Config::get('app.locales'))==1?'hidden':''; ?> ">
                                                <label>{{ trans(\Config::get('app.theme').'-app.login_register.language') }}</label>
                                                    <select name="language" class="form-control" required>
                                                        @foreach( Config::get('app.locales') as $key => $value)
                                                            <option <?= ($data['user']->idioma_cli == strtoupper($key))?'selected':''; ?> value="{{strtoupper($key)}}">{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="title_language" class="form-control" value="{{ trans(\Config::get('app.theme').'-app.login_register.language') }}">

                                            </div>
                                            <div class="col-xs-12 hidden">
                                                        <div class="checkbox">
                                                            <input
                                                                 checked="checked"
                                                                 type="checkbox"
                                                                 class="form-control"
                                                                 id="i-want-news"
                                                            />
                                                            <label for="i-want-news">{{ trans(\Config::get('app.theme').'-app.login_register.recibir_newsletter') }}</label>
                                                        </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12">
                                            <div class="sub_page">
                                                    <div class="tit">{{ trans(\Config::get('app.theme').'-app.user_panel.address') }}</div>
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label for="codigoVia">{{ trans(\Config::get('app.theme').'-app.login_register.via') }}</label>
                                                <select name="codigoVia" class="form-control" >
                                                    <option value="">----</option>
                                                    @if (!empty($data) && !empty($data["via"]) )
                                                        @foreach ($data["via"] as $via)
                                                            <option <?= ($via->cod_sg == $data['user']->sg_cli)?'selected':'';?> value="{{ $via->cod_sg }}">{{ $via->des_sg }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <input type="hidden" value='{{ trans(\Config::get('app.theme').'-app.login_register.via') }}' name='title_codigoVia'>
                                            </div>
                                             <div class="form-group-custom form-group">
                                                <label for="direccion">{{ trans(\Config::get('app.theme').'-app.user_panel.address') }}</label>
                                                <input type="text" name="direccion" class="form-control" id="direccion" placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.address') }}" required maxlength="60" value="{{$data['user']->dir_cli}}{{$data['user']->dir2_cli}}">
                                                <input type="hidden" name="title_direccion" class="form-control" value="{{ trans(\Config::get('app.theme').'-app.user_panel.address') }}">
                                             </div>
                                            <div class="form-group-custom form-group">
                                                <label for="pais">{{ trans(\Config::get('app.theme').'-app.user_panel.pais') }}</label>
                                                <select id="country_envio" name="pais" class="form-control notranslate" required>
                                                    <option value="">---</option>
                                                   @if (!empty($data) && !empty($data["countries"]) )
                                                        @foreach ($data["countries"] as $country)
                                                             <option <?= (!empty($data['user']->codpais_cli) && $data['user']->codpais_cli == $country->cod_paises)? 'selected' : ''; ?> value="{{ $country->cod_paises }}">{{ $country->des_paises }}</option>
                                                        @endforeach
                                                    @endif
                                               </select>
                                               <input type="hidden" name="title_pais" value="{{ trans(\Config::get('app.theme').'-app.user_panel.pais') }}">
                                            </div>

                                            <div class="form-group-custom form-group">
                                                <label for="codigo_postal">{{ trans(\Config::get('app.theme').'-app.user_panel.zip_code') }}</label>
                                                <input id="cpostal" type="text" name="cpostal" class="form-control" id="codigo_postal" placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.zip_code') }}" required maxlength="10" value="{{$data['user']->cp_cli}}">
                                                <input type="hidden" name="title_codigo_postal" value="{{ trans(\Config::get('app.theme').'-app.user_panel.zip_code') }}">
                                            </div>
                                             <div class="form-group form-group-custom">
                                                <label for="provincia">{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
                                                <input id="provincia"
                                                    name="provincia"
                                                    class="form-control"
                                                    placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}"
                                                    maxlength="30"
                                                    type="text"
                                                    value="{{$data['user']->pro_cli}}"
                                                />
                                                 <input type="hidden" name="title_provincia" value="{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}">
                                            </div>

                                            <div class="form-group-custom form-group">
                                                <label for="nombre">{{ trans(\Config::get('app.theme').'-app.user_panel.city') }}</label>
                                                <input type="text" name="poblacion" class="form-control" id="Ciudad" placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.city') }}" required required maxlength="30" value="{{$data['user']->pob_cli}}">
                                                <input type="hidden" name="title_poblacion" value="{{ trans(\Config::get('app.theme').'-app.user_panel.city') }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4"></div>
                                    </div>
                                    <?php if(!empty(Config::get('app.delivery_address')) && Config::get('app.delivery_address') == 1){ ?>
                                    <div class="sub_page">
                                        <div class="tit">{{ trans(\Config::get('app.theme').'-app.user_panel.title_envio') }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12">
                                            <div class="form-group-custom form-group">
                                            <label for="codigoVia">{{ trans(\Config::get('app.theme').'-app.login_register.via') }}</label>
                                                <select id="clid_codigoVia" name="clid_codigoVia" class="form-control">
                                                    <option value="">---</option>
                                                    @if (!empty($data) && !empty($data["via"]) )
                                                        @foreach ($data["via"] as $via)
                                                            <option <?= $via->cod_sg == $data['user']->sg_clid?'selected':'';?> value="{{ $via->cod_sg }}">{{ $via->des_sg }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <input type="hidden" name="title_clid_codigoVia" value="{{ trans(\Config::get('app.theme').'-app.login_register.via') }}">
                                            </div>
                                            <div class="form-group-custom form-group">
                                                <label for="direccion">{{ trans(\Config::get('app.theme').'-app.user_panel.address') }}</label>
                                                <input type="text" name="clid_direccion" class="form-control" id="direccion" placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.address') }}" required maxlength="60" value="{{$data['user']->dir_clid}}{{$data['user']->dir2_clid}}">
                                                <input type="hidden" name="title_clid_direccion" value="{{ trans(\Config::get('app.theme').'-app.user_panel.title_envio') }} - {{ trans(\Config::get('app.theme').'-app.user_panel.address') }}">
                                            </div>
                                            <div class="form-group-custom form-group">
                                                <label for="pais">{{ trans(\Config::get('app.theme').'-app.user_panel.pais') }}</label>
                                                <select id="country_envio" name="clid_pais" class="form-control" required>
                                                    <option value="">---</option>
                                                        @if (!empty($data) && !empty($data["countries"]) )
                                                             @foreach ($data["countries"] as $country)
                                                                  <option <?= (!empty($data['user']->codpais_clid) && $data['user']->codpais_clid == $country->cod_paises)? 'selected' : ''; ?> value="{{ $country->cod_paises }}">{{ $country->des_paises }}</option>
                                                             @endforeach
                                                         @endif
                                                </select>
                                                <input type="hidden" name="title_clid_pais" value="{{ trans(\Config::get('app.theme').'-app.user_panel.title_envio') }} - {{ trans(\Config::get('app.theme').'-app.user_panel.pais') }}">
                                            </div>
                                             <div class="form-group-custom form-group">
                                                <label for="codigo_postal">{{ trans(\Config::get('app.theme').'-app.user_panel.zip_code') }}</label>
                                                <input id="codigo_postal" type="text" name="clid_cpostal" class="form-control" id="codigo_postal" placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.zip_code') }}" required maxlength="10" value="{{$data['user']->cp_clid}}">
                                                <input type="hidden" name="title_clid_cpostal" value="{{ trans(\Config::get('app.theme').'-app.user_panel.title_envio') }} - {{ trans(\Config::get('app.theme').'-app.user_panel.zip_code') }}">
                                            </div>
                                            <div class="form-group-custom form-group">
                                                <label >{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
                                                <input id="clid_provincia"
                                                    name="clid_provincia"
                                                    class="form-control"
                                                    maxlength="30"
                                                    type="text"
                                                    value="<?=$data['user']->pro_clid?>"
                                                />
                                            </div>
                                            <input type="hidden" name="title_clid_provincia" value="{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}">

                                            <div class="form-group-custom form-group">
                                                <label for="nombre">{{ trans(\Config::get('app.theme').'-app.user_panel.city') }}</label>
                                                <input type="text" name="clid_poblacion" class="form-control" id="clid_poblacion" placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.city')}}" required required maxlength="30" value="{{$data['user']->pob_clid}}">
                                                <input type="hidden" name="title_clid_poblacion" value="{{ trans(\Config::get('app.theme').'-app.user_panel.title_envio') }} - {{ trans(\Config::get('app.theme').'-app.user_panel.city') }}">
                                            </div>
                                            <div class="form-group">
                                                <input class="hidden" name="codd_clid" value="{{$data['user']->codd_clid}}">
                                                <input class="hidden" name="title_codd_clid" value="{{ trans(\Config::get('app.theme').'-app.user_panel.cod_address_send') }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4"></div>
                                    </div>
                                    <?php } ?>
                                        <div class="row">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-step-reg">{{ trans(\Config::get('app.theme').'-app.user_panel.save') }}</button>
                                            </div>
                                        </div>
                                </form>
                            </div>



		    </div>

		  </div>

            </form>
		</div>

		</div>
	</div>
                        <div class="col-xs-12 col-sm-4 " >
                            <div class="sub_page">
                                    <div class="tit">{{ trans(\Config::get('app.theme').'-app.login_register.cuenta') }}</div>
                            </div>
                           <form method="post" class="frmLogin" id="frmUpdateUserPasswordADV" data-toggle="validator">
                                <div class="insert_msg"></div>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input style="display:none" type="password">
                                <div class="form form-group">
                                    <label for="contrasena">{{ trans(\Config::get('app.theme').'-app.user_panel.pass') }}</label>
                                    <input maxlength="20" name="last_password" type="password" class="form-control"  placeholder="Contraseña" data-minlength="5" required maxlength="8">
                                </div>
                                <br>
                                                                <input style="display:none" type="email" name="email" value="{{Session::get('user.usrw')}}">

                                <div class="form form-group">
                                    <label for="contrasena">{{ trans(\Config::get('app.theme').'-app.user_panel.new_pass') }}</label>
                                    <input maxlength="20" type="password" id="password" name="password" type="password" class="form-control" id="contrasena" placeholder="Contraseña" data-minlength="4" required maxlength="8">
                                </div>
                                <br>
                                <div class="form form-group">
                                    <label for="confirmcontrasena">{{ trans(\Config::get('app.theme').'-app.user_panel.new_pass_repeat') }}</label>
                                    <input maxlength="20" type="password" name="confirm_password" class="form-control" data-match="#password" id="confirmcontrasena" placeholder="Confirma contraseña" required >
                                </div>
                                <div class="text-center form-group">
                                    <button class="btn btn-step-reg" type="submit">{{ trans(\Config::get('app.theme').'-app.user_panel.save') }}</button>
                                </div>
                            </form>
                        </div>

</div>
</div>
<p class="error-form-validation hidden">{{ trans(\Config::get('app.theme').'-app.login_register.all_fields_are_required') }}</p>

@stop
