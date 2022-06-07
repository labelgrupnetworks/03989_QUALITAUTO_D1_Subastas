<style>
	.registerEnterprise {
		display: block;
	}

	.registerParticular {
		display: none;
	}
</style>
<div class="create-account color-letter">
	<div class="container register pb-5">
		<div class="row d-flex align-items-center justify-content-center">
			<div class="col-xs-12 general-container">
				<h3>{{ trans(\Config::get('app.theme').'-app.login_register.crear_cuenta') }} {{ trans("$theme-app.login_register.seller") }} </h3>
				<p class="mb-2">{{ trans(\Config::get('app.theme').'-app.login_register.all_fields_are_required') }}</p>

				<form method="post" id="registerForm" action="javascript:submit_register_form()">

					<input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">
					<input class="form-control" type="hidden" name="sexo" id="sexo" value="H">
					<input class="form-control" type="hidden" name="pri_emp" id="pri_emp" value="J">

					<input class="form-control" type="hidden" name="type_user"
						value="{{ \App\Models\V5\FxCli::TIPO_CLI_VENDEDOR }}">

					<div class="well">

						<div class="tit uppercase-text">
							{{'Datos para carlandia'}}
						</div>

						<div class="row d-flex flex-wrap">
							<div class="registerEnterprise col-xs-12 col-sm-4 col-md-3 mt-4">
								{!!$formulario->rsoc_cli!!}
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.company') }}</label>
							</div>
							<div class="registerEnterprise col-xs-12 col-sm-4 col-md-3 mt-4">
								{!!$formulario->contact!!}
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}</label>
							</div>

							<div class="col-xs-12 col-sm-4 col-md-3 mt-4">
								{!!$formulario->telefono!!}
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.phone') }}</label>
							</div>
							<div class="movil col-xs-12 col-sm-4 col-md-3 mt-4">
								{!!$formulario->movil!!}
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.mobile') }}</label>
							</div>


							<div class="col-xs-12 col-sm-4 col-md-3 mt-4 nif-labels">
								{!!$formulario->cif!!}
								<label class="cif registerEnterprise">
									{{ trans(\Config::get('app.theme').'-app.login_register.cif') }}
								</label>
							</div>


							<div class="observaciones col-xs-12 col-sm-4 col-md-3 mt-4">
								{!!$formulario->obscli!!}
								<label class="pt-3">{{
									trans(\Config::get('app.theme').'-app.login_register.observacion') }}</label>
							</div>
							<div class="checkbox-container condiciones2 col-xs-12 col-sm-4 col-md-3 mt-4">
								<label for="bool__0__condiciones2">{!!
									trans(\Config::get('app.theme').'-app.login_register.read_conditions2') !!}</label>
								{!! $formulario->condiciones2!!}
							</div>
							<div class="moneda col-xs-12 col-sm-4 col-md-3 mt-4">
								{!!$formulario->divisa!!}
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.currency') }}</label>
							</div>

							<div class="col-xs-12 col-sm-4 col-md-3 mt-4">
								{!!$formulario->direccion!!}
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}</label>
							</div>

							<div class="col-xs-12 col-sm-4 col-md-3 mt-4">
								{!!$formulario->cpostal!!}
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}</label>
							</div>

							<div class="col-xs-12 col-sm-4 col-md-3 mt-4">
								{!!$formulario->provincia!!}
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
							</div>

							<div class="col-xs-12 col-sm-4 col-md-3 mt-4">
								{!!$formulario->poblacion!!}
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}</label>
							</div>

							<div class="col-xs-12 col-sm-4 col-md-3 mt-4 withfocus">
								{!!$formulario->pais!!}
								<labeL>{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</label>
							</div>

							@if($esCedente)
							<div class="col-xs-12 col-sm-4 col-md-3 mt-4 withfocus registerEnterprise">
									{!!FormLib::Select("cargo", 1, "", $jobs, '', '', false)!!}
									<label>{{ trans(\Config::get('app.theme').'-app.login_register.position') }}</label>
							</div>
							@endif

							<input type="hidden" name="language" value="ES">

						</div>

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

					<div class="well @if(!$esCedente) hidden @endif">

						<div class="tit uppercase-text">
							{{'Datos para compradores'}}
							{{-- {{ trans(\Config::get('app.theme').'-app.login_register.title_direccion_envio') }} --}}
						</div>

						{{-- <div>
							<input id="shipping_address" class="form-contro" name="shipping_address" type="checkbox" checked="true" />
							<label for="shipping_address">{{ trans(\Config::get('app.theme').'-app.login_register.utilizar_direcc_direccenv') }}</label>
						</div> --}}

						{{-- <div class="form-group collapse" id="collapse_direccion" aria-expanded="true"> --}}
						<div class="row d-flex flex-wrap">
							{!!$formulario->clid!!}

							<div class="col-xs-12 col-sm-4 col-md-3 mt-4">
								<label for="clid_usuario">Nombre</label>
								<input type="text" class="form-control effect-16 has-content" name="clid_usuario" id="texto__0__clid_usuario" value="" onblur="comprueba_campo(this)" data-placement="right" placeholder="" autocomplete="off" data-content="">
							</div>

							<div class="col-xs-12 col-sm-4 col-md-3 mt-4">
								<label for="clid_telf">Teléfono</label>
								<input type="text" class="form-control effect-16 has-content" name="clid_telf" id="texto__0__clid_telf" value="" onblur="comprueba_campo(this)" data-placement="right" placeholder="" autocomplete="off" data-content="">
							</div>

							<div class="col-xs-12 col-sm-4 col-md-3 mt-4">
								<label for="email_clid">Email</label>
								<input type="text" class="form-control effect-16 has-content" name="email_clid" id="texto__0__email_clid" value="" onblur="comprueba_campo(this)" data-placement="right" placeholder="" autocomplete="off" data-content="">
							</div>

							<div class="col-xs-12 col-sm-4 col-md-3 mt-4">
								<label for="direccion">
									{{trans(\Config::get('app.theme').'-app.login_register.direccion') }}</label>
								{!!$formulario->clid_direccion!!}
							</div>

							<div class="col-xs-12 col-sm-4 col-md-3 mt-4">
								<label for="cpostal">
									{{trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}</label>
								{!!$formulario->clid_cpostal!!}
							</div>


							<div class="col-xs-12 col-sm-4 col-md-3 mt-4">
								<label for="provincia">{{
									trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
								{!!$formulario->clid_provincia!!}
							</div>

							<div class="col-xs-12 col-sm-4 col-md-3 mt-4">
								<label for="poblacion">{{ trans(\Config::get('app.theme').'-app.login_register.ciudad')
									}}</label>
								{!!$formulario->clid_poblacion!!}
							</div>

							<div class="col-xs-12 col-sm-4 col-md-3 mt-4">
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</label>
								{!!$formulario->clid_pais!!}
							</div>

						</div>
						<div class="clearfix"></div>

					</div>
					@endif

					<div class="well">

						<div class="row d-flex flex-wrap">

							<div class="col-xs-12 col-sm-6 mb-4">
								{!!$formulario->email!!}
								<label for="email">
									{{ trans(\Config::get('app.theme').'-app.login_register.email') }}
								</label>
							</div>
							<div class="col-xs-12 col-sm-6 mb-4">
								{!!$formulario->confirm_email!!}
								<label for="email">{{
									trans(\Config::get('app.theme').'-app.login_register.email_confirm') }}</label>
							</div>
							<input style="display:none" type="password">
							<div class="col-xs-12 col-sm-6 mb-4">
								{!!$formulario->password!!}
								<label for="contrasena">{{
									trans(\Config::get('app.theme').'-app.login_register.password') }}</label>
							</div>
							<div class="col-xs-12 col-sm-6 mb-4">
								{!!$formulario->confirm_password!!}
								<label for="confirmcontrasena">{{
									trans(\Config::get('app.theme').'-app.login_register.confirm_password') }}</label>
							</div>

						</div>

						<div class="row">
							<div class="col-xs-12">

								<div class="datos_condiciones mt-1">

									<div class="checkbox-container condiciones">
										{!! $formulario->condiciones!!}
										<label for="bool__1__condiciones">{!! trans(\Config::get('app.theme').'-app.login_register.read_conditions') !!}</label>


										<p>
											<span><input type="checkbox" value="on" id="bool__1__condiciones_4"
													autocomplete="off" data-placement="bottom"></span>
											<label for="bool__1__condiciones_4" style="display: inline;">{!!
												trans(\Config::get('app.theme').'-app.login_register.read_conditions_4')
												!!}</label>
										</p>

										<p>
											<span><input type="checkbox" value="on" id="bool__1__condiciones_3"
													autocomplete="off" data-placement="bottom"></span>
											<label for="bool__1__condiciones_3" style="display: inline;">{!!
												trans(\Config::get('app.theme').'-app.login_register.read_conditions_3')
												!!}</label>
										</p>



									</div>

									<div class="">
										<div class="g-recaptcha"
											data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"
											data-callback="onSubmit"></div>
									</div>

								</div>

							</div>
						</div>

					</div>

					<div class="mt-2 mb-2 text-center">
						<button type="submit" class="submitButton button-principal">
							{{ trans(\Config::get('app.theme').'-app.login_register.register') }}
						</button>
					</div>

					@if(!empty($formulario->subalia))
					{!!$formulario->subalia!!}
					{!!$formulario->info!!}
					@endif


				</form>

				@if(!empty($formulario->subalia))
				<form id="formToSubalia" method="post"
					action="@if(config('app.locale') == 'en') {{'https://subalia.es/registerclicli'}} @else {{'https://subalia.es/registerclicli'}} @endif">
					<input type="hidden" name="info" id="info_sent" value="">
					<input type="hidden" name="cod_auchouse" id="cod_auchouse_sent" value="">
					<input type="hidden" name="redirect" id="redirect_sent" value="">
				</form>
				@endif
			</div>
		</div>
	</div>
</div>
