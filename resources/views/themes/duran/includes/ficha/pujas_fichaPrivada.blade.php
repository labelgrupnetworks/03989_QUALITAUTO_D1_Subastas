<?php
$name="";
$phone="";
$email="";
if(!empty($data['usuario'])){
	$name=$data['usuario']->nom_cliweb;
	$phone=$data['usuario']->tel1_cli;
	$email=$data['usuario']->email_cliweb;

}

?>

<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>
<div class="info-type-auction  mt-2 ">{{$titulo}}</div>

<form name="infoLotForm" id="infoLotForm" method="post" action="javascript:sendInfoLot()">
	<input type="hidden" name="auction" value="Venta Privada">
	<input type="hidden" name="lot" value="{{$lote_actual->ref_asigl0}}">

  		@csrf
	<div class="form-group ">
		<div class="input-effect col-xs-12">
			<label>{{trans(\Config::get('app.theme').'-app.login_register.contact') }}</label>
			<input type="text" class="form-control  " name="nombre" id="texto__1__nombre" value="{{$name}}" onblur="comprueba_campo(this)" data-placement="right" placeholder="" autocomplete="off" data-content="">

		</div>

		<div class="input-effect col-xs-12">
			<label>{{trans(\Config::get('app.theme').'-app.foot.newsletter_text_input') }}</label>
			<input type="text" class="form-control  " name="email" id="email__1__email" value="{{$email}}" onblur="comprueba_campo(this)" data-placement="right" placeholder="" autocomplete="off" data-content="">

		</div>

		<div class="input-effect col-xs-12">
			<label>{{trans(\Config::get('app.theme').'-app.user_panel.phone') }}</label>
			<input type="text" class="form-control  " name="telefono" id="texto__1__telefono" value="{{$phone}}" onblur="comprueba_campo(this)" data-placement="right" placeholder="" autocomplete="off" data-content="">

		</div>

		<div class="input-effect col-xs-12">
			<label>Comentarios</label>
			<textarea  class="form-control  " name="comentario"  id="textogrande__1__comentario"   rows="10">  </textarea>

		</div>

		<div class="check_term col-xs-12">
			<div class="row">
				<div class="col-xs-2 col-md-1">
					<input type="checkbox" class="newsletter" name="condiciones" value="on"
						id="bool__1__condiciones" autocomplete="off">
				</div>
				<div class="col-xs-10 col-md-11">
					<label
						for="accept_new"><?= trans(\Config::get('app.theme') . '-app.emails.privacy_conditions') ?></label>
				</div>
			</div>
		</div>

		<div class="col-xs-12 mt-3">
			<div class="row">
				<div class="g-recaptcha col-xs-6"
					data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"
					data-callback="onSubmit">
				</div>
			</div>
		</div>

		<div class="col-xs-12 mt-3">
			<div class="row">
				<div class="col-xs-6">
					<a onclick="javascript:submit_form(document.getElementById('infoLotForm'),0);" class="button-principal submitButton">Enviar</a>
				</div>
			</div>
		</div>




	</div>
	<div class="clearfix"></div>



</form>

<script>
function sendInfoLot() {


		$.ajax({
			type: "POST",
			data: $("#infoLotForm").serialize(),
			url: '/api-ajax/ask-info-lot',
			success: function (res) {

				showMessage("¡Gracias! Hemos sido notificados.  ");


			},
			error: function (e) {
				showMessage("Ha ocurrido un error y no hemos podido ser notificados");
			}
		});

}
</script>
