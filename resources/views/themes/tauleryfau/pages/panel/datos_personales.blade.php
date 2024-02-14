@extends('layouts.default')

@section('title')
{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')

@include('pages.panel.principal_bar')

@php
$countries = array();
$prefix = array();

$divisas = $data['divisa']->mapWithKeys(function ($item) use ($theme) {
	return [$item->cod_div => $item->cod_div . " (" . trans($theme.'-app.user_panel.' . $item->cod_div) . ")" ];
});

$countries_aux = \App\Models\V5\FsPaises::JoinLangPaises()->addSelect('preftel_paises')->orderby("des_paises")->get();

foreach($countries_aux as $item) {
	$countries[$item->cod_paises] = $item->des_paises;
	$prefix[$item->cod_paises] = str_pad($item->preftel_paises, 4, 0, STR_PAD_LEFT);
}

$families = [
	2 => trans("$theme-app.user_panel.newsletter_2"),
	3 => trans("$theme-app.user_panel.newsletter_3"),
	4 => trans("$theme-app.user_panel.newsletter_4"),
	5 => trans("$theme-app.user_panel.newsletter_5")
];
@endphp


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<section class="account">
	<div class="container">
		<div class="row">
			<?php $tab="datos-personales"; ?>

			<div class="col-xs-12">
				@include('pages.panel.menu')
			</div>

			<div class="col-xs-12">

				<div class="user-datas-title">
					<p>{{ trans($theme.'-app.user_panel.datos_contacto') }}</p>
					{{-- <p class="error-form-validation" style="font-size: 12px; font-weight: 400;">
						{{ trans($theme.'-app.login_register.all_fields_are_required') }}
					</p>
					--}}
					<div class="col_reg_form"></div>
				</div>

			</div>

			<div class="col-xs-12">

				<form method="post" class="frmLogin" id="frmUpdateUserInfoADV" data-toggle="validator">
					@csrf

					{{-- La direccion las debemos mantener para que no se elimine --}}
					{!! \FormLib::Hidden('codigoVia', 0, $data['user']->sg_cli) !!}
					{!! \FormLib::Hidden('direccion', 0, $data['user']->dir_cli . $data['user']->dir2_cli) !!}
					{!! \FormLib::Hidden('cpostal', 0, $data['user']->cp_cli) !!}
					{!! \FormLib::Hidden('provincia', 0, $data['user']->pro_cli) !!}
					{!! \FormLib::Hidden('poblacion', 0, $data['user']->pob_cli) !!}

					<div class="row">

						@if($data['user']->fisjur_cli == 'J' )

						<div class="col-xs-12 col-md-3 mb-2">
							<label>{{ trans($theme.'-app.login_register.company') }}</label>
							{!! \FormLib::Text('rsoc_cli', 1, $data['user']->rsoc_cli) !!}
						</div>

						<div class="col-xs-12 col-md-3 mb-2">
							<label>{{ trans($theme.'-app.login_register.contact') }}</label>
							{!! \FormLib::Text('usuario', 1, $data['user']->nom_cli) !!}
						</div>

						@else

						<div class="col-xs-12 col-md-4 mb-2">
							<label for="nombre">{{ trans($theme.'-app.user_panel.name') }}</label>
							{!! \FormLib::Text('usuario', 1, $data['user']->nom_cli) !!}
						</div>

						@endif

						<div class="col-xs-12 col-md-6 mb-2">
							<label>{{ trans($theme.'-app.login_register.pais') }}</label>

							{!! \FormLib::SelectWithCountries('pais', $data['user']->codpais_cli, $countries) !!}
						</div>

					</div>


					<div class="row">

						<div class="col-xs-12 col-md-3 mb-2">
							<label>{{ trans("$theme-app.user_panel.dni") }}</label>
							{!! \FormLib::TextReadOnly('cif', 1, $data['user']->cif_cli) !!}
						</div>

						<div class="col-xs-12 col-md-4 mb-2">

							<div class="row">
								<div class="col-xs-3">
									<label>{{ trans($theme.'-app.login_register.prefix') }}</label>
									{!! \FormLib::Text("preftel_cli", 1, $data['user']->preftel_cli ?? '', 'maxlength="4"') !!}
								</div>
								<div class="col-xs-9">

									<label
										for="telefono">{{ trans($theme.'-app.user_panel.phone') }}</label>
									{!! \FormLib::Text("telefono",1,$data['user']->tel1_cli,0) !!}
								</div>
							</div>

						</div>

						<div class="col-xs-12 col-md-5 mb-2">
							<label>{{ trans($theme.'-app.user_panel.email') }}</label>
							{!! \FormLib::TextReadOnly('email', 1, $data['user']->usrw_cliweb) !!}
						</div>

					</div>


					<div class="row mt-1">

						<div class="col-xs-12 mb-1">
							<div class="user-datas-title m-0">
								<p>{{ trans("$theme-app.user_panel.my_profile_settings") }}</p>
							</div>
						</div>

						<div class="col-xs-12 col-md-4 mb-2 @if(count(Config::get('app.locales')) == 1) hidden @endif">
							<label>{{ trans($theme.'-app.login_register.language') }}</label>
							{!! \FormLib::SelectWithCountries('language', $data['user']->idioma_cli, $data['language']) !!}
						</div>

						<div class="col-xs-12 col-md-4 mb-2">
							<label>{{ trans($theme.'-app.login_register.currency') }}</label>
							{!! FormLib::Select("divisa", 1, $data['user']->cod_div_cli, $divisas, 0, '', false) !!}
						</div>

						<div class="col-xs-12 col-md-4 mb-2">
							<label>{{ trans("$theme-app.user_panel.preferred_categories") }}</label>
							<select class="js-select-categorias form-control" name="families[]" multiple="multiple">
								@foreach ($families as $key => $item)
								@php($prop = "nllist" . $key ."_cliweb")
								<option value="{{$key}}" @if ($data['user']->$prop == 'S') selected @endif>{{$item}}
								</option>
								@endforeach
							</select>
						</div>



						<div class="col-xs-12 text-center">
							<button type="submit"
								class="btn btn-color btn-update">{{ trans($theme.'-app.user_panel.save') }}</button>
						</div>

					</div>

				</form>

			</div>



			<div class="col-xs-12 mb-3 mt-3">

				<div class="row">
					<div class="col-xs-12 mb-1">
						<div class="user-datas-title">
							<p>{{ trans($theme.'-app.login_register.cuenta') }}</p>
						</div>
					</div>
				</div>

				<div class="row mt-1">

					<form method="post" class="frmLogin" id="frmUpdateUserPasswordADV" data-toggle="validator">
						<div class="insert_msg"></div>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input style="display:none" type="password">
						<input style="display:none" type="email" name="email" value="{{Session::get('user.usrw')}}">

						<div class="col-xs-12 col-md-4 mb-2">
							<label
								for="contrasena">{{ trans($theme.'-app.user_panel.pass') }}</label>
							<input maxlength="20" name="last_password" type="password" class="form-control"
								placeholder="Contraseña" data-minlength="4" required maxlength="8">
						</div>

						<div class="col-xs-12 col-md-4 mb-2">
							<label
								for="contrasena">{{ trans($theme.'-app.user_panel.new_pass') }}</label>
							<input maxlength="20" type="password" id="password" name="password" type="password"
								class="form-control" id="contrasena" placeholder="Contraseña" data-minlength="5"
								required maxlength="8">
						</div>

						<div class="col-xs-12 col-md-4 mb-2">
							<label
								for="confirmcontrasena">{{ trans($theme.'-app.user_panel.new_pass_repeat') }}</label>
							<input maxlength="20" type="password" name="confirm_password" class="form-control"
								data-match="#password" id="confirmcontrasena" placeholder="Confirma contraseña"
								required>
						</div>

						<div class="col-xs-12 text-center">
							<button class="btn btn-color btn-update"
								type="submit">{{ trans($theme.'-app.user_panel.save') }}</button>
						</div>

					</form>

				</div>

			</div>


		</div>
	</div>
</section>



<script>
const prefix = @json($prefix);

$(function () {
	reloadPrefix();
	$('select[name="pais"]').on('change', reloadPrefix);
	$('.js-select-categorias').select2();
});


function reloadPrefix() {
	$(`input[name='preftel_cli']`).val(prefix[$(`select[name='pais']`).val()]);
}
</script>

@stop
