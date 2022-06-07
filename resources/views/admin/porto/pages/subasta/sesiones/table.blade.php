<a href="/admin/sesion/nuevo/{{$cod_sub}}" class="btn btn-success" style="float:right">Nueva sesión</a>

				<h4>Sesiones</h4>
				<br>
				<table id="tablePujas" class="table table-striped table-bordered dataTable hover" style="width:100%">
				<thead>
					<th>ID</th>
					<th>Nombre</th>
					<th>Empieza</th>
					<th>Acaba</th>
					<th>Inicio ordenes</th>
					<th>Fin ordenes</th>
					<th>L. inicial</th>
					<th>L. final</th>
					<th>Estado</th>
					<th>Opciones</th>
				</thead>
				<tbody>
				@foreach($sesiones as $k => $item)
					<tr id="{!! $item->id_auc_sessions !!}">
						<td align="center">{!! $item->id_auc_sessions !!}</td>
						<td align="center">{!! $item->name !!}</td>
						<td align="right">{!! $item->start !!}</td>
						<td align="right">{!! $item->end !!}</td>
						<td align="center">{!! $item->orders_start !!}</td>
						<td align="center">{!! $item->orders_end !!}</td>
						<td align="center">{!! $item->init_lot !!}</td>
						<td align="center">{!! $item->end_lot !!}</td>
						<td align="center">{!! $item->status !!}</td>
						<td>
							<a href="/admin/sesion/edit/{!! $item->auction !!}/{!! $item->reference !!}" class="btn btn-primary">Editar</a>
							@if ($item->reference != '001')
							<a href="javascript:borrarSesion('{!! $item->auction !!}','{!! $item->reference !!}', '{!! $item->id_auc_sessions !!}');"
								class="btn btn-danger">Borrar</a>
							@endif

						</td>
					</tr>
				@endforeach
				</tbody>
				</table>
