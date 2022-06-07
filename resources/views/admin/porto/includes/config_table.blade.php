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
 *
 * @todo pendiente
 * - añadir campos checkeados por defecto.
 */
?>
{{-- <a class="btn btn-default btn-sm"><i style="cursor: pointer" class="fa fa-cog" aria-hidden="true" data-toggle="modal" data-target="#modal_config_{{$id}}"></i></a> --}}
<a class="btn btn-default btn-sm" aria-hidden="true" data-toggle="modal" data-target="#modal_config_{{$id}}"><i style="cursor: pointer" class="fa fa-cog"></i> Configurar Tabla</a>

<div class="modal fade text-left" id="modal_config_{{$id}}" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Columnas de tabla</h4>
			</div>

			<div class="modal-body">
				<form id="table_config_{{$id}}">
					<div class="form-group row">
						<div class="col-xs-12">

							@foreach ($params as $param => $check)

							<div class="form-check">
								<input class="form-check-input" type="checkbox"
									name="check_{{$param}}" @if($check) checked @endif>
								<label class="form-check-label" for="check_{{$param}}">
									{{ trans_choice("admin-app.fields.$param", '') }}
								</label>
							</div>

							@endforeach

						</div>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" form="table_config_{{$id}}"
					class="btn btn-success">Aceptar</button>
			</div>

		</div>
	</div>
</div>

<script>
	window.addEventListener('load', function(){
		tableConfig.addTable('{{$id}}', @json(array_keys($params)));
	});
</script>
