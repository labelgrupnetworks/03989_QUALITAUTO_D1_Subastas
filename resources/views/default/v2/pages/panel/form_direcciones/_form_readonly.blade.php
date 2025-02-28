<div class="row">

	<div class="col-xs-12 col-md-6 mb-2">
		@if (in_array($user->fisjur_cli, ['F', 'P']))
		<label for="nombre">{{ trans('web.login_register.nombre_apellido') }}</label>
		@else
		<label for="nombre">{{ trans('web.login_register.contact') }}</label>
		@endif
		{!! \FormLib::TextReadOnly('nom', 1, $user->nom_cli ?? '') !!}
	</div>

	@if ($user->fisjur_cli == 'J')
		<div class="col-xs-12 col-md-6 mb-2">
			<label for="nombre">{{ trans('web.login_register.company') }}</label>
			{!! \FormLib::TextReadOnly('cli_rsoc', 1, $user->rsoc_cli ?? '') !!}
		</div>
	@endif

</div>

<div class="row">

	<div class="col-xs-12 col-md-4 mb-2">
		<label>{{ trans('web.login_register.pais') }}</label>
		{!! \FormLib::SelectWithCountries('cli_pais', $user->codpais_cli ?? '', $data['countries'], true) !!}
	</div>

	<div class="col-xs-12 col-md-4 mb-2">

		<div class="row">
			<div class="col-xs-3">
				<label>{{ trans('web.login_register.prefix') }}</label>
				{!! \FormLib::TextReadOnly("preftel_cli", 1 , $user->preftel_cli ?? '') !!}
			</div>
			<div class="col-xs-9">

				<label
					for="telefono">{{ trans('web.user_panel.phone') }}</label>
				{!! \FormLib::TextReadOnly("telefono", 1, $user->tel1_cli ?? '', 0) !!}
			</div>
		</div>

	</div>

	<div class="col-xs-12 col-md-4 mb-2">
		<label>{{ trans('web.user_panel.email') }}</label>
		{!! \FormLib::TextReadOnly('email_cli', 1, $user->email_cli ?? '') !!}
	</div>

</div>

<div class="row">
	<div class="col-xs-12 col-md-2 mb-2">
		<label>{{ trans('web.login_register.via') }}</label>
		{!! \FormLib::TextReadOnly('cli_codigoVia', 1, !empty($user->sg_cli) ? $data['via'][$user->sg_cli] : '') !!}
	</div>

	<div class="col-xs-12 col-md-10 mb-2">
		<label>{{ trans('web.login_register.direccion') }}</label>
		{!! \FormLib::TextReadOnly('cli_direccion', 1, ($user->dir_cli ?? '') . ($user->dir2_cli ?? '') ) !!}
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-md-4 mb-2">
		<label>{{ trans('web.login_register.cod_postal') }}</label>
		{!! \FormLib::TextReadOnly('cli_cpostal', 1, $user->cp_cli ?? '' ) !!}
	</div>

	<div class="col-xs-12 col-md-4 mb-2">
		<label>{{ trans('web.login_register.ciudad') }}</label>
		{!! \FormLib::TextReadOnly('cli_poblacion', 1, $user->pob_cli ?? '' ) !!}
	</div>

	<div class="col-xs-12 col-md-4 mb-2">
		<label>{{ trans('web.login_register.provincia') }}</label>
		{!! \FormLib::TextReadOnly('cli_provincia', 1, $user->pro_cli ?? '' ) !!}
	</div>
</div>

{{--
<div class="row">
	<div class="col-xs-12">
		<i class="fa fa-info-circle" aria-hidden="true"></i> {!! trans('web.user_panel.billing_address_info') !!}
	</div>
</div>
--}}

