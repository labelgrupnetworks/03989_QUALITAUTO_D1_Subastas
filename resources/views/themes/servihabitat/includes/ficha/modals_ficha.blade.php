<div id="ordenFicha" class="container modal-block mfp-hide " >
            <div   data-to="pujarLoteFicha" class="modal-sub-w"  >
                    <section class="panel">
                            <div class="panel-body">
                                    <div class="modal-wrapper">
                                            <div class=" text-center single_item_content">
                                                <p class="class_h1"><?=trans(\Config::get('app.theme').'-app.lot.confirm_bid')?></p>
												<p>
                                                <span for="bid" class='desc_auc'>{{trans(\Config::get('app.theme').'-app.lot.bidding_for')}} </span>
												<strong><span class="precio_orden"></span> €</strong>
													@if(\Config::get("app.exchange"))
													|	<span id="newOrderExchange_JS" class="exchange"></span>
												   @endif
												</p>
												<p>
												<span>{!! trans("$theme-app.lot.aditional_info_puja") !!}</span>
												</p>
												<br>
                                                    <button id="confirm_orden_custom" data-subcsub="{{ $subasta_abierta_O }}" class="btn button_modal_confirm btn-custom"><?=trans(\Config::get('app.theme').'-app.lot.confirm')?> </button>

                                                     <div class='mb-10'></div>

                                            </div>
                                    </div>
                            </div>
                    </section>
            </div>
</div>


<div id="modalPujarFicha" class="container modal-block mfp-hide ">
            <div   data-to="pujarLoteFicha" class="modal-sub-w">
                    <section class="panel">
                            <div class="panel-body">
                                    <div class="modal-wrapper">
                                            <div class=" text-center single_item_content_">
                                                <p class="class_h1">{{ trans(\Config::get('app.theme').'-app.lot.confirm_bid') }}</p><br/>
                                                <span for="bid" class='desc_auc'>{{ trans(\Config::get('app.theme').'-app.lot.you_are_bidding') }} </span> <strong><span class="precio_orden"></span> €</strong><br/>
                                                    </br>
                                                    <button  class="confirm_puja btn button_modal_confirm btn-custom">{{ trans(\Config::get('app.theme').'-app.lot.confirm') }}</button>
                                                    <div class='mb-10'></div>
                                                     <div class='mb-10'></div>

                                            </div>
                                    </div>
                            </div>
                    </section>
            </div>
</div>




<div id="modal_frame"  data-to="pujarLoteFichaBalclis" class="container modal-block mfp-hide ">
    <div class='modal-dialog modal-sm'>
    </div>
</div>


 <div id="modalCloseBids" class="modal-block mfp-hide">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="txt_loading"> {{ trans(\Config::get('app.theme').'-app.lot.loading') }}</p>
						<div class="loader"></div>
					</div>
				</div>
			</div>
		</section>
	</div>
<div id="modalDisconnected" class="modal-block mfp-hide">
        <section class="panel">
                <div class="panel-body">
                        <div class="modal-wrapper">
                                <div class="modal-text text-center">
                                        <p class="txt_loading"> {{ trans(\Config::get('app.theme').'-app.lot.connect_to_serv') }}</p>
                                        <div class="loader"></div>
                                </div>
                        </div>
                </div>
        </section>
</div>
<div id="modalComprarFicha" class="modal-block mfp-hide" data-to="comprarLoteFicha">
	<section class="panel">
		<div class="panel-body">
			<div class="modal-wrapper">
				<div class="modal-text text-center">
					<p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.buy_lot_question') }}</p>
					<button class="btn btn-primary modal-confirm btn-custom">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
					<button class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
				</div>
			</div>
		</div>
	</section>
</div>


<?php  //estructura necesaria para crear lso elementos del listado de pujas  ?>
    <div id="duplicalte_list_pujas" class="hist_item hidden">
        <span class="col-xs-8">
            <span>{{ trans(\Config::get('app.theme').'-app.lot.bidder') }}</span> (
            <span class="yo">{{ trans(\Config::get('app.theme').'-app.lot.I') }}</span>
            <span class="uno hint--bottom-right hint--medium" data-hint=""></span>
            <span class="dos hint--bottom-right hint--medium" data-hint="<?= nl2br(trans(\Config::get('app.theme').'-app.lot.puja_automatica')) ?>">A</span>)
            <span class="date"></span>
        </span>
        <span class="col-xs-4">
            <span class="tres_item"><span class="price "> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</span></span>
        </span>
   </div>

<!--<div id="price_min_surpass" class="info hidden"><div class="col-xs-12 ">{{ trans(\Config::get('app.theme').'-app.lot.minimal-price') }} <strong> {{$lote_actual->impres_asigl0}} {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</strong></div></div>-->
<?php  //view more debe llevar el hidden para que funcione correctamente, el customized_tr_main ya se lo quitara si es necesario ?>
<div id="view_more" class="more more-historic-bids hidden col-xs-12 text-right hidden">
    <a title="ver todas"  data-toggle="collapse" data-target="#pujas-collapse" href="javascript:view_all_bids();">
     <span id="view_more_text">{{ trans(\Config::get('app.theme').'-app.lot.see-all') }} </span>
    <span id="hide_bids_text" class="hidden">{{ trans(\Config::get('app.theme').'-app.lot.hidden') }} </span> <i class="fa fa-angle-down"></i></a>

</div>


<!-- Modal -->
<div class="modal fade custom-modal" id="generalTermsModal" tabindex="-1" role="dialog" aria-labelledby="generalTermsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title d-inline-block" id="exampleModalLabel">{{ trans("$theme-app.subastas.general_conditions") }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{ trans("$theme-app.subastas.conditions_content") }}
      </div>

    </div>
  </div>
</div>

@if(!empty($ownerData))
<div class="modal fade custom-modal" id="prop-modal" tabindex="-1" role="dialog" aria-labelledby="prop-modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title d-inline-block" id="prop-modalLabel">{{ trans("$theme-app.lot.owner_details") }}</h4>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<div class="modal-body">
			{!! $ownerData !!}
		  {{-- {!! trans("$theme-app.subastas.conditions_prop_$lote_actual->prop_hces1") !!} --}}
		</div>
	  </div>
	</div>
</div>
@endif
