
<?php
$importe = \Tools::moneyFormat($lote_actual->actual_bid,"", 2);
if(!empty($lote_actual->impres_asigl0) && $lote_actual->impres_asigl0 >  $lote_actual->impsalhces_asigl0 ){
	$importe =  \Tools::moneyFormat($lote_actual->impres_asigl0,"",2);
}


?>






<div class="col-xs-12 no-padding ">


    <div class="info_single ficha_V col-xs-12 no-padding">

        <div class="col-xs-12 no-padding ficha-info-items-buy">
            <div class="pre">
                    <p class="pre-title-principal">{{ trans($theme.'-app.subastas.price_sale') }}</p>
                    <p class="pre-price">{{$importe}}  {{ trans($theme.'-app.subastas.euros') }}</p>
                </div>
                <div class="info_single_content info_single_button ficha-button-buy">
					@if (!$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
						<button data-from="modal" style="width: 100%;" class="button-principal addShippingCart_JS" type="button" >{{ trans($theme.'-app.subastas.buy_lot') }}</button>
						{{-- código de token --}}
							@csrf
					@endif
                </div>
            </div>

    </div>
</div>

