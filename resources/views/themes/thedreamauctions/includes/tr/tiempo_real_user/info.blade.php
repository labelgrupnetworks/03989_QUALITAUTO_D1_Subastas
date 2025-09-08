<div class="tr_user_info">
    <div class="user_info_items">

		{{-- currency --}}
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
			@if($data['js_item']['subasta']['currency']->symbol == "$")
				<p>{{ $data['js_item']['subasta']['currency']->symbol }}</p>
			@endif
            <span id="imptas" >{{ $data['subasta_info']->lote_actual->formatted_imptas_asigl0}} </span>
            -
			<span id="imptash" >  {{ $data['subasta_info']->lote_actual->formatted_imptash_asigl0}}</span>
			@if($data['js_item']['subasta']['currency']->symbol != "$")
				<p>{{ $data['js_item']['subasta']['currency']->symbol }}</p>
			@endif

        </div>
        @else
        <div></div>
        @endif

        <!-- precio de salida -->
        <div id="precioSalida" class="precioSalida salida">
            <p>
                {{ trans(\Config::get('app.theme').'-app.sheet_tr.start_price') }}:
            </p>
			@if($data['js_item']['subasta']['currency']->symbol == "$")
			{{ $data['js_item']['subasta']['currency']->symbol }}<span>{{ $data['subasta_info']->lote_actual->formatted_impsalhces_asigl0 }}</span>
			@else
				<span>{{ $data['subasta_info']->lote_actual->formatted_impsalhces_asigl0 }} </span>{{ $data['js_item']['subasta']['currency']->symbol }}
			@endif
				@if(\Config::get("app.exchange"))
			| <span id="startPriceExchange_JS" class="exchange"> </span>
			@endif

        </div>

		@php
			$bidClass = 'other';
			$maxPuja = $data['subasta_info']->lote_actual->max_puja;
			$jsUser = $data['js_item']['user'] ?? null;
			if(!empty($maxPuja) && $data['subasta_info']->lote_actual->impres_asigl0 > $maxPuja->imp_asigl1 || empty($maxPuja)){
				$bidClass = '';
			} elseif(!empty($jsUser) && !empty($maxPuja) && $maxPuja->cod_licit == $jsUser['cod_licit']){
				$bidClass = 'mine';
			}
		@endphp


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
					@if($data['js_item']['subasta']['currency']->symbol == "$")
						{{ $data['js_item']['subasta']['currency']->symbol }}{{ \Tools::moneyFormat($data['subasta_info']->lote_actual->actual_bid) }}
                	@else
					{{ \Tools::moneyFormat($data['subasta_info']->lote_actual->actual_bid) }} 	{{ $data['js_item']['subasta']['currency']->symbol }}
                	@endif
				@endif
            </span>
            @else
            <span id="actual_max_bid" class="{{$bidClass}}">
                @if( count($data['subasta_info']->lote_actual->pujas) >0 )
					@if($data['js_item']['subasta']['currency']->symbol == "$")
						{{ $data['js_item']['subasta']['currency']->symbol }}{{ \Tools::moneyFormat($data['subasta_info']->lote_actual->actual_bid) }}
					@else
						{{ \Tools::moneyFormat($data['subasta_info']->lote_actual->actual_bid) }} {{ $data['js_item']['subasta']['currency']->symbol }}
					@endif
				@endif
            </span>
            @endif
			@if(\Config::get("app.exchange"))
			| <span id="actualBidExchange_JS" class="exchange"> </span>
			@endif

        </div>

		<div class="reserv-price">
			<p class="@if(!in_array($bidClass, ['other', 'mine'])) {{'hidden'}} @endif mine reached">{{ trans("$theme-app.sheet_tr.reserve_price_reached") }}</p>
			<p class="@if(in_array($bidClass, ['other', 'mine']) || empty($maxPuja)) {{'hidden'}} @endif other not-reached">{{ trans("$theme-app.sheet_tr.reserve_price_not_reached") }}</p>
		</div>

        <!-- panel pujar -->
        <div class="pujar">
            <div class="tuorden">
                {{ trans(\Config::get('app.theme').'-app.sheet_tr.your_actual_order') }}:
				@if($data['js_item']['subasta']['currency']->symbol == "$")
               		 {{ $data['js_item']['subasta']['currency']->symbol }}
				@endif
				<span id="tuorden">
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
				@if($data['js_item']['subasta']['currency']->symbol != "$")
               		 {{ $data['js_item']['subasta']['currency']->symbol }}<span id="tuorden">
				@endif

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
           		<a class="add_bid btn button btn-custom-save"><i class="fa fa-gavel"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.place_bid') }}</a>
            	<input type="hidden" id="tiempo_real" value="1" readonly>
            @else
            	<a class="btn button btn-custom-save add_bid_nologin" onclick="initSesion();"><i class="fa fa-gavel"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.place_bid') }}</a>
            @endif

        </div>

		<div class="bp-text">
			@if(config('app.buyer_premium_active', false))
			{!! trans('web.sheet_tr.bp_info', ['bpValue' => config('app.addComisionEmailBid')]) !!}
			@endif
		</div>

    </div>
</div>
