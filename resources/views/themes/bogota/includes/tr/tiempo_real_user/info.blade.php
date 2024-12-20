<div class="tr_user_info">
    <div class="user_info_items">

		{{-- divisa --}}
		@if(\Config::get("app.exchange"))
			<div class="col-xs-12 text-right info-currency">
				{{ trans(\Config::get('app.theme').'-app.lot.foreignCurrencies') }}
					<select id="currencyExchange">
						@foreach($divisas as $divisa)

								<?php //quieren que salgan los dolares por defecto (sin no hay nada o hay euros  ?>
								<option value='{{ $divisa->cod_div }}' <?= ($data['js_item']['subasta']['cod_div_cli'] == $divisa->cod_div || ($divisa->cod_div == 'USD' &&  ($data['js_item']['subasta']['cod_div_cli'] == 'EUR'  || $data['js_item']['subasta']['cod_div_cli'] == '' )))? 'selected="selected"' : '' ?>>
									{{ $divisa->cod_div }}
								</option>

						@endforeach
					</select>

			</div>
		@else
		<div></div>
		@endif

        <!-- precio de estimado -->
        @if(!empty(\Config::get('app.tr_show_estimate_price')))
        <div id="precioestimado" class="precio_estimado">

            <p>{{ trans(\Config::get('app.theme').'-app.sheet_tr.estimate_price') }}: </p>
            <span id="imptas" >{{ $data['subasta_info']->lote_actual->formatted_imptas_asigl0}} </span>
            -
			<span id="imptash" >  {{ $data['subasta_info']->lote_actual->formatted_imptash_asigl0}}</span>
			<p>{{ $data['js_item']['subasta']['currency']->symbol }}</p>

        </div>
        @else
        <div></div>
        @endif


        <!-- precio de salida -->
        <div id="precioSalida" class="precioSalida salida">
            <p>
                {{ trans(\Config::get('app.theme').'-app.sheet_tr.start_price') }}:
            </p>

			<span>{{ $data['js_item']['subasta']['currency']->symbol }}{{ $data['subasta_info']->lote_actual->formatted_impsalhces_asigl0 }}</span>
			@if(\Config::get("app.exchange"))
			| <span id="startPriceExchange_JS" class="exchange"> </span>
			@endif

        </div>

        <!-- puja actual -->
        <div class="pactual salida">
            <p>
                <span id="text_actual_max_bid" class="<?= count($data['subasta_info']->lote_actual->pujas) > 0 ? '' : 'hidden' ?> ">
                    {{ trans(\Config::get('app.theme').'-app.sheet_tr.max_actual_bid') }}
                </span>
                <span id="text_actual_no_bid" class="<?= count($data['subasta_info']->lote_actual->pujas) > 0 ? 'hidden' : '' ?> ">
                    {{ trans(\Config::get('app.theme').'-app.sheet_tr.pending_bid') }}
                </span>
            </p>
            @if(!Session::has('user'))
            <span id="actual_max_bid" class="black">
                @if( count($data['subasta_info']->lote_actual->pujas) >0 )
                {{ $data['js_item']['subasta']['currency']->symbol }}{{ \Tools::moneyFormat($data['subasta_info']->lote_actual->actual_bid) }}
                @endif
            </span>
            @else
            <span id="actual_max_bid" class="@if (!empty($data['js_item']['user']) && !empty($data['subasta_info']->lote_actual->max_puja) &&  $data['subasta_info']->lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit']) mine @else other @endif">
                @if( count($data['subasta_info']->lote_actual->pujas) >0 )
				{{ $data['js_item']['subasta']['currency']->symbol }}{{ \Tools::moneyFormat($data['subasta_info']->lote_actual->actual_bid) }}
                @endif
            </span>
            @endif
			@if(\Config::get("app.exchange"))
			| <span id="actualBidExchange_JS" class="exchange"> </span>
			@endif

        </div>
		<div   class="siguientePujaClass ">
            <p>{{ trans(\Config::get('app.theme').'-app.lot.next_min_bid') }}</p>
			<span> {{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </span> &nbsp; <span class="siguiente_puja"> {{ \Tools::moneyFormat($data['subasta_info']->lote_actual->importe_escalado_siguiente) }}</span>
			@if(\Config::get("app.exchange"))
				| <span id="nextBidExchange_JS" class="exchange"> </span>
			@endif
        </div>
		<div></div>

        <!-- panel pujar -->
        <div class="pujar">
            <div class="tuorden">
                {{ trans(\Config::get('app.theme').'-app.sheet_tr.your_actual_order') }}:
                {{ $data['js_item']['subasta']['currency']->symbol }}<span id="tuorden">
                    <?php
                    if (!empty($data['js_item']['user']['maxOrden'])) {
                        echo $data['js_item']['user']['maxOrden']->himp_orlic;
                    }elseif(!empty($data['js_item']['user']['maxPuja'])) {
						echo $data['js_item']['user']['maxPuja']->imp_asigl1;
					}else{
						echo "0";
					}
                    ?>
				</span>
				@if(\Config::get("app.exchange"))
				|	<span  id="yourOrderExchange_JS" class="exchange"> </span>
			    @endif
				@if(Session::has('user') && !empty(\Config::get("app.DeleteOrdersAnyTime")))
					<input id="cancelarOrdenUser"  class="btn  @if(empty($data['js_item']['user']['maxOrden']))  hidden @endif" type="button" ref="{{$data['subasta_info']->lote_actual->ref_asigl0}}" sub="{{$data['subasta_info']->lote_actual->cod_sub}}" value="{{ trans(\Config::get('app.theme').'-app.user_panel.delete_orden') }}">
				@endif
			</div>



            <?php //deshabilitamos el input para que el usuario no pueda cambiar de importe  ?>
            <div class="input_puja">
                <input id="bid_amount"  autocomplete="off" type="text" class="form-control bid_amount_gestor" value="{{ $data['subasta_info']->lote_actual->importe_escalado_siguiente }}">
                <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
            </div>

            @if(Session::has('user'))
				@if(in_array(20,  (new App\Models\Newsletter())->getIdSuscriptions(session('user.usrw', ''))))
					<a class="add_bid btn button btn-custom-save"><i class="fa fa-gavel"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.place_bid') }}</a>
					<input type="hidden" id="tiempo_real" value="1" readonly>
				@else
					<a class="btn button btn-custom-save add_bid_nologin" data-toggle="modal" data-target="#acceptedTermsModal">
						<i class="fa fa-gavel"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.place_bid') }}
					</a>
				@endif

            @else
            <a class="btn button btn-custom-save add_bid_nologin" onclick="initSesion();"><i class="fa fa-gavel"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.place_bid') }}</a>
            @endif

        </div>
    </div>
</div>

@include('front::includes.modal_accept_newconditions')
