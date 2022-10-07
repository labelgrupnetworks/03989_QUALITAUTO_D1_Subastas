@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h1>
		</div>
	</div>
</div>
<div class="container panel sales-panel">
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			@php
			$tab="sales";
			@endphp

			@include('pages.panel.menu_micuenta')

			<div class="row">
				<div class="col-xs-12">
					<p class="sales-panel-info">{{ trans("$theme-app.user_panel.sales_info") }}</p>
				</div>
			</div>

			<div class="panel-group" id="accordion">
				<div class="panel panel-default">

					@foreach($subastas as $key_sub => $subasta)
					<div class="panel-heading">
						<a data-toggle="collapse" href="#{{ $key_sub }}">
							<h4 class="panel-title">
								{{ $key_sub }} - {{$subasta[0]->des_sub}} - {{ trans("$theme-app.global.from") }} {{ \Tools::getDateFormat($subasta[0]->dfec_sub, 'Y-m-d H:i:s', 'd/m/Y') }} {{ trans("$theme-app.global.to_the") }} {{ \Tools::getDateFormat($subasta[0]->hfec_sub, 'Y-m-d H:i:s', 'd/m/Y') }}
							</h4>
						</a>
					</div>

					<div id="{{ $key_sub }}"
						class="panel-collapse collapse">
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-striped table-custom" style="width:100%">
									<thead>
										<tr>
											<th class="no-sort sorting_disabled"> </th>
											<th class="hidden">Index</th>
											<th>{{ trans("$theme-app.user_panel.reference") }}</th>
											<th>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</th>
											<th>{{ trans(\Config::get('app.theme').'-app.user_panel.status') }}</th>
											<th>{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</th>
											<th>{{ trans("$theme-app.user_panel.bids_numbers") }}</th>
											<th>{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</th>
										</tr>
									</thead>
									<tbody>

										@php
										$totalSalida = 0;
										$totalMaxPuja = 0;
										@endphp

										@foreach($subasta->sortBy('lin_hces1')->sortByDesc('num_hces1') as $lote)

										@php
										$cerrado = $lote->cerrado_asigl0 == 'S';
										if($cerrado && empty($lote->implic_hces1)){
											continue;
										}

										$url_friendly = !empty($lote->webfriend_hces1) ? $lote->webfriend_hces1 : str_slug($lote->titulo_hces1);
										$url_friendly = \Routing::translateSeo('lote').$lote->cod_sub."-".str_slug($lote->name).'-'.$lote->id_auc_sessions."/".$lote->ref_asigl0.'-'.$lote->num_hces1.'-'.$url_friendly;
										$hay_pujas = !empty(count($lote->pujas));
										$maxPuja = !empty($lote->implic_hces1 && $hay_pujas) ? $lote->implic_hces1 : (new App\Models\Subasta())->sobre_puja_orden($lote->impsalhces_asigl0, $lote->max_order ?? 0, 0);
										$maxPujaFormat = \Tools::moneyFormat($maxPuja ?? 0, '€');
										$devuelto = ($lote->fac_hces1 == 'D' || $lote->fac_hces1 == 'R' || $lote->cerrado_asigl0 == 'D');
										$desadjudicado = $lote->desadju_asigl0 == 'S';

										$totalSalida += $lote->impsalhces_asigl0;
										$totalMaxPuja += $maxPuja;
										@endphp

										<tr>
											<td>
												<a href="{{$url_friendly}}"><img src="{{ \Tools::url_img("lote_small", $lote->num_hces1, $lote->lin_hces1) }}" height="42"></a>
											</td>
											<td class="hidden">{{$loop->index}}</td>
											<td>{{$lote->num_hces1}} / {{ $lote->lin_hces1 }}</td>
											<td>{{$lote->ref_asigl0}}</td>

											<td>
												@if(strtotime($lote->end) < time() && $hay_pujas)
													{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}

												{{-- @elseif($cerrado && empty($lote->implic_hces1))
													{{ trans(\Config::get('app.theme').'-app.user_panel.closed') }} --}}

												@elseif(strtotime($lote->orders_start) > time())
													{{ trans(\Config::get('app.theme').'-app.user_panel.soon') }}

												@elseif(strtotime($lote->orders_start) < time())
														{{ trans(\Config::get('app.theme').'-app.sheet_tr.in_auction') }}
												@endif
											</td>

											<td>{{ Tools::moneyFormat($lote->impsalhces_asigl0, '€') }} </td>

											<td>{{ $lote->pujas->count() + ($lote->orders ?? 0) }}</td>

											<td>{{ $maxPujaFormat }}</td>

										</tr>
											@endforeach
									</tbody>
									<tfoot>
										<tr>
											<th colspan="2">{{ trans("$theme-app.user_panel.totals") }}</th>
											<th></th>
											<th></th>
											<th>{{ Tools::moneyFormat($totalSalida, '€') }}</th>
											<th></th>
											<th>{{ Tools::moneyFormat($totalMaxPuja, '€') }}</th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>

@stop
