{!! \FormLib::Hidden('codd_clid', 1, $address->codd_clid ?? '') !!}

<div class="row">

	<div class="col-xs-12 col-md-6 mb-2">
		@if (in_array($fxcli->fisjur_cli, ['F', 'P']))
		<label for="nombre">{{ trans($theme.'-app.login_register.nombre_apellido') }}</label>
		@else
		<label for="nombre">{{ trans($theme.'-app.login_register.contact') }}</label>
		@endif
		{!! \FormLib::Text('usuario', 1, $address->nomd_clid ?? '', 'maxlength="60"') !!}
	</div>

	@if ($fxcli->fisjur_cli == 'J')
		<div class="col-xs-12 col-md-6 mb-2">
			<label for="nombre">{{ trans($theme.'-app.login_register.company') }}</label>
			{!! \FormLib::Text('clid_rsoc', 1, $address->rsoc_clid ?? '', 'maxlength="50"') !!}
		</div>
	@endif

</div>

<div class="row">

	<div class="col-xs-12 col-md-4 mb-2">
		<label>{{ trans($theme.'-app.login_register.pais') }}</label>

		@php
			$codAddress = !empty($address->codpais_clid) ? $address->codpais_clid : $fxcli->codpais_cli;
		@endphp

		{!! \FormLib::SelectWithCountries('clid_pais', $codAddress, $data['countries']) !!}



	</div>

	<div class="col-xs-12 col-md-4 mb-2">

		<div class="row">
			<div class="col-xs-3">
				<label>{{ trans($theme.'-app.login_register.prefix') }}</label>
				{!! \FormLib::Text("preftel_clid", 1, $address->preftel_clid ?? '', 'maxlength="4"') !!}
			</div>
			<div class="col-xs-9">

				<label
					for="telefono">{{ trans($theme.'-app.user_panel.phone') }}</label>
				{!! \FormLib::Text("telefono", 1, $address->tel1_clid ?? '', 'maxlength="40"') !!}
			</div>
		</div>

	</div>

	<div class="col-xs-12 col-md-4 mb-2">
		<label>{{ trans($theme.'-app.user_panel.email') }}</label>
		{!! \FormLib::Email('email_clid', 1, $address->email_clid ?? '', 'maxlength="80"') !!}
	</div>

</div>

<div class="row">
	<div class="col-xs-12 col-md-2 mb-2">
		<label>{{ trans($theme.'-app.login_register.via') }}</label>
		{!! \FormLib::Select('clid_codigoVia', 1, $address->sg_clid ?? '', $data['via'], 0, '', false) !!}
	</div>

	<div class="col-xs-12 col-md-10 mb-2">
		<label>{{ trans($theme.'-app.login_register.direccion') }}</label>
		{!! \FormLib::Text('clid_direccion', 1, ($address->dir_clid ?? '') . '' . ($address->dir2_clid ?? ''), 'maxlength="60"') !!}
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-md-4 mb-2">
		<label>{{ trans($theme.'-app.login_register.cod_postal') }}</label>
		{!! \FormLib::Text('clid_cpostal', 1, $address->cp_clid ?? '', 'maxlength="15"') !!}
	</div>

	<div class="col-xs-12 col-md-4 mb-2">
		<label>{{ trans($theme.'-app.login_register.ciudad') }}</label>
		{!! \FormLib::Text('clid_poblacion', 1, $address->pob_clid ?? '', 'maxlength="30"') !!}
	</div>

	<div class="col-xs-12 col-md-4 mb-2">
		<label>{{ trans($theme.'-app.login_register.provincia') }}</label>
		{!! \FormLib::Text('clid_provincia', 1, $address->pro_clid ?? '', 'maxlength="30"') !!}
	</div>
</div>

@if (!empty($address->codd_clid) && $address->codd_clid != 'W1')
<div class="row">
	<div class="col-xs-12">
		<button type="button" class="btn btn-color fav-address" cod="{{$address->codd_clid}}">{{ trans($theme.'-app.user_panel.define_default') }}</button>

		<button type="button" class="btn btn-color delete-address" cod="{{$address->codd_clid}}">{{ trans($theme.'-app.user_panel.delete') }}</button>
	</div>
</div>
@endif

@if (empty($address->codd_clid))
<div class="row">
	<div class="col-xs-12">
		<button type="submit" class="btn btn-color" cod="">{{ trans($theme.'-app.user_panel.save') }}</button>
	</div>
</div>
@endif

