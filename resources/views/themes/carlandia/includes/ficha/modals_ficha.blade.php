<div id="ordenFicha" class="container modal-block mfp-hide " >
            <div   data-to="pujarLoteFicha" class="modal-sub-w"  >
                    <section class="panel">
                            <div class="panel-body">
                                    <div class="modal-wrapper">
                                            <div class=" text-center single_item_content">
                                                <p class="class_h1"><?=trans(\Config::get('app.theme').'-app.lot.confirm_bid')?></p><br/>
                                                <span for="bid" class='desc_auc'>{{trans(\Config::get('app.theme').'-app.lot.bidding_for')}} </span>
												<strong><span class="precio_orden"></span> {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</strong>
													@if(\Config::get("app.exchange"))
													|	<span id="newOrderExchange_JS" class="exchange"></span>
												   @endif
												<br/>
                                                    </br>
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
                                                <span for="bid" class='desc_auc'>{{ trans(\Config::get('app.theme').'-app.lot.you_are_bidding') }} </span> <strong><span class="precio_orden"></span> {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</strong><br/>
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
<div id="modalComprarFicha" class="modal-block mfp-hide" data-to="comprarLoteFichaCarlandia">
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

<div id="modalContraofertarFicha" class="modal-block mfp-hide" data-to="contraofertarLoteFicha">
	<section class="panel">
		<div class="panel-body">
			<div class="modal-wrapper">
				<div class="modal-text text-center">
					<p class="insert_msg">{!! trans(\Config::get('app.theme').'-app.sheet_tr.counteroffer_lot_question') !!}</p>
					<button class="btn btn-primary modal-confirm btn-custom">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
					<button class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
				</div>
			</div>
		</div>
	</section>
</div>

<div id="modalEsperarRespuestaFicha" class="modal-block mfp-hide">
	<section class="panel">
		<div class="panel-body">
			<div class="modal-wrapper">
				<div class="modal-text text-center">
					<p>Te informaremos sobre la decisión del
						vendedor lo antes posible. Para evitar que se venda a otro
						comprador, asegura su compra al precio indicado o incrementa tu
						oferta, de nuevo con los botones del pop up anterior.
						</p>

					<p><button class="btn btn-default modal-dismiss">Cerrar</button></p>
				</div>
			</div>
		</div>
	</section>
</div>

<div id="modalContraofertarSinLicitador" class="modal-block custom-modal-block mfp-hide">
	<section class="panel">
		<div class="panel-body">
			<div class="modal-wrapper">
				<div class="well-buyer mb-2">
					<h4>
						<strong class="insert_msg"></strong>
					</h4>
				</div>
				<div class="modal-text text-center">

					<p class="mb-3"><a id="modal-contraofertarSinLicitador" class="button-principal" href="{{route('register', ['lang' => config('app.locale'), 'counteroffer' => true])}}">Facilitar datos</a></p>

					<p class="">Si ya tienes cuenta, accede</p>

					<p class="mb-3"><button class="button-principal custom_btn_login">Login</button></p>

					<p><button class="btn btn-default modal-dismiss">Cerrar</button></p>

				</div>
			</div>
		</div>
	</section>
</div>

<div id="modalCerrarContraofertar" class="modal-block custom-modal-block mfp-hide">
	<section class="panel">
		<div class="panel-body">
			<div class="modal-wrapper">

				<div class="modal-text text-center">
					<h4><strong><span class="insert_msg"></span></strong></h4>

					<p class="mt-2 mb-2">
						<a id="modalLinkRegister" class="button-principal" href="{{route('register', ['lang' => config('app.locale'), 'counteroffer' => true])}}">FACILITAR DATOS</a>
						<a id="modalLinkDeposit" class="button-principal clicDepositarSenal_JS" href="">DEPOSITAR SEÑAL</a>
					</p>

					<p class="mb-3"><strong>¿Necesitas ayuda?</strong></p>

					<div class="icons-modal mb-5">

						<a href="tel:{{config('app.phoneNumber')}}" class="phone-icon">
							<i class="fa fa-phone" aria-hidden="true"></i>
						</a>
						<a href="mailto:carlandia@calandia.es" class="mail-icon">
							<i class="fa fa-envelope-o" aria-hidden="true"></i>
						</a>
						<a href="https://api.whatsapp.com/send?phone=+34{{config('app.whatsAppNumer')}}&text=" target="_blank" class="whatsapp-icon hidden-sm hidden-md hidden-lg">
							<i class="fa fa-whatsapp" aria-hidden="true"></i>
						</a>
						<a href="https://api.whatsapp.com/send?phone=0034{{config('app.whatsAppNumer')}}&text=" target="_blank" class="whatsapp-icon hidden-xs">
							<i class="fa fa-whatsapp" aria-hidden="true"></i>
						</a>

					</div>

					<div class="similiar-lots mb-3">
						<p>Puedes ver otros <strong><u>Vehículos Similares</u></strong></p>
						<p><a class="button-principal" href="">Aquí</a></p>
					</div>

					<p><button class="btn btn-default modal-dismiss">Cerrar</button></p>

				</div>
			</div>
		</div>
	</section>
</div>

<div id="modalContraofertaRechazada" class="modal-block custom-modal-block mfp-hide">
	<section class="panel">
		<div class="panel-body">
			<div class="modal-wrapper">
				<div class="well-buyer mb-2">
					<h4><strong><span class="insert_msg"></span></strong></h4>
				</div>
				<div class="modal-text text-center">

					<div class="d-flex justify-content-space-between modal-buttons-list" style="margin-bottom: 10rem;">

						<button id="comprarYaModalEvent_JS" data-amount-over="false" class="button-principal lot-action_comprar_lot">Comprar por {{ \Tools::moneyFormat($lote_actual->impsalhces_asigl0, trans("$theme-app.subastas.euros")) }}</button>

						<button id="btn-focus-counteroffer" class="rechazoNuevaOfertaModalEvent_JS button-principal focus-counteroffer">HACER NUEVA OFERTA</button>

						<a id="btn-similares-modal" href="" class="button-principal clicRechazoVehículosSimilares_JS">VEHÍCULOS SIMILARES</a>
						<input id="amountOverModal" type="hidden">
					</div>

					<p><button class="btn btn-default modal-dismiss">Cerrar</button></p>

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
