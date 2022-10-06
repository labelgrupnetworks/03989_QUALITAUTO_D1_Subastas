@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<div class="color-letter">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 text-center">
				<h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h1>
			</div>
		</div>
	</div>
</div>

<div class="account-user color-letter panel-user">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-3 col-lg-3 account-user-menu">
				@php
				$tab = (request()->has('totals')) ? 'sales_totals' : 'sales';
				@endphp
				@include('pages.panel.menu_micuenta')
			</div>
			<div class="col-xs-12 col-md-9 col-lg-9 ">

				<div class="user-account-title-content @if(request()->has('totals')) hidden @endif">
					<div class="user-account-menu-title mb-3">
						{{ trans(\Config::get('app.theme').'-app.user_panel.my_lots') }}
					</div>
				</div>

				<table class="table @if(request()->has('totals')) hidden @endif">

					<?php
						$totalLotes = 0;
						$totalPsalida = 0;
						$totalPremate = 0;
						$lotsSold = 0;
						$lotsInAuction = 0;
					?>

					@foreach($subastas as $cod_sub => $lotes)

					<tr>
						<td colspan="12" data-toggle="collapse"
							class="accordion-toggle title-sub-list accordion-{{$cod_sub}}" data-target="#{{$cod_sub}}">
							<div class="d-flex align-items-center">
								<span class="w-100">{{$lotes[0]->name}}</span>
								<i style="float: right; font-size: 14px;" class="fas fa-plus"></i>
							</div>
						</td>
					</tr>

					<tr>
						<td colspan="12" class="hiddenRow">
							<div class="accordian-body collapse" id="{{$cod_sub}}">
								<table class="table table-condensed table-to-card" id="{{$cod_sub}}_table">

									<thead style="background-color: #f8f9fa">
										<tr>
											<th data-card-title class="col-xs-1"></th>
											<th class="col-xs-4">
												{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</th>
											<th class="col-xs-2">
												{{ trans(\Config::get('app.theme').'-app.user_panel.status') }}</th>
											<th class="col-xs-2">
												{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</th>
											<th class="col-xs-2">
												{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}
											</th>
										</tr>
									</thead>

									<tbody>

										@foreach($lotes as $lote)
										@php
										$url_friendly = str_slug($lote->desc_hces1);
										$url_friendly =
										\Routing::translateSeo('lote').$lote->cod_sub."-".str_slug($lote->name).'-'.$lote->id_auc_sessions."/".$lote->ref_asigl0.'-'.$lote->num_hces1.'-'.$url_friendly;
										$hay_pujas = !empty($lote->implic_hces1)? true : false;
										$maxPuja = \Tools::moneyFormat($lote->implic_hces1);
										$cerrado = $lote->cerrado_asigl0 == 'S'? true : false;
										$devuelto = ($lote->fac_hces1 == 'D' || $lote->fac_hces1 == 'R' ||
										$lote->cerrado_asigl0 == 'D') ? true : false;
										$desadjudicado = $lote->desadju_asigl0 == 'S'? true : false;

										$totalLotes++;
										$totalPsalida += $lote->impsalhces_asigl0;
										$totalPremate += $lote->implic_hces1;
										@endphp

										<tr>
											<td>
												<a onclick="javascript:document.location='{{$url_friendly}}';"><img
														src="{{ \Tools::url_img("lote_small", $lote->num_hces1, $lote->lin_hces1) }}"
														class="img-responsive"></a>
											</td>
											<td>
												<span>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}
													{{$lote->ref_asigl0}}</span>
												<p class="td-desciption">
													{!!$lote->desc_hces1!!}</p>
											</td>

											<td>
												@if($hay_pujas)
													{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
													@php
													$lotsSold++;
													@endphp

												@elseif(strtotime($lote->end) < time())
													{{ trans(\Config::get('app.theme').'-app.user_panel.closed') }}

												@elseif(strtotime($lote->orders_start) > time())
													{{ trans(\Config::get('app.theme').'-app.user_panel.soon') }}

												@elseif(strtotime($lote->orders_start) < time())
														{{ trans(\Config::get('app.theme').'-app.sheet_tr.in_auction') }}
													@php
													$lotsInAuction++;
													@endphp

												@endif
											</td>

											<td>
												{{$lote->impsalhces_asigl0 }} €
											</td>

											<td>{{$lote->implic_hces1 }} €</td>

										</tr>

										@endforeach

									</tbody>

								</table>
							</div>
						</td>
					</tr>

					{{-- con separacion entre subastas --}}
					{{--<tr class="separator" style="height: 30px"></tr>--}}


					@endforeach

				</table>

				<div class="row @if(!request()->has('totals')) hidden @endif">
					<div class="col-xs-12">

						<div class="user-account-menu-title mb-3">
							{{ /*trans(\Config::get('app.theme').'-app.user_panel.orders')*/ "Totales" }}</div>

						<table class="table table-condensed table-to-card" id="totals_table">

							<thead>

								<tr>
									<th><b>{{ trans(\Config::get('app.theme').'-app.lot_list.lots') }}</b></th>
									<th><b>{{ trans(\Config::get('app.theme').'-app.lot_list.award_filter') }}</b></th>
									<th><b>{{ trans(\Config::get('app.theme').'-app.sheet_tr.in_auction') }}</b></th>
									<th><b>{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</b></th>
									<th><b>{{ trans(\Config::get('app.theme').'-app.user_panel.price') }}</b></th>
								</tr>

							</thead>

							<tbody>
								<tr>
									<td>{{ $totalLotes }}</td>
									<td>{{ $lotsSold }}</td>
									<td>{{ $lotsInAuction }}</td>
									<td>{{ \Tools::moneyFormat($totalPsalida) }} €</td>
									<td>{{ \Tools::moneyFormat($totalPremate) }} €</td>
								</tr>
							</tbody>

						</table>

						{{--
						<div class="row text-center">

							<div class="col-xs-6 col-sm-4">
								<h3><b>{{ trans(\Config::get('app.theme').'-app.lot_list.lots') }}</b></h3>
								<h3>{{ $totalLotes }}</h3>
							</div>

							<div class="col-xs-6 col-sm-4">
								<h3><b>{{ trans(\Config::get('app.theme').'-app.lot_list.award_filter') }}</b></h3>
								<h3>{{ $lotsSold }}</h3>
							</div>

							<div class="col-xs-6 col-sm-4">
								<h3><b>{{ trans(\Config::get('app.theme').'-app.sheet_tr.in_auction') }}</b></h3>
								<h3>{{ $lotsSold }}</h3>
							</div>

							<div class="col-xs-6 col-sm-4 mt-2">
								<h3><b>{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</b></h3>
								<h3>{{ \Tools::moneyFormat($totalPsalida) }} €</h3>
							</div>

							<div class="col-xs-6 col-sm-4 mt-2">
								<h3><b>{{ trans(\Config::get('app.theme').'-app.user_panel.price') }}</b></h3>
								<h3>{{ \Tools::moneyFormat($totalPremate) }} €</h3>
							</div>

						</div>
						--}}

					</div>

				</div>

			</div>

		</div>
	</div>
</div>

<script>
	$('.table').on('hide.bs.collapse', function (e) {
		$(`.accordion-${e.target.id} i`).removeClass('fa-minus').addClass('fa-plus');
	})
	$('.table').on('show.bs.collapse', function (e) {
		$(`.accordion-${e.target.id} i`).removeClass('fa-plus').addClass('fa-minus');
	})
</script>
@stop

@push('scripts')
	<script src="{{ URL::asset('js/tableToCards.js') }}"></script>
@endpush
