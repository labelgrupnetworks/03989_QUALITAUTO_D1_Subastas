<a data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}" data-parent="#auctions_accordion">
	<div class="panel-heading panel-heading-auction">
		<h4 class="panel-title">
			{{$all_inf['inf']->name}}
		</h4>
		<i class="fas fa-sort-down"></i>
	</div>
</a>

<div id="{{$all_inf['inf']->cod_sub}}" class="panel-collapse collapse @if(!$subasta_finalizada && count($notFinalized) == 1) in @endif">

	<div class="custom-head-wrapper hidden-xs hidden-sm flex">
		<div class="img-data-custom flex "></div>
		<div class="lot-data-custon">
			<p>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</p>
		</div>
		<div class="name-data-custom" style="font-weight: 900 !important;">
			<p>{{ trans(\Config::get('app.theme').'-app.lot.description') }}</p>
		</div>

		<div class="remat-data-custom">
			<p>{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
		</div>
		<div class="auc-data-custom">
			@if ($subasta_finalizada)
				<p>{{ trans(\Config::get('app.theme').'-app.user_panel.award_price') }}</p>
			@else
				<p>{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
			@endif
		</div>
		<div class="auc-data-custom">
			<p>{{ trans(\Config::get('app.theme').'-app.user_panel.mi_puja') }}</p>
		</div>
		<div class="auc-data-custom"></div>
		<div class="auc-data-custom"></div>
		<div class="view-data view-fav"></div>
	</div>

	<?php $countBid = 1; ?>

	@foreach($all_inf['lotes'] as $inf_lot)

	@php
	$url_friendly = str_slug($inf_lot->titulo_hces1);
	$url_friendly =
	\Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->session_name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;

	$style = 'other';
	$bid_mine = false;
	if($inf_lot->cod_licit == $inf_lot->licit_winner_bid){
		$style = 'mine';
		$bid_mine = true;
	}
	elseif (!Config::get('app.notice_over_bid') && $inf_lot->tipo_sub == 'W') {
		$style = 'gold';
	}

	//escalado se inicia en cada subasta.
	$escalado->sin_pujas = true;
	if(!empty($inf_lot->licit_winner_bid)){
	$escalado->sin_pujas = false;
	}

	$nextScale = $escalado->NextScaleBid($inf_lot->impsalhces_asigl0, $inf_lot->implic_hces1);
	@endphp

	{{-- Vista mobile --}}
	<div class="custom-wrapper-responsive  hidden-md hidden-lg {{$inf_lot->ref_asigl0}}-{{$inf_lot->cod_sub}}">
		<div class="lot-data-custon d-flex justify-content-space-bettween">
			<p>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}
				{{$inf_lot->ref_asigl0}}
			</p>
			<div class="view-data flex auc-data-custom">
				@if(!empty($data['favorites']))
				<a title="{{trans(\Config::get('app.theme').'-app.lot.del_from_fav')}}" class="delete-fav btn-del"
					href="javascript:action_fav_lote('remove','{{ $inf_lot->ref_asigl0 }}','{{$inf_lot->cod_sub }}',' <?= $data['codigos_licitador'][$inf_lot->cod_sub] ?>')">
					<i class="fas fa-minus"></i>
				</a>
				@endif
			</div>
		</div>

		<div class="lot-data-custon">
			<img style="margin-left: auto; margin-right: auto" class="img-responsive mt-2 mb-2"
				src="{{ \Tools::url_img("lote_medium", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}">
		</div>

		<div class="name-data-custom max-line-2 mb-1" style="width: 100%">
			{!! $inf_lot->desc_hces1 !!}
		</div>


		<div class="flex justify-content-space-bettween mb-1">

			<div class="auc-data-custom">
				<p>{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
				<p>
					{{$inf_lot->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.lot.eur') }}
				</p>

				@if($divisa !='EUR')
				<p class="divisa_fav">
					{!!$currency->getPriceSymbol(0,$inf_lot->impsalhces_asigl0)!!}
				</p>
				@endif

			</div>

			<div class="auc-data-custom">
				@if ($subasta_finalizada)
					<p>{{ trans(\Config::get('app.theme').'-app.user_panel.award_price') }}</p>
				@else
					<p>{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
				@endif

				<p class="{{$style}}">

					<?php //todas las subastas de tauler tendran pujas, ya que las w ahora seran abiertas ?>
					<span class="actual-price">{{$inf_lot->implic_hces1 }}</span>
					{{ trans(\Config::get('app.theme').'-app.lot.eur') }}

				</p>
				@if($divisa !='EUR')
				<p class="divisa_fav divisa-actual-price">
					{!!$currency->getPriceSymbol(0,$inf_lot->implic_hces1)!!}</p>
				@endif
			</div>

			<div class="auc-data-custom">
				<p>{{ trans(\Config::get('app.theme').'-app.user_panel.mi_puja') }}</p>

				<p>
					<span class="my-max-bid">{{$inf_lot->formatted_imp }}</span> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}
				</p>

				@if($divisa !='EUR')
				<p class="divisa_fav divisa-my-max-bid"> {!!$currency->getPriceSymbol(0,$inf_lot->imp)!!}</p>
				@endif

			</div>

		</div>

		<div class="d-flex justify-content-space-bettween mb-1" style="gap: 20px">

			<div class="auc-data-custom">
				@if($inf_lot->cerrado_asigl0 != 'S' && $inf_lot->retirado_asigl0 != 'S' && strtotime("now") < strtotime($all_inf['inf']->start))
					<button type="button" data-from="modal" data-sub="{{$inf_lot->cod_sub}}"
						data-ref="{{$inf_lot->ref_asigl0}}" data-imp="{{$nextScale}}"
						class="btn-color js-lot-action_pujar_panel btn-puja-panel @if($bid_mine) bid-mine @endif"
						@if($bid_mine) disabled @endif>

						<p class="js-max-bid @if(!$bid_mine) hidden @endif">
							{{trans(\Config::get('app.theme').'-app.user_panel.higher_bid_es')}}
						</p>
						<p class="js-place-bid @if($bid_mine) hidden @endif">
							{{ trans(\Config::get('app.theme').'-app.sheet_tr.place_bid') }}
							<span value="{{$nextScale}}"
								id="button-escalado">{{ \Tools::moneyFormat($nextScale) }}
							</span>
							{{trans(\Config::get('app.theme').'-app.subastas.euros')}}
						</p>

					</button>
					<a href="{{  Routing::translateSeo('api/subasta').$inf_lot->cod_sub."-".str_slug($inf_lot->session_name)."-".$inf_lot->id_auc_sessions }}"
						data-from="modal"
						class="btn-color btn-puja-panel d-flex align-items-center justify-content-center hidden js-button-bid-live">
						{{trans(\Config::get('app.theme').'-app.lot_list.bid_live')}}
					</a>

				{{-- Cuando la subasta esta en vivo --}}
				@elseif(strtotime("now") > strtotime($all_inf['inf']->start) && strtotime("now") < strtotime($all_inf['inf']->end))

						<a href="{{  Routing::translateSeo('api/subasta').$inf_lot->cod_sub."-".str_slug($inf_lot->session_name)."-".$inf_lot->id_auc_sessions }}"
							data-from="modal"
							class="btn-color btn-puja-panel d-flex align-items-center justify-content-center js-button-bid-live">
							{{trans(\Config::get('app.theme').'-app.lot_list.bid_live')}}
						</a>

				@endif
			</div>

			<div class="auc-data-custom">
				<a class="btn btn-color btn-puja-panel btn-blue d-flex align-items-center justify-content-center"
					href="{{$url_friendly}}">{{trans(\Config::get('app.theme').'-app.lot.view_lot')}}</a>
			</div>


		</div>

	</div>

	{{-- Vista desktop --}}
	<div class="custom-wrapper hidden-xs hidden-sm flex valign {{$inf_lot->ref_asigl0}}-{{$inf_lot->cod_sub}}">

		<div class="img-data-custom flex valign">
			<img class="img-responsive"
				src="{{ \Tools::url_img("lote_medium", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}">
		</div>

		<div class="lot-data-custon">
			<p>{{$inf_lot->ref_asigl0}}</p>
		</div>

		<div class="name-data-custom">
			<?= $inf_lot->desc_hces1 ?>
		</div>

		<div class="auc-data-custom">
			<p>{{$inf_lot->impsalhces_asigl0}}
				{{ trans(\Config::get('app.theme').'-app.lot.eur') }}</p>
			@if($divisa !='EUR')
			<p class="divisa_fav">
				{!!$currency->getPriceSymbol(2,$inf_lot->impsalhces_asigl0)!!} </p>
			@endif
		</div>

		<div class="auc-data-custom">

			<p class="{{$style}}">
				{{-- todas las subastas de tauler tendran pujas, ya que las w ahora seran abiertas --}}
				<span class="actual-price">{{$inf_lot->implic_hces1 }}</span>
				{{ trans(\Config::get('app.theme').'-app.lot.eur') }}
			</p>

			@if($divisa !='EUR')
			<p class="divisa_fav divisa-actual-price">
				{!!$currency->getPriceSymbol(2,$inf_lot->implic_hces1)!!}
			</p>
			@endif

		</div>

		<div class="auc-data-custom">
			@if (!empty($inf_lot->imp))
			<p>
				<span class="my-max-bid">{{$inf_lot->formatted_imp }}</span>
				{{ trans(\Config::get('app.theme').'-app.lot.eur') }}
			</p>

				@if($divisa !='EUR')
				<p class="divisa_fav divisa-my-max-bid"> {!!$currency->getPriceSymbol(2,$inf_lot->imp)!!}</p>
				@endif

			@else
			<p>
				<span class="my-max-bid">{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }}</span>
			</p>
			@endif

		</div>

		<div class="auc-data-custom">
			@if($inf_lot->cerrado_asigl0 != 'S' && $inf_lot->retirado_asigl0 != 'S' && strtotime("now") < strtotime($all_inf['inf']->start))

				<button type="button" data-from="modal" data-sub="{{$inf_lot->cod_sub}}"
					data-ref="{{$inf_lot->ref_asigl0}}" data-imp="{{$nextScale}}"
					class="btn-color js-lot-action_pujar_panel btn-puja-panel @if($bid_mine) bid-mine @endif" @if($bid_mine) disabled @endif>

					<p class="js-max-bid @if(!$bid_mine) hidden @endif">
						{{trans(\Config::get('app.theme').'-app.user_panel.higher_bid_es')}}
					</p>
					<p class="js-place-bid @if($bid_mine) hidden @endif">
						{{ trans(\Config::get('app.theme').'-app.sheet_tr.place_bid') }}
						<span value="{{$nextScale}}" id="button-escalado">{{ \Tools::moneyFormat($nextScale) }}</span>
						{{trans(\Config::get('app.theme').'-app.subastas.euros')}}
					</p>

				</button>

				<a href="{{  Routing::translateSeo('api/subasta').$inf_lot->cod_sub."-".str_slug($inf_lot->session_name)."-".$inf_lot->id_auc_sessions }}"
					data-from="modal"
					class="btn-color btn-puja-panel d-flex align-items-center justify-content-center hidden js-button-bid-live">
					{{trans(\Config::get('app.theme').'-app.lot_list.bid_live')}}
				</a>
			{{-- Cuando la subasta esta en vivo --}}
			@elseif(strtotime("now") > strtotime($all_inf['inf']->start) && strtotime("now") < strtotime($all_inf['inf']->end))

					<a href="{{  Routing::translateSeo('api/subasta').$inf_lot->cod_sub."-".str_slug($inf_lot->session_name)."-".$inf_lot->id_auc_sessions }}"
						data-from="modal"
						class="btn-color btn-puja-panel d-flex align-items-center justify-content-center js-button-bid-live">
						{{trans(\Config::get('app.theme').'-app.lot_list.bid_live')}}
					</a>

			@endif

		</div>

		<div class="auc-data-custom">
			<a class="btn btn-color btn-puja-panel btn-blue d-flex align-items-center justify-content-center"
				href="{{$url_friendly}}">{{trans(\Config::get('app.theme').'-app.lot.view_lot')}}</a>
		</div>

		<div class="view-data view-fav flex hidden-xs hidden-sm">
			@if(!empty($data['favorites']))
			<a title="{{trans(\Config::get('app.theme').'-app.lot.del_from_fav')}}" class="delete-fav btn-del"
				href="javascript:action_fav_lote('remove','{{ $inf_lot->ref_asigl0 }}','{{$inf_lot->cod_sub }}',' <?= $data['codigos_licitador'][$inf_lot->cod_sub] ?>')"><i
					class="fas fa-minus"></i></a>
			@endif
		</div>

	</div>
	@endforeach
</div>
