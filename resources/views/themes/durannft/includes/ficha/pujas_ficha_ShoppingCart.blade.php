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

<div class="info_single ficha_Shopping">
	<div class="pre prices-wrapper mt-1">

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
