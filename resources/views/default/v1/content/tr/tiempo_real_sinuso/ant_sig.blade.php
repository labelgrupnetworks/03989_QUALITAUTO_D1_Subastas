<div class="row" id="ant_sig_boxes">
    @if (!empty($data['subasta_info']->lote_anterior))
        <div class="col-xs-6 col-lg-6 pull-left" id="lot_ant">
            <div class="aside">
                <h2>{{ trans(\Config::get('app.theme').'-app.sheet_tr.lote_anterior') }} (<span>{{ $data['subasta_info']->lote_anterior->ref_asigl0 }}</span>)</h2>
                <div class="row">
                    <div class="col-lg-6 img">
                        <img class="img-responsive" src="{{ $data['subasta_info']->lote_anterior->imagen }}">
                    </div>
                    <div class="col-lg-6 ant_title">
                        <p>{{ $data['subasta_info']->lote_anterior->titulo_hces1 }}</p>
                    </div>
                </div>
                <p class="ant_price">{{ trans(\Config::get('app.theme').'-app.sheet_tr.start_price') }}: <span>{{ $data['subasta_info']->lote_anterior->formatted_impsalhces_asigl0 }}</span>{{ $data['js_item']['subasta']['currency']->symbol }}</p>
            </div>
        </div>
    @endif

    @if (!empty($data['subasta_info']->lote_siguiente) && $data['subasta_info']->lote_siguiente != $data['subasta_info']->lote_actual)
        <div class="col-xs-6 col-lg-6 pull-left" id="lot_sig">
            <div class="aside">
                 <h2>{{ trans(\Config::get('app.theme').'-app.sheet_tr.lote_siguiente') }} (<span>{{ $data['subasta_info']->lote_siguiente->ref_asigl0 }}</span>)</h2>
                <div class="row">
                    <div class="col-lg-6">
                        <img class="img-responsive" src="{{ $data['subasta_info']->lote_siguiente->imagen }}">
                    </div>
                    <div class="col-lg-6 sig_title">
                         <p>{{ $data['subasta_info']->lote_siguiente->titulo_hces1 }}</p>
                    </div>
                </div>
                <p class="sig_price">{{ trans(\Config::get('app.theme').'-app.sheet_tr.start_price') }}: <span>{{ $data['subasta_info']->lote_siguiente->formatted_impsalhces_asigl0 }}</span>{{ $data['js_item']['subasta']['currency']->symbol }}</p>
            </div>
        </div>
    @endif

</div>
