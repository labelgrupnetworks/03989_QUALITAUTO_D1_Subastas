<div class="custom-head-wrapper hidden-xs hidden-sm flex">
	<div class="img-data-custom flex "></div>
	<div class="lot-data-custon">
		<p>{{ trans($theme.'-app.user_panel.lot') }}</p>
	</div>
	<div class="name-data-custom" style="font-weight: 900 !important;">
		<p>{{ trans($theme.'-app.lot.description') }}</p>
	</div>

	<div class="remat-data-custom">
		<p>{{ trans($theme.'-app.lot.lot-price') }}</p>
	</div>
	<div class="auc-data-custom">
		<p>{{ trans($theme.'-app.user_panel.award_price') }}</p>
	</div>
	<div class="auc-data-custom">
		<p>{{ trans($theme.'-app.user_panel.increase') }}</p>
	</div>
	<div class="auc-data-custom">
		<p>{{ trans("$theme-app.user_panel.settlement") }}</p>
	</div>
</div>

@php
use App\libs\Currency;
$currency = new Currency();
$divisa = !empty(Session::get('user.currency'))? Session::get('user.currency') : 'EUR';
$currency->setDivisa($divisa);
$divisas = $currency->getAllCurrencies();

$totalLiquidacion = 0;
$totalAdjudicacion = 0;
$totalComision = 0;
$count = 0;
@endphp

@foreach($lots as $lot)

@php
$count++;

$incremento = ($lot->implic_hces1 - $lot->impsalhces_asigl0) / $lot->impsalhces_asigl0 * 100;
$liquidacion = $lot->implic_hces1 - $lot->basea_dvc1l;
//$totalLiquidacion += $liquidacion;
$totalAdjudicacion += $lot->implic_hces1;
//$totalComision += $comision;

@endphp

{{-- Vista mobile --}}
<div class="custom-wrapper-responsive  hidden-md hidden-lg {{$lot->ref_hces1}}-{{$lot->sub_hces1}}">
	<div class="lot-data-custon">
		<p>{{ trans($theme.'-app.user_panel.lot') }}
			{{$lot->ref_hces1}} - <span>{{$lot->titulo_hces1}}</span>
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
			<p>{{ trans($theme.'-app.lot.lot-price') }}</p>
			<p>
				{{$lot->impsalhces_asigl0}} {{ trans($theme.'-app.lot.eur') }}
			</p>

			@if($divisa !='EUR')
			<p class="divisa_fav">
				{!!$currency->getPriceSymbol(0,$lot->impsalhces_asigl0)!!}
			</p>
			@endif

		</div>

		<div class="auc-data-custom">

			<p>{{ trans($theme.'-app.user_panel.award_price') }}</p>

			<p class="gold">

				<?php //todas las subastas de tauler tendran pujas, ya que las w ahora seran abiertas ?>
				<span class="actual-price">{{$lot->implic_hces1 }}</span>
				{{ trans($theme.'-app.lot.eur') }}

			</p>
			@if($divisa !='EUR')
			<p class="divisa_fav divisa-actual-price">
				{!!$currency->getPriceSymbol(0,$lot->implic_hces1)!!}</p>
			@endif
		</div>

		<div class="auc-data-custom text-right">

			<p>{{ trans($theme.'-app.user_panel.increase') }}</p>
			<p class="mine">{{ \Tools::moneyFormat($incremento, '%', 1) }}</p>

		</div>


	</div>

	<div class="flex justify-content-flex-end">


		<div class="auc-data-custom text-right">
			<p>{{ trans($theme.'-app.user_panel.settlement') }}</p>
			<p>{{ \Tools::moneyFormat($liquidacion, false, 2) }} {{ trans($theme.'-app.lot.eur') }}
			</p>
			@if($divisa !='EUR')
			<p class="divisa_fav">
				{!!$currency->getPriceSymbol(2, $liquidacion)!!}
			</p>
			@endif
		</div>
	</div>

</div>

{{--<div class="divider-prices hidden-md hidden-lg"></div>--}}

{{-- Vista desktop --}}
<div class="custom-wrapper hidden-xs hidden-sm flex valign {{$lot->ref_hces1}}-{{$lot->sub_hces1}}">

	<div class="img-data-custom flex valign">
		<img loading="lazy" class="img-responsive"
			src="{{ \Tools::url_img("lote_medium", $lot->num_hces1, $lot->lin_hces1) }}">
	</div>

	<div class="lot-data-custon">
		<p>{{$lot->ref_hces1}}</p>
	</div>

	<div class="name-data-custom">
		<?= $lot->desc_hces1 ?>
	</div>

	<div class="auc-data-custom">
		<p>{{$lot->impsalhces_asigl0}}
			{{ trans($theme.'-app.lot.eur') }}</p>
		@if($divisa !='EUR')
		<p class="divisa_fav">
			{!!$currency->getPriceSymbol(2,$lot->impsalhces_asigl0)!!} </p>
		@endif
	</div>

	<div class="auc-data-custom">

		<p class="gold">
			{{-- todas las subastas de tauler tendran pujas, ya que las w ahora seran abiertas --}}
			<span class="actual-price">{{$lot->implic_hces1 }}</span>
			{{ trans($theme.'-app.lot.eur') }}
		</p>

		@if($divisa !='EUR')
		<p class="divisa_fav divisa-actual-price">
			{!!$currency->getPriceSymbol(2,$lot->implic_hces1)!!}
		</p>
		@endif

	</div>

	<div class="auc-data-custom">
		<p class="mine">{{ \Tools::moneyFormat($incremento, '%', 1) }}</p>
	</div>

	<div class="auc-data-custom">
		<p>{{ \Tools::moneyFormat($liquidacion, false, 2) }} {{ trans($theme.'-app.lot.eur') }}</p>
		@if($divisa !='EUR')
		<p class="divisa_fav">
			{!!$currency->getPriceSymbol(2, $liquidacion)!!}
		</p>
		@endif
	</div>

