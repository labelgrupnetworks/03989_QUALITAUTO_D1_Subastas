<div class="tr_user_info">
    <div class="user_info_items">

        <!-- precio de estimado -->

        @if(!empty(\Config::get('app.tr_show_estimate_price')))
        <div id="precioestimado" class="precio_estimado">

            <p>{{ trans($theme.'-app.sheet_tr.estimate_price') }}: </p>
            <span id="imptas" >{{ $data['subasta_info']->lote_actual->formatted_imptas_asigl0}} </span>
            -
            <span id="imptash" >  {{ $data['subasta_info']->lote_actual->formatted_imptash_asigl0}} {{ $data['js_item']['subasta']['currency']->symbol }}</span>

        </div>
        @else
        <div></div>
        @endif



        <!-- precio de salida -->
        <div id="precioSalida" class="precioSalida salida">
            <p>
                {{ trans($theme.'-app.sheet_tr.start_price') }}:
            </p>
            <span>{{ $data['subasta_info']->lote_actual->formatted_impsalhces_asigl0 }} {{ $data['js_item']['subasta']['currency']->symbol }}</span> 

        </div>

        <!-- puja actual -->
        <div class="pactual salida">
            <p>
                <span id="text_actual_max_bid" class="<?= count($data['subasta_info']->lote_actual->pujas) > 0 ? '' : 'hidden' ?> ">  
                    {{ trans($theme.'-app.sheet_tr.max_actual_bid') }}
                </span>
                <span id="text_actual_no_bid" class="<?= count($data['subasta_info']->lote_actual->pujas) > 0 ? 'hidden' : '' ?> ">
                    {{ trans($theme.'-app.sheet_tr.pending_bid') }}
                </span>
            </p>
            @if(!Session::has('user'))
            <span id="actual_max_bid" class="black">
                @if( count($data['subasta_info']->lote_actual->pujas) >0 )
                {{ \Tools::moneyFormat($data['subasta_info']->lote_actual->actual_bid) }} {{ $data['js_item']['subasta']['currency']->symbol }}
                @endif
            </span>
            @else
            <span id="actual_max_bid" class="@if (!empty($data['js_item']['user']) && !empty($data['subasta_info']->lote_actual->max_puja) &&  $data['subasta_info']->lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit']) mine @else other @endif">
                @if( count($data['subasta_info']->lote_actual->pujas) >0 )
                {{ \Tools::moneyFormat($data['subasta_info']->lote_actual->actual_bid) }} {{ $data['js_item']['subasta']['currency']->symbol }}
                @endif
            </span>
            @endif


        </div>

        <!-- panel pujar -->
        <div class="pujar">
            <div class="tuorden">
                {{ trans($theme.'-app.sheet_tr.your_actual_order') }}: 
                <span id="tuorden">
                    <?php
                    if (!empty($data['js_item']['user']['maxOrden'])) {
                        echo $data['js_item']['user']['maxOrden']->himp_orlic;
                    }
                    ?>
                </span>
            </div>
            <div></div>

            <?php //deshabilitamos el input para que el usuario no pueda cambiar de importe  ?>
            <div class="input_puja">
                <input id="bid_amount"  autocomplete="off" type="text" class="form-control bid_amount_gestor" value="{{ $data['subasta_info']->lote_actual->importe_escalado_siguiente }}">
                <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
            </div>

            @if(Session::has('user'))
            <a class="add_bid btn button btn-custom-save"><i class="fa fa-gavel"></i> {{ trans($theme.'-app.sheet_tr.place_bid') }}</a>
            <input type="hidden" id="tiempo_real" value="1" readonly>
            @else
            <a class="btn button btn-custom-save add_bid_nologin" onclick="initSesion();"><i class="fa fa-gavel"></i> {{ trans($theme.'-app.sheet_tr.place_bid') }}</a>
            @endif

        </div>
    </div>
</div>