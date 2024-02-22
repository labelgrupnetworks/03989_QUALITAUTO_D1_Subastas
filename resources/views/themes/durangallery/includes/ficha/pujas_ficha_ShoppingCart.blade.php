@php
$name="";
$phone="";
$email="";
if(!empty($data['usuario'])){
	$name=$data['usuario']->nom_cliweb;
	$phone=$data['usuario']->tel1_cli;
	$email=$data['usuario']->email_cliweb;
}
@endphp

<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>

<div class="info_single ficha_Shopping">
	<div class="pre prices-wrapper mt-1">
		@if($compra)
			<div class="shop-price">
				<p class="pre-price m-0">{{  \Tools::moneyFormat(\Tools::PriceWithTaxForEuropean( $lote_actual->impsalhces_asigl0,\Session::get('user.cod')),false,2) }} {{ trans($theme.'-app.subastas.euros') }}</p>
			</div>

			@if(Session::has('user'))
			<button data-from="modal" class="button-principal addShippingCart_JS" type="button">
				<i class="fa fa-shopping-bag" aria-hidden="true"></i>
				{{trans($theme.'-app.subastas.buy_lot') }}
			</button>
			@else
			<button data-from="modal" class="button-principal" type="button" id="js-ficha-login">
				<i class="fa fa-shopping-bag" aria-hidden="true"></i>
				{{trans($theme.'-app.subastas.buy_lot') }}
			</button>
			@endif

		@else
		<div  class="col-xs-12 ">
			<div id="RequestInformationView" class="cursor">
				<strong>
				{{trans($theme.'-app.galery.request_information') }}
				</strong>
				<span id="desplegableOFF"><img src="/default/img/icons/flechaDer.png"> </span>
				<span id="desplegableON" class=" hidden"><img src="/default/img/icons/flechaAba.png"> </span>
			</div>
			<div id="formRequest" class="hidden">
				<form name="infoLotForm" id="infoLotForm" method="post" action="javascript:sendInfoLot()">
					<input type="hidden" name="auction" value="{{ $lote_actual->cod_sub}}">
					<input type="hidden" name="lot" value="{{$lote_actual->ref_asigl0 }}">
					<input type="hidden" name="info_lot" value="1">


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
							<label>{{trans($theme.'-app.global.coment') }}</label>
							<textarea  class="form-control  " name="comentario"  id="textogrande__0__comentario"   rows="10">  </textarea>

						</div>
						{{--
						<input type="hidden" name="INFORMACIÓN DE LA OBRA" value="">

						@foreach($caracteristicas as $key => $caracteristica)
							@if($key==1)
								<input type="hidden" name="Autor" value="{{$Artistname}}">

							@else
							<input type="hidden" name="{{ $caracteristica->name_caracteristicas }}" value="{{$caracteristica->value_caracteristicas_hces1 }}">


							@endif
						@endforeach
						--}}
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
			</div>
		</div>
		@endif
		@csrf

	</div>
</div>

<script>

	$(function() {
		$("#RequestInformationView").on("click", function(){
		//	if($("#biographyArtistText").hasClass("hidden"){
				$("#formRequest").toggleClass("hidden");
				$("#desplegableOFF").toggleClass("hidden");
				$("#desplegableON").toggleClass("hidden");
			//}
		})
	});

</script>
