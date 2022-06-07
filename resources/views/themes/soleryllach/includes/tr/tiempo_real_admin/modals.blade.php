<div id="modalConfigPausada" class="modal-block mfp-hide" data-to="pausarSubastaMinutos">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg"> La subasta se reanudará en: <br /> </p>
                    <?php
                    $minutes_default = !empty(Config::get('app.default_minuts_pause')) ? Config::get('app.default_minuts_pause') : 0;
                    $days_default = floor($minutes_default / 1440);
                    $minutes_default = $minutes_default % 1440;

                    $hours_default = floor($minutes_default / 60);
                    $minutes_default = $minutes_default % 60;
                    ?>
                    <div class="input-append date " >
                        <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                            <div class="col-xs-4">
                                <div>{{ ucwords (trans(\Config::get('app.theme').'-app.msg_neutral.days')) }}</div>
                                <select class="form-control pause_auction" id="days_pause" style='padding: 0; padding-left: 3px;'  >
                                    @for($dp = 0;$dp < 50; $dp++)
                                    <option value="{{$dp}}"  <?= $dp == $days_default ? "selected='selected'" : "" ?>>{{ $dp }} </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <div>{{ ucwords (trans(\Config::get('app.theme').'-app.msg_neutral.hours')) }}</div>
                                <select class="form-control pause_auction" id="hours_pause" style='padding: 0; padding-left: 3px;'  >
                                    @for($hp = 0;$hp < 24; $hp++)
                                    <option value="{{$hp}}" <?= $hp == $hours_default ? "selected='selected'" : "" ?>>{{ $hp }} </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <div>{{ ucwords (trans(\Config::get('app.theme').'-app.msg_neutral.minutes')) }}</div>
                                <select class="form-control pause_auction" id="minutes_pause" style='padding: 0; padding-left: 3px;'  >
                                    @for($mp = 0;$mp < 60; $mp++)
                                    <option value="{{$mp}}"  <?= $mp == $minutes_default ? "selected='selected'" : "" ?>>{{ $mp }} </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div style="margin-top: 10px;" class="col-xs-12">
                            {{ trans(\Config::get('app.theme').'-app.sheet_tr.activate_auctions') }} <br>
                            <span id="restart_auc_date" style="font-size: 16px;"> </span>
                            <input id="total_minutes_pause" type="hidden" value="<?= !empty(Config::get('app.default_minuts_pause')) ? Config::get('app.default_minuts_pause') : 0 ?>" >
						</div>
						<div style="margin-top: 10px;" class="col-xs-12">
							<input id="new_status_auction" type="checkbox" value="reload" > Recargar Página usuarios
						</div>
                    </div>
                    <div class="col-xs-12" style='margin-top: 20px'>
                        <button class="btn btn-primary modal-confirm">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
                        <button class="btn btn-primary modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="modalPausarTime" class="modal-block mfp-hide" data-to="pausarSubasta">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.activate_auctions') }}   <br /> </p>
                    <div class="input-append date " >
                        <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                            <div class="col-xs-12">
                                {{ trans(\Config::get('app.theme').'-app.user_panel.date') }}
                                <input type="text" id="date_pause" value="<?= date('d m Y') ?>" data-inputmask="'mask': '99/99/2099'" class="inputmask" style="width:100px">
                            </div>


                            <div class="col-xs-12">
                                <br>
                                {{ trans(\Config::get('app.theme').'-app.msg_neutral.hour') }}
                                <input type="text" id="hour_pause" value="{{ Config::get('app.put_off_auction') }}" data-inputmask="'mask': '99:99:00'" class="inputmask" style="width:70px">
                            </div>
                        </div>

                    </div>
                    <div class="col-xs-12" style='margin-top: 20px'>
                        <button class="btn btn-primary modal-confirm">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
                        <button class="btn btn-primary modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php //si no se puede asignar licitador por que ha entrado una puja mientras asignaban ?>
<div id="modal_cancelasignlicit" class="modal-block mfp-hide" data-to="pausar_lote">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="txt_msg">Ha entrado una puja mientras asignabas el licitador</p>

                    <button class="btn btn-primary lotPause cancelasignlicit" data-status="P">Aceptar</button>

                </div>
            </div>
        </div>
    </section>
