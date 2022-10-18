
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

        <div class="col-xs-12 no-padding ficha-info-items-buy">
            <div class="pre">
                    <p class="pre-title-principal">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</p>
                    <p class="pre-price">{{$importe}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
						@if(\Config::get("app.exchange"))
						|   <span id="directSaleExchange_JS" class="exchange"> </span>
							<input id="startPriceDirectSale" type="hidden" value="{{$importeExchange}}">
						@endif

					</p>
                </div>
                <div class="info_single_content info_single_button ficha-button-buy">
                    @if (!$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
						{{-- Si el lote es NFT y el usuario está logeado pero no tiene wallet --}}
						@if ($lote_actual->es_nft_asigl0 == "S" &&  !empty($data["usuario"])  && empty($data["usuario"]->wallet_cli) )
							<div class="require-wallet">{!! trans(\Config::get('app.theme').'-app.lot.require_wallet') !!}</div>
						@elseif(!Session::has('user'))
							<button data-from="modal" class="button-principal" type="button" id="js-ficha-login">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</button>
						@else
                        	<button data-from="modal" class="button-principal lot-action_comprar_lot" type="button" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</button>
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
