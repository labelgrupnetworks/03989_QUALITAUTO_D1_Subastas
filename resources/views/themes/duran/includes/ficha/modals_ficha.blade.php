<div id="modalOrdenTelefonica" class="container modal-block mfp-hide " >
	<div   data-to="pujarLoteFicha" class="modal-sub-w"  >
			<section class="panel">
					<div class="panel-body">
							<div class="modal-wrapper">
									<div class=" text-center single_item_content">
										<p class="class_h1"><?=trans(\Config::get('app.theme').'-app.lot.title_phone_bid')?> <span id="numLotPhoneBid"> </span></p>
										<br/>

										<br>
										{{ Session::get('user.name')}},	{{trans(\Config::get('app.theme').'-app.lot.phonebid_text')}} <br><br>

										<p><?=trans(\Config::get('app.theme').'-app.login_register.phone')?>: <input style="padding: 0 0 0 5px;" type="text" value="" name="phone1" id="phone1Bid_JS"></p>
										<p><?=trans(\Config::get('app.theme').'-app.login_register.mobile')?>: <input style="padding: 0 0 0 5px;" type="text" value="" name="phone2" id="phone2Bid_JS"></p>
										<?=trans(\Config::get('app.theme').'-app.global.coment')?>:
										<br/>
										<p><textarea style="padding: 5px;" id="commentsBid_JS" placeholder="{{trans(\Config::get('app.theme').'-app.lot.placeholder_phonebid_text')}}"  name="comments" cols="40" rows="10"></textarea></p>
										<br/>

										<br/>
											<button id="confirm_orden_telefonica" class="btn button_modal_confirm btn-custom"><?=trans(\Config::get('app.theme').'-app.lot.confirm')?> </button>

											 <div class='mb-10'></div>

									</div>
							</div>
					</div>
			</section>
	</div>
</div>



<div id="ordenFicha" class="container modal-block mfp-hide " >
            <div   data-to="pujarLoteFicha" class="modal-sub-w"  >
                    <section class="panel">
                            <div class="panel-body">
                                    <div class="modal-wrapper">
                                            <div class=" text-center single_item_content">
												<p class="class_h1"><?=trans(\Config::get('app.theme').'-app.lot.confirm_bid')?></p>

												<br/>
                                                <span for="bid" class='desc_auc'>{{trans(\Config::get('app.theme').'-app.lot.bidding_for')}} </span> <strong><span class="precio_orden"></span> €</strong><br/>
													<br/>
													<p id="phonebid_js">{{trans(\Config::get('app.theme').'-app.lot.phonebid_text')}} <br><span id="phone_contact_js"> </span></p>
													<br/>
                                                    <button id="confirm_orden" class="btn button_modal_confirm btn-custom"><?=trans(\Config::get('app.theme').'-app.lot.confirm')?> </button>

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
