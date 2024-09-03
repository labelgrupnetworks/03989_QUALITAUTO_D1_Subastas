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

<div class="info-type-auction  mt-2 ">{{$titulo}}</div>

<form name="infoLotForm" id="infoLotForm" method="post" action="javascript:sendInfoLot()">
	<input type="hidden" name="auction" value="Venta Privada">
	<input type="hidden" data-sitekey="{{ config('app.captcha_v3_public') }}" name="captcha_token" value="">
	<input type="hidden" name="lot" value="{{$lote_actual->ref_asigl0}}">

  		@csrf
	<div class="form-group ">
		<div class="input-effect col-xs-12">
			<label>{{trans($theme.'-app.login_register.contact') }}</label>
			<input type="text" class="form-control  " name="nombre" id="texto__1__nombre" value="{{$name}}" onblur="comprueba_campo(this)" data-placement="right" placeholder="" autocomplete="off" data-content="">

		</div>

		<div class="input-effect col-xs-12">
			<label>{{trans($theme.'-app.foot.newsletter_text_input') }}</label>
			<input type="text" class="form-control  " name="email" id="email__1__email" value="{{$email}}" onblur="comprueba_campo(this)" data-placement="right" placeholder="" autocomplete="off" data-content="">

		</div>

		<div class="input-effect col-xs-12">
			<label>{{trans($theme.'-app.user_panel.phone') }}</label>
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
						for="accept_new"><?= trans($theme . '-app.emails.privacy_conditions') ?></label>
				</div>
			</div>
		</div>

		<div class="col-xs-12 mt-3">
			<p class="captcha-terms">
				{!! trans("$theme-app.global.captcha-terms") !!}
			</p>
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
