@extends('reports.layout.layout')


@section('content')

<div>
	<h1>{{$reportTitle}}</h1>

	@if(!empty($tablaSubasta))
	<table class="table-bordered" style="width: 100%">

		<tbody>

			@while ($value = current($tablaSubasta))

			<tr>
				<td class="td-title">{{key($tablaSubasta)}}</td>
				<td>{{$value}}</td>

				@php
				next($tablaSubasta);
				$value = current($tablaSubasta);
				@endphp

				@if(!empty(key($tablaSubasta)))
				<td class="td-title">{{key($tablaSubasta)}}</td>
				<td>{{$value}}</td>
					@php
					next($tablaSubasta);
					@endphp
				@else
				<td class="td-title"></td>
				<td></td>
				@endif

			</tr>
			@endwhile

		</tbody>
	</table>
	@endif

	@if(!empty($content))
	<p>{!! $content !!}</p>
	@endif

	@if(!empty($awards))
	<h2>{{ $titleTable }}</h2>

	<table class="table-bordered table-content" style="width: 100%; max-width: 100%;">

		<thead>
			<tr>
				<th>{{ trans("$theme-app.reports.lot_name") }}</th>
				<th>{{ trans("$theme-app.reports.licit") }}</th>
				<th>{{ trans("$theme-app.reports.cli_name") }}</th>
				<th>{{ trans("$theme-app.reports.imp_asigl1") }}</th>
				<th>{{ trans("$theme-app.reports.bid_date") }}</th>
			</tr>
		</thead>

		<tbody>

			@foreach ($awards as $award)
			<tr>
				<td>{{ $award['ref'] }}</td>

				@if($award['is_award'])
				<td>{{ $award['licit'] }}</td>
				<td class="colummn-name">{{ $award['name'] }}</td>
				<td>{{ $award['import'] }}</td>
				<td>{{ $award['date'] }}</td>

				@else
				<td colspan="4">{{ mb_strtoupper(trans("$theme-app.emails.asunto_lote_no_adjudicado")) }}</td>
				@endif
			</tr>
			@endforeach
		</tbody>
	</table>
	@endif
</div>


@stop
