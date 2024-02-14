@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')
<section class="principal-bar no-principal">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="princiapl-bar-wrapper">
                    <div class="principal-bar-title">
                        <h3>{{ trans($theme.'-app.user_panel.mi_cuenta') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
        <section class="account">
            <div class="container">
                <div class="row">
					<?php $tab="datos-personales"; ?>

					<div class="col-xs-12">
						@include('pages.panel.menu')
					</div>

                    <div class="col-xs-2 col-md-3">

                        @include('pages.panel.menu_micuenta')
                    </div>

                    <div class="col-xs-10 col-md-9">
                        <div class="user-datas">

                            <div class="personal-datas-wrapper flex">
                                <div class="block-contact flex">
                                    <div role="tabpanel" class="user-datas-title">
                                        <p>{{ trans($theme.'-app.user_panel.datos_contacto') }}</p>
                                        <p class="error-form-validation" style="font-size: 12px;
                                                                                font-weight: 400;"
                                        >{{ trans($theme.'-app.login_register.all_fields_are_required') }}</p>
                                        <div class="col_reg_form"></div>
                                    </div>
                                    <form method="post" class="frmLogin" id="frmUpdateUserInfoADV" data-toggle="validator">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" class="form-control">

                                            @if($data['user']->fisjur_cli == 'J' )
                                                    <div class="form-group-custom form-group">
                                                        <label >{{ trans($theme.'-app.login_register.company') }}</label>
                                                        <input type="text" class="form-control"  placeholder="{{ trans($theme.'-app.login_register.company') }}" type="text" name="rsoc_cli" value="{{$data['user']->rsoc_cli}}" required>
                                                        <input type="hidden" class="form-control"  name="title_rsoc_cli" value="{{ trans($theme.'-app.login_register.company') }}">
                                                    </div>
                                                    <div class="form-group-custom form-group">
                                                        <label >{{ trans($theme.'-app.login_register.contact') }}</label>
                                                        <input type="text" class="form-control"  placeholder="{{ trans($theme.'-app.login_register.contact') }}" type="text" name="usuario" value="{{$data['user']->nom_cli}}" required>
                                                        <input type="hidden" class="form-control"  name="title_contact" value="{{ trans($theme.'-app.login_register.contact') }}">
                                                    </div>
                                                @else
                                        <div class="form-group-custom form-group">
                                            <label for="nombre">{{ trans($theme.'-app.user_panel.name') }}</label>
                                            <input type="text" class="form-control" name="usuario" placeholder="{{ trans($theme.'-app.user_panel.name') }}" required value="<?= $data['user']->nom_cli ?>">
                                            <input type="hidden" class="form-control" name="title_rsoc_cli" value="{{ trans($theme.'-app.user_panel.name') }}">
                                        </div>
                                                @endif


                                        <div class="form-group-custom form-group">
                                            <label for="email">{{ trans($theme.'-app.user_panel.email') }}</label>
                                            <input type="text" class="form-control" id="email" placeholder="{{ trans($theme.'-app.user_panel.email') }}" type="email" disabled name="email" value="{{$data['user']->usrw_cliweb}}" required>
                                            <input type="hidden" class="form-control"  name="title_email" value="{{ trans($theme.'-app.user_panel.email') }}">
                                        </div>
                                        <div class="form-group-custom form-group">
                                            <label for="telefono">{{ trans($theme.'-app.user_panel.phone') }}</label>
                                            <input type="text" name="telefono" class="form-control" placeholder="{{ trans($theme.'-app.user_panel.phone') }}" required="" maxlength="40" value="{{$data['user']->tel1_cli}}">
                                            <input type="hidden" name="title_telefono" class="form-control" value="{{ trans($theme.'-app.user_panel.phone') }}">
                                        </div>
                                        <div class="form-group-custom form-group <?= count(Config::get('app.locales'))==1?'hidden':''; ?> ">
                                            <label>{{ trans($theme.'-app.login_register.language') }}</label>
                                            <select name="language" class="form-control" required>
                                                @foreach( $data['language'] as $key => $value)
                                                    <option <?= ($data['user']->idioma_cli == strtoupper($key))?'selected':''; ?> value="{{strtoupper($key)}}">{{$value}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="title_language" class="form-control" value="{{ trans($theme.'-app.login_register.language') }}">
                                        </div>
                                                <div class="form-group form-group-custom">
                                                <label>{{ trans($theme.'-app.login_register.currency') }}</label>
                                                <select name="divisa" class="form-control" required>
                                                    @foreach( $data['divisa'] as $key => $value)
                                                        <option <?= ($data['user']->cod_div_cli == strtoupper($value->cod_div))?'selected':''; ?> value="{{$value->cod_div}}">{{$value->cod_div}}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                        <div class="form-group form-group-custom">
                                            <input type="hidden" name="codigoVia" value="">
                                            <input type="hidden" value='{{ trans($theme.'-app.login_register.via') }}' name='title_codigoVia'>
                                        </div>
                                                 <div class="form-group-custom form-group">
                                            <label for="direccion">{{ trans($theme.'-app.user_panel.address') }}</label>
                                            <input type="text" name="direccion" class="form-control" id="direccion" placeholder="{{ trans($theme.'-app.user_panel.address') }}" required maxlength="60" value="{{$data['user']->dir_cli}}{{$data['user']->dir2_cli}}">
                                            <input type="hidden" name="title_direccion" class="form-control" value="{{ trans($theme.'-app.user_panel.address') }}">
                                        </div>
                                                <div class="form-group-custom form-group">
                                            <label for="pais">{{ trans($theme.'-app.user_panel.pais') }}</label>
                                            <select id="country" name="pais" class="form-control notranslate" required>
                                                <option value="">---</option>
                                               @if (!empty($data) && !empty($data["countries"]) )
                                                    @foreach ($data["countries"] as $country)
                                                         <option <?= (!empty($data['user']->codpais_cli) && $data['user']->codpais_cli == $country->cod_paises)? 'selected' : ''; ?> value="{{ $country->cod_paises }}">{{ $country->des_paises }}</option>
                                                    @endforeach
                                                @endif
                                           </select>
                                           <input type="hidden" name="title_pais" value="{{ trans($theme.'-app.user_panel.pais') }}">
                                        </div>
                                                <div class="form-group-custom form-group">
                                            <label for="codigo_postal">{{ trans($theme.'-app.user_panel.zip_code') }}</label>
                                            <input id="cpostal" type="text" name="cpostal" class="form-control" id="codigo_postal" placeholder="{{ trans($theme.'-app.user_panel.zip_code') }}" required maxlength="10" value="{{$data['user']->cp_cli}}">
                                            <input type="hidden" name="title_codigo_postal" value="{{ trans($theme.'-app.user_panel.zip_code') }}">
                                        </div>
                                                <div class="form-group form-group-custom">
                                           <label for="provincia">{{ trans($theme.'-app.login_register.provincia') }}</label>
                                           <input
                                               name="provincia"
                                               class="form-control"
                                               id="provincia"
                                               placeholder="{{ trans($theme.'-app.login_register.provincia') }}"
                                               maxlength="30"
                                               type="text"
                                               value="{{$data['user']->pro_cli}}"
                                           />
                                            <input type="hidden" name="title_provincia" value="{{ trans($theme.'-app.login_register.provincia') }}">
                                        </div>


                                        <div class="form-group-custom form-group">
                                            <label for="nombre">{{ trans($theme.'-app.user_panel.city') }}</label>
                                            <input type="text" name="poblacion" class="form-control" id="Ciudad" placeholder="{{ trans($theme.'-app.user_panel.city') }}" required required maxlength="30" value="{{$data['user']->pob_cli}}">
                                            <input type="hidden" name="title_poblacion" value="{{ trans($theme.'-app.user_panel.city') }}">
                                        </div>

										<input type="hidden" name="cod" value="01">
									@if(!empty(Config::get('app.delivery_address')) && Config::get('app.delivery_address') == 1)

                                    <div class="form-group form-group-custom">
                                            <div class="checkbox">
                                                <input id="add_addres"  name="add_address" class="form-control filled-in" type="checkbox" <?= !empty($data["user"]->codd_clid)?'checked':'';?> >
                                                <label for="add_addres">{{ trans($theme.'-app.user_panel.add_addres') }}</label>
                                            </div>
										</div>

                                                @if(!empty($data["user"]->codd_clid))
                                                    <script>
                                                    $( document ).ready(function() {
                                                        $("#add_addres").change();
                                                    });
                                                    </script>
                                                @endif
                                    <div class='add-addres'>
                                        <div class="titles-form-login">
                                                <h3>{{ trans($theme.'-app.user_panel.title_envio') }}</h3>
                                        </div>
                                            <div class="form-group-custom form-group">
                                                <label for="pais">{{ trans($theme.'-app.user_panel.pais') }}</label>
                                                <select id="country_envio" name="clid_pais" class="form-control">
                                                    <option value="">---</option>
                                                        @if (!empty($data) && !empty($data["countries"]) )
                                                             @foreach ($data["countries"] as $country)
                                                                  <option <?= (!empty($data['user']->codpais_clid) && $data['user']->codpais_clid == $country->cod_paises)? 'selected' : ''; ?> value="{{ $country->cod_paises }}">{{ $country->des_paises }}</option>
                                                             @endforeach
                                                         @endif
                                                </select>
                                                <input type="hidden" name="title_clid_pais" value="{{ trans($theme.'-app.user_panel.title_envio') }} - {{ trans($theme.'-app.user_panel.pais') }}">
                                            </div>

                                            <div class="form-group-custom form-group">
                                                <input type="hidden" id="clid_codigoVia" name="clid_codigoVia" value="">
                                            </div>
                                                <div class="form-group-custom form-group">
                                                <label for="direccion">{{ trans($theme.'-app.user_panel.address') }}</label>
                                                <input type="text" name="clid_direccion" class="form-control" id="cli_direccion" placeholder="{{ trans($theme.'-app.user_panel.address') }}" maxlength="60" value="{{$data['user']->dir_clid}}{{$data['user']->dir2_clid}}">
                                                <input type="hidden" name="title_clid_direccion" value="{{ trans($theme.'-app.user_panel.title_envio') }} - {{ trans($theme.'-app.user_panel.address') }}">
                                            </div>

                                            <div class="form-group-custom form-group">
                                                <label for="codigo_postal">{{ trans($theme.'-app.user_panel.zip_code') }}</label>
                                                <input id="codigo_postal" type="text" name="clid_cpostal" class="form-control" id="codigo_postal" placeholder="{{ trans($theme.'-app.user_panel.zip_code') }}" maxlength="10" value="{{$data['user']->cp_clid}}">
                                                <input type="hidden" name="title_clid_cpostal" value="{{ trans($theme.'-app.user_panel.title_envio') }} - {{ trans($theme.'-app.user_panel.zip_code') }}">
                                            </div>
                                                <div class="form-group-custom form-group">
                                                <label >{{ trans($theme.'-app.login_register.provincia') }}</label>
                                                <input id="clid_provincia"
                                                    name="clid_provincia"
                                                    class="form-control"
                                                    maxlength="30"
                                                    type="text"
                                                    value="<?=$data['user']->pro_clid?>"
                                                    placeholder="{{ trans($theme.'-app.login_register.provincia') }}"
                                                />
                                            </div>
                                            <div class="form-group-custom form-group">
                                                <label for="nombre">{{ trans($theme.'-app.user_panel.city') }}</label>
                                                <input type="text" name="clid_poblacion" class="form-control" id="clid_poblacion" placeholder="{{ trans($theme.'-app.user_panel.city')}}"  maxlength="30" value="{{$data['user']->pob_clid}}">
                                                <input type="hidden" name="title_clid_poblacion" value="{{ trans($theme.'-app.user_panel.title_envio') }} - {{ trans($theme.'-app.user_panel.city') }}">
                                            </div>


                                            <div class="form-group">
                                                <input class="hidden" name="codd_clid" value="{{$data['user']->codd_clid}}">
                                                <input class="hidden" name="title_codd_clid" value="{{ trans($theme.'-app.user_panel.cod_address_send') }}">
                                            </div>

                                        </div>
                                        @endif
                                        <div class="btn-save-data">
                                            <button type="submit" class="btn btn-color btn-update">{{ trans($theme.'-app.user_panel.save') }}</button>
                                        </div>


                                </form>
                                </div>
                                <div class="block-account flex">
                                    <div role="tabpanel" class="user-datas-title">
                                <p>{{ trans($theme.'-app.login_register.cuenta') }}</p>

                            </div>
                                <form method="post" class="frmLogin" id="frmUpdateUserPasswordADV" data-toggle="validator">
                                <div class="insert_msg"></div>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input style="display:none" type="password">
                                <div class="form form-group">
                                    <label for="contrasena">{{ trans($theme.'-app.user_panel.pass') }}</label>
                                    <input maxlength="20" name="last_password" type="password" class="form-control"  placeholder="Contraseña" data-minlength="4" required maxlength="8">
                                </div>
                                                               <input style="display:none" type="email" name="email" value="{{Session::get('user.usrw')}}">

                                <div class="form form-group">
                                    <label for="contrasena">{{ trans($theme.'-app.user_panel.new_pass') }}</label>
                                    <input maxlength="20" type="password" id="password" name="password" type="password" class="form-control" id="contrasena" placeholder="Contraseña" data-minlength="5" required maxlength="8">
                                </div>

                                <div class="form form-group">
                                    <label for="confirmcontrasena">{{ trans($theme.'-app.user_panel.new_pass_repeat') }}</label>
                                    <input maxlength="20" type="password" name="confirm_password" class="form-control" data-match="#password" id="confirmcontrasena" placeholder="Confirma contraseña" required >
                                </div>
                                <div class="btn-save-data form-group">
                                    <button class="btn btn-color btn-update" type="submit">{{ trans($theme.'-app.user_panel.save') }}</button>
                                </div>

                            </form>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>



@stop
