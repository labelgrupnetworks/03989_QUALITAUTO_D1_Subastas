@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

<?php
//query que mira si hay alguna subasta activa actualmente, si la hay no se pueden registrar 5/1440 es para bloquearlo 5 minutos antes de que empieze
$sql = "select count(asigl0.ref_asigl0) as cuantos  from \"auc_sessions\" auc
join fgsub sub   on sub.emp_sub=auc.\"company\" and  sub.cod_sub = auc.\"auction\"
join fgasigl0 asigl0 on asigl0.emp_asigl0=auc.\"company\" and asigl0.sub_asigl0 = sub.cod_sub
where
auc.\"company\" = :emp and
sub.subc_sub in ('S') and
sub.tipo_sub = 'W' and

asigl0.ref_asigl0 >= auc.\"init_lot\"    AND
asigl0.ref_asigl0 <=  auc.\"end_lot\" and
 asigl0.cerrado_asigl0 = 'N' and
(auc.\"start\" - (5/1440)) < sysdate  and auc.\"end\" > sysdate";


$bindings = array(
    'emp' => Config::get('app.emp')
);
$active_lots = DB::select($sql, $bindings);
?>
@if(!empty($active_lots) && $active_lots[0]->cuantos > 0)
<section class="principal-bar no-principal body-auctions2">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="princiapl-bar-wrapper">
					<div class="principal-bar-title ">
						<h3 class="titlePage" style="text-align:center;">
							{{ trans(\Config::get('app.theme').'-app.login_register.register_blocked') }}</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@else

<section class="principal-bar no-principal">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="princiapl-bar-wrapper">
					<div class="principal-bar-title">
						<h3 class="titlePage"> {{ trans(\Config::get('app.theme').'-app.login_register.crear_cuenta') }}
						</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>

<div class="create-account color-letter">
	<div class="container register pb-5">



		<p class="error-form-validation">
			{{ trans(\Config::get('app.theme').'-app.login_register.all_fields_are_required') }}</p>

		<form method="post" id="registerForm" action="javascript:submit_register_form()">

			<input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">
			<input class="form-control" type="hidden" name="sexo" id="sexo" value="H">
			<input class="form-control" type="hidden" name="pri_emp" id="pri_emp" value="F">

			<div class="tipo_usuario">

				<div class="perfil flex valign">
					<div class="form-group" onclick="javascript:particular();">
						<label class="input-check-custom " for="inlineCheckbox1">

							<input class="form-control change_job" style="display:none;" id="inlineCheckbox1"
								name="pri_emp" value="F" checked="checked" @if (isset($data['postUser']->fisjur_cli) &&
							$data['postUser']->fisjur_cli=='F') checked="checked" @endif
							type="radio"
							/>
							<div class="modern-button"></div>
							<p>{{ trans(\Config::get('app.theme').'-app.login_register.particular') }}</p>
						</label>
					</div>
					<div class="form-group" onclick="javascript:empresa();">
						<label class="input-check-custom " for="inlineCheckbox2">
							<input class="form-control change_job" style="display: none;" id="inlineCheckbox2"
								name="pri_emp" value="J" @if (isset($data['postUser']->fisjur_cli) &&
							$data['postUser']->fisjur_cli=='J') checked="checked" @endif
							type="radio"
							/>
							<p>{{ trans(\Config::get('app.theme').'-app.login_register.empresa') }}</p>
							<div class="modern-button"></div>
						</label>
					</div>
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
						<label>{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</label>
						{!!$formulario->pais!!}
					</div>

					<div class="registerParticular nombre">
						<label>{{ trans(\Config::get('app.theme').'-app.login_register.nombre') }}</label>
						{!!$formulario->usuario!!}
					</div>
					<div class="registerParticular apellidos">
						<label>{{ trans(\Config::get('app.theme').'-app.login_register.apellidos') }}</label>
						{!!$formulario->last_name!!}
					</div>
					<div class="registerEnterprise rsoc_cli">
						<label>{{ trans(\Config::get('app.theme').'-app.login_register.company') }}</label>
						{!!$formulario->rsoc_cli!!}
					</div>
					<div class="registerEnterprise contact">
						<label>{{ trans(\Config::get('app.theme').'-app.login_register.contact') }}</label>
						{!!$formulario->contact!!}
					</div>
					<div class="via" style="@if(\Config::get('app.locale') != 'es') display: none; @endif">
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
						<label>{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}</label>
						{!!$formulario->poblacion!!}
					</div>

					<div class="movil">
						<label>{{ trans(\Config::get('app.theme').'-app.login_register.mobile') }}</label>
						{!!$formulario->movil!!}
					</div>

					<div>
						<label>{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
						{!!$formulario->provincia!!}
					</div>



				</div>

				<div class="datos_direccion">
					<div class="col-xs-3">
						<label>{{ trans(\Config::get('app.theme').'-app.login_register.prefix') }}</label>
						{!!$formulario->prefix!!}
					</div>
					<div class="col-xs-9">
						<label>{{ trans(\Config::get('app.theme').'-app.login_register.phone') }}</label>
						{!!$formulario->telefono!!}
					</div>
					<div>
						@if(\Config::get('app.locale') != 'es')
						<label class="nif labelDni"
							style="display: none">{{ trans(\Config::get('app.theme').'-app.login_register.dni') }}</label>
						@else
						<label class="nif labelDni"
							style="display: inline-block">{{ trans(\Config::get('app.theme').'-app.login_register.dni') }}</label>
						@endif

						<label class="cif labelDni"
							style="display: none">{{ trans(\Config::get('app.theme').'-app.login_register.cif') }}</label>

						@if(\Config::get('app.locale') != 'es')
						<label class="passport labelDni"
							style="display: inline-block">{{ trans(\Config::get('app.theme').'-app.login_register.passport') }}</label>
						@else
						<label class="passport labelDni"
							style="display: none">{{ trans(\Config::get('app.theme').'-app.login_register.passport') }}</label>
						@endif

						<label class="vat labelDni"
							style="display: none">{{ trans(\Config::get('app.theme').'-app.login_register.vat') }}</label>
						{!!$formulario->cif!!}
					</div>
					<div class="idioma">
						<label>{{ trans(\Config::get('app.theme').'-app.login_register.language') }}</label>
						{!!$formulario->language!!}
					</div>
					<div class="moneda">
						<label>{{ trans(\Config::get('app.theme').'-app.login_register.currency') }}</label>
						{!!$formulario->divisa!!}
					</div>
					<div class="observaciones">
						<label>{{ trans(\Config::get('app.theme').'-app.login_register.observacion') }}</label>
						<label
							class="hidden">{{ trans(\Config::get('app.theme').'-app.login_register.observacion_holder') }}</label>
						{!!$formulario->obscli!!}
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
					{{ trans(\Config::get('app.theme').'-app.login_register.title_direccion_envio') }}
				</div>

				<div>
					<input id="shipping_address" class="form-contro filled-in" name="shipping_address" type="checkbox"
						checked="true" />
					<label
						for="shipping_address">{{ trans(\Config::get('app.theme').'-app.login_register.utilizar_direcc_direccenv') }}</label>

					<input id="shipping_address_required" class="form-contro filled-in" name="shipping_address_required"
						type="checkbox" />
					<label class="shipping_address_required"
						for="shipping_address_required">{{ trans(\Config::get('app.theme').'-app.login_register.add_addres') }}</label>
				</div>

				<div class="clearfix"></div>


				<div class="form-group collapse col-md-offset-2 col-md-8 mt-3" id="collapse_d" aria-expanded="true">

					{!!$formulario->clid!!}

					<div class="form-row">

						<div class="form-group col-xs-12 col-md-6">
							<div>
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.pais') }}</label>
								{!!$formulario->clid_pais!!}
							</div>

							{!! \FormLib::Hidden("usuario_clid", 0) !!}

							<div class="nombre">
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.nombre') }}</label>
								{!! \FormLib::Text("name_clidTemp", 0) !!}
							</div>

							<div class="apellidos">
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.apellidos') }}</label>
								{!! \FormLib::Text("lastName_clidTemp", 0) !!}
							</div>

							<div class="clid-via" style="@if(\Config::get('app.locale') != 'es') display: none; @endif">
								<label>{{ trans(\Config::get('app.theme').'-app.login_register.via') }}</label>
								{!!$formulario->clid_codigoVia!!}
							</div>

							<div>
								<label
									for="direccion">{{ trans(\Config::get('app.theme').'-app.login_register.direccion') }}</label>
								{!!$formulario->clid_direccion!!}
							</div>

						</div>

						<div class="form-group col-xs-12 col-md-6">
							<div>
								<label
									for="cpostal">{{ trans(\Config::get('app.theme').'-app.login_register.cod_postal') }}</label>
								{!!$formulario->clid_cpostal!!}
							</div>

							<div>
								<label for="poblacion">
									{{ trans(\Config::get('app.theme').'-app.login_register.ciudad') }}
								</label>
								{!!$formulario->clid_poblacion!!}
							</div>

							<div>
								<label
								for="provincia">{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
								{!!$formulario->clid_provincia!!}
							</div>


							<div class="phone_address">
								<div>
									<label>{{ trans(\Config::get('app.theme').'-app.login_register.prefix') }}</label>
									{!! \FormLib::Int("preftel_clid", 0, '') !!}
								</div>

								<div>
									<label>{{ trans(\Config::get('app.theme').'-app.login_register.phone') }}</label>
									{!! \FormLib::Int("tele_clid", 0, '') !!}
								</div>

							</div>
						</div>



					</div>



				</div>
				<div class="clearfix"></div>

			</div>
			@endif


			<div class="well">
				<div class="tit">{{ trans(\Config::get('app.theme').'-app.login_register.cuenta') }}</div>

				<div class="datos_cuenta">

					<div class="left">
						<label for="email">{{ trans(\Config::get('app.theme').'-app.login_register.email') }}</label>
						{!!$formulario->email!!}
					</div>
					<div class="right">
						<label
							for="email">{{ trans(\Config::get('app.theme').'-app.login_register.email_confirmacion') }}</label>
						{!!$formulario->confirm_email!!}
					</div>

					<input style="display:none" type="password">

					<div class="left">
						<label
							for="contrasena">{{ trans(\Config::get('app.theme').'-app.login_register.password') }}</label>
						{!!$formulario->password!!}
					</div>

					<div class="right">
						<label
							for="confirmcontrasena">{{ trans(\Config::get('app.theme').'-app.login_register.confirm_password') }}</label>
						{!!$formulario->confirm_password!!}
					</div>

					<div class="clearfix"></div>
				</div>

				<div class="clearfix"></div>
				<div class="checks_box">
					<div>
						<input type="hidden" name="newsletter" value="on" id="bool_0_newsletter">
					</div>


					<p class="clearfix"></p>


					<div class="datos_condiciones">

						<div>
							<input id="bool__1__condiciones" class="form-contro filled-in" name="condiciones"
								type="checkbox" checked="true" />
							<label for="bool__1__condiciones">
								<span>{!! trans(\Config::get('app.theme').'-app.login_register.read_conditions') !!}
									(<a href="<?php echo Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.term_condition') ?>"
										target="_blank">{{ trans(\Config::get('app.theme').'-app.login_register.more_info') }}</a>)</span>
							</label>

						</div>
						<div class="clearfix"></div>
						<br>

						<div>
							<noscript>
								<p class="error-javascript">{{ trans("$theme-app.login_register.enable-javascript") }}</p>
							</noscript>
							<div class="g-recaptcha" data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"
								data-callback="onSubmit"></div>
						</div>


						<div class="clearfix"></div>
					</div>

				</div>
			</div>


			<div class="col-xs-12 text-center">
				<button type="submit" class="btn btn-primary submitButton">
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
@endif


<script>
	const prefix = @json($jsitem->prefix);
</script>


@stop
