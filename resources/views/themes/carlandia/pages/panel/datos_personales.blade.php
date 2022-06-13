@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<div class="color-letter">
	<div class="container titlePage-container">
		<div class="row">
			<div class="col-xs-12 text-center ">
				<h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h1>
			</div>
		</div>
	</div>
</div>

<div class="account-user {{-- color-letter --}}  panel-user">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-3 col-lg-3 account-user-menu">
				<?php $tab="datos-personales";?>
				@include('pages.panel.menu_micuenta')
				<div class="col-xs-12 mt-1 border-password">
					<div class="user-account-title-content" style="padding-left: 0">
						<div class="user-account-menu-title">{{ trans(\Config::get('app.theme').'-app.login_register.cuenta') }}</div>
					</div>

					<form method="post" class="frmLogin" id="frmUpdateUserPasswordADV" data-toggle="validator">
						<div class="inputs-custom-group d-flex justify-content-space-between flex-wrap">
							<div class="insert_msg"></div>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input style="display:none" type="password">
							<div class="form-group input-group col-xs-12 ">
								<label for="contrasena">{{ trans(\Config::get('app.theme').'-app.user_panel.pass')
									}}</label>
								<input maxlength="20" name="last_password" type="password" class="form-control"
									placeholder="Contraseña" data-minlength="4" required maxlength="8">
							</div>
							<br>
							<input style="display:none" type="email" name="email" value="{{Session::get('user.usrw')}}">

							<div class="form-group input-group col-xs-12 " style="position: relative">
								<label for="contrasena">{{ trans(\Config::get('app.theme').'-app.user_panel.new_pass')
									}}</label>
								<input maxlength="20" type="password" id="password" name="password" type="password"
									class="form-control" id="contrasena" placeholder="Contraseña" data-minlength="5"
									required maxlength="8">
								<img class="view_password eye-password"
									src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
							</div>
							<br>
							<div class="form-group input-group col-xs-12" style="position: relative">
								<label for="confirmcontrasena">{{
									trans(\Config::get('app.theme').'-app.user_panel.new_pass_repeat') }}</label>
								<input maxlength="20" type="password" name="confirm_password" class="form-control"
									data-match="#password" id="confirmcontrasena" placeholder="Confirma contraseña"
									required>
								<img class="view_password eye-password"
									src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">

							</div>
							<div class="form-group input-group col-xs-12 col-sm-4 col-md-12">
								<button class="button-principal" type="submit">{{
									trans(\Config::get('app.theme').'-app.user_panel.save') }}</button>
							</div>
						</div>
						<p class="error-form-validation hidden">{{
							trans(\Config::get('app.theme').'-app.login_register.all_fields_are_required') }}</p>

					</form>
				</div>
				<div class="clearfix"></div>
				<br><br><br><br>
			</div>
			<div class="col-xs-12 col-md-9 col-lg-9 ">
				<div class="user-account-title-content">
					<div class="user-account-menu-title">{{
						trans(\Config::get('app.theme').'-app.user_panel.datos_contacto') }}</div>
				</div>
				<div class="col-xs-12">


					<form method="post" class="frmLogin" id="frmUpdateUserInfoADV" data-toggle="validator">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" class="form-control">

						<div class="col_reg_form"></div>

						<div class="inputs-custom-group d-flex justify-content-space-between flex-wrap">
							<input type="hidden" name="language" value="ES">

							@if($data['user']->fisjur_cli == 'J')
							<div class="form-group input-group name_client col-xs-12 col-sm-6">
								<label class="" for="">{{ trans(\Config::get('app.theme').'-app.login_register.company')
									}}</label>
								<input type="text" class="form-control"
									placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.company') }}"
									type="text" name="rsoc_cli" value="{{$data['user']->rsoc_cli}}" required>
								<input type="hidden" class="form-control" name="title_rsoc_cli"
									value="{{ trans(\Config::get('app.theme').'-app.login_register.company') }}">
							</div>

							<div class="form-group input-group name_client col-xs-12 col-sm-6">
								<label class="" for="apellido">{{
									trans(\Config::get('app.theme').'-app.login_register.contact') }}</label>
								<input type="text" class="form-control"
									placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}"
									type="text" name="usuario" value="{{$data['user']->nom_cli}}" required>
								<input type="hidden" class="form-control" name="title_contact"
									value="{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}">

							</div>
							@else

							<div class="form-group input-group name_client col-xs-12">
								<label class="" for="nombre">{{ trans(\Config::get('app.theme').'-app.user_panel.name')}}</label>
								<input type="text" class="form-control" name="usuario"
									placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.name') }}" required
									value="{{$data['user']->nom_cli}}">
								<input type="hidden" class="form-control" name="title_name"
									value="{{ trans(\Config::get('app.theme').'-app.user_panel.name') }}">

							</div>
							@endif


							<?php /* OCULTO GENERO Y FECHA DE NACIMIENTO



							<div class="form-group input-group name_client col-xs-12 col-sm-3">
							<label class="" for="genero">{{ trans(\Config::get('app.theme').'-app.login_register.genre') }}</label>
							<select name="genero"  class="form-control" >
								<option value="H" <?= $data['user']->sexo_cli == "H"? "selected" : ""  ?>> {{
							trans(\Config::get('app.theme').'-app.login_register.hombre') }}</option>
							<option value="M" <?=$data['user']->sexo_cli == "M"? "selected" : "" ?>> {{
								trans(\Config::get('app.theme').'-app.login_register.mujer') }}</option>

							</select>
							<input type="hidden" class="form-control" name="title_name"
								value="{{ trans(\Config::get('app.theme').'-app.login_register.genre') }}">

						</div>
						<div class="form-group input-group name_client col-xs-12 col-sm-3">
							<label class="" for="genero">{{
								trans(\Config::get('app.theme').'-app.user_panel.date_birthday') }}</label>
							<input type="date" class="form-control" name="nacimiento" value="<?= date(" Y-m-d",
								strtotime($data['user']->fecnac_cli)) ?>" data-content="" >
							<input type="hidden" class="form-control" name="title_name"
								value="{{ trans(\Config::get('app.theme').'-app.user_panel.date_birthday') }}">

						</div>



						*/ ?>

						<div class="form-group form-group-custom col-xs-12 col-sm-5">
							<label class="" for="telefono">{{
								trans(\Config::get('app.theme').'-app.login_register.phone') }}</label>
							<input type="text" name="telefono" class="form-control"
								placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.phone') }}" required=""
								maxlength="40" value="{{$data['user']->tel1_cli}}">
							<input type="hidden" name="title_telefono" class="form-control"
								value="{{ trans(\Config::get('app.theme').'-app.user_panel.phone') }}">
						</div>

						<div class="form-group form-group-custom col-xs-12 col-sm-1"></div>

						<div class="form-group form-group-custom col-xs-12 col-sm-6">

							<label class="" for="email">{{ trans(\Config::get('app.theme').'-app.login_register.email')
								}}</label>
							<input type="text" class="form-control" id="email"
								placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.email') }}"
								type="email" disabled name="email" value="{{$data['user']->usrw_cliweb}}" required>
							<input type="hidden" class="form-control" name="title_email"
								value="{{ trans(\Config::get('app.theme').'-app.user_panel.email') }}">
						</div>
						{{-- <div class="form-group form-group-custom col-xs-12 col-sm-3">
							<label>{{ trans(\Config::get('app.theme').'-app.login_register.currency') }}</label>
							<select name="divisa" class="form-control" required>
								@foreach( $data['divisa'] as $key => $value)
								<option {{ ( $data['user']->cod_div_cli == strtoupper($value->cod_div) ) ? 'selected' : '' }}
									value="{{$value->cod_div}}">{{$value->des_div}}</option>
								@endforeach
							</select>
						</div> --}}

						<div class="col-xs-12 hidden">
							<label for="i-want-news">{{
								trans(\Config::get('app.theme').'-app.login_register.recibir_newsletter') }}</label>
							<div class="checkbox">
								<input checked="checked" type="checkbox" class="form-control" id="i-want-news" />
							</div>
						</div>

						{{-- <div class="form-group input-group col-xs-12 col-sm-3">
							<label for="codigoVia">{{ trans(\Config::get('app.theme').'-app.login_register.via')
								}}</label>
							<select name="codigoVia" class="form-control">
								<option value="">----</option>
								@if (!empty($data) && !empty($data["via"]) )
								@foreach ($data["via"] as $via)
								<option {{($via->cod_sg == $data['user']->sg_cli)?'selected':'';}} value="{{ $via->cod_sg }}">{{ $via->des_sg }}</option>
								@endforeach
								@endif
							</select>
							<input type="hidden" value='{{ trans(\Config::get(' app.theme').'-app.login_register.via')
								}}' name='title_codigoVia'>
						</div> --}}

						<div class="form-group form-group-custom col-xs-12 col-sm-5">
							<label for="direccion">{{ trans(\Config::get('app.theme').'-app.user_panel.address')
								}}</label>
							<input type="text" name="direccion" class="form-control" id="direccion"
								placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.address') }}" required
								maxlength="60" value="{{$data['user']->dir_cli}}{{$data['user']->dir2_cli}}">
							<input type="hidden" name="title_direccion" class="form-control"
								value="{{ trans(\Config::get('app.theme').'-app.user_panel.address') }}">
						</div>
						<div class="form-group input-group col-xs-12 col-sm-1"></div>
						<div class="form-group input-group col-xs-12 col-sm-3">
							<label for="pais">{{ trans(\Config::get('app.theme').'-app.user_panel.pais') }}</label>
							<select id="country" name="pais" class="form-control notranslate" required>
								<option value="">---</option>
								@if (!empty($data) && !empty($data["countries"]) )
								@foreach ($data["countries"] as $country)
								<option <?=(!empty($data['user']->codpais_cli) && $data['user']->codpais_cli ==
									$country->cod_paises)? 'selected' : ''; ?> value="{{ $country->cod_paises }}">{{
									$country->des_paises }}</option>
								@endforeach
								@endif
							</select>
							<input type="hidden" name="title_pais"
								value="{{ trans(\Config::get('app.theme').'-app.user_panel.pais') }}">
						</div>
						<div class="form-group input-group col-xs-12 col-sm-1"></div>
						<div class="form-group input-group col-xs-12 col-sm-4 col-md-2">
							<label for="codigo_postal">{{ trans(\Config::get('app.theme').'-app.user_panel.zip_code')
								}}</label>
							<input id="cpostal" type="text" name="cpostal" class="form-control" id="codigo_postal"
								placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.zip_code') }}" required
								maxlength="10" value="{{$data['user']->cp_cli}}">
							<input type="hidden" name="title_codigo_postal"
								value="{{ trans(\Config::get('app.theme').'-app.user_panel.zip_code') }}">
						</div>

						<div class="form-group input-group col-xs-12 col-sm-4 col-md-5">
							<label for="provincia">{{ trans(\Config::get('app.theme').'-app.login_register.provincia')
								}}</label>
							<input name="provincia" class="form-control"
								placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}"
								maxlength="30" type="text" value="{{$data['user']->pro_cli}}" />
							<input type="hidden" name="title_provincia"
								value="{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}">
						</div>
						<div class="form-group input-group col-xs-12 col-sm-1"></div>
						<div class="form-group input-group col-xs-12 col-sm-4 col-md-6">
							<label for="nombre">{{ trans(\Config::get('app.theme').'-app.user_panel.city') }}</label>
							<input type="text" name="poblacion" class="form-control" id="Ciudad"
								placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.city') }}" required
								required maxlength="30" value="{{$data['user']->pob_cli}}">
							<input type="hidden" name="title_poblacion"
								value="{{ trans(\Config::get('app.theme').'-app.user_panel.city') }}">
						</div>



						<div class="form-group input-group col-xs-12">
							<button type="submit" class="button-principal">{{
								trans(\Config::get('app.theme').'-app.user_panel.save') }}</button>
						</div>
				</form>
			</div>
		</div>
	</div>
</div>


{{-- @if(!empty(Config::get('app.delivery_address')) && Config::get('app.delivery_address') == 1)
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
						<p>{{ trans(\Config::get('app.theme').'-app.user_panel.delete_address') }}</p><br />
						<input name="_token" type="hidden" id="_token" value="{{ csrf_token() }}" />
						<input value="" name="cod" type="hidden" id="cod_delete">
						<input value="{{Config::get('app.locale')}}" name="lang" type="hidden" id="lang">
						<button class=" btn button_modal_confirm modal-dismiss modal-confirm">{{
							trans(\Config::get('app.theme').'-app.lot.accept') }}</button>

					</div>
				</div>
			</div>
		</section>
	</div>
</div>
@endif --}}
@stop
