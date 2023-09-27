<?php

$name="";
$phone="";
$email="";
if(!empty($data['usuario'])){
	$name=$data['usuario']->nom_cliweb;
	$phone=$data['usuario']->tel1_cli;
	$email=$data['usuario']->email_cliweb;

}


    $precio_venta=NULL;
if (!empty($lote_actual->himp_csub)){
	$precio_venta=$lote_actual->himp_csub;
}
//si es un histórico y la subasta del asigl0 = a la del hces1 es que no está en otra subasta y podemso coger su valor de compra de implic_hces1
else{
    $precio_venta = $lote_actual->implic_hces1;
}

//Si hay precio de venta y impsalweb_asigl0 contiene valor, mostramos este como precio de venta
//$precio_venta = (!empty($precio_venta) && $lote_actual->impsalweb_asigl0 != 0) ? $lote_actual->impsalweb_asigl0 : $precio_venta;

//dd($lote_actual);
?>
<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>
<div class="info_single lot-sold col-xs-12 no-padding">

            <div class="col-xs-8 col-sm-12 no-padding ">
                @if($cerrado && !empty($precio_venta) && $remate )
                    <div class="pre">
                        <p class="pre-title-principal adj-text">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}</p>
                        <p class="pre-price">{{ \Tools::moneyFormat($precio_venta) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                    </div>

                @elseif($cerrado && !empty($precio_venta) &&  !$remate)

                <div class="pre">
                        <p class="pre-title-principal adj-text">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</p>
                </div>
                @elseif($devuelto)
                    <div class="pre">
                            <p class="pre-title-principal adj-text">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
                    </div>
				@elseif($retirado)
                    <div class="pre">
                            <p class="pre-title-principal adj-text">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
                    </div>
				@else {{--if(!$sub_historica && !$sub_cerrada ) --}}
					{{-- Formulario de  petición de información--}}
					@if(!empty($lote_actual->impsalhces_asigl0))
					<div class="pre lot-sold_impsal">
                        <p class="pre-title-principal adj-text">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
                        <p class="pre-price">{{ \Tools::moneyFormat($lote_actual->impsalhces_asigl0) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                    </div>
					@endif

					<p class="pre-title-principal adj-text">	{{ trans(\Config::get('app.theme').'-app.galery.request_information') }} </p>
					<form name="infoLotForm" id="infoLotForm" method="post" action="javascript:sendInfoLot()">
						<input type="hidden" name="auction" value="{{ $lote_actual->cod_sub}} - {{ $lote_actual->des_sub}}">
						<input type="hidden" name="lot" value="   {{$lote_actual->descweb_hces1 }} ">

						<div class="form-group">
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
								<label>{{trans(\Config::get('app.theme').'-app.global.coment') }}</label>
								<textarea  class="form-control  " name="comentario"  id="textogrande__0__comentario"   rows="10">  </textarea>

							</div>
							@php
								/*

							<div class="check_term col-xs-12 mt-2">
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
							*/
							@endphp
							<div class="col-xs-12 mt-3">
								<div class="row">
									<div class="g-recaptcha col-xs-6"
										data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"
										data-callback="onSubmit">
									</div>
								</div>
							</div>

							<div class="col-xs-12 mt-3 mb-3">
								<div class="row">
									<div class="col-xs-6">
										<a onclick="javascript:submit_form(document.getElementById('infoLotForm'),0);" class="button-principal submitButton">Enviar</a>
									</div>
								</div>
							</div>
						</div>
					</form>

                @endif

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

		function sendInfoLot() {


				$.ajax({
					type: "POST",
					data: $("#infoLotForm").serialize(),
					url: '/api-ajax/ask-info-lot',
					success: function (res) {

						showMessage("¡Gracias! Hemos sido notificados.  ");
						$("input[name=nombre]").val('');
						$("input[name=email]").val('');
						$("input[name=telefono]").val('');
						$("textarea[name=comentario]").val('');

					},
					error: function (e) {
						showMessage("Ha ocurrido un error y no hemos podido ser notificados");
					}
				});

		}
		</script>
