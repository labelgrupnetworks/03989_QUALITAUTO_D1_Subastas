<div class="aside pujas">

    <div id="pujas_list">

        <?php
        $ultima_orden = false;
        foreach ($data['subasta_info']->lote_actual->pujas as $puja) :
            ?>

            <?php
            /* Nombre de los licitadores */
            $name_licit = '-';
            if (!empty($data['licitadores']) && !empty($data['js_item']['user']['is_gestor']) && $puja->cod_licit != Config::get('app.dummy_bidder')) {
                $name_licit = !empty($data['licitadores'][$puja->cod_licit]) ? $data['licitadores'][$puja->cod_licit] : "-";
            }
            /* Fin de nombre de los licitadores */
            ?>
            <div class="pujas_model col-xs-12">
                <div class="col-xs-7 tipoPuja">
                    <p data-type="I" @if ($puja->pujrep_asigl1 != 'I')class="hidden" @endif><i class="fa fa-globe" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-internacional') }}</p>
                    <p data-type="S" @if ($puja->pujrep_asigl1 != 'S')class="hidden" @endif><i class="fa fa-hand-paper-o" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-sala') }}</p>
                    <p data-type="T" @if ($puja->pujrep_asigl1 != 'T')class="hidden" @endif><i class="fa fa-phone" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-telf') }}</p>
                    <p data-type="E" @if ($puja->pujrep_asigl1 != 'E' && $puja->pujrep_asigl1 != 'P') class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i>  {{ trans(\Config::get('app.theme').'-app.sheet_tr.books_bid') }}</p>

                    <p data-type="W" @if ($puja->pujrep_asigl1 != 'W')class="hidden" @endif><i class="fa fa-wikipedia-w" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-web') }}</p>

                    <p data-type="U" @if ($puja->pujrep_asigl1 != 'U')class="hidden" @endif><i class="fab fa-stripe-s" aria-hidden="true"></i> Subalia</p>

                    <p data-type="O" @if ($puja->pujrep_asigl1 != 'O')class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.books_bid') }}</p>
                </div>
                <div class="col-xs-5 text-center importePuja">
                    <p>
                        <span>{{ $puja->formatted_imp_asigl1 }}</span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
                        @if(!empty($data['js_item']['user']['is_gestor']))
                        <span class="licitadorPuja">({{ $puja->cod_licit }})<span style="font-size: 12px;"> {{$name_licit}}</span></span>
                        @endif
                    </p>
                </div>

            </div>

        <?php endforeach; ?>

        <div class="pujas_model hidden col-xs-12" id="type_bid_model">
            <div class="col-xs-7 tipoPuja">
                <p data-type="I"><i class="fa fa-globe" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-internacional') }}</p>
                <p data-type="S" class="hidden"><i class="fa fa-hand-paper-o" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-sala') }}</p>
                <p data-type="T" class="hidden"><i class="fa fa-phone" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-telf') }}</p>
                <p data-type="E" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.books_bid') }}</p>
                <p data-type="P" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.books_bid') }}</p>
                <p data-type="W" class="hidden"><i class="fa fa-wikipedia-w" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-web') }}</p>
                <p data-type="O" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.books_bid') }}</p>
                <p data-type="U" class="hidden"><i class="fab fa-stripe-s" aria-hidden="true"></i> SUBALIA</p>
            </div>
            <div class="col-xs-5 text-center importePuja">
                <p>
                    <span class="puj_imp"></span>
                    <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
                </p>
            </div>

        </div>
        <div class="clearfix"></div>
    </div>
</div>