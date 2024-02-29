<div id="ordenFicha" class="container modal-block mfp-hide " > 
            <div   data-to="pujarLoteFicha" class="modal-sub-w"  >
                    <section class="panel">
                            <div class="panel-body">
                                    <div class="modal-wrapper">
                                            <div class=" text-center single_item_content">
                                                <p class="class_h1"><?=trans($theme.'-app.lot.confirm_bid')?></p><br/>
                                                <span for="bid" class='desc_auc'>{{trans($theme.'-app.lot.bidding_for')}} </span> <strong><span class="precio_orden"></span> €</strong><br/>
                                                    </br>
                                                    <button id="confirm_orden" class="btn button_modal_confirm btn-custom"><?=trans($theme.'-app.lot.confirm')?> </button>
                                                   
                                                     <div class='mb-10'></div>
                                                    <ul class="items_list">     
                                                        <li><?=trans($theme.'-app.lot.tax_not_included')?> </li>
                                                    </ul>
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
                                                <p class="class_h1">{{ trans($theme.'-app.lot.confirm_bid') }}</p><br/>
                                                <span for="bid" class='desc_auc'>{{ trans($theme.'-app.lot.you_are_bidding') }} </span> <strong><span class="precio_orden"></span> €</strong><br/>
                                                    </br>
                                                    <button  class="confirm_puja btn button_modal_confirm btn-custom">{{ trans($theme.'-app.lot.confirm') }}</button>
                                                    <div class='mb-10'></div>
                                                     <div class='mb-10'></div>
                                                    <ul class="items_list">
                                                        <li><?=trans($theme.'-app.lot.tax_not_included')?> </li>
                                                        
                                                    </ul>
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
						<p class="txt_loading"> {{ trans($theme.'-app.lot.loading') }}</p>
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
                                        <p class="txt_loading"> {{ trans($theme.'-app.lot.connect_to_serv') }}</p>
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
					<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.buy_lot_question') }}</p>
					<button class="btn btn-primary modal-confirm btn-custom">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
					<button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
				</div>
			</div>
		</div>
	</section>
</div>


<?php  //estructura necesaria para crear lso elementos del listado de pujas  ?>
    <div id="duplicalte_list_pujas" class="hist_item hidden">
        <span class="col-xs-6">
            <span>{{ trans($theme.'-app.lot.bidder') }}</span> (
            <em class="yo">{{ trans($theme.'-app.lot.I') }}</em>
            <em class="uno hint--bottom-right hint--medium" data-hint=""></em>
            <em class="dos hint--bottom-right hint--medium" data-hint="<?= nl2br(trans($theme.'-app.lot.puja_automatica')) ?>">A</em>)
            <em class="date"></em>
        </span>
        <span class="col-xs-6">
            <span class="tres_item"><em class="price "> {{ trans($theme.'-app.lot.eur') }}</em></span>
        </span>
   </div>

<!--<div id="price_min_surpass" class="info hidden"><div class="col-xs-12 ">{{ trans($theme.'-app.lot.minimal-price') }} <strong> {{$lote_actual->impres_asigl0}} {{ trans($theme.'-app.lot.eur') }}</strong></div></div>-->
<div id="view_more" class="more hidden col-xs-12 text-right">
    <a title="ver todas"  data-toggle="collapse" data-target="#pujas-collapse" href="javascript:view_all_bids();">
     <span id="view_more_text">{{ trans($theme.'-app.lot.see-all') }} </span> 
    <span id="hide_bids_text" class="hidden">{{ trans($theme.'-app.lot.hidden') }}</span></a>
    <i class="fa fa-angle-down"></i>
</div>
