
<?php
$importe = \Tools::moneyFormat($lote_actual->actual_bid);
if(!empty($lote_actual->impres_asigl0) && $lote_actual->impres_asigl0 >  $lote_actual->impsalhces_asigl0 ){
	$importe =  \Tools::moneyFormat($lote_actual->impres_asigl0);
}


?>
<div class="col-xs-12 no-padding ">


    <div class="info_single ficha_V col-xs-12 no-padding">

        <div class="col-xs-12 no-padding ficha-info-items-buy">
            <div class="pre">
                    <p class="pre-title-principal">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</p>
                    <p class="pre-price">{{$importe}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                </div>
                <div class="info_single_content info_single_button ficha-button-buy">
					@if (!$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
						@if ($lote_actual->opcioncar_sub == 'S')
							<button style="background: red;" data-from="modal" class="button-principal lot-action_info_lot" type="button" data-toowner="1" data-numhces1="{{ $lote_actual->num_hces1 }}" data-linhces1="{{ $lote_actual->lin_hces1 }}">{{ trans(\Config::get('app.theme').'-app.subastas.contact') }}</button>
						@else
							<button data-from="modal" class="button-principal lot-action_info_lot" type="button" data-toowner="0" data-numhces1="{{ $lote_actual->num_hces1 }}" data-linhces1="{{ $lote_actual->lin_hces1 }}">{{ trans(\Config::get('app.theme').'-app.subastas.ask_information') }}</button>
						@endif

                    @endif
                </div>
            </div>

    </div>
</div>
