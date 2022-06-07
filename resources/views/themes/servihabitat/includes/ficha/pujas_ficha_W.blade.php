

<div  class="col-lg-12 col-md-12 info-ficha-buy-info no-padding">
    <div class=" col-xs-12 no-padding info-ficha-buy-info-price d-flex">

			@if($lote_actual->ocultarps_asigl0 != 'S')
            <div class="pre">
                <p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
                <p class="pre-price">{{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
					@if(\Config::get("app.exchange"))
					| <span id="startPriceExchange_JS" class="exchange"> </span>
					@endif
				</p>
			</div>
			@endif
			@if(!empty($lote_actual->imptas_asigl0))
				<div class="pre">
					<p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.estimate') }}</p>
					<p class="pre-price">{{ \Tools::moneyFormat($lote_actual->imptas_asigl0)}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
						@if(\Config::get("app.exchange"))
						| <span id="estimateExchange_JS" class="exchange"> </span>
						@endif
					</p>

				</div>
			@endif

    </div>
</div>


<div  class="col-lg-12 col-md-12 info-ficha-buy-info no-padding">
	@include('front::includes.ficha.ficha_conditions')
</div>


@if(!$fact_devuelta)
<div class="info_single col-xs-12 ficha-puja no-padding">
	<div class="col-lg-12 no-padding">
		<div class="mb-1 info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">
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

				@if (config('app.edit_orders', 0))
				<a style="float: right" class="delete_order @if(empty($data['js_item']['user']['ordenMaxima'])) hidden @endif" ref="{{$lote_actual->ref_asigl0}}" sub="{{$lote_actual->cod_sub}}">{{ trans(\Config::get('app.theme').'-app.lot.delete_my_bid') }}</a>
				@endif

			</strong>
		</div>
	</div>
</div>
@endif

 @if( $cerrado_N && $fact_N && $start_session && !$end_session && $end_orders)
 {{--
    <div class="col-xs-12 no-padding">
        <div class="ficha-live-btn-content">
            <a class="ficha-live-btn-link secondary-button" href='{{\Tools::url_real_time_auction($data['subasta_info']->lote_actual->cod_sub,$data['subasta_info']->lote_actual->name,$data['subasta_info']->lote_actual->id_auc_sessions)}}'>
                <div class="bid-online"></div>
                <div class="bid-online animationPulseRed"></div>
                {{trans(\Config::get('app.theme').'-app.lot.bid_live')}}
            </a>
        </div>
    </div>
	 --}}
@else




<div class="ficha-info-item-for-pay col-xs-12 no-padding">
    <div class="info_single_content">
        <?php //las subastas abiertas tipo P se veran como W cuando empiece la subasta, pero controlamos que no sep uedan hacer ordenes (!$subasta_abierta_P) ?>
        @if( $cerrado_N && $fact_N &&  $start_orders  &&   !$end_orders && !$subasta_abierta_P)
            <div class="insert-max-bid"><?=trans(\Config::get('app.theme').'-app.lot.insert_max_puja_start')?></div>
            <div class="input-group group-pujar-custom d-flex justify-content-space-between">
                    <input id="bid_modal_pujar" placeholder="{{ $data['precio_salida'] }}" class="form-control control-number" value="{{ $data['precio_salida'] }}" type="text">
                    <div class="input-group-btn">
                        <button id="pujar_ordenes_w" data-from="modal" type="button" class="ficha-btn-bid button-principal" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.lot.place_bid') }}</button>
                    </div>
			</div>
			@if(!empty($lote_actual->ordentel_sub) && $lote_actual->ordentel_sub <= $lote_actual->impsalhces_asigl0)
				<div class="input-group-btn ">
					<button id="pujar_orden_telefonica" data-from="modal" type="button" class="ficha-btn-telephone-bid  button-principal" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.lot.puja_telefonica') }}</button>
					<input id="orderphone" type="hidden" >
					<input id="userphone1" type="hidden" value="" >
				<a href="javascript:;" data-toggle="modal" data-target="#modalAjax" class="info-ficha-lot pt-1 c_bordered" data-ref="{{ Routing::translateSeo('pagina')."info-pujas-presencial"  }}?modal=1" data-title="{{ trans(\Config::get('app.theme').'-app.lot.title_info_pujas') }}"><i class="fas fa-info-circle"></i></a>
				</div>
			@endif

        @endif
    </div>
</div>


@endif
