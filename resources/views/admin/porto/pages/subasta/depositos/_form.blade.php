<div class="col-xs-12">
	<p>{!!trans("admin-app.help_fields.deposito")!!}</p>
</div>

<div class="col-xs-12 col-md-6">
	@foreach ($formulario as $field => $input)
	<label style="margin-top: 1rem" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
	<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
		data-toggle="tooltip" data-placement="right"
		data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
	{!! $input !!}
	@endforeach

	{!! FormLib::Hidden('fecha_deposito', 1, date("Y-m-d H:i:s")) !!}

	<input type="submit" class="btn btn-success" value="{{ trans("admin-app.button.save") }}"  style="margin-top: 1rem">
</div>

@if (Config::get('withRepresented', false))
    <script>

		$('[name=cli_deposito]').on('change', (event) => getRepresented(event.target.value));

		function getRepresented(id) {
			const select = $('[name=representado_deposito]');
			const url = `/admin/clientes/${id}/representados`;

			select.empty();
			select.append('<option value="0">Nadie</option>');

			$.ajax({
				url,
				type: 'GET',
				contentType: 'application/json',
				success: (response) => {
					const represented = response.representados;
					represented.forEach((item) => {
						select.append(`<option value="${item.id}">${item.nom_representados}</option>`);
					});
				}
			});
		}
    </script>
@endif
