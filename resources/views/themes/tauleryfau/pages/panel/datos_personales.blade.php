@extends('layouts.panel')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
	use App\Models\User;
    use App\Models\V5\FgSg;
	use Illuminate\Support\Facades\Storage;

	$avatar = (new User)->getAvatar($data['user']->cod_cliweb);

	$countries = [];
    $prefix = [];
    $divisas = $data['divisa']->mapWithKeys(function ($item) use ($theme) {
        return [$item->cod_div => $item->cod_div . ' (' . trans($theme . '-app.user_panel.' . $item->cod_div) . ')'];
    });

    $countries_aux = \App\Models\V5\FsPaises::JoinLangPaises()
        ->addSelect('preftel_paises')
        ->orderby('des_paises')
        ->get();

    foreach ($countries_aux as $item) {
        $countries[$item->cod_paises] = $item->des_paises;
        $prefix[$item->cod_paises] = str_pad($item->preftel_paises, 4, 0, STR_PAD_LEFT);
    }

    $families = [
        2 => trans("$theme-app.user_panel.newsletter_2"),
        3 => trans("$theme-app.user_panel.newsletter_3"),
        4 => trans("$theme-app.user_panel.newsletter_4"),
        5 => trans("$theme-app.user_panel.newsletter_5"),
    ];

	$vias = collect($data['via'])->pluck('des_sg', 'cod_sg');
	$locale = Config::get('app.locale');
@endphp

