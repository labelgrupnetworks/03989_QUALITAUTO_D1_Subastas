
@php
use Illuminate\Support\Str;
$comesFromCounteroffering = request('counteroffer');
@endphp

<div class="create-account create-account-buyer color-letter">
	<div class="container register pb-5">
		<div class="row d-flex align-items-center justify-content-center">
			<div class="col-xs-12 general-container">

				@if(!$comesFromCounteroffering)
				<div class="well-buyer">
					<h1>
						Datos de contacto y acceso a la plataforma de Carlandia
					</h1>
				</div>
				@endif

				<form method="post" id="registerForm" action="javascript:submit_register_form()">

					<input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">
					<input class="form-control" type="hidden" name="sexo" id="sexo" value="H">
					<input class="form-control" type="hidden" name="pri_emp" id="pri_emp" value="F">
					<input type="hidden" name="backUrl" value="{{$backUrl}}">

					<div class="row d-flex flex-wrap">

						@if(!$comesFromCounteroffering)
						<div class="col-xs-12 col-sm-4 col-md-3 mt-1">
							{!!$formulario->usuario!!}
							<label>{{ trans(\Config::get('app.theme').'-app.login_register.nombre') }} <span>*</span></label>
						</div>
						<div class="apellidos col-xs-12 col-sm-4 col-md-6 mt-1">
							{!!$formulario->last_name!!}
							<label>{{ trans(\Config::get('app.theme').'-app.login_register.apellidos') }} <span>*</span></label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-3 mt-1">
							{!!$formulario->rsoc_cli!!}
							<label>Razón social (para empresas)</label>
						</div>

						<div class="col-xs-12 col-sm-4 col-md-3 mt-1">
							{{-- {!!$formulario->telefono!!} --}}
							<input type="number" minlength="9" class="form-control effect-16" name="telefono" id="texto__1__telefono" value="" onblur="comprueba_campo(this)" data-placement="right" placeholder="Teléfono *" autocomplete="off" style="padding-left: 10px;">
							<label>{{ trans(\Config::get('app.theme').'-app.login_register.phone') }} <span>*</span></label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-6 mt-1">
							{!!$formulario->poblacion!!}
							<label>{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }} <span>*</span></label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-3"> </div>

						<div class="col-xs-12 col-sm-4 col-md-3 mt-1">
							{!!$formulario->email!!}
							<label for="email">Usuario: {{ trans(\Config::get('app.theme').'-app.login_register.email')}} <span>*</span></label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-6 mt-1">
							{!!$formulario->confirm_email!!}
							<label for="email">Repetir Usuario: {{ trans(\Config::get('app.theme').'-app.login_register.email')}} <span>*</span></label>
						</div>

						<div class="col-xs-12 col-sm-4 col-md-3"></div>

						<input style="display:none" type="password">
						<div class="col-xs-12 col-sm-4 col-md-3 mt-1">
							{!!$formulario->password!!}
							<label for="contrasena">{{ trans(\Config::get('app.theme').'-app.login_register.password') }} <span>*</span></label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-6 mt-1">
							{!!$formulario->confirm_password!!}
							<label for="confirmcontrasena">Repetir {{ trans(\Config::get('app.theme').'-app.login_register.password') }} <span>*</span></label>
						</div>

						<div class="col-xs-12 col-sm-4 col-md-3"></div>

						@else

						<input id="g-recaptcha-response" type="hidden" name="g-recaptcha-response" value="true">
						<input type="hidden" name="password" value="{{ Str::random(16) }}">

						<div class="col-xs-12 col-sm-4 mt-1">
							{!!$formulario->usuario!!}
							<label>{{ trans(\Config::get('app.theme').'-app.login_register.nombre') }} <span>*</span></label>
						</div>
						<div class="apellidos col-xs-12 col-sm-8 mt-1">
							{!!$formulario->last_name!!}
							<label>{{ trans(\Config::get('app.theme').'-app.login_register.apellidos') }} <span>*</span></label>
						</div>

						<div class="col-xs-12 col-sm-4 mt-1">
							<input type="number" minlength="6" class="form-control effect-16" name="telefono" id="texto__1__telefono" value="" onblur="comprueba_campo(this)" data-placement="right" placeholder="Teléfono *" autocomplete="off">
							<label>{{ trans(\Config::get('app.theme').'-app.login_register.phone') }} <span>*</span></label>
						</div>

						<div class="col-xs-12 col-sm-4 mt-1">
							{!!$formulario->email!!}
							<label for="email">Usuario: {{ trans(\Config::get('app.theme').'-app.login_register.email')}} <span>*</span></label>
						</div>
						<div class="col-xs-12 col-sm-4 mt-1">
							{!!$formulario->confirm_email!!}
							<label for="email">Repetir Usuario: {{ trans(\Config::get('app.theme').'-app.login_register.email')}} <span>*</span></label>
						</div>

						@endif
					</div>

					<div class="row">

						<div class="col-xs-12">

							<div class="datos_condiciones mt-1">

								<div class="checkbox-container condiciones">
									{!! $formulario->condiciones!!}
									<label for="bool__1__condiciones">
										{!! trans(\Config::get('app.theme').'-app.login_register.read_conditions') !!}
									</label>


								</div>

								@if(!$comesFromCounteroffering)
								<div class="">
									<div class="g-recaptcha"
										data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"
										data-callback="onSubmit"></div>
								</div>
								@endif

								<button type="submit" class="submitButton button-principal mt-2">
									ENVIAR
								</button>

								@if($comesFromCounteroffering)
								<button type="button" class="button-principal mt-2" onclick="history.go(-1)">
									CERRAR
								</button>
								@endif

							</div>

						</div>
					</div>

				</form>

			</div>
		</div>
	</div>
</div>

<script>
	reloadPlaceholders();
	forceRequiredInputs();
</script>
