
<?php
$importe = \Tools::moneyFormat($lote_actual->actual_bid,"", 2);
if(!empty($lote_actual->impres_asigl0) && $lote_actual->impres_asigl0 >  $lote_actual->impsalhces_asigl0 ){
	$importe =  \Tools::moneyFormat($lote_actual->impres_asigl0,"",2);
}


?>






<div class="col-xs-12 no-padding ">


    <div class="info_single ficha_V col-xs-12 ">

		<div class="row">
			<div class="col-xs-12 ficha-info-items-buy border-top-bottom">

				<div class="pre d-flex justify-content-space-between">
					<p class="pre-price mt-1 mb-1">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</p>
                    <p class="pre-price mt-1 mb-1">{{$importe}}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                </div>
			</div>


			<div class="col-xs-12 mt-1 p-0">
				<div class="info_single_content info_single_button ficha-button-buy">
					@if (!$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
						<button data-from="modal"  class="button-principal addShippingCart_JS button-principal  w-100" type="button" >{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</button>
						{{-- código de token --}}
							@csrf
					@endif
				</div>
			</div>
		</div>

	</div>

</div>

