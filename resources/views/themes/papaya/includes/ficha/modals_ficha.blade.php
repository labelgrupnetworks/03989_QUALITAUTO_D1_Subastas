<div id="ordenFicha" class="container modal-block mfp-hide " >
            <div   data-to="pujarLoteFicha" class="modal-sub-w"  >
                    <section class="panel">
                            <div class="panel-body">
                                    <div class="modal-wrapper">
                                            <div class=" text-center single_item_content">
                                                <p class="class_h1"><?=trans(\Config::get('app.theme').'-app.lot.confirm_bid')?></p><br/>
                                                <span for="bid" class='desc_auc'>{{trans(\Config::get('app.theme').'-app.lot.bidding_for')}} </span> <strong><span class="precio_orden"></span> €</strong><br/>
                                                    </br>
                                                    <button id="confirm_orden" class="btn button_modal_confirm btn-custom"><?=trans(\Config::get('app.theme').'-app.lot.confirm')?> </button>

                                                     <div class='mb-10'></div>

                                            </div>
                                    </div>
                            </div>
                    </section>
            </div>
</div>


<div id="modalPujarFicha" class="container modal-block mfp-hide">
	<div data-to="pujarLoteFicha" class="modal-sub-w">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class=" text-center single_item_content_">
						<p class="class_h1 mb-3">{{ trans(\Config::get('app.theme').'-app.lot.confirm_bid') }}</p>
						<p class="mb-3">
							<span for="bid" class='desc_auc'>{{ trans(\Config::get('app.theme').'-app.lot.you_are_bidding') }}</span> <strong><span class="precio_orden"></span> €</strong>
						</p>
						<button class="confirm_puja btn button_modal_confirm btn-custom">{{ trans(\Config::get('app.theme').'-app.lot.confirm') }}</button>

						@if(config('app.withMultipleBidders', false))
						<button id="multipleBiddersLink" class="btn btn-custom">{{ trans("$theme-app.lot.add_bidders") }}</button>
						@endif

					</div>
				</div>
			</div>
		</section>
	</div>
</div>
{{-- Cuando confirma se ejecuta confirm_puja() de tr_main --}}

@if(!empty($data['usuario']))
@php
	$usuario = $data['usuario'];
@endphp
<div id="multipleBidders" class="modal-lg modal-block mfp-hide">
	<div class="modal-sub-w">
		<section class="panel">

			<div class="panel-body">
				<h2 class="class_h1 mb-3">{{ trans("$theme-app.lot.bidders") }}</h2>

				<form id="biddersForm">
					<div class="d-flex align-items-center mb-2 flex-wrap bidder-wrap" id="bidder_0">
						<p class="m-0">1</p>
						<input type="text" class="form-control" name="name" placeholder="Nombre o Razón Social" value="{{$usuario->fisjur_cli === 'R' ? $usuario->rsoc_cli : $usuario->nom_cli}}">
						<input type="text" class="form-control" name="last-name" placeholder="Apellidos">

						<div class="input-group">
							<input type="number" max="100" class="form-control" name="ratio" value="100">
							<div class="input-group-addon">%</div>
						</div>
						<div class="input-group">
							<input type="number" step="0.01" class="form-control" name="import" readonly>
							<div class="input-group-addon">{{ trans("$theme-app.lot.eur") }}</div>
						</div>
					</div>
				</form>
				<div class="mt-2 mb-3 d-flex align-items-end gap-5">
					<button id="addBidder" class="btn btn-primary"><i class="fa fa-plus"></i></button>
					<p class="m-0 text-center has-error hidden" id="multipleBidderError" style="flex: 1">{{ trans("$theme-app.msg_error.bidders_ratio") }}</p>
				</div>

				<button class="btn btn-primary btn-custom confirm_puja_multiple">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
				<button class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
			</div>
		</section>
	</div>
</div>
@endif

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
<div id="modalPujarInfFicha" class="container modal-block mfp-hide ">
	<div   data-to="pujarInfLoteFicha" class="modal-sub-w">
			<section class="panel">
					<div class="panel-body">
							<div class="modal-wrapper">
									<div class=" text-center single_item_content_">
										<p class="class_h1">{!! trans(\Config::get('app.theme').'-app.lot.confirm_bid_inf') !!}</p><br/>
										<span for="bid" class='desc_auc'>{{ trans(\Config::get('app.theme').'-app.lot.you_are_bidding') }} </span> <strong><span class="precio_orden"></span> €</strong><br/>
											</br>
											<button  class="confirm_puja_inf btn button_modal_confirm btn-custom">{{ trans(\Config::get('app.theme').'-app.lot.confirm') }}</button>
											<div class='mb-10'></div>
											 <div class='mb-10'></div>

									</div>
							</div>
					</div>
			</section>
	</div>
</div>


<?php  //estructura necesaria para crear lso elementos del listado de pujas  ?>
    <div id="duplicalte_list_pujas" class="hist_item hidden">
        <span class="col-xs-8 col-md-offset-1 col-md-6">
            <span>{{ trans(\Config::get('app.theme').'-app.lot.bidder') }}</span> (
            <span class="yo">{{ trans(\Config::get('app.theme').'-app.lot.I') }}</span>
            <span class="uno hint--bottom-right hint--medium" data-hint=""></span>
            <span class="dos hint--bottom-right hint--medium" data-hint="<?= nl2br(trans(\Config::get('app.theme').'-app.lot.puja_automatica')) ?>">A</span>)
            <span class="date"></span>
        </span>
        <span class="col-xs-4 text-right">
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


<div id="modalFormularioFicha" class="modal-block mfp-hide">
	<section class="panel">
		<div class="panel-body">
			<div class="modal-wrapper">
				<div class="modal-text text-center">
					<p id="insert_msg"></p>
					<button id="send_form_ficha" class="btn btn-primary modal-confirm btn-custom">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
					<button class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
				</div>
			</div>
		</div>
	</section>
</div>
