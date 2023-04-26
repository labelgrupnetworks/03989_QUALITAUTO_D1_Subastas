<div id="modalComprar" class="modal-block mfp-hide" data-to="comprarLote">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.buy_lot_question') }}</p>
                    <button class="btn btn-primary modal-confirm">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
                    <button class="btn btn-danger modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>


<div id="modalPausada" class="modal-block mfp-hide">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg"> {{ trans(\Config::get('app.theme').'-app.sheet_tr.auction_stopped') }} <br /></p>
                    <button class="btn btn-primary modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="modalPendiente" class="modal-block mfp-hide">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg"> {{ trans(\Config::get('app.theme').'-app.sheet_tr.auction_pendiente') }}</p>
                    <button class="btn btn-primary modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
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
                    <p class="txt_loading"> {{ trans(\Config::get('app.theme').'-app.sheet_tr.auction_disconnected') }}</p>
                    <div class="loader"></div>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="modalCancelarPujaUser" class="modal-block mfp-hide" data-to="cancelar_puja_user">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel_bid_question') }}</p>

                    <button class="btn btn-primary modal-confirm">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
                    <button class="btn btn-danger modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="modalCancelarOrdenUser" class="modal-block mfp-hide" data-to="cancelar_orden_user">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel_order_question') }}</p>

                    <button class="btn btn-primary modal-confirm">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
                    <button class="btn btn-danger modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="modalCloseBids" class="modal-block mfp-hide">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="txt_loading"> {{ trans(\Config::get('app.theme').'-app.sheet_tr.loading') }} </p>
                    <p class="txt_esperando_sala hidden"> {{ trans(\Config::get('app.theme').'-app.sheet_tr.esperando_sala') }} </p>
                    <div class="loader"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="modalOrdenImporte" class="modal-block mfp-hide" data-to="orderAmount">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.order_amount') }}</p>
                    <input id="order_amount" autocomplete="off" class="form-control bid_amount_gestor" type="text">
                    <br />
                    <button class="btn btn-primary modal-confirm add_order_amount" data-boton="1">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
                    <button class="btn btn-danger modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="modalMensaje" class="container modal-block mfp-hide ">
    <div   data-to="pujarLoteFicha" class="modal-sub-w">
            <section class="panel">
                    <div class="panel-body">
                            <div class="modal-wrapper">
                                    <div class=" text-center single_item_content_">
                                        <span class="class_h1"><p id="insert_msg_title"></p></span><br/>

                                        <!-- METODO NUEVO -->
                                        <p><span id="insert_msg_login_required"></span><a class="btn_login" href="#" onclick="clickLogin();"><span id="insert_msg_log_in"></span></a><span id="insert_msg"></span></p><br/>

                                        <!-- METODO ORIGINAL EN EL QUE INICIAR SESION NO ES UN LINK -->
                                        <!-- <p id="insert_msg"></p><br/> -->

                                            <button  class=" btn button_modal_confirm modal-dismiss btn-custom">{{ trans(\Config::get('app.theme').'-app.lot.accept') }}</button>

                                    </div>
                            </div>
                    </div>
            </section>
    </div>
</div>
