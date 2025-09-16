@extends('reports.layout.layout')


@section('content')

<div>
	<h1>{{$reportTitle}}</h1>

	@include('reports._tableSubasta')

	@if(!empty($content))
	<p>{!! $content !!}</p>
	@endif

	@if(!empty($tableContent))
	<h2>{{ $titleTable }}</h2>

	<table class="table-bordered table-content" style="width: 100%; max-width: 100%;">

		<thead>
			<tr>
				@foreach (array_keys($tableContent[0]) as $propiedades)
					<th>{{ $propiedades }}</th>
				@endforeach
			</tr>
		</thead>

		<tbody>

			@foreach ($tableContent as $item)
			<tr>
				@foreach ($item as $key => $propiedad)
					@if($key == 'not_award' || $key == 'not_bids')
					<td colspan="4" class="{{$key}}">{{ $propiedad }}</td>
					@else
					<td class="{{$key}}">{{ $propiedad }}</td>
					@endif
				@endforeach
			</tr>
			@endforeach

		</tbody>
	</table>
	@endif
</div>


@stop
