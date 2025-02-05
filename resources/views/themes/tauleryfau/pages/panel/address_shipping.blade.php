@php
    extract($data);
	$countries = collect($countries)->pluck('des_paises', 'cod_paises');
	$vias = collect($via)->pluck('des_sg', 'cod_sg');
@endphp

@csrf
{!! \FormLib::Hidden('codd_clid', 1, $address->codd_clid ?? '') !!}
{!! \FormLib::Hidden('lang_dirreciones', 1, Config::get('app.locale')) !!}

<div class="form-group">
    <label>
        Alias
        {!! FormLib::Text('rsoc2_clid', 1, $address->rsoc2_clid ?? '', 'maxlength="40"') !!}
    </label>
</div>

<div class="form-group">
    <label>
        @if (in_array($user->fisjur_cli, ['F', 'P']))
            {{ trans("$theme-app.login_register.nombre_apellido") }}
        @else
            {{ trans("$theme-app.login_register.contact") }}
        @endif
        {!! \FormLib::Text('usuario', 1, $address->nomd_clid ?? '', 'maxlength="60"') !!}
    </label>
</div>

@if ($user->fisjur_cli == 'J')
    <div class="form-group">
        <label>
            {{ trans($theme . '-app.login_register.company') }}
            {!! \FormLib::Text('clid_rsoc', 1, $address->rsoc_clid ?? '', 'maxlength="50"') !!}
        </label>
    </div>
@endif

<div class="row">
    <div class="col-xs-3">
        <div class="form-group">
            <label>{{ trans($theme . '-app.login_register.via') }}</label>
            {!! \FormLib::Select('clid_codigoVia', 1, $address->sg_clid ?? '', $vias, 0, '', false) !!}
        </div>
    </div>
    <div class="col-xs-9">
        <div class="form-group">
            <label>{{ trans("$theme-app.login_register.direccion") }}</label>
            {!! \FormLib::Text(
                'clid_direccion',
                1,
                ($address->dir_clid ?? '') . '' . ($address->dir2_clid ?? ''),
                'maxlength="60"',
            ) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-3">
        <div class="form-group">
            <label>{{ trans("$theme-app.login_register.cod_postal") }}</label>
            {!! \FormLib::Text('clid_cpostal', 1, $address->cp_clid ?? '', 'maxlength="15"') !!}
        </div>
    </div>
    <div class="col-xs-9">
        <div class="form-group">
            <label>{{ trans("$theme-app.login_register.ciudad") }}</label>
            {!! \FormLib::Text('clid_poblacion', 1, $address->pob_clid ?? '', 'maxlength="30"') !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label>{{ trans("$theme-app.login_register.provincia") }}</label>
            {!! \FormLib::Text('clid_provincia', 1, $address->pro_clid ?? '', 'maxlength="30"') !!}
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        @php
            $codAddress = !empty($address->codpais_clid) ? $address->codpais_clid : $user->codpais_cli;
        @endphp
        <label>{{ trans($theme . '-app.login_register.pais') }}</label>
        {!! \FormLib::SelectWithCountries('clid_pais', $codAddress, $countries) !!}
    </div>
</div>

<div class="row">
    <div class="col-xs-3">
        <div class="form-group">
            <label>{{ trans("$theme-app.login_register.prefix") }}</label>
            {!! \FormLib::Text('preftel_clid', 1, $address->preftel_clid ?? '', 'maxlength="4"') !!}
        </div>
    </div>
    <div class="col-xs-9">
        <div class="form-group">
            <label>{{ trans("$theme-app.login_register.phone") }}</label>
            {!! \FormLib::Text('telefono', 1, $address->tel1_clid ?? '', 'maxlength="40"') !!}
        </div>
    </div>
</div>

<div class="form-group">
    <label>{{ trans("$theme-app.user_panel.email") }}</label>
    {!! \FormLib::Email('email_clid', 1, $address->email_clid ?? '', 'maxlength="80"') !!}
</div>

<div class="">
    <button class="btn btn-lb btn-lb-primary" type="button" onclick="saveAddress(this)">
        {{ trans($theme . '-app.user_panel.save') }}
    </button>

    @if (!empty($address->codd_clid) && $address->codd_clid != 'W1')
        <button class="btn btn-lb btn-lb-primary fav-address" type="button" cod="{{ $address->codd_clid }}"
            onclick="favAddress(this)">
            {{ trans($theme . '-app.user_panel.define_default') }}
        </button>

        <button class="btn btn-lb btn-lb-danger  delete-address" type="button" cod="{{ $address->codd_clid }}"
            onclick="deleteAddress(this)">
            {{ trans($theme . '-app.user_panel.delete') }}
        </button>
    @endif
</div>

<script>
    $(function() {
        reloadPrefix('clid_pais', 'preftel_clid');
        $('.selectpicker').selectpicker();
        $('select[name="clid_pais"]').on('change', () => reloadPrefix('pais', 'preftel_cli'));
    });
</script>