@section('content')

    <script>
        const prefix = @json($prefix);
		let locale = @json($locale);
    </script>

    <section class="profile-page">

        <div class="container profile-container">
            <div class="panel-title">
                <h1>{{ trans("$theme-app.user_panel.datos_contacto") }}</h1>
            </div>

            <div class="profile-form">
                <form class="frmLogin" id="frmUpdateUserInfoADV" data-toggle="validator" method="post" enctype="multipart/form-data">
                    @csrf

					<fieldset class="profile-avatar">
						<legend>{{ trans("$theme-app.user_panel.profile_picture") }}</legend>

						<img src="{{$avatar}}" alt="avatar del usuario" width="75px" style="aspect-ratio: 1">

						<label>
							{{ trans("$theme-app.user_panel.upload_photo") }}
							<input type="file" name="avatar" style="display: none">
						</label>
					</fieldset>

                    <fieldset>
                        <legend>{{ trans("$theme-app.user_panel.billing_address") }}</legend>

                        <div class="">
                            <label>{{ trans($theme . '-app.login_register.pais') }}</label>
                            {!! \FormLib::SelectWithCountries('pais', $data['user']->codpais_cli, $countries) !!}
                        </div>

                        @if ($data['user']->fisjur_cli == 'J')
                            <div class="row">
                                <div class="col-xs-12 col-md-5">
                                    <div class="form-group">
                                        <label>{{ trans($theme . '-app.login_register.company') }}</label>
                                        {!! \FormLib::Text('rsoc_cli', 1, $data['user']->rsoc_cli) !!}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-7">
                                    <div class="form-group">
                                        <label>{{ trans($theme . '-app.login_register.contact') }}</label>
                                        {!! \FormLib::Text('usuario', 1, $data['user']->nom_cli) !!}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="nombre">{{ trans($theme . '-app.user_panel.name') }}</label>
                                {!! \FormLib::Text('usuario', 1, $data['user']->nom_cli) !!}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <label>{{ trans("$theme-app.user_panel.dni") }}</label>
                                    {!! \FormLib::TextReadOnly('cif', 1, $data['user']->cif_cli, '', trans("$theme-app.user_panel.dni")) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <label>{{ trans("$theme-app.user_panel.date_birthday") }}</label>
                                    {!! FormLib::Date('nacimiento', 1, $data['user']->fecnac_cli, 0) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>{{ trans($theme . '-app.login_register.via') }}</label>
                                    {!! FormLib::TextReadOnly('codigoVia', 1, $data['user']->sg_cli) !!}
                                </div>
                            </div>
                            <div class="col-xs-9">
                                <div class="form-group">
                                    <label>{{ trans("$theme-app.login_register.direccion") }}</label>
                                    {!! FormLib::TextReadOnly('direccion', 1, $data['user']->dir_cli . $data['user']->dir2_cli) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>{{ trans("$theme-app.login_register.cod_postal") }}</label>
                                    {!! FormLib::TextReadOnly('cpostal', 1, $data['user']->cp_cli) !!}
                                </div>
                            </div>
                            <div class="col-xs-9">
                                <div class="form-group">
                                    <label>{{ trans("$theme-app.login_register.ciudad") }}</label>
                                    {!! FormLib::TextReadOnly('poblacion', 1, $data['user']->pob_cli) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ trans("$theme-app.login_register.provincia") }}</label>
                            {!! FormLib::TextReadOnly('provincia', 1, $data['user']->pro_cli) !!}
                        </div>
                    </fieldset>

                    <fieldset class="address-form-section">
                        <legend>{{ trans("$theme-app.user_panel.my_shipping_addresses") }}</legend>
                        <div class="list-group" role="tablist">

                            @foreach ($data['shippingaddress'] as $address)
                                <a class="list-group-item" data-toggle="collapse" type="button" href="#address_form_html"
                                    aria-controls="address_{{ $address->codd_clid }}" aria-expanded="false"
                                    cod="{{ $address->codd_clid }}">
                                    {{ $address->obs_clid ?? $address->dir_clid }}
                                    @if ($address->codd_clid == 'W1')
                                        <span>*{{ trans("$theme-app.user_panel.default") }}</span>
                                    @endif
                                </a>
                            @endforeach

                            <a class="list-group-item" data-toggle="collapse" type="button" href="#address_form_html"
                                aria-controls="address_new" aria-expanded="false" cod="new">
                                + {{ trans("$theme-app.user_panel.add_new_address") }}
                            </a>
                        </div>

                        <div class="address-form">
                            <div class="collapse" id="address_form_html">
                                <div id="ajax_shipping_add"></div>
                            </div>
                        </div>

                    </fieldset>

                    <fieldset>
                        <legend>{{ trans("$theme-app.login_register.contact") }}</legend>

                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label>{{ trans("$theme-app.login_register.prefix") }}</label>
                                    {!! \FormLib::Text('preftel_cli', 1, $data['user']->preftel_cli ?? '', 'maxlength="4"') !!}
                                </div>
                            </div>
                            <div class="col-xs-9">
                                <div class="form-group">
                                    <label>{{ trans("$theme-app.login_register.phone") }}</label>
                                    {!! \FormLib::Text('telefono', 1, $data['user']->tel1_cli, 0) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ trans("$theme-app.user_panel.email") }}</label>
                            {!! \FormLib::TextReadOnly('email', 1, $data['user']->usrw_cliweb) !!}
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>{{ trans("$theme-app.user_panel.preferences") }}</legend>

                        <div class="form-group">
                            <label>{{ trans($theme . '-app.login_register.currency') }}</label>
                            {!! FormLib::Select('divisa', 1, $data['user']->cod_div_cli, $divisas, 0, '', false) !!}
                        </div>

                        <div class="">
                            <label>{{ trans($theme . '-app.login_register.language') }}</label>
                            {!! \FormLib::SelectWithCountries('language', $data['user']->idioma_cli, $data['language']) !!}
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>{{ trans("$theme-app.user_panel.preferred_auction") }}</legend>

                        @foreach ($families as $key => $item)
                            @php($prop = 'nllist' . $key . '_cliweb')
                            <div class="checkbox">
                                <label>
                                    <input name="families[]" type="checkbox" value="{{ $key }}"
                                        @checked($data['user']->{"nllist{$key}_cliweb"} == 'S')>
                                    {{ $item }}
                                </label>
                            </div>
                        @endforeach
                    </fieldset>

                    <div class="btn-wrapp">
                        <button class="btn btn-lb btn-lb-primary btn-update" type="submit">
                            {{ trans($theme . '-app.user_panel.save') }}

                        </button>
                    </div>
                </form>

                <form class="frmLogin" id="frmUpdateUserPasswordADV" data-toggle="validator" method="post">
                    @csrf
                    <input type="password" style="display:none">
                    <input name="email" type="email" value="{{ Session::get('user.usrw') }}" style="display:none">

                    <fieldset>
                        <legend>{{ trans("$theme-app.user_panel.change_password") }}</legend>

                        <div class="form-group">
                            <label for="contrasena">{{ trans($theme . '-app.user_panel.pass') }}</label>
                            <input class="form-control" name="last_password" data-minlength="4" type="password"
                                maxlength="20" placeholder="Contraseña" required maxlength="8">
                        </div>

                        <div class="form-group">
                            <label for="contrasena">{{ trans($theme . '-app.user_panel.new_pass') }}</label>
                            <input class="form-control" id="password" id="contrasena" name="password" data-minlength="5"
                                type="password" type="password" maxlength="20" placeholder="Contraseña" required
                                maxlength="8">
                        </div>

                        <div class="form-group">
                            <label for="confirmcontrasena">{{ trans($theme . '-app.user_panel.new_pass_repeat') }}</label>
                            <input class="form-control" id="confirmcontrasena" name="confirm_password"
                                data-match="#password" type="password" maxlength="20" placeholder="Confirma contraseña"
                                required>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-lb btn-lb-primary btn-update" type="submit">
                                {{ trans($theme . '-app.user_panel.save') }}
                            </button>
                        </div>

                    </fieldset>

                </form>
            </div>
        </div>

    </section>

    <div class="container modal-block mfp-hide" id="modalDeletAddress" data-to="modalDeletAddress">
        <div class="" data-to="modalDeletAddress">
            <section class="panel">
                <div class="panel-body">
                    <div class="modal-wrapper">
                        <div class=" text-center single_item_content_">
                            <p>{{ trans($theme . '-app.user_panel.delete_address') }}</p><br>
                            <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}" />
                            <input id="cod_delete" name="cod" type="hidden" value="">
                            <input id="lang" name="lang" type="hidden"
                                value="{{ Config::get('app.locale') }}">
                            <button class=" btn button_modal_confirm modal-dismiss modal-confirm">
                                {{ trans($theme . '-app.lot.accept') }}
                            </button>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

@stop
