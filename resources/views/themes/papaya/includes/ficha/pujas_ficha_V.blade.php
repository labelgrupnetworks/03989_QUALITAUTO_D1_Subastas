<div class="col-xs-12 no-padding ">


    <div class="info_single ficha_V col-xs-12 no-padding">

        <div class="col-xs-12 no-padding ficha-info-items-buy">
            <div class="pre">
					<?php
					/*
					<p class="pre-title-principal">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</p>
					<p class="pre-price">{{$lote_actual->formatted_actual_bid}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
					*/
					?>
                </div>
                <div class="info_single_content info_single_button ficha-button-buy">
					@if (!$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
						@if(Session::has('user'))
							<a target="_blank" class="" title="{{ trans(\Config::get('app.theme').'-app.subastas.ask_information') }}" href="mailto:info@inbusa.es?Subject=<?= \Config::get('app.name')?>">
								<button class="button-principal lot-action_info_lot-tomail" type="button">{{ trans(\Config::get('app.theme').'-app.foot.contact') }}</button>
							</a>
						@else
							<button data-from="modal" class="button-principal lot-action_info_lot" type="button" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.foot.contact') }}</button>
						@endif
					@endif

                </div>
            </div>

    </div>
</div>

