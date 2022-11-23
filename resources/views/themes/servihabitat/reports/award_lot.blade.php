@extends('reports.layout.layout')
@section('content')
<div>
	<h1>{{$reportTitle}}</h1>

	<p>{!! trans("$theme-app.reports.content_lot_award", [
		'prop' => $prop, 'lot' => $lot, 'award' => $award, 'imp' => $imp]) !!}</p>

</div>
@stop
