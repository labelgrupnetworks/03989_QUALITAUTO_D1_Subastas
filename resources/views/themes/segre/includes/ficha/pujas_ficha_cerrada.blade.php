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
$precio_venta = (!empty($precio_venta) && $lote_actual->impsalweb_asigl0 != 0) ? $lote_actual->impsalweb_asigl0 : $precio_venta;
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
