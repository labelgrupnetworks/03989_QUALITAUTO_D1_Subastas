
@include('includes.ficha._pujas_ficha_info')




@if($lote_actual->fac_hces1!='D')
<div class="info_single col-xs-12 ficha-puja">

	<div>
		<div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">
			{{trans(\Config::get('app.theme').'-app.lot.max_puja')}}
			<strong><span id="tuorden">
					@if ( !empty($data['js_item']['user']['ordenMaxima']))
					{{ $data['js_item']['user']['ordenMaxima']}}
					@endif
				</span>
				{{trans(\Config::get('app.theme').'-app.subastas.euros')}}</strong>
		</div>
	</div>

	@if ($lote_actual->tipo_sub == 'W' && $lote_actual->subabierta_sub == 'S' && $lote_actual->cerrado_asigl0 == 'N' )
	<div class="col-lg-12">
		<div class="info_single_title">
			<div id="text_actual_max_bid" class=" <?=  $lote_actual->open_price >0? '':'hidden' ?>">
				{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }} <strong><span
						id="actual_max_bid">{{\Tools::moneyFormat($lote_actual->open_price) }} </span>
					{{trans(\Config::get('app.theme').'-app.subastas.euros')}}</strong>

				@if (isset($data['js_item']['user']))
				<span
					class="winner <?= (count($data['ordenes']) > 0 && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'])? '':'hidden' ?>">{{ trans(\Config::get('app.theme').'-app.subastas.exceeded') }}</span>
				<span
					class="no_winner <?= (count($data['ordenes']) > 0 && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'])? 'hidden':'' ?>">{{ trans(\Config::get('app.theme').'-app.subastas.not_exceeded') }}</span>
				@endif
			</div>
			<div id="text_actual_no_bid" class=" <?=  $lote_actual->open_price >0? 'hidden':'' ?>">
				{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }} </div>
		</div>
	</div>
	@endif

	<div class="col-xs-12">
		<div class="info_single_content">
			@if( $lote_actual->cerrado_asigl0=='N' && $lote_actual->fac_hces1=='N' && strtotime("now") >
			strtotime($lote_actual->start_session) && strtotime("now") < strtotime($lote_actual->end_session) )
				<a
					href='{{  Routing::translateSeo('api/subasta').$data['subasta_info']->lote_actual->cod_sub."-".str_slug($data['subasta_info']->lote_actual->name)."-".$data['subasta_info']->lote_actual->id_auc_sessions }}'>
					<button
						class="btn btn-lg btn-custom live-btn"><?=trans(\Config::get('app.theme').'-app.lot.bid_live')?></button>
				</a>
				@endif
				@if( $lote_actual->cerrado_asigl0=='N' && $lote_actual->fac_hces1=='N' && strtotime("now") >
				strtotime($lote_actual->orders_start) && strtotime("now") < strtotime($lote_actual->orders_end) && !$subasta_abierta_P)
					<p><strong><?=trans(\Config::get('app.theme').'-app.lot.insert_max_puja_start')?></strong></p>
					<div class="input-group group-pujar-custom">
						<input id="bid_modal_pujar" placeholder="{{ $data['precio_salida'] }}"
							class="form-control input-lg control-number" value="{{ $data['precio_salida'] }}"
							type="text">
						<div class="input-group-btn">
							<button id="pujar_ordenes_w" data-from="modal" type="button"
								class="btn btn-lg btn-custom pujar_ordenes_btn"
								ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}"
								codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.lot.place_bid') }}</button>
						</div>
					</div>
					@endif

		</div>
	</div>

	<div class="col-xs-12 separator-ficha"></div>

</div>


@endif

