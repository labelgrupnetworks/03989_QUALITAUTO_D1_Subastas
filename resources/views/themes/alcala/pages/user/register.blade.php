@extends('layouts.default')

@section('title')
{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')

<?php
$families = array();
?>


<div class="create-account color-letter">
	<div class="container register pb-5">
		<div class="row d-flex align-items-center justify-content-center">
			<div class="col-xs-12 general-container">
				<div class="title-register">
					<center>
						<p>{{ trans($theme.'-app.login_register.crear_cuenta') }}</p>
					</center>
				</div>

				<form method="post" id="registerForm" action="javascript:submit_register_form()">

					<input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" data-sitekey="{{ config('app.captcha_v3_public') }}" name="captcha_token" value="">

					<input class="form-control" type="hidden" name="sexo" id="sexo" value="H">
					<input class="form-control" type="hidden" name="pri_emp" id="pri_emp" value="F">

					<div class="tipo_usuario">
						<div class="particular selected" onclick="javascript:particular();">
							{{ trans($theme.'-app.login_register.particular') }}
						</div>
						<div class="empresa" onclick="javascript:empresa();">
							{{ trans($theme.'-app.login_register.empresa') }}
						</div>
					</div>











					<div class="well">

						<div class="tit">
							{{ trans($theme.'-app.login_register.personal_information') }}
						</div>


						<div class="tipo_sexo">
							<div class="hombre selected" onclick="javascript:hombre();">
								{{ trans($theme.'-app.login_register.hombre') }}
							</div>
							<div class="mujer" onclick="javascript:mujer();">
								{{ trans($theme.'-app.login_register.mujer') }}
							</div>
						</div>

						<div class="clearfix"></div>
						<br>

						<div class="datos_contacto">

							<div>
								<label>{{ trans($theme.'-app.user_panel.date_birthday') }}</label>
								{!!$formulario->fecha_nacimiento!!}
							</div>

							<div class="registerParticular">
								<label>{{ trans($theme.'-app.login_register.nombre') }}</label>
								{!!$formulario->usuario!!}
							</div>
							<div class="registerParticular apellidos">
								<label>{{ trans($theme.'-app.login_register.apellidos') }}</label>
								{!!$formulario->last_name!!}
							</div>
							<div class="registerEnterprise">
								<label>{{ trans($theme.'-app.login_register.company') }}</label>
								{!!$formulario->rsoc_cli!!}
							</div>
							<div class="registerEnterprise">
								<label>{{ trans($theme.'-app.login_register.contact') }}</label>
								{!!$formulario->contact!!}
							</div>
							<div>
								<label>{{ trans($theme.'-app.login_register.phone') }}</label>
								{!!$formulario->telefono!!}
							</div>
							<div class="movil">
								<label>{{ trans($theme.'-app.login_register.mobile') }}</label>
								{!!$formulario->movil!!}
							</div>
							<div>
								<label
									class="nif">{{ trans($theme.'-app.login_register.dni') }}</label>
								<label class="cif"
									style="display:none">{{ trans($theme.'-app.login_register.cif') }}</label>
								{!!$formulario->cif!!}
							</div>
							<div class="observaciones">
								<label>{{ trans($theme.'-app.login_register.observacion') }}</label>
								{!!$formulario->obscli!!}
							</div>
							<div class="idioma">
								<label>{{ trans($theme.'-app.login_register.language') }}</label>
								{!!$formulario->language!!}
							</div>
							<div class="moneda">
								<label>{{ trans($theme.'-app.login_register.currency') }}</label>
								{!!$formulario->divisa!!}
							</div>
						</div>

						<div class="datos_direccion">

							<div>
								<label>{{ trans($theme.'-app.login_register.via') }}</label>
								{!!$formulario->vias!!}
							</div>
							<div>
								<label>{{ trans($theme.'-app.login_register.direccion') }}</label>
								{!!$formulario->direccion!!}
							</div>
							<div>
								<label>{{ trans($theme.'-app.login_register.cod_postal') }}</label>
								{!!$formulario->cpostal!!}
							</div>
							<div>
								<labeL>{{ trans($theme.'-app.login_register.pais') }}</label>
								{!!$formulario->pais!!}
							</div>
							<div>
								<label>{{ trans($theme.'-app.login_register.ciudad') }}</label>
								{!!$formulario->poblacion!!}
							</div>
							<div>
								<label>{{ trans($theme.'-app.login_register.provincia') }}</label>
								{!!$formulario->provincia!!}
							</div>
						</div>

						<div class="clearfix"></div>
					</div>









					<!-- Bloque para dirección de envio -->

					@if(empty(\Config::get('app.delivery_address')) || !\Config::get('app.delivery_address'))


					{!!$formulario->clid!!}
					{!!$formulario->clid_pais!!}
					{!!$formulario->clid_cpostal!!}
					{!!$formulario->clid_provincia!!}
					{!!$formulario->clid_codigoVia!!}
					{!!$formulario->clid_direccion!!}

					@else

					<div class="well">
						<div class="tit">
							{{ trans($theme.'-app.login_register.title_direccion_envio') }}
						</div>

						<div>
							<input id="shipping_address" class="form-contro" name="shipping_address" type="checkbox"
								checked="true" />
							<label
								for="shipping_address">{{ trans($theme.'-app.login_register.utilizar_direcc_direccenv') }}</label>
						</div>

						<div class="clearfix"></div>


						<div class="form-group collapse" id="collapse_direccion" aria-expanded="true">

							{!!$formulario->clid!!}
							<div>
								<label>{{ trans($theme.'-app.login_register.pais') }}</label>
								{!!$formulario->clid_pais!!}
							</div>

							<div>
								<label
									for="cpostal">{{ trans($theme.'-app.login_register.cod_postal') }}</label>
								{!!$formulario->clid_cpostal!!}
							</div>

							<div>
								<label
									for="provincia">{{ trans($theme.'-app.login_register.provincia') }}</label>
								{!!$formulario->clid_provincia!!}
							</div>

							<div>
								<label
									for="poblacion">{{ trans($theme.'-app.login_register.ciudad') }}</label>
								{!!$formulario->clid_poblacion!!}
							</div>

							<div>
								<label
									for="provincia">{{ trans($theme.'-app.login_register.via') }}</label>
								{!!$formulario->clid_codigoVia!!}
							</div>
							<div>
								<label
									for="direccion">{{ trans($theme.'-app.login_register.direccion') }}</label>
								{!!$formulario->clid_direccion!!}
							</div>


						</div>
						<div class="clearfix"></div>

					</div>
					@endif
















					<div class="well">
						<div class="tit">{{ trans($theme.'-app.login_register.cuenta') }}</div>

						<div class="datos_cuenta">

							<div>
								<label
									for="email">{{ trans($theme.'-app.login_register.email') }}</label>
								{!!$formulario->email!!}
							</div>
							<div>
								<label
									for="email">{{ trans($theme.'-app.login_register.email_confirmacion') }}</label>
								{!!$formulario->confirm_email!!}
							</div>

							<input style="display:none" type="password">

							<div>
								<label
									for="contrasena">{{ trans($theme.'-app.login_register.password') }}</label>
								{!!$formulario->password!!}
							</div>

							<div>
								<label
									for="confirmcontrasena">{{ trans($theme.'-app.login_register.confirm_password') }}</label>
								{!!$formulario->confirm_password!!}
							</div>

							<div class="clearfix"></div>
						</div>

						<div class="clearfix"></div>
						@if(!empty($formulario->newsletter))
						<div class="datos_newsletter">

							@if(!empty($families) && count($families) > 0)

							<!-- con categorias newsletter -->
							<big>{{ trans($theme.'-app.login_register.recibir_newsletter') }}</big>
							<br>

							@foreach($families as $t => $item)
							<div>
								<input type="checkbox" name="families[{{$t}}]" value="1" id="{{$t}}">
								<label for="{{$t}}">{{ $item }}</label>
							</div>
							@endforeach

							@else
							<!-- sin categorias newsletter -->

							<div>
								{!! $formulario->newsletter!!}
								<label for="bool__1__condiciones">
									{{ trans($theme.'-app.login_register.recibir_newsletter') }}
								</label>
							</div>

							@endif
						</div>
						@endif
						<p class="clearfix"></p>

						<div class="datos_condiciones">

							<div>
								{!! $formulario->condiciones!!}
								<label for="bool__1__condiciones">{!!
									trans($theme.'-app.login_register.read_conditions') !!} (<a
										href="<?php echo Routing::translateSeo('pagina') . trans($theme . '-app.links.term_condition') ?>"
										target="_blank">{{ trans($theme.'-app.login_register.more_info') }}</a>)
								</label>
							</div>

							<div class="mt-1">
								<p class="captcha-terms">
									{!! trans("$theme-app.global.captcha-terms") !!}
								</p>
							</div>

							<div class="clearfix"></div>
						</div>

					</div>


					<div class="col-xs-12 text-center">
						<button type="submit" class="button-principal submitButton">
							{{ trans($theme.'-app.login_register.register') }}
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