</div>
<div id="modalEndLot" class="modal-block mfp-hide" data-to="asign_licit">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <div class="winner_undefined hidden">
						<div class="with_winner">
							<p class="insert_msg msg_winner_undefined">{{ trans(\Config::get('app.theme').'-app.sheet_tr.winner_undefined') }}</p>
							<div class="col-sm-6 col-sm-offset-3">
								<input type="text" class="form-control" name="w_undefined" id="w_undefined">
							</div>
							<br>
							<br>
							<br>
						</div>
                    </div>

                    <p id="modalEndLot_msg_error" class="error_msg hidden" style="color:#d9534f;">{{ trans(\Config::get('app.theme').'-app.msg_error.no_licit') }}</p>
                    <p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.end_lot_question') }}</p>
                    <button class="btn btn-primary modal-confirm">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
                    <button class="btn btn-default modal-dismiss cancelasignlicit">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
					<button class="btn btn-default assignToMinistry">{{ trans("$theme-app.sheet_tr.assign_ministry") }}</button>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="modalStart" class="modal-block mfp-hide" data-to="iniciar_subasta">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.start_auction_question') }}</p>
                    <button class="btn btn-primary modal-confirm">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
                    <button class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="modalLotAbrir" class="modal-block mfp-hide" data-to="abrir_lote">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.open_lot') }}</p>
                    <p class="insert_msg <?= !empty(Config::get('app.deleteBids')) ? '' : 'hidden'; ?>"><input type="checkbox" class="deleteBids"> {{ trans(\Config::get('app.theme').'-app.sheet_tr.open_lot_delete') }}</p>
                    <button class="btn btn-primary modal-confirm lotPause" data-status="P">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
                    <button class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="modalLotPause" class="modal-block mfp-hide" data-to="pausar_lote">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.pause_lot') }}</p>

                    <button class="btn btn-primary modal-confirm lotPause" data-status="P">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
                    <button class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="modalLotPauseReanudar" class="modal-block mfp-hide" data-to="reanudar_lote">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.resume_lot') }}</p>

                    <input id="lotOrden" autocomplete="off" class="form-control" type="hidden">
                    <br />

                    <button class="btn btn-primary modal-confirm lotResume" data-status="N">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
                    <button class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="modalCancelarPuja" class="modal-block mfp-hide" data-to="cancelar_puja">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel_bid_question') }}</p>

                    <button class="btn btn-primary modal-confirm">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
                    <button class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="modalCancelarOrden" class="modal-block mfp-hide" data-to="cancelar_orden">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel_order_question') }}</p>

                    <button class="btn btn-primary modal-confirm">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
                    <button class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>



<div id="modalLotReanudarList" class="modal-block mfp-hide">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.stopped_lots') }}</p>
                    <br />
                    <div id="reanudarList">

                    </div>
                    <br />

                    <button class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- modelo -->
<div id="reanudarListModel" style="text-align:left; margin-bottom: 10px;" class="hidden">
    <div class="row">
        <div class="col-lg-10 titulo"></div>
        <div class="col-lg-2 boton"><button class="btn btn-primary reanudarLote" type="button">{{ trans(\Config::get('app.theme').'-app.sheet_tr.continue') }}</button></div>
    </div>
</div>

<div id="modalJumpLot" class="modal-block mfp-hide" data-to="jump_lot">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.jump_to_lots') }}</p>

                    <input id="jumpLot" autocomplete="off" class="form-control" type="text">
                    <br />

                    <button class="btn btn-primary modal-confirm">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
                    <button class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="BajaClient" class="modal-block modal-lg mfp-hide" data-to="baja_client">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <div class='col-md-12'>
                        <p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.resume_licit_baja') }}</p>

                        <input id="licit_baja" autocomplete="off" class="form-control" type="text">
                        <br />

                        <button class="btn btn-primary modal-confirm">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
                        <button class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
                    </div>
                    <div class='col-md-12'>
                        <hr>
                        <span class='text-center'><strong>Usuarios bloqueados</strong></span>
                        <table style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{ trans(\Config::get('app.theme').'-app.sheet_tr.cod_licit') }}</th>
                                    <th>{{ trans(\Config::get('app.theme').'-app.sheet_tr.name_licit') }}</th>
                                    <th>{{ trans(\Config::get('app.theme').'-app.sheet_tr.cod_cli_licit') }}</th>
                                    <th>{{ trans(\Config::get('app.theme').'-app.sheet_tr.reactivar') }}</th>
                                </tr>
                            </thead>
                            <tbody class='clientes_baja'></tbody>
                        </table>
                        <div class="loader search-loader" style="display:hide;width: 25px;height: 25px;margin-top:10px"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="AltaClient" class="modal-block mfp-hide" data-to="alta_client">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <p class="insert_msg">{{ trans(\Config::get('app.theme').'-app.sheet_tr.resume_alta_client') }}</p>

                    <button id="alta_client" class="btn btn-primary modal-confirm" cli_licit="">{{ trans(\Config::get('app.theme').'-app.sheet_tr.confirm') }}</button>
                    <button class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>


<div id="ClientsCredit" class="modal-block modal-lg mfp-hide" data-to="baja_client">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center">
                    <div class='col-md-12'>
						<p style="font-size: 15px;"><b>{{ trans(\Config::get('app.theme').'-app.sheet_tr.credit_licits') }}</b></p>
						<p class="insert_msg"></p>

                    </div>
                    <div class='col-md-12' style="max-height: 300px; overflow: auto; height: 300px">
                        <hr>
                        <table style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{ trans(\Config::get('app.theme').'-app.sheet_tr.cod_cli_licit') }}</th>
                                    <th>{{ trans(\Config::get('app.theme').'-app.sheet_tr.name_licit') }}</th>
                                    <th>{{ trans(\Config::get('app.theme').'-app.sheet_tr.riesini_cli') }}</th>
									<th>{{ trans(\Config::get('app.theme').'-app.sheet_tr.ries_cli') }}</th>
									<th>{{ trans(\Config::get('app.theme').'-app.sheet_tr.current_credit') }}</th>
									<th>{{ trans(\Config::get('app.theme').'-app.sheet_tr.moment') }}</th>
                                </tr>
                            </thead>
                            <tbody class='clientes_credito' style="text-align: left"></tbody>
                        </table>
                        <div class="loader search-loader" style="display:hide;width: 25px;height: 25px;margin-top:10px"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
