<div id="ordenFicha" class="container order-modal modal-block mfp-hide">
    <div data-to="pujarLoteFicha" class="modal-sub-w">
        <section class="panel">
			<div class="modal-wrapper">
				<div class="modal-body d-flex flex-column gap-4">
					<p class="h2">
						{{ trans("$theme-app.lot.confirm") }}
					</p>
					<p>
						<span for="bid" class='desc_auc'>{{ trans("$theme-app.lot.bidding_for") }} </span>
						<strong>
							<span class="precio_orden"></span> {{ trans("$theme-app.subastas.euros") }}
						</strong>
					</p>
					<p class="phonebid_js">{{ trans("$theme-app.lot.phonebid_text") }}</p>

					<div class="form-floating phonebid_js">
						<input id="precio_orden_modal" type="text" class="form-control control-number" placeholder="0000" value="" autocomplete="off">
						<label for="floatingInput">{{ trans("$theme-app.lot.bid_amount") }}</label>
					</div>

					<div class="form-floating phonebid_js">
						<input type="text" class="form-control" value="" name="phone1" id="phone1Bid_JS" placeholder="{{ trans("$theme-app.login_register.phone") }} 1">
						<label for="floatingInput">{{ trans("$theme-app.login_register.phone") }} 1</label>
					</div>

					<div class="form-floating phonebid_js">
						<input type="text" class="form-control" value="" name="phone2" id="phone2Bid_JS" placeholder="{{ trans("$theme-app.login_register.phone") }} 2">
						<label for="floatingInput">{{ trans("$theme-app.login_register.phone") }} 2</label>
					</div>
					<p id="errorOrdenFicha" style="color:red"><p>

					<button id="confirm_orden" class="btn btn-medium button_modal_confirm btn-lb-primary my-2">
						{{ trans("$theme-app.lot.confirm") }}
					</button>

					<p>
						{!! trans("$theme-app.lot.text_condition_confirm_bid") !!}
					</p>

				</div>
				<div class="modal-footer">
					<button class="btn modal-dismiss btn-lb-primary">{{ trans("$theme-app.global.cancel") }}</button>
				</div>
			</div>
        </section>
    </div>
</div>


<div id="modalPujarFicha" class="container modal-block mfp-hide ">
	<div data-to="pujarLoteFicha" class="modal-sub-w">
    	<section class="panel">
			<div class="modal-wrapper">
				<div class="modal-body d-flex flex-column gap-4">
					<p class="h2">
						{{ trans("$theme-app.lot.confirm") }}
					</p>

					<p>
						<span for="bid" class='desc_auc'>{{ trans("$theme-app.lot.you_are_bidding") }} </span>
						<strong>
							<span class="precio_orden"></span> {{ trans("$theme-app.subastas.euros") }}
						</strong>
					</p>

					<p>
						{!! trans("$theme-app.lot.text_condition_confirm_bid") !!}
					</p>
				</div>
				<div class="modal-footer">
					<button class="btn btn-lb-primary confirm_puja confirm_puja_Ansorena button_modal_confirm">
						{{ trans("$theme-app.lot.confirm") }}
					</button>
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
		<div class="modal-wrapper">
			<div class="modal-body">
				<div class="modal-text text-center">
					<p class="insert_msg h4">
						{{ trans($theme.'-app.sheet_tr.buy_lot_question') }}
					</p>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-lb-primary modal-confirm btn-custom">
					{{ trans($theme.'-app.sheet_tr.confirm') }}
				</button>
				<button class="btn btn-lb-primary modal-dismiss">
					{{ trans($theme.'-app.sheet_tr.cancel') }}
				</button>
			</div>
		</div>
	</section>
</div>


<?php  //estructura necesaria para crear lso elementos del listado de pujas  ?>
    <div id="duplicalte_list_pujas" class="hist_item hidden">
        <span class="col-xs-8">
            <span>{{ trans($theme.'-app.lot.bidder') }}</span> (
            <span class="yo">{{ trans($theme.'-app.lot.I') }}</span>
            <span class="uno hint--bottom-right hint--medium" data-hint=""></span>
            <span class="dos hint--bottom-right hint--medium" data-hint="<?= nl2br(trans($theme.'-app.lot.puja_automatica')) ?>">A</span>)
            <span class="date"></span>
        </span>
        <span class="col-xs-4">
            <span class="tres_item"><span class="price "> {{ trans($theme.'-app.lot.eur') }}</span></span>
        </span>
   </div>

<!--<div id="price_min_surpass" class="info hidden"><div class="col-xs-12 ">{{ trans($theme.'-app.lot.minimal-price') }} <strong> {{$lote_actual->impres_asigl0}} {{ trans($theme.'-app.lot.eur') }}</strong></div></div>-->
<?php  //view more debe llevar el hidden para que funcione correctamente, el customized_tr_main ya se lo quitara si es necesario ?>
<div id="view_more" class="more more-historic-bids hidden col-xs-12 text-right hidden">
    <a title="ver todas"  data-toggle="collapse" data-target="#pujas-collapse" href="javascript:view_all_bids();">
     <span id="view_more_text">{{ trans($theme.'-app.lot.see-all') }} </span>
    <span id="hide_bids_text" class="hidden">{{ trans($theme.'-app.lot.hidden') }} </span> <i class="fa fa-angle-down"></i></a>

</div>
