@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<div class="color-letter">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 text-center">
				<h1 class="titlePage">{{ trans("$theme-app.user_panel.my_sale_title") }}</h1>
			</div>
		</div>
	</div>
</div>

<div class="account-user color-letter panel-user sales-page">
	<div class="container container-75">

		<div class="row">
			<div class="col-xs-12 col-md-7">
				<div class="user-account-title-content">
					<div class="user-account-menu-title extra-account mb-3">
						Filtros
					</div>
				</div>

				<form action="">
					<div class="filters-group d-flex align-items-end flex-wrap" style="gap: 20px">
						<div class="form-group">
							<label for="fromDate">Desde:</label>
							<input class="form-control" type="date" name="from-date" id="fromDate" value="{{ request('from-date', null) }}">
						</div>

						<div class="form-group">
							<label for="toDate">Hasta:</label>
							<input class="form-control" type="date" name="to-date" id="toDate" value="{{ request('to-date', null) }}">
						</div>

						<div class="form-group">
							<button type="submit" class="btn default-btn">Filtrar</button>
						</div>
						<div class="form-group">
							<a href="{{ route('panel.sales', ['lang' => config('app.locale')]) }}" class="btn default-btn">Limpiar</a>
						</div>
					</div>
				</form>

			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<div class="user-account-title-content">
					<div class="user-account-menu-title extra-account mb-3">
						{{ trans(\Config::get('app.theme').'-app.user_panel.my_sale_title_detail') }}
					</div>
				</div>

				@php
					use Illuminate\Support\Carbon;
					$lotes = $subastas->flatten();
					$totales = $lotes
						->groupBy(function($lote) {
							return \Tools::getDateFormat($lote->start, 'Y-m-d H:i:s', 'm-Y');
						})
						->each(function($total, $key) {
							Carbon::setLocale(config('app.locale'));
							$date = Carbon::createFromFormat('m-Y', $key);
							$total->dateFormat = $date->isoFormat('MMMM YYYY');

							$total->offers = $total->map(function($lote) {
								return !empty($lote->implic_hces1) ? 1 : 0;
							})->sum();
						});

					$usersWithDeposit = App\Models\V5\FgDeposito::getAllUsersWithValidDepositInAuctions($subastas->keys());
				@endphp
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-condensed table-to-card" id="auctions_table">

							<thead>
								<tr>
									<th data-card-title class="col-xs-1"></th>
									<th class="col-xs-2">
										PROCESO
									</th>

									<th class="col-xs-2">
										FECHA INICIO COMERCIALIZACIÓN
									</th>

									<th class="col-xs-2">
										FECHA FIN DE COMERCIALIZACIÓN
									</th>

									<th class="col-xs-2 text-uppercase">
										{{ trans("$theme-app.user_panel.licits") }}
									</th>

									<th class="col-xs-2">
										OFERTAS PRESENTADAS
									</th>

									<th class="col-xs-2">
										Nº OFERTAS CERRADAS
									</th>

									<th class="col-xs-2">
										OFERTAS CERRADAS
									</th>
								</tr>
							</thead>

							<tbody>

								@foreach($lotes as $lote)

								@php
								$url_friendly = str_slug($lote->desc_hces1);
								$url_friendly = \Routing::translateSeo('lote').$lote->cod_sub."-".str_slug($lote->name).'-'.$lote->id_auc_sessions."/".$lote->ref_asigl0.'-'.$lote->num_hces1.'-'.$url_friendly;
								$depositInLot = $usersWithDeposit->where('sub_deposito', $lote->cod_sub)->where('ref_deposito', $lote->ref_asigl0)->count();
								$depositInAuction = $usersWithDeposit->where('sub_deposito', $lote->cod_sub)->where('ref_deposito', null)->count();
								$isFinished = strtotime($lote->end) < time() && !empty($lote->implic_hces1);
								@endphp

								<tr>
									<td>
										<a href="{{$url_friendly}}">
											<img src="{{ \Tools::url_img("lote_small", $lote->num_hces1, $lote->lin_hces1) }}" class="img-responsive">
										</a>
									</td>
									<td>
										<span class="max-line-2">
											{!! $lote->descweb_hces1 !!}
										</span>
									</td>

									<td>
										{{ \Tools::getDateFormat($lote->start, 'Y-m-d H:i:s', 'd/m/Y H:i') }}
									</td>
									<td>
										{{ \Tools::getDateFormat($lote->end, 'Y-m-d H:i:s', 'd/m/Y H:i') }}
									</td>

									<td>
										{{ $depositInLot + $depositInAuction /* $lote->licits_orders */ }}
									</td>

									<td>{{ $lote->orders ?? 0 }}</td>

									<td>
										{{ $isFinished ? 1 : 0 }}
									</td>

									<td>
										{{ Tools::moneyFormat($lote->implic_hces1, '€') }}
									</td>

								</tr>

								@endforeach

							</tbody>

						</table>
					</div>
				</div>


				<div class="row">
					<div class="col-xs-12">

						<div class="user-account-menu-title extra-account mb-3">
							Totales por mes
						</div>

						<table class="table table-condensed table-to-card" id="totals_month_table">

							<thead>

								<tr>
									<th><b>MES</b></th>
									<th><b>NÚMERO DE PROCESOS</b></th>
									<th><b>OFERTAS PRESENTADAS</b></th>
									<th><b>Nº OFERTAS CERRADAS</b></th>
									<th><b>OFERTAS CERRADAS</b></th>
								</tr>

							</thead>

							<tbody>
								@foreach ($totales as $date => $total)
								<tr>
									<td class="text-capitalize">
										{{ $total->dateFormat /* \Tools::getDateFormat($date, 'm-Y', 'F Y') */ }}
									</td>
									<td>{{ $total->count() }}</td>
									<td>{{ $total->sum('orders') }}</td>
									<td>{{ $total->offers }}</td>
									<td>{{ Tools::moneyFormat($total->sum('implic_hces1'), '€') }}</td>
								</tr>
								@endforeach
							</tbody>

						</table>

					</div>

				</div>

				<div class="row">
					<div class="col-xs-12">

						<div class="user-account-menu-title extra-account mb-3">
							Totales
						</div>

						<table class="table table-condensed table-to-card" id="totals_table">

							<thead>
								<tr>
									<th><b>NÚMERO DE PROCESOS</b></th>
									<th><b>OFERTAS PRESENTADAS</b></th>
									<th><b>OFERTAS CERRADAS</b></th>
								</tr>
							</thead>

							<tbody>
								<tr>
									<td>{{ $totales->map(function($total) { return $total->count(); })->sum() }}</td>
									<td>{{ $totales->map(function($total) { return $total->sum('orders'); })->sum() }}</td>
									<td>{{ Tools::moneyFormat($totales->map(function($total) { return $total->sum('implic_hces1'); })->sum(), '€') }}</td>
								</tr>
							</tbody>

						</table>

					</div>
				</div>

			</div>

		</div>
	</div>
</div>
@stop

@push('scripts')
<script src="{{ URL::asset('js/tableToCards.js') }}"></script>
@endpush
