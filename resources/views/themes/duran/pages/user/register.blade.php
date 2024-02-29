@extends('layouts.session_layout')

@php
$families = array();
$isNftPage = !empty($formulario->back) && $formulario->back == 'nft';
$isGalleryPage = !empty($formulario->back) && $formulario->back == 'gallery';
$isDuranPage = empty($formulario->back);

$origen = 2;
if($isGalleryPage) {
	$origen = 3;
}
elseif ($isNftPage) {
	$origen = 4;
}

$document_type = (new \App\Models\V5\FxCli)->getTipoDocumento();
@endphp

@push('styles')
@if($isNftPage)
	<link rel="stylesheet" type="text/css" href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/nft.css') }}">
@endif
@endpush

@section('title')
{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')



<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>

<div class="create-account color-letter mt-5">
	<div class="container register pb-5">
		<div class="row d-flex align-items-center justify-content-center">
			<div class="col-xs-12 general-container">

				<div class="text-center mb-5">
					@if($isGalleryPage)
						<a id="logo_link" title="{{(\Config::get( 'app.name' ))}}" href="{{ request('context_url', '') }}">
							<img class="logo-landing img-responsive" src="/themes/{{$theme}}/assets/img/logo_gallery.png"  alt="{{(\Config::get( 'app.name' ))}}">
						</a>
					@elseif($isNftPage)
					<a id="logo_link" title="{{(\Config::get( 'app.name' ))}}" href="{{ request('context_url', '') }}">
						<img class="logo-landing img-responsive" src="/themes/{{$theme}}/assets/img/logo_nft.png" style="width: 200px;"  alt="{{(\Config::get( 'app.name' ))}}">
					</a>
					@else
						<a id="logo_link" title="{{(\Config::get( 'app.name' ))}}" href="/">
							<img class="logo-landing img-responsive" src="/themes/{{$theme}}/assets/img/logo.png" style="width: 200px;"  alt="{{(\Config::get( 'app.name' ))}}">
						</a>
					@endif
				</div>

				<h1 class="titlePage">{{ trans($theme.'-app.login_register.crear_cuenta') }}</h1>
				<p>{{ trans($theme.'-app.login_register.all_fields_are_required') }}</p>

				<form method="post" id="registerForm" autocomplete="off"  action="javascript:submit_register_form()">


					<input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">
					<input class="form-control" type="hidden" name="back" value="{{ $formulario->back ?? '' }}">
					<input class="form-control" type="hidden" name="context_url" value="{{ request('context_url', '') }}">
					<input class="form-control" type="hidden" name="sexo" id="sexo" value="H">
					<input class="form-control" type="hidden" name="sexo" id="tipo_cli" value="1">
					<input class="form-control" type="hidden" name="pri_emp" id="pri_emp" value="F">

					<input class="form-control" type="hidden" name="origen" value="{{ $origen }}">

					<div class="tipo_usuario">
						<div class="particular selected" onclick="javascript:particular();">
							{{ trans($theme.'-app.login_register.particular') }}
						</div>
						<div class="empresa" onclick="javascript:empresa();">
							{{ trans($theme.'-app.login_register.empresa') }}
						</div>
					</div>

					<div>

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


						<div class="datos_left">
							<label>{{ trans($theme.'-app.login_register.nombre') }}</label>
							{!!$formulario->usuario!!}
						</div>
						<div class=" apellidos datos_right">
							<label>{{ trans($theme.'-app.login_register.apellidos') }}</label>
							{!!$formulario->last_name!!}
						</div>



						<div class="registerParticular datos_left">
							<label>{{ trans($theme.'-app.login_register.document_type') }} </label>
							{!!FormLib::Select("tdocid_cli", 1, "", $document_type, '', '', true)!!}
						</div>

						<div class="registerEnterprise datos_left">
							<label> {{ trans($theme.'-app.login_register.en_calidad_de') }} </label>
							<select data-placement="right" class="form-control select2 has-content" type="select"
								name="tipv_cli" id="select__0__tipvcli" onblur="comprueba_campo(this)">
								<option value="A">
									{{ trans($theme.'-app.login_register.only_manager') }}</option>
								<option value="R">
									{{ trans($theme.'-app.login_register.legal_representative') }}
								</option>
							</select>
						</div>

						<div class="registerEnterprise datos_right">
							<label>{{ trans($theme.'-app.login_register.dni_representante') }}</label>

							<input type="text" class="form-control effect-16" name="docid_cli" id="texto__0__docidcli"
								value="" onblur="comprueba_campo(this)" data-placement="right" placeholder=""
								autocomplete="off">

						</div>

						<div class="registerEnterprise datos_left">
							<label>{{ trans($theme.'-app.login_register.company') }}</label>
							{!!$formulario->rsoc_cli!!}
						</div>
						<div class="datos_right">
							<label
								class="registerParticular">{{ trans($theme.'-app.login_register.dni') }}</label>
							<label class="registerEnterprise"
								style="display:none">{{ trans($theme.'-app.login_register.cif') }}</label>

							<input type="text" class="form-control effect-16 has-content" name="nif" id="nif__1__nif"
								value="" onblur="comprueba_campo(this)" data-placement="right" placeholder=""
								autocomplete="off" data-content="">

						</div>
						<div class="registerEnterprise " style="color:#d0043c; float: left;width: 100%;">
							<div class="datos_left"> </div>
							<div class="datos_right">
							{{ trans($theme.'-app.login_register.send_CIF') }}
							</div>
						</div>
						<div class="registerParticular  " style="color:#d0043c; float: left;width: 100%;">
							<div class="datos_left"> </div>
							<div class="datos_right">
								{{ trans($theme.'-app.login_register.send_DNI') }}
							</div>
						</div>




						<div class="idioma datos_right">
							<label>{{ trans($theme.'-app.login_register.language') }}</label>
							{!!$formulario->language!!}
						</div>



						<div class="datos_left">
							<label>{{ trans($theme.'-app.login_register.phone') }}</label>
							{!!$formulario->telefono!!}
						</div>


						<div class="movil datos_right ">
							<label>{{ trans($theme.'-app.login_register.mobile') }}</label>
							{!!$formulario->movil!!}
						</div>



						<div class="datos_left">
							<labeL>{{ trans($theme.'-app.login_register.pais') }}</label>
							{!!$formulario->pais!!}
						</div>

						<div class="datos_right">
							<label>{{ trans($theme.'-app.login_register.cod_postal') }}</label>
							{!!$formulario->cpostal!!}
						</div>



						<div class="datos_left">
							<label>{{ trans($theme.'-app.login_register.provincia') }}</label>
							{!!$formulario->provincia!!}
						</div>




						<div class="datos_right">
							<label>{{ trans($theme.'-app.login_register.ciudad') }}</label>
							{!!$formulario->poblacion!!}
						</div>

						<div class="datos_left">
							<label>{{ trans($theme.'-app.login_register.direccion') }}</label>
							{!!$formulario->direccion!!}
						</div>



						<div class="datos_right">
							<label>{{ trans($theme.'-app.user_panel.date_birthday') }}</label>
							{!! str_replace("fecha__1__date","fecha__0__date",$formulario->fecha_nacimiento) !!}
						</div>



						<div class="observaciones datos_left">
							<label>{{ trans($theme.'-app.login_register.observacion') }}</label>
							{!!$formulario->obscli!!}
						</div>

						<div class="moneda datos_left">
							<label>{{ trans($theme.'-app.login_register.currency') }}</label>
							{!!$formulario->divisa!!}
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

					<div>
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
									for="via">{{ trans($theme.'-app.login_register.via') }}</label>
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
















					<div>
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
								<small style="color: red;"> La contraseña debe tener mínimo 5 carácteres </small>
							</div>

							<div>
								<label
									for="confirmcontrasena">{{ trans($theme.'-app.login_register.confirm_password') }}</label>
								{!!$formulario->confirm_password!!}
								<small style="color: red;"> Debe coincidir con contraseña </small>
							</div>

							<div class="clearfix"></div>
						</div>

						<div class="clearfix"></div>

						<div class="datos_newsletter">
							<span>
								<input type="checkbox" name="newsletter" value="1">
							</span>
							<label for="bool__1__condiciones">
								{{ trans($theme.'-app.login_register.recibir_newsletter') }}
							</label>
							<br>
							<span>
								<input type="checkbox" name="newsletter2" value="1">
							</span>
							<label for="bool__1__condiciones">
								{{ trans($theme.'-app.login_register.informacion_comercial') }}
							</label>






						</div>


						<div>

							<div>
								{!! $formulario->condiciones!!}
								<label for="bool__1__condiciones">{!!
									trans($theme.'-app.login_register.read_conditions') !!} (<a
										href="<?php echo Routing::translateSeo('pagina') . trans($theme . '-app.links.term_condition') ?>"
										target="_blank">{{ trans($theme.'-app.login_register.more_info') }}</a>)
								</label>
							</div>
							<br>
							<div class="col-xs-12 col-md-offset-3">
								<div class="g-recaptcha" data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"
									data-callback="onSubmit">
								</div>

							</div>

							<div class="clearfix"></div>
						</div>

					</div>


					<div class="col-xs-12 text-center">
						<button type="submit" class="btn btn-primary submitButton">
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
				<form id="formToSubalia" method="post" action="{{\Config::get("app.subalia_URL", "https://subalia.es")}}/registerclicli">
					@else
					<form id="formToSubalia" method="post" action="{{\Config::get("app.subalia_URL", "https://subalia.es")}}/registerclicli">
						@endif
						<input type="hidden" name="info" id="info_sent" value="">
						<input type="hidden" name="cod_auchouse" id="cod_auchouse_sent" value="">
						<input type="hidden" name="redirect" id="redirect_sent" value="">
					</form>
					@endif

				{{-- @if(!empty(\Config::get('app.ps_activate')))
					@php
					$urlToExternalLogin = $formulario->back == 'nft';
					@endphp
					<iframe id="iframePresta" width="1px" height="1px" frameborder="0px"></iframe>
					<form id="formPresta" method="post" action="{{ \Config::get('app.ps_shop_path') . 'api-ajax/external-login' }}">
					<input type="hidden" name="valoresPresta" id="valoresPresta" value="">
					<input type="hidden" name="submitLogin" id="submitLogin" value="1">
				</form>
				@endif --}}
					<br><br><br><br><br><br><br><br>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {

	//quitamos la comprobación de NIF si han elegido pasaporte
	$("[name='tdocid_cli']").change(function (){
		if( $(this).val() == '3'){
			$("[name='nif']").attr("id", "text__1__nif");
		}else{
			$("[name='nif']").attr("id", "nif__1__nif");
		}
		console.log($(this).val());
	})


})
</script>
@stop
