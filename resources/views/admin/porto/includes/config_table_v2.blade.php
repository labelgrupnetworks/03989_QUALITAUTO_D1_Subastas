<?
/**
 * Permite visualizar u ocultar columnas de una tabla y guardar el resultado en
 * el navegador
 *
 * @info Para que funcione correctamente, es necesario que tanto th's como td's de la tabla
 * tengan como clase los identificadores contenidos en el array $params
 *
 * @param string $id identificador de modal y tabla
 * @param array $params campos editables de la talba
 * @param array $formulario de filtros
 *
 * @todo pendiente
 * - añadir campos checkeados por defecto.
 */
?>
<div class="btn-group dropright">
	<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
		aria-expanded="false">
		<i style="cursor: pointer" class="fa fa-filter"></i> Filtros <span class="caret"></span>
	</button>
	<ul class="dropdown-menu custom-dropdown" id="midropdown">


		<li class="dropdown-header">Filtros</li>
		<form class="form-group" action="">

			<li class="d-flex">
				<input type="submit" class="btn btn-info w-100" value="{{ trans("admin-app.button.search") }}">
				<a href="{{ route(Route::current()->getName()) }}" class="btn btn-warning w-100">{{ trans("admin-app.button.restart") }}</a>
			</li>

			<input type="hidden" name="order" value="{{ request('order', 'cod_cli') }}">
			<input type="hidden" name="order_dir" value="{{ request('order_dir', 'desc') }}">

			@foreach ($formulario as $param => $input)
			<li class="">
				<label>{{ trans("admin-app.fields.$param") }}</label>
				{!! $input ?? '' !!}
			</li>
			@endforeach
		</form>

		<li role="separator" class="divider"></li>

		<li class="dropdown-header">Columnas</li>
		<div id="modal_config_{{$id}}">
			<form id="table_config_{{$id}}">
				@foreach ($params as $param => $check)

				<li class="form-check">
					<input class="form-check-input" type="checkbox" name="check_{{$param}}" @if($check) checked @endif>
					<label class="form-check-label" for="check_{{$param}}">
						{{ trans("admin-app.fields.$param") }}
					</label>
				</li>

				@endforeach
				<li role="separator" class="divider"></li>
			</form>
		</div>

	</ul>
</div>


<script>
	let test;
	$('#midropdown').on('click', function (e) {
  		e.stopPropagation();
		if(e.target.type == 'submit'){
			e.target.click();
		}
	});

	window.addEventListener('load', function(){
		tableConfig.addTable('{{$id}}', @json(array_keys($params)));
	});
</script>
