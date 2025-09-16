@extends('reports.layout.layout')
@section('content')
<div>
	<h1>{{$reportTitle}}</h1>

	@include('reports._tableSubasta')

	<p>{!! trans("$theme-app.reports.content_lot_award", [
		'prop' => $prop, 'lot' => $lot, 'award' => $award, 'imp' => Tools::moneyFormat($imp)]) !!}</p>

	@if(!empty($bidders))
		<p style="margin: 0;"><u>{{ trans("$theme-app.lot.bid_participants") }}</u>:</p>
		@foreach ($bidders as $bidder)

		@continue($bidder['ratio_asigl1mt'] == 0)

		<p>
			{!! trans("$theme-app.emails.multiple_bidder", [
				'name' => $bidder['nom_asigl1mt'] . ' ' . $bidder['apellido_asigl1mt'],
				'ratio' => $bidder['ratio_asigl1mt'],
				'value' => Tools::moneyFormat($imp * $bidder['ratio_asigl1mt'] / 100, "€", 2)]) !!}
		</p>
		@endforeach
	@endif

</div>
@stop
