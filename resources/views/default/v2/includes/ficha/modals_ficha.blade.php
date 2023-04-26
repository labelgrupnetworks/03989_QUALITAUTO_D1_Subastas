<div class="container modal-block mfp-hide " id="ordenFicha">
    <section class="panel">
        <div class="modal-wrapper">
            <div class="modal-header">
                <h5 class="class_h1">{{ trans("$theme-app.lot.confirm_bid") }}</h5>
            </div>
            <div class="modal-body">
                <p>
                    <span class='desc_auc' for="bid">{{ trans("$theme-app.lot.bidding_for") }}</span>
                    <span class="fw-bold precio_orden"></span>
                    <span class="fw-bold">{{ trans("$theme-app.subastas.euros") }}</span>
                    @if (config('app.exchange'))
                        | <span class="exchange" id="newOrderExchange_JS"></span>
                    @endif
                </p>
                <div class="phonebid_js">
                    <p class="mb-2">{{ trans("$theme-app.lot.phonebid_text") }}</p>
                    <div class="row flex-column flex-sm-row flex-wrap text-start mb-2">
                        <label class="col-sm-3 col-form-label" for="phone1Bid_JS">
                            {{ trans("$theme-app.login_register.phone") }} 1:
                        </label>
                        <div class="col">
                            <input class="form-control" id="phone1Bid_JS" name="phone1" type="tel" value="">
                        </div>
                    </div>
                    <div class="row flex-column flex-sm-row flex-wrap text-start">
                        <label class="col-sm-3 col-form-label" for="phone2Bid_JS">
                            {{ trans("$theme-app.login_register.phone") }} 2:
                        </label>
                        <div class="col">
                            <input class="form-control" id="phone2Bid_JS" name="phone2" type="tel" value="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-lb-primary" id="confirm_orden">
                    {{ trans("$theme-app.lot.confirm") }}
                </button>
            </div>
        </div>
    </section>
</div>

<div class="container modal-block mfp-hide" id="modalPujarFicha">
    <section class="panel">
        <div class="modal-wrapper">
            <div class="modal-header">
                <h5 class="class_h1">{{ trans("$theme-app.lot.confirm_bid") }}</h5>
            </div>
            <div class="modal-body">
                <p class='desc_auc' for="bid">
                    {{ trans("$theme-app.lot.you_are_bidding") }} </p>
                <p><strong><span class="precio_orden"></span>
                        {{ trans("$theme-app.subastas.euros") }}</strong></p>
            </div>
            <div class="modal-footer">
                <button class="confirm_puja btn button_modal_confirm">
                    {{ trans("$theme-app.lot.confirm") }}
                </button>
            </div>
        </div>
    </section>
</div>

<div class="container modal-block mfp-hide" id="modalCloseBids">
    <section class="panel">
        <div class="modal-wrapper py-4">
            <div class="modal-body">
                <p>{{ trans("$theme-app.lot.loading") }}</p>
            </div>
        </div>
    </section>
</div>

<div class="container modal-block mfp-hide" id="modalDisconnected">
    <section class="panel">
        <div class="modal-wrapper py-4">
            <div class="modal-body">
                <p>{{ trans("$theme-app.lot.connect_to_serv") }}</p>
            </div>
        </div>
    </section>
</div>

<div class="container modal-block mfp-hide" id="modalComprarFicha" data-to="comprarLoteFicha">
    <section class="panel">
        <div class="modal-wrapper">
            <div class="modal-body">
                <p>{{ trans("$theme-app.sheet_tr.buy_lot_question") }}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-lb-primary modal-confirm">
                    {{ trans("$theme-app.sheet_tr.confirm") }}
                </button>
                <button class="btn btn-default modal-dismiss">{{ trans("$theme-app.sheet_tr.cancel") }}</button>
            </div>
        </div>
    </section>
</div>


<div class="container modal-block mfp-hide" id="modalMakeOffer" data-to="makeOfferFicha">
    <section class="panel">
        <div class="modal-wrapper">
			<div class="modal-header">
                <h5 class="class_h1">{{ trans("$theme-app.lot.confirm_bid") }}</h5>
            </div>
            <div class="modal-body">
                <p class='desc_auc' for="bid">{{ trans("$theme-app.lot.you_are_bidding") }}</p>
				<p class="fw-bold"><span class="imp_make_offer"></span> {{ trans("$theme-app.subastas.euros") }}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-lb-primary modal-confirm">
                    {{ trans("$theme-app.sheet_tr.confirm") }}
                </button>
                <button class="btn btn-lb-secondary modal-dismiss">{{ trans("$theme-app.sheet_tr.cancel") }}</button>
            </div>
        </div>
    </section>
</div>
