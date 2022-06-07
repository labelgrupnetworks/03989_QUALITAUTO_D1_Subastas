@foreach ($subastas as $cod_sub => $lots)


<a data-toggle="collapse" href="#{{$cod_sub}}" data-parent="#auctions_accordion">
	<div class="panel-heading panel-heading-auction">
		<h4 class="panel-title">
			{{$lots->first()->name}}
		</h4>
		<i class="fas fa-sort-down"></i>
	</div>
</a>

<div id="{{$cod_sub}}" class="panel-collapse collapse">

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
			@if ($finalizada)
			<p>{{ trans(\Config::get('app.theme').'-app.user_panel.award_price') }}</p>
			@else
			<p>{{ trans("$theme-app.lot.current_bid") }}</p>
			@endif

		</div>
		<div class="auc-data-custom">
			@if ($finalizada)
			<p>{{ trans(\Config::get('app.theme').'-app.user_panel.increase') }}</p>
			@else
			<p>{{ trans(\Config::get('app.theme').'-app.user_panel.bids_bidders') }}</p>
			@endif
		</div>
		<div class="auc-data-custom">
			@if ($finalizada)
			<p>{{ trans(\Config::get('app.theme').'-app.user_panel.settlement') }}</p>
			@else
			<p></p>
			@endif
		</div>
	</div>

	@php
		$totalLiquidacion = 0;
		$totalAdjudicacion = 0;
		$totalComision = 0;
		$count = 0;
	@endphp

	@foreach($lots as $lot)

	{{-- solo mostramos lotes adjudicados en las subastas finalizadas --}}
	@continue ($finalizada && empty($lot->implic_hces1))

	@php
	$url_friendly = str_slug($lot->desc_hces1);
	$url_friendly = \Routing::translateSeo('lote').$lot->cod_sub."-".str_slug($lot->name).'-'.$lot->id_auc_sessions."/".$lot->ref_asigl0.'-'.$lot->num_hces1.'-'.$url_friendly;
	$count++;

	if($finalizada){
		$revalorizacion = ($lot->implic_hces1 - $lot->impsalhces_asigl0) / $lot->impsalhces_asigl0 * 100;
		$comision = $lot->implic_hces1 * $lot->comphces_asigl0 / 100;
		$liquidacion = $lot->implic_hces1 - $comision;
		$totalLiquidacion += $liquidacion;
		$totalAdjudicacion += $lot->implic_hces1;
		$totalComision += $comision;
	}


	@endphp

	{{-- Vista mobile --}}
	<div class="custom-wrapper-responsive  hidden-md hidden-lg {{$lot->ref_asigl0}}-{{$lot->cod_sub}}">
		<div class="lot-data-custon">
			<p>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}
				{{$lot->ref_asigl0}} - <span>{{$lot->titulo_hces1}}</span>
			</p>
		</div>

		<div class="lot-data-custon">
			<img style="margin-left: auto; margin-right: auto" class="img-responsive mt-2 mb-2"
				src="{{ \Tools::url_img("lote_medium", $lot->num_hces1, $lot->lin_hces1) }}">
		</div>

		<div class="name-data-custom max-line-2 mb-1" style="width: 100%">
			{!! $lot->desc_hces1 !!}
		</div>

		<div class="flex justify-content-space-bettween">

			<div class="auc-data-custom">
				<p>{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
				<p>
					{{$lot->impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.lot.eur') }}
				</p>

				@if($divisa !='EUR')
				<p class="divisa_fav">
					{!!$currency->getPriceSymbol(0,$lot->impsalhces_asigl0)!!}
				</p>
				@endif

			</div>

			<div class="auc-data-custom">

				<p>{{ trans(\Config::get('app.theme').'-app.user_panel.award_price') }}</p>

				<p class="gold">

					<?php //todas las subastas de tauler tendran pujas, ya que las w ahora seran abiertas ?>
					<span class="actual-price">{{$lot->implic_hces1 }}</span>
					{{ trans(\Config::get('app.theme').'-app.lot.eur') }}

				</p>
				@if($divisa !='EUR')
				<p class="divisa_fav divisa-actual-price">
					{!!$currency->getPriceSymbol(0,$lot->implic_hces1)!!}</p>
				@endif
			</div>

			<div class="auc-data-custom text-right">

				@if ($finalizada)
				<p>{{ trans(\Config::get('app.theme').'-app.user_panel.increase') }}</p>
					<p class="mine">{{ \Tools::moneyFormat($revalorizacion, '%', 1) }}</p>
				@else
					<p>{{ trans("$theme-app.user_panel.bids_bidders") }}</p>
					<p><img src="/themes/{{\Config::get('app.theme')}}/assets/img/auction.png" width="16px" height="16px" style="margin-right: 5px;"/>{{$lot->bids}}
                    <img src="/themes/{{\Config::get('app.theme')}}/assets/img/man-user.png" width="16px" height="16px" style="margin-left: 10px; margin-right: 5px;" />{{$lot->licits}}</p>
				@endif

			</div>


		</div>

		<div class="flex justify-content-flex-end">


			<div class="auc-data-custom text-right">
				@if ($finalizada)

					<p>{{ trans(\Config::get('app.theme').'-app.user_panel.settlement') }}</p>
					<p>{{ \Tools::moneyFormat($liquidacion, false, 2) }} {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</p>
					@if($divisa !='EUR')
					<p class="divisa_fav">
						{!!$currency->getPriceSymbol(2, $liquidacion)!!}
					</p>
					@endif
				@else
					<a class="btn btn-color btn-puja-panel btn-blue d-flex align-items-center justify-content-center"
						href="{{$url_friendly}}">{{trans(\Config::get('app.theme').'-app.lot.view_lot')}}</a>
				@endif
			</div>
		</div>

	</div>

	{{--<div class="divider-prices hidden-md hidden-lg"></div>--}}

	{{-- Vista desktop --}}
	<div class="custom-wrapper hidden-xs hidden-sm flex valign {{$lot->ref_asigl0}}-{{$lot->cod_sub}}">

		<div class="img-data-custom flex valign">
			<img class="img-responsive"
				src="{{ \Tools::url_img("lote_medium", $lot->num_hces1, $lot->lin_hces1) }}">
		</div>

		<div class="lot-data-custon">
			<p>{{$lot->ref_asigl0}}</p>
		</div>

		<div class="name-data-custom">
			<?= $lot->desc_hces1 ?>
		</div>

		<div class="auc-data-custom">
			<p>{{$lot->impsalhces_asigl0}}
				{{ trans(\Config::get('app.theme').'-app.lot.eur') }}</p>
			@if($divisa !='EUR')
			<p class="divisa_fav">
				{!!$currency->getPriceSymbol(2,$lot->impsalhces_asigl0)!!} </p>
			@endif
		</div>

		<div class="auc-data-custom">

			<p class="gold">
				{{-- todas las subastas de tauler tendran pujas, ya que las w ahora seran abiertas --}}
				<span class="actual-price">{{$lot->implic_hces1 }}</span>
				{{ trans(\Config::get('app.theme').'-app.lot.eur') }}
			</p>

			@if($divisa !='EUR')
			<p class="divisa_fav divisa-actual-price">
				{!!$currency->getPriceSymbol(2,$lot->implic_hces1)!!}
			</p>
			@endif

		</div>

		<div class="auc-data-custom">
			@if ($finalizada)
				<p class="mine">{{ \Tools::moneyFormat($revalorizacion, '%', 1) }}</p>
			@else
				<img src="/themes/{{\Config::get('app.theme')}}/assets/img/auction.png" width="16px" height="16px" style="margin-right: 5px;"/>{{$lot->bids}}
				<img src="/themes/{{\Config::get('app.theme')}}/assets/img/man-user.png" width="16px" height="16px" style="margin-left: 10px; margin-right: 5px;" />{{$lot->licits}}
			@endif

		</div>

		<div class="auc-data-custom">
			@if ($finalizada)
				<p>{{ \Tools::moneyFormat($liquidacion, false, 2) }} {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</p>
				@if($divisa !='EUR')
				<p class="divisa_fav">
					{!!$currency->getPriceSymbol(2, $liquidacion)!!}
				</p>
				@endif
			@else
				<a class="btn btn-color btn-puja-panel btn-blue d-flex align-items-center justify-content-center"
					href="{{$url_friendly}}">{{trans(\Config::get('app.theme').'-app.lot.view_lot')}}</a>
			@endif
		</div>

	</div>

	@endforeach

</div>



@endforeach
