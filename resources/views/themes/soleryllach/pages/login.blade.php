@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@php
	$newsletters = (new \App\Models\Newsletter())->getNewslettersNames();
@endphp

@section('content')

<main class="login-page">
	<form method="post" class="frmLogin" id="frmRegister-adv" data-toggle="validator">
		<input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" data-sitekey="{{ config('app.captcha_v3_public') }}" name="captcha_token" value="">

		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12">
					<div class="crear">
						<div class="tit_page">
							<h1 class="titlePage">
								{{ trans(\Config::get('app.theme').'-app.login_register.crear_cuenta') }}
							</h1>
							<p class="error-form-validation">
								{{ trans(\Config::get('app.theme').'-app.login_register.all_fields_are_required') }}
							</p>
						</div>
						<div class="col-xs-12">
							<div class="item-perfil">
								<div class="input-group-custom">
									<div class="input-group-addon-custom form-group ">
										<input
											class="form-control change_job"
											style="display: none;"
											id="inlineCheckbox1"
											name="pri_emp"
											value="F"
											checked="checked"
											type="radio"
										/>
										<label class="input-check-custom " for="inlineCheckbox1">{{ trans(\Config::get('app.theme').'-app.login_register.particular') }}</label>
									</div>
									<div class="form-group input-group-addon-custom">
										<input class="form-control change_job" style="display: none;" id="inlineCheckbox2" name="pri_emp" value="J" type="radio">
										<label class="input-check-custom" for="inlineCheckbox2">{{ trans(\Config::get('app.theme').'-app.login_register.empresa') }}</label>
									</div>
								</div>
							</div>

							<section class="row register-dni-section">
								<div class="item-contact-data col-md-8 col-md-offset-2" style="border: 1px solid #ccc; margin-bottom: 10px; padding-bottom:20px;">
									<div class="tit">{{ trans("$theme-app.login_register.important") }}</div>
										<div class="row">
											<div class="col-xs-12 data-contact-container">
												<div class="col-xs-12">
													<div class="row">



													<div class="col-xs-12 col-md-6">
														<div  class="form-group input-group">
															<label for="dni" class="dni_txt">{{ trans(\Config::get('app.theme').'-app.login_register.dni') }}</label>
															<label for="dni" class="cif_txt hidden">{{ trans(\Config::get('app.theme').'-app.login_register.cif') }}</label>
															<input
																id="dni"
																placeholder="Ej: 12345678P"
																class="form-control dni"
																size="10"
																name="nif"
																title="Formato del NIF/NIE(12345678A/X1234567A)"
																required=""
																type="text"
															/>
														</div>
													</div>

													<div class="col-xs-12 col-md-6">
														<div class="form-group input-group">
															<label for="nif_file" class="dni_txt">{{ trans("$theme-app.login_register.attach_image_dni") }}</label>
															<label for="nif_file" class="cif_txt hidden">{{ trans("$theme-app.login_register.attach_image_cif") }}</label>
															<input type="file" name="files_email[]" title="Adjuntar imagén DNI / NIE / Pasaporte"
																accept="image/png, image/jpeg, image/jpg, image/gif, image/bmp, image/tiff, application/pdf"
																multiple/>
														</div>
													</div>
												</div>
												<div class="row">

													<div class="col-xs-12 col-md-6">
														<div  class="form-group input-group">
															<label>{{ trans(\Config::get('app.theme').'-app.login_register.observacion') }}</label>
															<textarea style="max-width: 100%; max-height:100px;" rows="5" maxlength="200" class="form-control" name="obscli"
																placeholder="Ej: Aste Bolaffi, Corinphila, Künker, Spink" required
																></textarea>
														</div>
													</div>

													<div class="col-xs-12 col-md-6">
														<div class="form-group input-group">
															<label>{{ trans(\Config::get('app.theme').'-app.login_register.language') }}</label>
																<select name="language" class="form-control" required>
																	@foreach( $data['language'] as $key => $value)
																		<option value="{{strtoupper($key)}}">{{$value}}</option>
																	@endforeach
																</select>
														</div>
													</div>
												</div>

												<div class="row">
													<div class="col-xs-12">
														<p>
															{!! trans("$theme-app.login_register.must_register_days") !!}
														</p>
													</div>
												</div>

											</div>

										</div>
									</div>
								</div>
							</section>


							<div class="row">
								<div class="item-contact-data col-md-8 col-md-offset-2" style="border: 1px solid #ccc; margin-bottom: 10px; padding-bottom:20px;">
									<div class="tit">{{ trans(\Config::get('app.theme').'-app.login_register.personal_information') }}</div>
										<div class="row">
											<div class="col-xs-6">
												<div class="input-group pull-right">
													<div class="form-group input-gener">
														<input class="form-control" style="display: none;" id="inlineRadio1" value="H" name="sexo" checked="checked" type="radio">
														<label class="input-check-custom" for="inlineRadio1">{{ trans(\Config::get('app.theme').'-app.login_register.hombre') }}</label>
													</div>
												</div>
											</div>
											<div class="col-xs-6">
												<div class="input-group">
													<div class="form-group input-gener">
														<input class="form-control" style="display: none;" id="inlineRadio2" name="sexo" value="M" type="radio">
														<label class="input-check-custom" for="inlineRadio2">{{ trans(\Config::get('app.theme').'-app.login_register.mujer') }}</label>
													</div>
												</div>
											</div>
										</div>
									<div class="row">
										<div class="col-xs-12 data-contact-container">
											<div class="col-xs-12 col-md-6">
												<div class="col-xs-12 col-md-12 pull-right">
													<div class="input-group date" data-provide="datepicker">
														<label for="nombre">{{ trans(\Config::get('app.theme').'-app.user_panel.date_birthday') }}</label>
														<div class="form-group" style="display: inline-table;">
															<input name="date" class="form-control" type="date" required="" id="datetimepicker">
															<div class="input-group-addon icon-date">
																<span class="glyphicon glyphicon-th"></span>
															</div>
														</div>

													</div>
													<div class="form-group input-group name_client">
														<label for="nombre">{{ trans(\Config::get('app.theme').'-app.login_register.nombre_apellido') }}</label>
														<input class="form-control" id="nombre" name="usuario" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.nombre_apellido') }}" required="" type="text">
													</div>
													<div class="form-group input-group name_client">
														<label for="apellido">{{ trans(\Config::get('app.theme').'-app.login_register.apellidos') }}</label>
														<input class="form-control" id="apellido" name="last_name" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.apellidos') }}" required="" type="text">
													</div>
													<div class="form-group input-group hidden rsoc_cli">
														<label for="">{{ trans(\Config::get('app.theme').'-app.login_register.company') }}</label>
														<input class="form-control" name="rsoc_cli" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.company') }}" type="text">
													</div>
													<div class="form-group input-group hidden rsoc_cli">
														<label for="">{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}</label>
														<input class="form-control" name="contact" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}" type="text">
													</div>
													<div class="form-group input-group">
														<label for="telefono">{{ trans(\Config::get('app.theme').'-app.login_register.phone') }}</label>
														<input
															name="telefono"
															class="form-control"
															placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.phone') }}"
															required=""
															maxlength="40"
															type="text"
														/>
													</div>

													<div class="form-group input-group">
														<label for="provincia">{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
														<input
															name="provincia"
															class="form-control"
															id="provincia"
															placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}"
															maxlength="30"
															type="text"
														/>
													</div>


										</div>
									</div>
									<div class="col-xs-12 col-md-6">
										<div class="col-xs-12 col-md-12">

											@if(config('app.locale') == 'es')
											<div class="form-group input-group">
												<label for="codigoVia">{{ trans(\Config::get('app.theme').'-app.login_register.via') }}</label>
												<select id="codigoVia" name="codigoVia" class="form-control">
													<option value="">---</option>
													@if (!empty($data) && !empty($data["via"]) )
														@foreach ($data["via"] as $via)
															<option value="{{ $via->cod_sg }}">{{ $via->des_sg }}</option>
														@endforeach
													@endif
												</select>
											</div>
											@else
											<div class="form-group input-group">
												<label for="country">{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</label>
												<select id="country" name="pais" class="form-control notranslate" required>
													<option value="">---</option>
													@if (!empty($data) && !empty($data["countries"]) )
														@foreach ($data["countries"] as $country)
															<option value="{{ $country->cod_paises }}">{{ $country->des_paises }}</option>
														@endforeach
													@endif
												</select>
											</div>
											@endif

												<div class="form-group input-group">
													<label for="direccion">{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}</label>
													<input
														name="direccion"
														class="form-control"
														id="direccion"
														placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}"
														required=""
														maxlength="60"
														type="text"
													/>
												</div>

											@if(config::get('app.locale') == 'es')
											<div class="form-group input-group">
												<label for="country">{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</label>
												<select id="country" name="pais" class="form-control notranslate" required>
													<option value="">---</option>
													@if (!empty($data) && !empty($data["countries"]) )
														@foreach ($data["countries"] as $country)
															<option value="{{ $country->cod_paises }}">{{ $country->des_paises }}</option>
														@endforeach
													@endif
												</select>
											</div>
											@endif
											<div class="form-group input-group">
											<label for="codigo_postal">{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}</label>
											<input
												id="cpostal"
												name="cpostal"
												class="form-control"
												placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}"
												required=""
												maxlength="10"
												type="text"
											/>
										</div>
											<div class="form-group input-group">
											<label for="Ciudad">{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}</label>
											<input
												name="poblacion"
												class="form-control"
												id="Ciudad"
												placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}"
												required=""
												maxlength="30"
												type="text"
											/>
										</div>


									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if(Config::get('app.delivery_address', 0)){ ?>

				<input type="hidden" name="mater_clid" value="S">
				<div class="row">
					<div class="col-xs-12 col-md-8 col-md-offset-2" style="border: 1px solid #ccc; margin-bottom: 10px; padding-bottom:20px;">
						<div class="col-xs-12 ">
							<div class="sub_page">
								<div class="tit">{{ trans(\Config::get('app.theme').'-app.login_register.title_direccion_envio') }}</div>
							</div>
						<div class="ship-check">
							<input
								id="shipping_address"
								class="form-control"
								name="shipping_address"
								type="checkbox"
							/>
							<label for="shipping_address">{{ trans(\Config::get('app.theme').'-app.login_register.utilizar_direcc_direccenv') }}</label>
						</div>
					</div>
				<div class="col-xs-12 data-address">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="col-sm-12 col-md-12">
						<div class="form-group input-group">
						<label for="codigoVia">{{ trans(\Config::get('app.theme').'-app.login_register.via') }}</label>
							<select id="clid_codigoVia" name="clid_codigoVia" class="form-control">
								<option value="">---</option>
								@if (!empty($data) && !empty($data["via"]) )
									@foreach ($data["via"] as $via)
										<option value="{{ $via->cod_sg }}">{{ $via->des_sg }}</option>
									@endforeach
								@endif
							</select>
						</div>
						<div class="form-group input-group">
							<label for="country_envio">{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</label>
							<select
								id="country_envio"
								name="clid_pais"
								onchange=""
								class="form-control"
								required
							>
								<option value="">---</option>
									@if (!empty($data) && !empty($data["countries"]) )
										@foreach ($data["countries"] as $country)
											<option value="{{ $country->cod_paises }}">{{ $country->des_paises }}</option>
										@endforeach
									@endif
							</select>
						</div>

						<div class="form-group input-group">
							<label >{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
							<input id="clid_provincia"
								name="clid_provincia"
								class="form-control"
								id="provincia"
								maxlength="30"
								type="text"
								placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}"
							/>
						</div>

					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-sm-6">
					<div class="col-sm-12 col-md-12">
						<div class="form-group input-group">
							<label for="direccion_envio">{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}</label>
							<input
								name="clid_direccion"
								class="form-control"
								id="direccion_envio"
								placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}"
								required=""
								maxlength="60"
								type="text" />
						</div>
						<div class="form-group input-group">
							<label for="cpostal_envio">{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}</label>
							<input
								id="codigo_postal"
								name="clid_cpostal"
								class="form-control"
								placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}"
								required=""
								maxlength="10"
								type="text" />
						</div>
						<div class="form-group input-group">
							<label for="Ciudad_envio">{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}</label>
							<input
								name="clid_poblacion"
								class="form-control"
								id="clid_poblacion"
								placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}"
								required=""
								maxlength="30"
								type="text" />
						</div>


					</div>

				</div>
			</div>

		</div>

	</div>
				<?php } ?>
	<div class="row">
		<div class="col-xs-12 col-md-8 col-md-offset-2" style="border: 1px solid #ccc; margin-bottom: 10px; padding-bottom:20px;">
			<div class="sub_page">
				<div class="tit">{{ trans(\Config::get('app.theme').'-app.login_register.cuenta') }}</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-sm-offset-0">
				<div class="form-group form-group-custom">
					<label for="email">{{ trans(\Config::get('app.theme').'-app.login_register.email') }}</label>
					<input
						class="form-control"
						id="email"
						placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.email') }}"
						name="email"
						required=""
						type="text"
					/>
					<label id="erroremail" class="hidden text-danger"></label>
				</div>
				<div class="form-group form-group-custom">
					<label for="emailconfirm">{{ trans(\Config::get('app.theme').'-app.login_register.email_confirmacion') }}</label>
					<input data-match="#email"
						class="form-control"
						id="emailconfirm"
						placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.email_confirmacion') }}"
						required=""
						type="text"
					/>
				</div>
				<div class="form-group form-group-custom">
					<label for="contrasena">{{ trans(\Config::get('app.theme').'-app.login_register.password') }}</label>
					<input
						maxlength="20"
						id="password"
						name="password"
						class="form-control"
						placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.password') }}"
						data-minlength="5"
						required=""
						type="password" />
				</div>
				<div class="form-group form-group-custom">
					<label for="confirmcontrasena">{{ trans(\Config::get('app.theme').'-app.login_register.confirm_password') }}</label>
					<input
						maxlength="20"
						name="confirm_password"
						class="form-control"
						data-match="#password"
						id="confirmcontrasena"
						placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.confirm_password') }}"
						required=""
						type="password">
				</div>
			</div>

			<div class="col-xs-12">

										<div class="text-center">{{ trans(\Config::get('app.theme').'-app.login_register.newsletter_category') }}</div>
										<ul class="list-unstyled my-3">
											<div class="list-block">
												@foreach ($newsletters as $id_newsletters => $name_newsletters)
													<li>
														<div class="form-check">
															<label>
																<input type="checkbox" class="newsletter" name="families[{{ $id_newsletters }}]"
																	value="{{ $id_newsletters }}">
																{{ $name_newsletters }}
															</label>
														</div>
													</li>
												@endforeach
											</div>
										</ul>

				<div class="checkbox">
					<input style="height:15px;width: 15px;"
						name="condiciones"
						required
						type="checkbox"
						class="form-control"
						id="recibir-newletter"
					/>
					<label for="recibir-newletter" class="recibir-newletter">
								<?= trans(\Config::get('app.theme').'-app.login_register.read_conditions_politic') ?>
					</label>
				</div>

				<p class="captcha-terms">
					{!! trans("$theme-app.global.captcha-terms") !!}
				</p>

			</div>


		</div>

	</div>

						<div class="input-button-register">

							<p id="error-form-validation" class="text-danger" style="font-size: 18px;"></p>
							<?php /* le he quitado la clase boton-registro para que no bloquee el boton si hay algun error ya que la funcion ya conrola los errores */ ?>
							<button type="submit" class="btn btn-registro-soler">{{ trans(\Config::get('app.theme').'-app.login_register.finalizar') }}</button>
						</div>
					</div>
						<div class="col-xs-12 col-sm-12 text-center">
							<div id="regCallback" class="alert alert-danger"></div>
						</div>
					</div>
					<div class="confirmacion">
						<div class="tit_page">
							<h1 class="step"><span class="col_reg_form"></span></h1>
						</div>
					</div>

				</div>
		</div>
		</div>
	</form>
</main>
<p class="error-form-validation hidden">{{ trans(\Config::get('app.theme').'-app.login_register.all_fields_are_required') }}</p>

<script>
$( document ).ready(function() {

    $('.icon-date').click(function(){

        $('#datetimepicker').focus();
    });
});
</script>
@stop
