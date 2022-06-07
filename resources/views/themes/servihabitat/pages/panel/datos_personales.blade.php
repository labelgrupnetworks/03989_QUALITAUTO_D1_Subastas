@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<div class="color-letter">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 text-center">
				<h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h1>
			</div>
		</div>
	</div>
</div>

<div class="account-user color-letter  panel-user">
	<div class="container">
		<div class="row mb-5">

			<div class="col-xs-12 col-md-3 col-lg-3 account-user-menu">
				<?php $tab="datos-personales";?>
				@include('pages.panel.menu_micuenta')
			</div>

			<div class="col-xs-12 col-md-9 col-lg-9 ">

				<div class="col-xs-12 border-password mb-3">

					<div class="user-account-menu-title extra-account">
						{{ trans(\Config::get('app.theme').'-app.user_panel.info') }}</div>

					<form method="post" class="frmLogin" id="frmUpdateUserInfoADV" data-toggle="validator">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" class="form-control">

						<div class="col_reg_form"></div>

						<div class="inputs-custom-group d-flex justify-content-space-between flex-wrap">

							<div class="form-group input-group name_client col-xs-12 col-sm-8">
								<label class="" for="nombre">{{ trans(\Config::get('app.theme').'-app.login_register.nombre_apellido') }}</label>
								<input type="text" class="form-control" name="usuario"
									placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.nombre_apellido') }}" required
									value="{{ $data['user']->nom_cli }}">
								<input type="hidden" class="form-control" name="title_name"
									value="{{ trans(\Config::get('app.theme').'-app.login_register.nombre_apellido') }}">
							</div>

							<div class="form-group form-group-custom col-xs-12 col-sm-4">
								<label class=""
									for="telefono">{{ trans(\Config::get('app.theme').'-app.login_register.phone') }}</label>
								<input type="text" name="telefono" class="form-control"
									placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.phone') }}" required=""
									maxlength="40" value="{{$data['user']->tel1_cli}}">
								<input type="hidden" name="title_telefono" class="form-control"
									value="{{ trans(\Config::get('app.theme').'-app.user_panel.phone') }}">
							</div>

							<div class="col-xs-12 mb-2">
								<button type="submit"
									class="button-principal">{{ trans(\Config::get('app.theme').'-app.user_panel.save') }}</button>
							</div>
						</div>
					</form>
				</div>

				<div class="col-xs-12 border-password">

					<div class="user-account-title-content">
						<div class="user-account-menu-title extra-account">
							{{ trans(\Config::get('app.theme').'-app.login_register.cuenta') }}</div>
					</div>

					<form method="post" class="frmLogin" id="frmUpdateUserPasswordADV" data-toggle="validator">
						<div class="inputs-custom-group d-flex justify-content-space-between flex-wrap">
							<div class="insert_msg"></div>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input style="display:none" type="password">
							<div class="form-group input-group col-xs-12 ">
								<label
									for="contrasena">{{ trans(\Config::get('app.theme').'-app.user_panel.pass') }}</label>
								<input maxlength="20" name="last_password" type="password" class="form-control"
									placeholder="Contraseña" data-minlength="4" required maxlength="8">
							</div>
							<br>
							<input style="display:none" type="email" name="email" value="{{Session::get('user.usrw')}}">

							<div class="form-group input-group col-xs-12 " style="position: relative">
								<label
									for="contrasena">{{ trans(\Config::get('app.theme').'-app.user_panel.new_pass') }}</label>
								<input maxlength="20" type="password" id="password" name="password" type="password"
									class="form-control" id="contrasena" placeholder="Contraseña" data-minlength="5"
									required maxlength="8">
								<img class="view_password eye-password"
									src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
							</div>
							<br>
							<div class="form-group input-group col-xs-12" style="position: relative">
								<label
									for="confirmcontrasena">{{ trans(\Config::get('app.theme').'-app.user_panel.new_pass_repeat') }}</label>
								<input maxlength="20" type="password" name="confirm_password" class="form-control"
									data-match="#password" id="confirmcontrasena" placeholder="Confirma contraseña"
									required>
								<img class="view_password eye-password"
									src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">

							</div>
							<div class="form-group input-group col-xs-12 col-sm-4 col-md-12">
								<button class="button-principal"
									type="submit">{{ trans(\Config::get('app.theme').'-app.user_panel.save') }}</button>
							</div>
						</div>
						<p class="error-form-validation hidden">
							{{ trans(\Config::get('app.theme').'-app.login_register.all_fields_are_required') }}</p>

					</form>
				</div>

			</div>
		</div>
	</div>

	@stop
