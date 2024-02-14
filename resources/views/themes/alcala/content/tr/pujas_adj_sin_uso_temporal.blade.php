<!-- row 1 -->
<div class="row">
    @if (\Config::get('app.tr_show_pujas'))
    	<div class="col-lg-6">

    		<div class="aside pujas">

    			<h2>{{ trans($theme.'-app.sheet_tr.bids') }}</h2>

    			<?php foreach ($data['subasta_info']->lote_actual->pujas as $puja) : ?>
                    <div class="pujas_model">
    					<div class="col-lg-6 tipoPuja">
    						<p data-type="I" @if ($puja->pujrep_asigl1 != 'W')class="hidden" @endif ><i class="fa fa-globe" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-web') }}</p>
    						<p data-type="S" @if ($puja->pujrep_asigl1 != 'S')class="hidden" @endif><i class="fa fa-hand-paper-o" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-sala') }}</p>
    						<p data-type="T" @if ($puja->pujrep_asigl1 != 'T' && $puja->pujrep_asigl1 != 'B')class="hidden" @endif><i class="fa fa-phone" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-telf') }}</p>
                            <p data-type="E" @if ($puja->pujrep_asigl1 != 'E')class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-erp') }}</p>
                            <p data-type="W" @if ($puja->pujrep_asigl1 != 'E')class="hidden" @endif><i class="fa fa-wikipedia-w" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-erp') }}</p>
    					</div>
    					<div class="col-lg-6 importePuja">
    						<p><span>{{ $puja->formatted_imp_asigl1 }}</span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span></p>
    					</div>
                    </div>

    			<?php endforeach;?>
                <div class="pujas_model hidden" id="type_bid_model">
                    <div class="col-lg-6 tipoPuja">
                        <p data-type="I"><i class="fa fa-globe" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-web') }}</p>
                        <p data-type="S" class="hidden"><i class="fa fa-hand-paper-o" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-sala') }}</p>
                        <p data-type="T" class="hidden"><i class="fa fa-phone" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-telf') }}</p>
                        <p data-type="B" class="hidden"><i class="fa fa-phone" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-telf') }}</p>
                        <p data-type="E" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-erp') }}</p>
                        <p data-type="W" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.bid-erp') }}</p>
                    </div>
                    <div class="col-lg-6 importePuja">
                        <p>
                            <span class="puj_imp"></span>
                            <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
                        </p>
                    </div>
                </div>
    			<div class="clearfix"></div>
    		</div>

    	</div>
        @endif

    @if (\Config::get('app.tr_show_adjudicaciones') and Session::has('user'))
    	<div class="col-lg-6">

    		<div class="aside adjudicaciones">
    			<h2>Tus adjudicaciones</h2>
                <div>
                    @if (!empty($data['js_item']['user']) && !empty($data['js_item']['user']['adjudicaciones']))
                        <?php foreach ($data['js_item']['user']['adjudicaciones'] as $key => $val): ?>
                            <div class="adjudicaciones_model">
                                <div class="col-lg-6 adj_ref">
                                    <p>{{ trans($theme.'-app.sheet_tr.lot') }}</i> <span>{{ $val->ref_asigl1 }}</span></p>
                                </div>
                                <div class="col-lg-6">
                                    <p><span class="adj_imp">{{ $val->imp_asigl1 }}</span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span></p>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="adjudicaciones_model hidden" id="type_adj_model">
                            <div class="col-lg-6 adj_ref">
                                <p>{{ trans($theme.'-app.sheet_tr.lot') }}</i> <span></span></p>
                            </div>
                            <div class="col-lg-6">
                                <p><span class="adj_imp"></span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span></p>
                            </div>
                        </div>

                    @endif
                </div>
    		</div>

    	</div>
    @endif
</div>
