

<div  class="col-lg-12 col-md-12 info-ficha-buy-info no-padding">
    <div class=" col-xs-12 no-padding info-ficha-buy-info-price d-flex">

			@if ($lote_actual->ocultarps_asigl0 != 'S')
				<div class="pre">
					<p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
					@if($lote_actual->impsalhces_asigl0 ==0)
						{{ trans(\Config::get('app.theme').'-app.lot.free') }}
					@else
						<p class="pre-price">{{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
							@if(\Config::get("app.exchange"))
								<span id="startPriceExchange_JS" class="exchange"> </span>
							@endif
						</p>
					@endif
			@endif

			</div>
			@if(!empty($lote_actual->imptash_asigl0))
				<div class="pre">
					<p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.estimate') }}</p>
					<p class="pre-price">{{ \Tools::moneyFormat($lote_actual->imptash_asigl0)}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
						@if(\Config::get("app.exchange"))
						| <span id="estimateExchange_JS" class="exchange"> </span>
						@endif
					</p>

				</div>
			@endif

    </div>
</div>
<div class=" col-xs-12 no-padding info-ficha-buy-info-price d-flex">
    <div class="pre">
        @if ($subasta_web && $subasta_abierta_O && $cerrado_N  )
            <div id="text_actual_max_bid" class="price-title-principal pre <?=  $lote_actual->open_price >0? '':'hidden' ?>">
                    <p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
                    <div id="max_bid_color" class="pre-price <?= (count($data['ordenes']) > 0 && !empty($data['js_item']['user']) && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'])? 'winner':'no_winner' ?>">
                        <span id="actual_max_bid"  >
                            {{\Tools::moneyFormat($lote_actual->open_price) }}
                        </span> {{trans(\Config::get('app.theme').'-app.subastas.euros')}}
                    </div>

            </div>

            <div  id="text_actual_no_bid" class="pre <?=  $lote_actual->open_price >0? 'hidden':'' ?>"> <p class="pre-title-principal">{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }}</p></div>

        @elseif ($subasta_web && $subasta_abierta_P && $cerrado_N  )
        <div id="text_actual_max_bid" class="pre-price price-title-principal <?=  count($lote_actual->pujas) >0? '':'hidden' ?>">
                <p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
                <strong>
                    {{-- aparecera en rojo(clase other) si no eres el ganador y en verde si loeres (clase mine) , si no estas logeado no se modifica el color --}}
                    @if(Session::has('user'))
                        @php($class = (!empty($lote_actual->max_puja) &&   $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'])? 'mine':'other')
                    @else
                        @php($class = '')
                    @endif
                    <span id="actual_max_bid" class="{{$class}}">{{ $lote_actual->formatted_actual_bid }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>

                </strong>
            </div>
            @endif
    </div>
</div>

@if(!$fact_devuelta)
<div class="info_single col-xs-12 ficha-puja no-padding">
	<div class="col-lg-12 no-padding">
		<div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">
		{{trans(\Config::get('app.theme').'-app.lot.max_puja')}}
			<strong>
				<span id="tuorden">
					@if ( !empty($data['js_item']['user']['ordenMaxima']))
					{{ $data['js_item']['user']['ordenMaxima']}}
					@endif
				</span>
				{{trans(\Config::get('app.theme').'-app.subastas.euros')}}
				@if(\Config::get("app.exchange"))
				 |	<span  id="yourOrderExchange_JS" class="exchange"> </span>
				@endif
			</strong>
		</div>
	</div>
</div>
@endif

@if( $cerrado_N && $fact_N && $start_session  &&  !$end_session )
    <div class="col-xs-12 no-padding">
        <div class="ficha-live-btn-content">
            <a class="ficha-live-btn-link secondary-button" href='{{\Tools::url_real_time_auction($data['subasta_info']->lote_actual->cod_sub,$data['subasta_info']->lote_actual->name,$data['subasta_info']->lote_actual->id_auc_sessions)}}'>
                <div class="bid-online"></div>
                <div class="bid-online animationPulseRed"></div>
                <?=trans(\Config::get('app.theme').'-app.lot.bid_live')?>
            </a>
        </div>
    </div>


@else




<div class="ficha-info-item-for-pay col-xs-12 no-padding">
    <div class="info_single_content">
        <?php //las subastas abiertas tipo P se veran como W cuando empiece la subasta, pero controlamos que no sep uedan hacer ordenes (!$subasta_abierta_P) ?>
        @if( $cerrado_N && $fact_N &&  $start_orders  &&   !$end_orders && !$subasta_abierta_P)

			{{-- Si el lote es NFT y el usuario está logeado pero no tiene wallet --}}
			@if ($lote_actual->es_nft_asigl0 == "S" &&  !empty($data["usuario"])  && empty($data["usuario"]->wallet_cli) )
				<div class="require-wallet">{!! trans(\Config::get('app.theme').'-app.lot.require_wallet') !!}</div>
			@else
				<div class="insert-max-bid"><?=trans(\Config::get('app.theme').'-app.lot.insert_max_puja_start')?></div>
				<div class="input-group group-pujar-custom d-flex justify-content-space-between">
						<input id="bid_modal_pujar" placeholder="{{ $data['precio_salida'] }}" class="form-control control-number" value="{{ $data['precio_salida'] }}" type="text">
						<div class="input-group-btn">
							<button id="pujar_ordenes_w" data-from="modal" type="button" class="ficha-btn-bid button-principal" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.lot.place_bid') }}</button>
						</div>
						@if(!empty($lote_actual->ordentel_sub) && $lote_actual->ordentel_sub <= $lote_actual->impsalhces_asigl0)
							<div >
								<button id="pujar_orden_telefonica" data-from="modal" type="button" class="ficha-btn-telephone-bid  button-principal" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.lot.puja_telefonica') }}</button>
								<input id="orderphone" type="hidden" >
							</div>
						@endif
				</div>
			@endif

        @endif
    </div>
</div>


@endif
