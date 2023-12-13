@php
if(empty($data['seo'])){
	$data['seo'] = new \Stdclass();
}
$data['seo']->noindex_follow='true';
@endphp
@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

@php
	$newsletters = (new \App\Models\Newsletter())->getNewslettersNames();
@endphp

<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>

<div class="create-account color-letter">
	<div class="container register pb-5">
		<div class="row d-flex align-items-center justify-content-center">
			<div class="col-xs-12 general-container">

				<h1>{{ trans(\Config::get('app.theme').'-app.login_register.crear_cuenta') }}</h1>
				<p>{{ trans(\Config::get('app.theme').'-app.login_register.all_fields_are_required') }}</p>

				<form method="post" id="registerForm" action="javascript:submit_register_form()" enctype="multipart/form-data">

					<input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">
					<input class="form-control" type="hidden" name="sexo" id="sexo" value="H">
					<input class="form-control" type="hidden" name="pri_emp" id="pri_emp" value="F">

					<div class="tipo_usuario">
						<div class="particular selected" onclick="javascript:particular();">
							{{ trans(\Config::get('app.theme').'-app.login_register.particular') }}
						</div>
						<div class="empresa" onclick="javascript:empresa();">
							{{ trans(\Config::get('app.theme').'-app.login_register.empresa') }}
						</div>
					</div>

					<div class="well">

						<div class="tit">
							{{ trans(\Config::get('app.theme').'-app.login_register.personal_information') }}
						</div>


						<div class="tipo_sexo">
							<div class="hombre selected" onclick="javascript:hombre();">
								{{ trans(\Config::get('app.theme').'-app.login_register.hombre') }}
							</div>
							<div class="mujer" onclick="javascript:mujer();">
								{{ trans(\Config::get('app.theme').'-app.login_register.mujer') }}
							</div>
						</div>

						<div class="clearfix"></div>
						<br>

 						<div class="datos_contacto">

							<div>
								<label>{{ trans(\Config::get('app.theme').'-app.user_panel.date_birthday') }}</label>
								{!!$formulario->fecha_nacimiento!!}
							</div>

							<div class="registerParticular">
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.nombre') }}</label>
								{!!$formulario->usuario!!}
							</div>
							<div class="registerParticular apellidos">
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.apellidos') }}</label>
								{!!$formulario->last_name!!}
							</div>
							<div class="registerEnterprise">
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.company') }}</label>
								{!!$formulario->rsoc_cli!!}
							</div>
							<div class="registerEnterprise">
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}</label>
								{!!$formulario->contact!!}
							</div>
							<div>
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.phone') }}</label>
								{!!$formulario->telefono!!}
							</div>
							<div class="movil">
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.mobile') }}</label>
								{!!$formulario->movil!!}
							</div>
							<div class="observaciones">
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.observacion') }}</label>
								{!!$formulario->obscli!!}
							</div>
							<div class="idioma">
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.language') }}</label>
								{!!$formulario->language!!}
							</div>
							<div class="moneda">
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.currency') }}</label>
								{!!$formulario->divisa!!}
							</div>

							<div class="col-xs-12 credit_card">
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.credit_card') }}</label>
								{!! FormLib::Hidden("creditcard_fxcli", 1, "") !!}
								{!! FormLib::Text("creditcard", 1, '', 'minlength="14" maxlength="16" pattern="[0-9]+"') !!}
							</div>

							<div class="form-row card-expired-wrapper">

								<div class="form-group col-xs-12" style="margin: 0; padding: 0">
									<label for="">{{ trans(\Config::get('app.theme').'-app.login_register.expiration_date') }}</label>
								</div>
								<div class="form-group col-xs-3 col-sm-2">
									<input id="texto__1__card-expired-month" type="number" name="card-expired-month" class="form-control without-arrow text-center" max="12" placeholder="mm">
								</div>
								<div class="form-group col-xs-1 col-sm-1 card-expired-separator text-center">
									<span class="form-control"> / </span>
								</div>

								<div class="form-group col-xs-3 col-sm-2">
									<input id="texto__1__card-expired-year" type="number" name="card-expired-year" class="form-control without-arrow text-center" min="20" max="99" placeholder="yy">
								</div>
							</div>


							<div>
								<label class="nif">{{ trans(\Config::get('app.theme').'-app.login_register.dni') }}</label>
								<label class="cif" style="display:none">{{ trans(\Config::get('app.theme').'-app.login_register.cif') }}</label>
								{!!$formulario->cif!!}
							</div>

						</div>

						<div class="datos_direccion">

							<div>
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.via') }}</label>
								{!!$formulario->vias!!}
							</div>
							<div>
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}</label>
								{!!$formulario->direccion!!}
							</div>
							<div>
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}</label>
								{!!$formulario->cpostal!!}
							</div>
							<div>
								<labeL>{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</label>
								{!!$formulario->pais!!}
							</div>
							<div>
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}</label>
								{!!$formulario->poblacion!!}
							</div>
							<div>
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
								{!!$formulario->provincia!!}
							</div>

							<div>
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.dni_obverse') }}</label>
								{!! FormLib::File('dni1', $boolObligatorio = 1, $strExtra = "") !!}
							</div>

							<div>
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.dni_reverse') }}</label>
								{!! FormLib::File('dni2', $boolObligatorio = 1, $strExtra = "") !!}
							</div>
							<div>{{ trans("$theme-app.login_register.credit_card_register_note") }}</div>
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
                            {{ trans(\Config::get('app.theme').'-app.login_register.title_direccion_envio') }}
                        </div>

                        <div class="col-xs-1 text-center">
                    	    <input id="shipping_address" class="form-contro" name="shipping_address" type="checkbox" checked="true"/>
                    	</div>
						<div class="col-xs-11">
							<label for="shipping_address">{{ trans(\Config::get('app.theme').'-app.login_register.utilizar_direcc_direccenv') }}</label>
						</div>

                        <div class="clearfix"></div>


                        <div class="form-group collapse" id="collapse_direccion" aria-expanded="true">

                            {!!$formulario->clid!!}
                            <div>
                            	<label>{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</label>
                                {!!$formulario->clid_pais!!}
                            </div>

                            <div>
                                <label for="cpostal">{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}</label>
                                {!!$formulario->clid_cpostal!!}
                            </div>

                            <div>
                                <label for="provincia">{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
                                {!!$formulario->clid_provincia!!}
                            </div>

                            <div>
                                <label for="poblacion">{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}</label>
                                {!!$formulario->clid_poblacion!!}
                            </div>

                            <div>
                            	<label for="provincia">{{ trans(\Config::get('app.theme').'-app.login_register.via') }}</label>
                                {!!$formulario->clid_codigoVia!!}
                            </div>
                            <div>
                                <label for="direccion">{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}</label>
                                {!!$formulario->clid_direccion!!}
                            </div>


                        </div>
                        <div class="clearfix"></div>

                	</div>
                    @endif
















					<div class="well">
						<div class="tit">{{ trans(\Config::get('app.theme').'-app.login_register.cuenta') }}</div>

						<div class="datos_cuenta">

							<div>
								<label for="email">{{ trans(\Config::get('app.theme').'-app.login_register.email') }}</label>
								{!!$formulario->email!!}
							</div>
							<div>
								<label for="email">{{ trans(\Config::get('app.theme').'-app.login_register.email_confirmacion') }}</label>
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

						<div class="clearfix"></div>

						<div class="datos_newsletter">

						@if (!empty($newsletters) && count($newsletters) > 0)
							<!-- con categorias newsletter -->
							<big>{{ trans(\Config::get('app.theme') . '-app.login_register.recibir_newsletter') }}</big>
							<br>
							@foreach ($newsletters as $id_newsletters => $name_newsletters)
								<div>
									<input id="register_newsletter_{{ $id_newsletters }}" type="checkbox"
										name="families[{{ $id_newsletters }}]" value="{{ $id_newsletters }}">
									<label for="register_newsletter_{{ $id_newsletters }}">
										{{ $name_newsletters }}
									</label>
								</div>
							@endforeach

						@else
                            <!-- sin categorias newsletter -->

                        	<div>
								{!! $formulario->newsletter!!}
	                            <label for="bool__0__newsletter">{{ trans(\Config::get('app.theme').'-app.login_register.recibir_newsletter') }}</label>
	                        </div>

                        @endif
                        <p class="clearfix"></p>

                        </div>



                        <div class="datos_condiciones">

							<div class="row">
								<div class="col-xs-2 col-sm-1 text-center">{!! $formulario->condiciones!!}</div>
								<div class="col-xs-10 col-sm-11">
									<label for="bool__1__condiciones">{!! trans(\Config::get('app.theme').'-app.login_register.read_conditions') !!} (<a href="<?php echo Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.term_condition') ?>" target="_blank">{{ trans(\Config::get('app.theme').'-app.login_register.more_info') }}</a>)
									</label>
								</div>
							</div>
							<br>
							<div class="col-xs-12 col-md-offset-3">
								<div class="g-recaptcha" data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}" data-callback="onSubmit">
								</div>

							</div>

							<div class="clearfix"></div>
							<br><br>
						</div>

                    </div>














					<div class="col-xs-12 text-center">
						<button type="submit" class="btn btn btn-registro-salaretiro submitButton" style="background:#015BA9;color:#FFF;">
							{{ trans(\Config::get('app.theme').'-app.login_register.register') }}
						</button>
					</div>

					<div class="clearfix"></div>
					<br><br><br><br><br><br><br><br>

				</form>
			</div>
		</div>
	</div>
</div>

	@stop
