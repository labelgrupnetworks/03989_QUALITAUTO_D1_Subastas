<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>ID</th>
			<th>Valor</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($featureValues as $value)
			<tr>
				<td>{{ $value->id_caracteristicas_value }}</td>
				<td>{{ $value->value_caracteristicas_value }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
