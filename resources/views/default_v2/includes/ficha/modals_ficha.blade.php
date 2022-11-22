<div class="container modal-block mfp-hide " id="ordenFicha">
    <div class="modal-sub-w" data-to="pujarLoteFicha">
        <section class="panel">
            <div class="panel-body">
                <div class="modal-wrapper">
                    <div class=" text-center single_item_content">
                        <p class="class_h1"><?= trans(\Config::get('app.theme') . '-app.lot.confirm_bid') ?></p><br />
                        <span class='desc_auc'
                            for="bid">{{ trans(\Config::get('app.theme') . '-app.lot.bidding_for') }} </span>
                        <strong><span class="precio_orden"></span>
                            {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}</strong>
                        @if (\Config::get('app.exchange'))
                            | <span class="exchange" id="newOrderExchange_JS"></span>
                        @endif
                        <br />
                        <br />
                        <p class="phonebid_js">{{ trans(\Config::get('app.theme') . '-app.lot.phonebid_text') }} <br>
                        </p>
                        <p class="phonebid_js"><?= trans(\Config::get('app.theme') . '-app.login_register.phone') ?> 1:
                            <input id="phone1Bid_JS" name="phone1" type="text" value=""
                                style="padding: 0 0 0 5px;">
                        </p>
                        <p class="phonebid_js"><?= trans(\Config::get('app.theme') . '-app.login_register.phone') ?> 2:
                            <input id="phone2Bid_JS" name="phone2" type="text" value=""
                                style="padding: 0 0 0 5px;">
                        </p>


                        <button class="btn button_modal_confirm btn-custom"
                            id="confirm_orden"><?= trans(\Config::get('app.theme') . '-app.lot.confirm') ?> </button>

                        <div class='mb-10'></div>

                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


<div class="container modal-block mfp-hide" id="modalPujarFicha">
    <div class="modal-sub-w" data-to="pujarLoteFicha">
        <section class="panel">
            <div class="modal-wrapper">
                <div class="modal-header">
                    <h5 class="class_h1">{{ trans(\Config::get('app.theme') . '-app.lot.confirm_bid') }}</h5>
                </div>
                <div class="modal-body">
                    <p class='desc_auc' for="bid">
                        {{ trans(\Config::get('app.theme') . '-app.lot.you_are_bidding') }} </p>
                    <p><strong><span class="precio_orden"></span>
                            {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}</strong></p>
                </div>
                <div class="modal-footer">
                    <button
                        class="confirm_puja btn button_modal_confirm">{{ trans(\Config::get('app.theme') . '-app.lot.confirm') }}</button>
                </div>

            </div>
        </section>
    </div>
</div>




<div class="container modal-block mfp-hide " id="modal_frame" data-to="pujarLoteFichaBalclis">
    <div class='modal-dialog modal-sm'>
    </div>
</div>


<div class="modal-block mfp-hide" id="modalCloseBids">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="txt_loading"> {{ trans(\Config::get('app.theme') . '-app.lot.loading') }}</p>
                    <div class="loader"></div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal-block mfp-hide" id="modalDisconnected">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="txt_loading"> {{ trans(\Config::get('app.theme') . '-app.lot.connect_to_serv') }}</p>
                    <div class="loader"></div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal-block mfp-hide" id="modalComprarFicha" data-to="comprarLoteFicha">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg">{{ trans(\Config::get('app.theme') . '-app.sheet_tr.buy_lot_question') }}</p>
                    <button
                        class="btn btn-primary modal-confirm btn-custom">{{ trans(\Config::get('app.theme') . '-app.sheet_tr.confirm') }}</button>
                    <button
                        class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme') . '-app.sheet_tr.cancel') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>



<div class="container modal-block mfp-hide " id="modalMakeOffer" data-to="makeOfferFicha">
    <div class="modal-sub-w">
        <section class="panel">
            <div class="panel-body">
                <div class="modal-wrapper">
                    <div class=" text-center single_item_content_">
                        <p class="class_h1">{{ trans(\Config::get('app.theme') . '-app.lot.confirm_bid') }}</p><br />
                        <span class='desc_auc'
                            for="bid">{{ trans(\Config::get('app.theme') . '-app.lot.you_are_bidding') }} </span>
                        <strong><span class="imp_make_offer"></span>
                            {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}</strong><br />
                        </br>

                        <button
                            class="btn btn-primary modal-confirm btn-custom">{{ trans(\Config::get('app.theme') . '-app.sheet_tr.confirm') }}</button>
                        <button
                            class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme') . '-app.sheet_tr.cancel') }}</button>
                        <div class='mb-10'></div>
                        <div class='mb-10'></div>

                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
