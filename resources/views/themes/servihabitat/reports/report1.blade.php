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
					<td class="{{$key}}">{{ $propiedad }}</td>
				@endforeach
			</tr>
			@endforeach

		</tbody>
	</table>
	@endif
</div>


@stop