</div>

@endforeach

<div class="adj adj-panel-wrapper">

	<div class="adj-panel w-100">
		<div class="panel panel-default panel-payment">
			<div class="panel-heading">
				<p class="panel-title">

					<a class="" data-toggle="collapse" data-parent="#fact_accordion" href="#adj_fact_{{$lot->sub_hces1}}">
						<label class="w-100 d-flex align-items-center" for="">
							<span class="titlecat" style="margin-left: auto">
								<span>{{ trans("$theme-app.user_panel.total_settled") }}: </span>
								<span
									class="precio_final_{{$lot->sub_hces1}}">{{\Tools::moneyFormat($totalAdjudicacion - $lot->base_dvc0, false, 2)}}</span>
								<span>{{ trans($theme.'-app.lot.eur') }} |</span>
								<span class="divisa_fav">
									{!!$currency->getPriceSymbol(2, $totalAdjudicacion - $lot->base_dvc0)!!}
								</span>
							</span>
							{{-- <i class="fa fa-2x fa-caret-right" aria-hidden="true"></i> --}}
						</label>
					</a>

				</p>
			</div>
			<div id="adj_fact_{{$lot->sub_hces1}}" class="panel-collapse collapse">
				<div class="panel-body" id="">
					<div class="info-pay-modal">

						<div class="price flex justify-content-space-bettween">
							<div class="title"></div>
							<div class="money gold">{{ trans($theme.'-app.user_panel.fecha_fact') }}
								{{ \Tools::getDateFormat($lot->fecha_dvc0, 'Y-m-d H:i:s', 'd/m/Y') }}</div>
						</div>
						<div class="price flex justify-content-space-bettween">
							<div class="title"></div>
							<div class="money gold">{{ trans($theme.'-app.user_panel.cod_factura') }} {{ $lot->anum_dvc0 . ' / ' . $lot->num_dvc0 }}
							</div>
						</div>
						<br>

						<div class="price flex justify-content-space-bettween">
							<div class="title">{{ trans($theme.'-app.user_panel.total_lots') }}</div>
							<div class="money">{{ $count }}</div>
						</div>
						<br>
						<div class="price flex justify-content-space-bettween">
							<div class="title">{{ trans($theme.'-app.user_panel.total_award') }}</div>
							<div class="money">{!!\Tools::moneyFormat($totalAdjudicacion,false,2)!!}
								{{ trans($theme.'-app.lot.eur') }}
								&nbsp;|&nbsp;&nbsp;
								<span class="divisa_fav">
									{!!$currency->getPriceSymbol(0, $totalAdjudicacion)!!}
								</span>
							</div>
						</div>
						<div class="price flex justify-content-space-bettween">
							<div class="title">{{ trans($theme.'-app.user_panel.taxable_base_commission') }}</div>
							<div class="money">{!!\Tools::moneyFormat($lot->base_dvc0, false, 2)!!}
								{{ trans($theme.'-app.lot.eur') }}
								&nbsp;|&nbsp;&nbsp;
								<span class="divisa_fav">
									{!!$currency->getPriceSymbol(0, $lot->base_dvc0)!!}
								</span>
							</div>
						</div>
						<div class="price flex justify-content-space-bettween">
							<div class="title">{{ trans($theme.'-app.user_panel.commission_vat') }}({{ $lot->iva_dvc0 }}%)</div>
							<div class="money">{!!\Tools::moneyFormat($lot->impiva_dvc0,false,2)!!}
								{{ trans($theme.'-app.lot.eur') }}
								&nbsp;|&nbsp;&nbsp;
								<span class="divisa_fav">
									{!!$currency->getPriceSymbol(0, $lot->impiva_dvc0)!!}
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="text-right factura-buttons">
		@if(!empty($facturaPdf))
			<a class="btn btn-color factura-button factura-buttons-sales" target="_blank" href="/factura/{{$lot->anum_dvc0}}-{{$lot->num_dvc0}}">
				{{ trans($theme.'-app.user_panel.facturado') }}
			</a>
		@endif
	</div>

</div>
