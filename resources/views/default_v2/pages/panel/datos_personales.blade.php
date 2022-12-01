@extends('layouts.default')

@php


if ($data['user']->fisjur_cli == 'F' || $data['user']->fisjur_cli == null) {

	$name = explode(',', $data['user']->nom_cli);

	if (count($name) != 2) {
        $name[1] = $data['user']->nom_cli;
        $name[0] = '';
    }
}
@endphp

@section('content')
    <section class="container user-panel-page account-page">
        <div class="row">
            <div class="col-lg-3">
                @include('pages.panel.menu_micuenta')
            </div>

            <div class="col-lg-9">
				<section class="account-info mb-5">
					<h1>{{ trans("$theme-app.user_panel.datos_contacto") }}</h1>

					<form method="post" class="frmLogin" id="frmUpdateUserInfoADV" data-toggle="validator">
						@csrf
						<div class="col_reg_form"></div>

						<div class="row gy-1">

							<div class="col-md-4">
								<label class="form-label">
									{{ trans("$theme-app.login_register.language") }}
									<select name="language" class="form-select" required>
                                        @foreach ($data['language'] as $key => $value)
                                            <option value="{{ strtoupper($key) }}" @selected($data['user']->idioma_cli == strtoupper($key))>{{ $value }}</option>
                                        @endforeach
                                    </select>
								</label>
							</div>

							<div class="col-md-4">
								<label class="form-label">
									@if ($data['user']->fisjur_cli == 'J')
									{{ trans("$theme-app.login_register.company") }}
									<input type="text" class="form-control" name="rsoc_cli" required value="{{ $data['user']->rsoc_cli }}">
									@else
									{{ trans("$theme-app.user_panel.name") }}
									<input type="text" class="form-control" name="usuario" required value="{{ $name[1] }}">
									@endif
								</label>
							</div>

							<div class="col-md-4">
								<label class="form-label">
									@if ($data['user']->fisjur_cli == 'J')
									{{ trans("$theme-app.login_register.contact") }}
									<input type="text" class="form-control" name="usuario" required value="{{ $data['user']->nom_cli }}">
									@else
									{{ trans("$theme-app.login_register.apellidos") }}
									<input type="text" class="form-control" name="last_name" required value="{{ $name[0] }}">
									@endif
								</label>
							</div>

							<div class="col-md-4">
								<label class="form-label">
									{{ trans("$theme-app.login_register.phone") }}
									<input type="text" class="form-control" name="telefono" required maxlength="40" value="{{ $data['user']->tel1_cli }}">
								</label>
							</div>

							<div class="col-md-4">
								<label class="form-label">
									{{ trans("$theme-app.user_panel.email") }}
									<input type="text" class="form-control" name="email" disabled required value="{{ $data['user']->usrw_cliweb }}">
								</label>
							</div>

							<div class="col-md-4">
								<label class="form-label">
									{{ trans("$theme-app.login_register.currency") }}
									<select name="divisa" class="form-select" required>
                                        @foreach ($data['divisa'] as $key => $value)
                                            <option value="{{ $value->cod_div }}" @selected($data['user']->cod_div_cli == strtoupper($value->cod_div))>
												{{ $value->des_div }}
											</option>
                                        @endforeach
                                    </select>
								</label>
							</div>

							<div class="col-md-4">
								<label class="form-label">
									{{ trans("$theme-app.user_panel.pais") }}
									<select name="pais" class="form-select" required>
                                        @foreach ($data['countries'] as $country)
                                            <option value="{{ $country->cod_paises }}" @selected($data['user']->codpais_cli == $country->cod_paises)>
												{{ $country->des_paises }}
											</option>
                                        @endforeach
                                    </select>
								</label>
							</div>

							<div class="col-md-4">
								<label class="form-label">
									{{ trans("$theme-app.user_panel.zip_code") }}
									<input type="text" class="form-control" name="cpostal" required maxlength="10" value="{{ $data['user']->cp_cli }}">
								</label>
							</div>

							<div class="col-md-4">
								<label class="form-label">
									{{ trans("$theme-app.login_register.provincia") }}
									<input type="text" class="form-control" name="provincia" required maxlength="30" value="{{ $data['user']->pro_cli }}">
								</label>
							</div>

							<div class="col-md-4">
								<label class="form-label">
									{{ trans("$theme-app.user_panel.city") }}
									<input type="text" class="form-control" name="poblacion" required maxlength="30" value="{{ $data['user']->pob_cli }}">
								</label>
							</div>

							<div class="col-md-2">
								<label class="form-label">
									{{ trans("$theme-app.login_register.via") }}
									<select name="codigoVia" class="form-select" required>
                                        @foreach ($data['via'] as $via)
                                            <option value="{{ $via->cod_sg }}" @selected($via->cod_sg == $data['user']->sg_cli)>
												{{ $via->des_sg }}
											</option>
                                        @endforeach
                                    </select>
								</label>
							</div>

							<div class="col-md-6">
								<label class="form-label">
									{{ trans("$theme-app.user_panel.address") }}
									<input type="text" class="form-control" name="direccion" required maxlength="60" value="{{ $data['user']->dir_cli }}{{ $data['user']->dir2_cli }}">
								</label>
							</div>

							<div class="col-12">
								<label class="form-check-label">
									<input checked="checked" type="checkbox" class="form-check-input" id="i-want-news" />
									{{ trans("$theme-app.login_register.recibir_newsletter") }}
								</label>
							</div>
							<div class="col-12">
								<button type="submit" class="btn btn-lb-primary">{{ trans("$theme-app.user_panel.save") }}</button>
							</div>
						</div>
					</form>
				</section>

				{{-- cuenta de usuario --}}
				<section class="account-user mb-5">
					<h1>{{ trans("$theme-app.login_register.cuenta") }}</h1>

					<form method="post" class="frmLogin" id="frmUpdateUserPasswordADV" data-toggle="validator">
						@csrf
						<input class="d-none" name="email" value="{{ Session::get('user.usrw') }}">

						<div class="insert_msg"></div>

						<div class="row gy-1">

							<div class="col-md-4">
								<label class="form-label">
									{{ trans("$theme-app.user_panel.pass") }}
									<div class="input-group">
										<input class="form-control" type="password" name="last_password"
											required data-minlength="4" maxlength="20" autocomplete="off">
										<span class="input-group-text view_password">
											<img class="eye-password" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
										</span>
									</div>
								</label>
							</div>

							<div class="col-md-4">
								<label class="form-label">
									{{ trans("$theme-app.user_panel.new_pass") }}
									<div class="input-group">
										<input type="password" class="form-control" name="password"
											required data-minlength="5" maxlength="20" id="contrasena">
										<span class="input-group-text view_password">
											<img class="eye-password" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
										</span>
									</div>

								</label>
							</div>

							<div class="col-md-4">
								<label class="form-label">
									{{ trans("$theme-app.user_panel.new_pass_repeat") }}
									<div class="input-group">
										<input type="password" class="form-control" name="confirm_password"
											required maxlength="20" data-match="#contrasena" id="confirmcontrasena">
										<span class="input-group-text view_password">
											<img class="eye-password" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
										</span>
									</div>

								</label>
							</div>

							<div class="col-12">
								<button type="submit" class="btn btn-lb-primary">{{ trans("$theme-app.user_panel.save") }}</button>
							</div>

						</div>

					</form>
				</section>

				{{-- wallet de usuario --}}
				@if (config('app.useNft', false))
				<section class="account-user-wallet">
					@include('front::pages.panel.user_panel.wallet')
				</section>
				@endif

            </div>
        </div>
    </section>
@stop
