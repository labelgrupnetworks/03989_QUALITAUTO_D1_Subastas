
<?php
$importe = \Tools::moneyFormat($lote_actual->actual_bid);
$importeExchange = $lote_actual->actual_bid;
if(!empty($lote_actual->impres_asigl0) && $lote_actual->impres_asigl0 >  $lote_actual->impsalhces_asigl0 ){
	$importe =  \Tools::moneyFormat($lote_actual->impres_asigl0);
	$importeExchange = $lote_actual->impres_asigl0;
}


?>
<div class="col-xs-12 no-padding ">


    <div class="info_single ficha_V col-xs-12 no-padding">

        <div class="col-xs-12 no-padding ">

                <div class="info_single_content info_single_button ">
                    @if (!$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
						@if ($lote_actual->es_nft_asigl0 == "S" &&  !empty($data["usuario"])  && empty($data["usuario"]->wallet_cli) )
						<div class="require-wallet">{!! trans($theme.'-app.lot.require_wallet') !!}</div>

						@else
							<div class="input-group d-block group-pujar-custom ">
								<div>
									<div class="insert-bid insert-max-bid mb-1">{{ trans($theme.'-app.lot.insert_max_puja') }}</div>
								</div>
								<div class="d-flex mb-2">
									<input id="bid_make_offer"  class="NoAutoComplete_JS form-control control-number" type="text" value="">
								<div class="input-group-btn">
									<button type="button" data-from="modal" class=" lot-action_comprar_lot makeOffer_JS ficha-btn-bid ficha-btn-bid-height button-principal <?= Session::has('user')?'add_favs':''; ?>" type="button" ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}" >{{ trans($theme.'-app.lot.pujar') }}</button>
								</div>
							</div>
						@endif
					@endif
                </div>
				<div class="mt-3">
					@if (\Config::get('app.urlToPackengers'))
						<?php
							$lotFotURL = $lote_actual->cod_sub . '-' . $lote_actual->ref_asigl0;
							$urlCompletePackengers = \Config::get('app.urlToPackengers') . $lotFotURL;
						?>
						<div class="packengers-container-button-ficha">
							<a class="packengers-button-ficha" href="{{ $urlCompletePackengers }}" target="_blank">
								<i class="fa fa-truck" aria-hidden="true"></i>
								{{ trans("$theme-app.lot.packengers_ficha") }}
							</a>
						</div>
					@endif
				</div>
            </div>

    </div>
</div>
