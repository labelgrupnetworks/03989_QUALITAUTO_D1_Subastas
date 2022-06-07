@extends('reports.layout.layout')


@section('content')

<div>
	<h1>{{ trans("$theme-app.reports.lot_report") }}</h1>

	@if (!empty($lote))
	<table class="table-bordered" style="width: 100%">
		<tr>
			<td class="td-title">{{ trans("$theme-app.reports.prop_hces1") }}</td>
			<td colspan="3">{{ $lote->rsoc_cli ?? '' }}</td>
		</tr>
		<tr>
			<td class="td-title">{{ trans("$theme-app.reports.auction_code") }}</td>
			<td>{{ $lote->sub_asigl0 }}</td>

			<td class="td-title">{{ trans("$theme-app.reports.lot_ref") }}</td>
			<td>{{ $lote->ref_asigl0 }}</td>
		</tr>
		<tr>
			<td class="td-title">{{ trans("$theme-app.reports.lot_name") }}</td>
			<td colspan="3">{{ $lote->descweb_hces1 }}</td>
		</tr>
		<tr>
			<td class="td-title">{{ trans("$theme-app.reports.date_start") }}</td>
			@if ($lote->tipo_sub = 'W')
				<td>{{ Tools::getDateFormat($lote->orders_start, 'Y-m-d H:i:s', 'd/m/Y H:i:s') }}</td>
			@else
				<td>{{ Tools::getDateFormat($lote->fini_asigl0, 'Y-m-d H:i:s', 'd/m/Y') }} {{ $lote->hini_asigl0 }}</td>
			@endif


			<td class="td-title">{{ trans("$theme-app.reports.date_end") }}</td>
			@if ($lote->tipo_sub = 'W')
				<td>{{ Tools::getDateFormat($lote->orders_end, 'Y-m-d H:i:s', 'd/m/Y H:i:s') }}</td>
			@else
				<td>{{ Tools::getDateFormat($lote->ffin_asigl0, 'Y-m-d H:i:s', 'd/m/Y') }} {{ $lote->hfin_asigl0 }}</td>
			@endif

		</tr>
	</table>
	@endif

	@if (!empty($lote) && $lote->tipo_sub != 'W')
	<h2>{{ trans("$theme-app.reports.bids") }}</h2>

	<table class="table-bordered  table-content" style="width: 100%; max-width: 100%;">

		<thead>
			<tr>
				<th>{{ trans("$theme-app.reports.licit") }}</th>
				<th>{{ trans("$theme-app.reports.cli_name") }}</th>
				<th>{{ trans("$theme-app.reports.imp_asigl1") }}</th>
				<th>{{ trans("$theme-app.reports.bid_date") }}</th>
			</tr>
		</thead>

		<tbody>

			@forelse ($pujas as $puja)

			<tr>
				<td>{{$puja->licit . ' - ' . $puja->cod_cli}}</td>
				<td>{{ $puja->nom_cli }}</td>
				<td>{{ Tools::moneyFormat($puja->imp, '€') }}</td>
				<td>{{ Tools::getDateFormat($puja->fecha, 'Y-m-d H:i:s', 'd/m/Y H:i:s') }}</td>
			</tr>

			@empty

			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>

			@endforelse

		</tbody>
	</table>
	@endif

	<h2>{{ trans("$theme-app.reports.offers") }}</h2>

	<table class="table-bordered  table-content" style="width: 100%; max-width: 100%;">

		<thead>
			<tr>
				<th>{{ trans("$theme-app.reports.licit") }}</th>
				<th>{{ trans("$theme-app.reports.cli_name") }}</th>
				<th>{{ trans("$theme-app.reports.imp_asigl1") }}</th>
				<th>{{ trans("$theme-app.reports.bid_date") }}</th>
			</tr>
		</thead>

		<tbody>

			@forelse ($ordenes as $orden)

			<tr>
				<td>{{ $orden->licit . ' - ' .$orden->cod_cli }}</td>
				<td>{{ $orden->nom_cli }}</td>
				<td>{{ Tools::moneyFormat($orden->imp, '€')  }}</td>
				<td>{{ Tools::getDateFormat($orden->fecha, 'Y-m-d H:i:s', 'd/m/Y H:i:s') }}</td>
			</tr>

			@empty

				<td></td>
				<td></td>
				<td></td>
				<td></td>

			@endforelse

		</tbody>
	</table>

	@if(!empty($historicos))
	<h2>{{ trans("$theme-app.reports.history_offers") }}</h2>
	<table class="table-bordered  table-content" style="width: 100%; max-width: 100%;">

		<thead>
			<tr>
				<th>{{ trans("$theme-app.reports.licit") }}</th>
				<th>{{ trans("$theme-app.reports.cli_name") }}</th>
				<th>{{ trans("$theme-app.reports.imp_asigl1") }}</th>
				<th>{{ trans("$theme-app.reports.bid_date") }}</th>
				<th>{{ trans("$theme-app.reports.state") }}</th>
			</tr>
		</thead>

		<tbody>

			@forelse ($historicos as $historico)

			<tr>
				<td>{{ $historico->licit . ' - ' .$historico->cod_cli }}</td>
				<td>{{ $historico->nom_cli }}</td>
				<td>{{ Tools::moneyFormat($historico->imp, '€')  }}</td>
				<td>{{ Tools::getDateFormat($historico->fecha, 'Y-m-d H:i:s', 'd/m/Y H:i:s') }}</td>
				<td>{{ $historico->is_deleted ? trans("$theme-app.reports.is_deleted") : '' }} </td>
			</tr>

			@empty

				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>

			@endforelse

		</tbody>
	</table>
	@endif

</div>


@stop
