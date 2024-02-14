<div class="own_box" data-rel="login">
    <div rel="content">
        <div id="login_box">
            <form id="accerder-user-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group has-feedback">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                        <input data-error="{{ trans($theme.'-app.login_register.write_valid_email') }}" type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    <div class="help-block with-errors"></div>
                </div>

                <div class="form-group has-feedback">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input maxlength="20" type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <span class="message-error-log"></span>
                <input onclick="javascript:login_web()" class="btn btn-primary btn-block btnLogin " type="button" value="{{ trans($theme.'-app.login_register.sign_in') }}">
            </form>
        </div>
    </div>
</div>

<div id="modalComprar" class="modal-block mfp-hide" data-to="comprarLote">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg">{{ trans($theme.'-app.sheet_tr.buy_lot_question') }}</p>
                    <button class="btn btn-primary modal-confirm">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
                    <button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
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
                    <p class="insert_msg"> {{ trans($theme.'-app.sheet_tr.auction_stopped') }} <br /></p>
                    <button class="btn btn-primary modal-dismiss">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
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
                    <p class="insert_msg"> {{ trans($theme.'-app.sheet_tr.auction_pendiente') }}</p>
                    <button class="btn btn-primary modal-dismiss">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
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
                    <p class="txt_loading"> {{ trans($theme.'-app.sheet_tr.auction_disconnected') }}</p>
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
                    <p class="insert_msg">{{ trans($theme.'-app.sheet_tr.cancel_bid_question') }}</p>

                    <button class="btn btn-primary modal-confirm">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
                    <button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
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
                    <p class="txt_loading"> {{ trans($theme.'-app.sheet_tr.loading') }} </p>
                    <p class="txt_esperando_sala hidden"> {{ trans($theme.'-app.sheet_tr.esperando_sala') }} </p> 
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
                    <p class="insert_msg">{{ trans($theme.'-app.sheet_tr.order_amount') }}</p>
                    <input id="order_amount" autocomplete="off" class="form-control bid_amount_gestor" type="text">
                    <br />
                    <button class="btn btn-primary modal-confirm add_order_amount" data-boton="1">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
                    <button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>


<?php /*
<div id="infoLot" class="modal-block modal-block-lg mfp-hide">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text">
                    <p class="insert_msg">{{ trans($theme.'-app.sheet_tr.lot') }} <span class="i_lot"></span></p>
                    <div class="row">
                        <div class="col-lg-4 img">
                            <img class="img-responsive" src="">
                        </div>
                        <div class="col-lg-8 txt">
                            <p class="i_title"></p>
                            <p class="i_desc"></p>
                            <p>
                                <span>{{ trans($theme.'-app.sheet_tr.start_price') }}</span>
                                <span class="i_imp"></span>
                                <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
                            </p>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="add_to_fav" data-from="modal"> {{ trans($theme.'-app.sheet_tr.add_to_fav') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <span class="lot-msg_adjudicado hidden"><b><i class="fa fa-exclamation" aria-hidden="true"></i>  {{ trans($theme.'-app.sheet_tr.awarded') }}</b> <span class="imp_adj"></span></span>
                        <span class="lot-msg_ensubasta hidden"><b><i class="fa fa-exclamation" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.in_auction') }}</b></span>

                        <button data-from="modal" class="lot-action_comprar btn btn-primary pull-left hidden" type="button" ref="" codsub="{{ $data['subasta_info']->lote_siguiente->cod_sub }}">{{ trans($theme.'-app.sheet_tr.buy') }}</button>

                        <button data-from="modal" class="lot-order_importe btn btn-primary  hidden" type="button">{{ trans($theme.'-app.sheet_tr.import_order') }}</button>  
                    </div>
                    <div >
                        <button class="btn btn-primary modal-dismiss pull-right">{{ trans($theme.'-app.sheet_tr.close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
 * 
 */?>

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

                                            <button  class=" btn button_modal_confirm modal-dismiss btn-custom">{{ trans($theme.'-app.lot.accept') }}</button>

                                    </div>
                            </div>
                    </div>
            </section>
    </div>
</div>