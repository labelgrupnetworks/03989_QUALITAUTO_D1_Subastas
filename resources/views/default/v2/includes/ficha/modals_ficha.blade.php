<div class="container modal-block mfp-hide " id="ordenFicha">
    <section class="panel">
        <div class="modal-wrapper">
            <div class="modal-header">
                <h5 class="class_h1">{{ trans("web.lot.confirm_bid") }}</h5>
            </div>
            <div class="modal-body">
                <p>
                    <span class='desc_auc' for="bid">{{ trans("web.lot.bidding_for") }}</span>
                    <span class="fw-bold precio_orden"></span>
                    <span class="fw-bold">{{ trans("web.subastas.euros") }}</span>
                    @if (config('app.exchange'))
                        | <span class="exchange" id="newOrderExchange_JS"></span>
                    @endif
                </p>
                <div class="phonebid_js">
                    <p class="mb-2">{{ trans("web.lot.phonebid_text") }}</p>
                    <div class="row flex-column flex-sm-row flex-wrap text-start mb-2">
                        <label class="col-sm-3 col-form-label" for="phone1Bid_JS">
                            {{ trans("web.login_register.phone") }} 1:
                        </label>
                        <div class="col">
                            <input class="form-control" id="phone1Bid_JS" name="phone1" type="tel" value="">
                        </div>
                    </div>
                    <div class="row flex-column flex-sm-row flex-wrap text-start">
                        <label class="col-sm-3 col-form-label" for="phone2Bid_JS">
                            {{ trans("web.login_register.phone") }} 2:
                        </label>
                        <div class="col">
                            <input class="form-control" id="phone2Bid_JS" name="phone2" type="tel" value="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-lb-primary" id="confirm_orden">
                    {{ trans("web.lot.confirm") }}
                </button>
            </div>
        </div>
    </section>
</div>

<div class="container modal-block mfp-hide" id="modalPujarFicha">
    <section class="panel">
        <div class="modal-wrapper">
            <div class="modal-header">
                <h5 class="class_h1">{{ trans("web.lot.confirm_bid") }}</h5>
            </div>
            <div class="modal-body">
                <p class='desc_auc' for="bid">
                    {{ trans("web.lot.you_are_bidding") }} </p>
                <p><strong><span class="precio_orden"></span>
                        {{ trans("web.subastas.euros") }}</strong></p>
            </div>
            <div class="modal-footer">
                <button class="confirm_puja btn button_modal_confirm">
                    {{ trans("web.lot.confirm") }}
                </button>
            </div>
        </div>
    </section>
</div>

<div class="container modal-block mfp-hide" id="modalCloseBids">
    <section class="panel">
        <div class="modal-wrapper py-4">
            <div class="modal-body">
                <p>{{ trans("web.lot.loading") }}</p>
            </div>
        </div>
    </section>
</div>

<div class="container modal-block mfp-hide" id="modalDisconnected">
    <section class="panel">
        <div class="modal-wrapper py-4">
            <div class="modal-body">
                <p>{{ trans("web.lot.connect_to_serv") }}</p>
            </div>
        </div>
    </section>
</div>

<div class="container modal-block mfp-hide" id="modalComprarFicha" data-to="comprarLoteFicha">
    <section class="panel">
        <div class="modal-wrapper">
            <div class="modal-body">
                <p>{{ trans("web.sheet_tr.buy_lot_question") }}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-lb-primary modal-confirm">
                    {{ trans("web.sheet_tr.confirm") }}
                </button>
                <button class="btn btn-default modal-dismiss">{{ trans("web.sheet_tr.cancel") }}</button>
            </div>
        </div>
    </section>
</div>


<div class="container modal-block mfp-hide" id="modalMakeOffer" data-to="makeOfferFicha">
    <section class="panel">
        <div class="modal-wrapper">
			<div class="modal-header">
                <h5 class="class_h1">{{ trans("web.lot.confirm_bid") }}</h5>
            </div>
            <div class="modal-body">
                <p class='desc_auc' for="bid">{{ trans("web.lot.you_are_bidding") }}</p>
				<p class="fw-bold"><span class="imp_make_offer"></span> {{ trans("web.subastas.euros") }}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-lb-primary modal-confirm">
                    {{ trans("web.sheet_tr.confirm") }}
                </button>
                <button class="btn btn-lb-secondary modal-dismiss">{{ trans("web.sheet_tr.cancel") }}</button>
            </div>
        </div>
    </section>
</div>

<div class="modal-block mfp-hide" id="modalFormularioFicha">
    <section class="panel">
		<div class="panel-body">
			<div class="modal-wrapper">
				<div class="modal-header">
					<h5 class="class_h1 mb2">{{ trans("web.lot.confirm_bid") }}</h5>
				</div>
				<div class="modal-body">
					<p id="insert_msg"></p>
				</div>
				<div class="modal-footer">
					<button id="send_form_ficha" class="btn btn-lb-primary modal-confirm btn-custom">{{ trans('web.sheet_tr.confirm') }}</button>
					<button class="btn btn-lb-secondary modal-dismiss">{{ trans('web.sheet_tr.cancel') }}</button>
				</div>
			</div>
		</div>
    </section>
</div>
