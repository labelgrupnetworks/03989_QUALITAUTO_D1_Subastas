@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')

    @php
        $tab = 'datos-personales';
    @endphp

    <div class="color-letter">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h1 class="titlePage">{{ trans(\Config::get('app.theme') . '-app.user_panel.mi_cuenta') }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="account-user color-letter panel-user">

        <div class="container">

            <div class="row">

                <div class="col-xs-12 col-md-3 col-lg-3 account-user-menu">
                    @include('pages.panel.menu_micuenta')
                </div>

                <div class="col-xs-12 col-md-9 col-lg-9 ">

                    <div class="user-account-title-content">
                        <div class="user-account-menu-title">
                            {{ trans(\Config::get('app.theme') . '-app.user_panel.datos_contacto') }}
                        </div>
                    </div>

                    <div class="col-xs-12">

                        <div class="user-account-menu-title extra-account">
                            {{ trans(\Config::get('app.theme') . '-app.user_panel.info') }}</div>

                        <form method="post" class="frmLogin" id="frmUpdateUserInfoADV" data-toggle="validator">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" class="form-control">

                            <div class="col_reg_form"></div>

                            <div class="inputs-custom-group d-flex justify-content-space-between flex-wrap">
                                @if ($data['user']->fisjur_cli == 'J')
                                    <div class="form-group input-group name_client col-xs-12 col-sm-6">
                                        <label class=""
                                            for="">{{ trans(\Config::get('app.theme') . '-app.login_register.company') }}</label>
                                        <input disabled type="text" class="form-control"
                                            placeholder="{{ trans(\Config::get('app.theme') . '-app.login_register.company') }}"
                                            type="text" value="{{ $data['user']->rsoc_cli }}">
                                        {{-- pongo oculto el nombre ya que hemos deshabilitado que se pueda modificar y el input visible no es el que se envia --}}
                                        <input type="hidden" class="form-control" type="hidden" name="rsoc_cli"
                                            value="{{ $data['user']->rsoc_cli }}">
                                        <input type="hidden" class="form-control" name="title_rsoc_cli"
                                            value="{{ trans(\Config::get('app.theme') . '-app.login_register.company') }}">
                                    </div>
                                    <div class="form-group input-group name_client col-xs-12 col-sm-6">
                                        <label class=""
                                            for="apellido">{{ trans(\Config::get('app.theme') . '-app.login_register.contact') }}</label>
                                        <input disabled type="text" class="form-control"
                                            placeholder="{{ trans(\Config::get('app.theme') . '-app.login_register.contact') }}"
                                            type="text" value="{{ $data['user']->nom_cli }}">
                                        {{-- pongo oculto el nombre ya que hemos deshabilitado que se pueda modificar y el input visible no es el que se envia --}}
                                        <input type="hidden" class="form-control" type="text" name="usuario"
                                            value="{{ $data['user']->nom_cli }}">
                                        <input type="hidden" class="form-control" name="title_contact"
                                            value="{{ trans(\Config::get('app.theme') . '-app.login_register.contact') }}">

                                    </div>
                                @else
                                    <div class="form-group input-group name_client col-xs-12 col-sm-6">
                                        <label class=""
                                            for="nombre">{{ trans(\Config::get('app.theme') . '-app.user_panel.surname_name') }}</label>
                                        <input disabled type="text" class="form-control"
                                            placeholder="{{ trans(\Config::get('app.theme') . '-app.user_panel.surname_name') }}"
                                            value="<?= str_replace(',', '', $data['user']->nom_cli) ?>">
                                        {{-- pongo oculto el nombre ya que hemos deshabilitado que se pueda modificar y el input visible no es el que se envia --}}
                                        <input type="hidden" class="form-control" name="usuario"
                                            value="<?= str_replace(',', '', $data['user']->nom_cli) ?>">

                                        <input type="hidden" class="form-control" name="title_name"
                                            value="{{ trans(\Config::get('app.theme') . '-app.user_panel.surname_name') }}">

                                    </div>
                                    <div class="form-group input-group name_client col-xs-12 col-sm-3">
                                        <label class=""
                                            for="genero">{{ trans(\Config::get('app.theme') . '-app.login_register.genre') }}</label>
                                        <select name="genero" class="form-control">
                                            <option value="H" <?= $data['user']->sexo_cli == 'H' ? 'selected' : '' ?>>
                                                {{ trans(\Config::get('app.theme') . '-app.login_register.hombre') }}
                                            </option>
                                            <option value="M" <?= $data['user']->sexo_cli == 'M' ? 'selected' : '' ?>>
                                                {{ trans(\Config::get('app.theme') . '-app.login_register.mujer') }}
                                            </option>

                                        </select>
                                        <input type="hidden" class="form-control" name="title_name"
                                            value="{{ trans(\Config::get('app.theme') . '-app.login_register.genre') }}">

                                    </div>
                                    <div class="form-group input-group name_client col-xs-12 col-sm-3">
                                        <label class=""
                                            for="genero">{{ trans(\Config::get('app.theme') . '-app.user_panel.date_birthday') }}</label>
                                        <input type="date" class="form-control" name="nacimiento"
                                            value="<?= !empty($data['user']->fecnac_cli) ? date('Y-m-d', strtotime($data['user']->fecnac_cli)) : '' ?>"
                                            data-content="">
                                        <input type="hidden" class="form-control" name="title_name"
                                            value="{{ trans(\Config::get('app.theme') . '-app.user_panel.date_birthday') }}">

                                    </div>
                                @endif

                                <div class="form-group form-group-custom col-xs-12 col-sm-3">
                                    <label class=""
                                        for="telefono">{{ trans(\Config::get('app.theme') . '-app.login_register.phone') }}</label>
                                    <input type="text" name="telefono" class="form-control"
                                        placeholder="{{ trans(\Config::get('app.theme') . '-app.user_panel.phone') }}"
                                        required="" maxlength="40" value="{{ $data['user']->tel1_cli }}">
                                    <input type="hidden" name="title_telefono" class="form-control"
                                        value="{{ trans(\Config::get('app.theme') . '-app.user_panel.phone') }}">
                                </div>
                                <div class="form-group form-group-custom col-xs-12 col-sm-3">
                                    <label class=""
                                        for="movil">{{ trans(\Config::get('app.theme') . '-app.login_register.mobile') }}</label>
                                    <input type="text" name="telefono2" class="form-control"
                                        placeholder="{{ trans(\Config::get('app.theme') . '-app.login_register.mobile') }}"
                                        maxlength="40" value="{{ $data['user']->tel2_cli }}">
                                    <input type="hidden" name="title_movil" class="form-control"
                                        value="{{ trans(\Config::get('app.theme') . '-app.login_register.mobile') }}">
                                </div>
                                <div class="form-group form-group-custom col-xs-12 col-sm-6">

                                    <label class=""
                                        for="email">{{ trans(\Config::get('app.theme') . '-app.login_register.email') }}</label>
                                    <input type="text" class="form-control" id="email"
                                        placeholder="{{ trans(\Config::get('app.theme') . '-app.user_panel.email') }}"
                                        type="email" disabled name="email" value="{{ $data['user']->usrw_cliweb }}"
                                        required>
                                    <input type="hidden" class="form-control" name="title_email"
                                        value="{{ trans(\Config::get('app.theme') . '-app.user_panel.email') }}">
                                </div>
                                <div class="col-xs-12 hidden">
                                    <label
                                        for="i-want-news">{{ trans(\Config::get('app.theme') . '-app.login_register.recibir_newsletter') }}</label>
                                    <div class="checkbox">
                                        <input checked="checked" type="checkbox" class="form-control"
                                            id="i-want-news" />
                                    </div>
                                </div>

                                <div class="form-group form-group-custom col-xs-12 col-sm-5">
                                    <label
                                        for="direccion">{{ trans(\Config::get('app.theme') . '-app.user_panel.address') }}</label>
                                    <input type="text" name="direccion" class="form-control" id="direccion"
                                        placeholder="{{ trans(\Config::get('app.theme') . '-app.user_panel.address') }}"
                                        required maxlength="60"
                                        value="{{ $data['user']->dir_cli }}{{ $data['user']->dir2_cli }}">
                                    <input type="hidden" name="title_direccion" class="form-control"
                                        value="{{ trans(\Config::get('app.theme') . '-app.user_panel.address') }}">
                                </div>
                                <div class="form-group input-group col-xs-12 col-sm-4 col-md-4">
                                    <label
                                        for="pais">{{ trans(\Config::get('app.theme') . '-app.user_panel.pais') }}</label>
                                    <select id="country" name="pais" class="form-control notranslate" required>
                                        <option value="">---</option>
                                        @if (!empty($data) && !empty($data['countries']))
                                            @foreach ($data['countries'] as $country)
                                                <option
                                                    <?= !empty($data['user']->codpais_cli) && $data['user']->codpais_cli == $country->cod_paises ? 'selected' : '' ?>
                                                    value="{{ $country->cod_paises }}">{{ $country->des_paises }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <input type="hidden" name="title_pais"
                                        value="{{ trans(\Config::get('app.theme') . '-app.user_panel.pais') }}">
                                </div>
                                <div class="form-group input-group col-xs-12 col-sm-4 col-md-2">
                                    <label
                                        for="codigo_postal">{{ trans(\Config::get('app.theme') . '-app.user_panel.zip_code') }}</label>
                                    <input id="cpostal" type="text" name="cpostal" class="form-control"
                                        id="codigo_postal"
                                        placeholder="{{ trans(\Config::get('app.theme') . '-app.user_panel.zip_code') }}"
                                        required maxlength="10" value="{{ $data['user']->cp_cli }}">
                                    <input type="hidden" name="title_codigo_postal"
                                        value="{{ trans(\Config::get('app.theme') . '-app.user_panel.zip_code') }}">
                                </div>

                                <div class="form-group input-group col-xs-12 col-sm-4 col-md-5">
                                    <label
                                        for="provincia">{{ trans(\Config::get('app.theme') . '-app.login_register.provincia') }}</label>
                                    <input name="provincia" class="form-control"
                                        placeholder="{{ trans(\Config::get('app.theme') . '-app.login_register.provincia') }}"
                                        maxlength="30" type="text" value="{{ $data['user']->pro_cli }}" />
                                    <input type="hidden" name="title_provincia"
                                        value="{{ trans(\Config::get('app.theme') . '-app.login_register.provincia') }}">
                                </div>
                                <div class="form-group input-group col-xs-12 col-sm-4 col-md-5">
                                    <label
                                        for="nombre">{{ trans(\Config::get('app.theme') . '-app.user_panel.city') }}</label>
                                    <input type="text" name="poblacion" class="form-control" id="Ciudad"
                                        placeholder="{{ trans(\Config::get('app.theme') . '-app.user_panel.city') }}"
                                        required required maxlength="30" value="{{ $data['user']->pob_cli }}">
                                    <input type="hidden" name="title_poblacion"
                                        value="{{ trans(\Config::get('app.theme') . '-app.user_panel.city') }}">
                                </div>



                                <div class="col-xs-12">
                                    <button type="submit"
                                        class="button-principal">{{ trans(\Config::get('app.theme') . '-app.user_panel.save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if (config('app.useNft', false))
                        <div class="col-xs-12 col-md-9 mt-5">
                            <div class="user-account-title-content">
                                <div class="user-account-menu-title">
                                    {{ trans("$theme-app.user_panel.wallet") }}
                                </div>
                            </div>


                            <div class="col-xs-12 pt-2">

                                <form method="post" name="save-wallet" id="save-wallet">
                                    @csrf
                                    <div
                                        class="inputs-custom-group d-flex justify-content-space-between flex-wrap align-items-end">
                                        <div class="form-group input-group col-xs-12 col-md-6">
                                            <label
                                                for="wallet_direction">{{ trans("$theme-app.user_panel.wallet_direction") }}</label>
                                            <input type="text" class="form-control" name="wallet_dir"
                                                placeholder="{{ trans("$theme-app.user_panel.wallet_direction") }}"
                                                value="{{ $data['user']->wallet_cli ?? '' }}">
                                        </div>

                                        <div class="form-group col-xs-12 col-md-6" style="gap-5px">
                                            <button class="button-principal" type="submit"
                                                for="save-wallet">{{ trans("$theme-app.user_panel.save") }}</button>
                                            <button class="button-principal"
                                                id="create-wallet">{{ trans("$theme-app.user_panel.wallet_new") }}</button>
                                        </div>
                                    </div>
                                </form>

                                <div id="wallet-call-result"></div>

                            </div>

                        </div>
                    @endif

                </div>




            </div>

        </div>


        @if (!empty(Config::get('app.delivery_address')) && Config::get('app.delivery_address') == 1)
            <section class="panel-user">
                <div class="container panel">
                    <div class="row">
                        @include('pages.panel.address_shipping')
                    </div>
                </div>
            </section>
            <div id="modalDeletAddress" class="container modal-block mfp-hide" data-to="modalDeletAddress">
                <div data-to="modalDeletAddress" class="">
                    <section class="panel">
                        <div class="panel-body">
                            <div class="modal-wrapper">
                                <div class=" text-center single_item_content_">
                                    <p>{{ trans(\Config::get('app.theme') . '-app.user_panel.delete_address') }}</p><br />
                                    <input name="_token" type="hidden" id="_token" value="{{ csrf_token() }}" />
                                    <input value="" name="cod" type="hidden" id="cod_delete">
                                    <input value="<?= Config::get('app.locale') ?>" name="lang" type="hidden"
                                        id="lang">
                                    <button
                                        class=" btn button_modal_confirm modal-dismiss modal-confirm">{{ trans(\Config::get('app.theme') . '-app.lot.accept') }}</button>

                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        @endif

    </div>
@stop
