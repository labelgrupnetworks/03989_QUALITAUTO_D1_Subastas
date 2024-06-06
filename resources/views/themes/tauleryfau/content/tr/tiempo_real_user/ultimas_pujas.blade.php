<div class="aside pujas h-100">

    {{-- <h2>{{ trans($theme.'-app.sheet_tr.last_bids') }}</h2> --}}
    <div id="pujas_list">

        <?php
	$ultima_orden =false;
foreach ($data['subasta_info']->lote_actual->pujas as $puja) : ?>

        <?php
        $lat_order = false;
        foreach ($data['subasta_info']->lote_actual->ordenes as $ordenes) {
            if (!$ultima_orden && $puja->cod_licit == $ordenes->cod_licit && $puja->formatted_imp_asigl1 == $ordenes->himp_orlic_formatted && $puja->type_asigl1 == 'A') {
                $lat_order = true;
            }
            $ultima_orden = true;
        }

        ?>
        <div class="pujas_model no_padding no-padding col-xs-12">
            <div class="col-xs-4 no-padding tipoPuja">
                <p data-type="I" @if ($puja->pujrep_asigl1 != 'I') class="hidden" @endif><i class="fa fa-wikipedia-w"
                        aria-hidden="true"></i>
                    {{ trans($theme . '-app.sheet_tr.bid-web') }}
                </p>
                <p data-type="S" @if ($puja->pujrep_asigl1 != 'S') class="hidden" @endif><i class="fa fa-hand-paper-o"
                        aria-hidden="true"></i>
                    {{ trans($theme . '-app.sheet_tr.bid-sala') }}</p>
                <p data-type="T" @if ($puja->pujrep_asigl1 != 'T' && $puja->pujrep_asigl1 != 'B') class="hidden" @endif><i class="fa fa-phone"
                        aria-hidden="true"></i>
                    {{ trans($theme . '-app.sheet_tr.bid-telf') }}</p>
                <p data-type="E" @if ($puja->pujrep_asigl1 != 'E' && $puja->pujrep_asigl1 != 'P') class="hidden" @endif><i class="fa fa-desktop"
                        aria-hidden="true"></i>
                    {{ trans($theme . '-app.sheet_tr.books_bid') }}</p>
                <p data-type="W" @if ($puja->pujrep_asigl1 != 'W') class="hidden" @endif><i class="fa fa-wikipedia-w"
                        aria-hidden="true"></i>
                    {{ trans($theme . '-app.sheet_tr.bid-web') }}</p>
                <p data-type="O" @if ($puja->pujrep_asigl1 != 'O') class="hidden" @endif><i class="fa fa-desktop"
                        aria-hidden="true"></i>
                    {{ trans($theme . '-app.sheet_tr.books_bid') }}</p>
                <p data-type="U" @if ($puja->pujrep_asigl1 != 'U') class="hidden" @endif><i class="fa fa-wikipedia-w"
                        aria-hidden="true"></i>
                    Subalia</p>
            </div>
            <div class="col-xs-4 importePuja text-right">
                <p>
                    <span>{{ $puja->formatted_imp_asigl1 }}</span>
                    <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>

                </p>
            </div>

            <div class="col-xs-4 no-padding ordenes">
                @if ($lat_order)
                    <span>{{ trans($theme . '-app.sheet_tr.last_order') }}</span>
                @endif
            </div>

        </div>

        <?php endforeach;?>

        <div class="pujas_model no-padding hidden col-xs-12" id="type_bid_model">
            <div class="col-xs-4 no-padding tipoPuja">
                <p data-type="I"><i class="fa fa-wikipedia-w" aria-hidden="true"></i>
                    {{ trans($theme . '-app.sheet_tr.bid-web') }}
                </p>
                <p class="hidden" data-type="S"><i class="fa fa-hand-paper-o" aria-hidden="true"></i>
                    {{ trans($theme . '-app.sheet_tr.bid-sala') }}</p>
                <p class="hidden" data-type="T"><i class="fa fa-phone" aria-hidden="true"></i>
                    {{ trans($theme . '-app.sheet_tr.bid-telf') }}</p>
                <p class="hidden" data-type="B"><i class="fa fa-phone" aria-hidden="true"></i>
                    {{ trans($theme . '-app.sheet_tr.bid-telf') }}</p>
                <p class="hidden" data-type="E"><i class="fa fa-desktop" aria-hidden="true"></i>
                    {{ trans($theme . '-app.sheet_tr.books_bid') }}</p>
                <p class="hidden" data-type="P"><i class="fa fa-desktop" aria-hidden="true"></i>
                    {{ trans($theme . '-app.sheet_tr.books_bid') }}</p>
                <p class="hidden" data-type="W"><i class="fa fa-wikipedia-w" aria-hidden="true"></i>
                    {{ trans($theme . '-app.sheet_tr.bid-web') }}</p>
                <p class="hidden" data-type="O"><i class="fa fa-desktop" aria-hidden="true"></i>
                    {{ trans($theme . '-app.sheet_tr.books_bid') }}</p>

                <p class="hidden" data-type="U"><i class="fa fa-wikipedia-w" aria-hidden="true"></i>
                    Subalia</p>
            </div>
            <div class="col-xs-4 importePuja">
                <p>
                    <span class="puj_imp"></span>
                    <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>


                </p>
            </div>
            <div class="col-xs-4 no-padding ordenes">
                <span class="orden hidden">{{ trans($theme . '-app.sheet_tr.last_order') }}</span>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
