@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>

<div class="create-account color-letter">
	<div class="container register pb-5">
		<div class="row d-flex align-items-center justify-content-center">
			<div class="col-xs-12 general-container">
				<center>
					<h3>{{ trans(\Config::get('app.theme').'-app.login_register.crear_cuenta') }}</h3>
					<p>{{ trans(\Config::get('app.theme').'-app.login_register.all_fields_are_required') }}</p>
				</center>

				<form method="post" id="registerForm" action="javascript:submit_register_form()">

					<input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">
					<input class="form-control" type="hidden" name="sexo" id="sexo" value="H">
					<input class="form-control" type="hidden" name="pri_emp" id="pri_emp" value="F">

					<div class="well">

						<div class="tit">
							{{ trans(\Config::get('app.theme').'-app.login_register.personal_information') }}
						</div>

						<div class="clearfix"></div>
						<br>
							<div class="registerParticular datos_left">
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.nombre_apellido') }}</label>
								{!!$formulario->usuario!!}
							</div>
							<div class="datos_right">
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.phone') }}</label>
								{!!$formulario->telefono!!}
							</div>
						<div class="clearfix"></div>
					</div>


					<div class="well">
						<div class="tit">{{ trans(\Config::get('app.theme').'-app.login_register.cuenta') }}</div>

						<div class="datos_cuenta">

							<div>
								<label for="email">{{ trans(\Config::get('app.theme').'-app.login_register.email') }}</label>
								{!!$formulario->email!!}
							</div>
							<div>
								<label for="email">{{ trans(\Config::get('app.theme').'-app.login_register.email_confirm') }}</label>
								{!!$formulario->confirm_email!!}
							</div>

							<input style="display:none" type="password">

							<div>
								<label for="contrasena">{{ trans(\Config::get('app.theme').'-app.login_register.password') }}</label>
								{!!$formulario->password!!}
							</div>

							<div>
								<label for="confirmcontrasena">{{ trans(\Config::get('app.theme').'-app.login_register.confirm_password') }}</label>
								{!!$formulario->confirm_password!!}
							</div>

							<div class="clearfix"></div>
						</div>

                        <div class="datos_condiciones">
							<div class="checkbox-container condiciones col-xs-12 mb-1">
								{!! $formulario->condiciones!!}
								<label for="bool__1__condiciones">
									{!! trans("$theme-app.login_register.read_conditions_politic") !!}
									</label>
							</div>
							<div class="checkbox-container condiciones col-xs-12">
								{!! $formulario->newsletter !!}
								<label for="bool__0__newsletter">
									{!! trans("$theme-app.login_register.recibir_newsletter") !!}
								</label>
							</div>
							<br>
							<div class="col-xs-12 col-lg-offset-3">
								<div class="g-recaptcha" data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}" data-callback="onSubmit">
								</div>

							</div>

							<div class="clearfix"></div>
						</div>

                    </div>


					<div class="col-xs-12 text-center">
						<button type="submit" class="submitButton button-principal">
							{{ trans(\Config::get('app.theme').'-app.login_register.register') }}
						</button>
					</div>

                                        @if(!empty($formulario->subalia))
                                        {!!$formulario->subalia!!}
                                        {!!$formulario->info!!}
                                        @endif

					<div class="clearfix"></div>

				</form>
                            @if(!empty($formulario->subalia))
                                @if(\Config::get("app.locale") == 'en')
                            <form id="formToSubalia" method="post" action="https://subalia.es/registerclicli">
                                @else
                            <form id="formToSubalia" method="post" action="https://subalia.es/registerclicli">
                                @endif
                                    <input type="hidden" name="info" id="info_sent" value="">
                                    <input type="hidden" name="cod_auchouse" id="cod_auchouse_sent" value="">
                                    <input type="hidden" name="redirect" id="redirect_sent" value="">
                            </form>
                            @endif
					<br><br><br><br><br><br><br><br>
			</div>
		</div>
	</div>
</div>

	@stop
