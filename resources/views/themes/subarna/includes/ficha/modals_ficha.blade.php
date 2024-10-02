<div class="container modal-block mfp-hide " id="ordenFicha">
    <div class="modal-sub-w" data-to="pujarLoteFicha">
        <section class="panel">
            <div class="panel-body">
                <div class="modal-wrapper">
                    <div class=" text-center single_item_content">
                        <p class="class_h1"><?= trans($theme . '-app.lot.confirm_bid') ?></p><br />
                        <span class='desc_auc' for="bid">{{ trans($theme . '-app.lot.bidding_for') }} </span>
                        <strong><span class="precio_orden"></span> €</strong><br />
                        </br>
                        <button class="btn button_modal_confirm btn-lb-outline btn-lb-gray"
                            id="confirm_orden"><?= trans($theme . '-app.lot.confirm') ?> </button>

                        <div class='mb-10'></div>
                        <ul class="items_list">
                            <li><?= trans($theme . '-app.lot.tax_not_included') ?> </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


<div class="container modal-block mfp-hide " id="modalPujarFicha">
    <div class="modal-sub-w" data-to="pujarLoteFicha">
        <section class="panel">
            <div class="panel-body">
                <div class="modal-wrapper">
                    <div class=" text-center single_item_content_">
                        <p class="class_h1">{{ trans($theme . '-app.lot.confirm_bid') }}</p><br />
                        <span class='desc_auc' for="bid">{{ trans($theme . '-app.lot.you_are_bidding') }} </span>
                        <strong><span class="precio_orden"></span> €</strong><br /><br />
                        <p class="recuerde_comision">{!! trans($theme . '-app.lot.recuerde_comision') !!} </p><br />
                        <button class="confirm_puja button_modal_confirm btn btn-lb-outline btn-lb-gray">
							{{ trans($theme . '-app.lot.confirm') }}
						</button>
                        <div class='mb-10'></div>
                        <div class='mb-10'></div>
                        <ul class="items_list">
                            <li><?= trans($theme . '-app.lot.tax_not_included') ?> </li>
                        </ul>
                    </div>
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
                    <p class="txt_loading"> {{ trans($theme . '-app.lot.loading') }}</p>
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
                    <p class="txt_loading"> {{ trans($theme . '-app.lot.connect_to_serv') }}</p>
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
                    <p class="insert_msg mb-3">{{ trans($theme . '-app.sheet_tr.buy_lot_question') }}</p>
                    <button
                        class="btn btn-lb btn-lb-confirm modal-confirm">{{ trans($theme . '-app.sheet_tr.confirm') }}</button>
                    <button class="btn btn-lb-outline btn-lb-gray modal-dismiss">{{ trans($theme . '-app.sheet_tr.cancel') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>

<?php //estructura necesaria para crear lso elementos del listado de pujas
?>
<div class="hist_item hidden" id="duplicalte_list_pujas">
    <div class="col-xs-4">
        <em class="yo">{{ trans($theme . '-app.lot.I') }}</em>
        <em class="uno hint--bottom-right hint--medium" data-hint=""></em>
        <em class="dos hint--bottom-right hint--medium"
            data-hint="<?= nl2br(trans($theme . '-app.lot.puja_automatica')) ?>">A</em>
    </div>
    <div class="col-xs-4 text-center">
        <em class="date"></em>
    </div>
    <div class="col-xs-4 text-right">
        <span class="tres_item"><em class="price "> {{ trans($theme . '-app.lot.eur') }}</em></span>
    </div>
</div>

<!--<div class="info hidden" id="price_min_surpass"><div class="col-xs-12 ">{{ trans($theme . '-app.lot.minimal-price') }} <strong> {{ $lote_actual->impres_asigl0 }} {{ trans($theme . '-app.lot.eur') }}</strong></div></div>-->
<div class="more hidden col-xs-12" id="view_more">
    <a data-toggle="collapse" data-target="#pujas-collapse" href="javascript:view_all_bids();" title="ver todas">
        <span id="view_more_text">{{ trans($theme . '-app.lot.see-all') }} </span>
        <span class="hidden" id="hide_bids_text">{{ trans($theme . '-app.lot.hidden') }}</span></a>
    <i class="fa fa-angle-down"></i>
</div>


{{-- Modal de video en formato panoramico --}}
<div class="modal fade modal-video" id="modalVideo" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
    tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-body mb-0 p-0">
                <div class="embed-responsive embed-responsive-16by9 z-depth-1-half">
                    <div id="js-video"></div>
                </div>
            </div>

            <div class="modal-footer justify-content-center">
                <button class="btn btn-outline-primary btn-rounded btn-md ml-4" data-dismiss="modal"
                    type="button">{{ trans("$theme-app.head.close") }}</button>
            </div>

        </div>

    </div>
</div>
