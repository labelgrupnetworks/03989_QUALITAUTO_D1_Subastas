@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	<header class="page-header">
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="/admin">
						<i class="fa fa-home"></i>
					</a>
				</li>

			</ol>

			<a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>

	<a href="/admin/cliente/nuevo" class="btn btn-primary right">Nuevo</a>

	<a href="{{ route('cliente.export') }}" class="btn btn-primary"
		style="float:right; margin: 0px 5px">{{ trans('admin-app.button.export') }}</a>

	<h1>Clientes</h1>
	<br>

	@csrf

	<table id="tableClientes" class="table table-striped table-bordered dataTable hover" style="width:100%">
		<thead>
			<tr>
				<th>ID</th>
				<th>Id Origen</th>
				<th>Estado</th>
				<th>Nombre</th>
				<th>Opciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($clientes as $cliente)

			<tr id="cliente{{$cliente->cod_cli}}">
				<td>
					{{$cliente->cod_cli}}
				</td>
				<td>
					{{ $cliente->cod2_cli }}
				</td>
				<td>
					@if ($cliente->baja_tmp_cli == "N")
					<span class="badge badge-success">Activo</span>
					@elseif ($cliente->baja_tmp_cli == "S")
					<span class="badge badge-success">Bloqueado</span>
					@elseif ($cliente->baja_tmp_cli == "A")
					<span class="badge badge-success">Pendiente API</span>
					@elseif ($cliente->baja_tmp_cli == "W")
					<span class="badge badge-success">Pendiente Email</span>
					@else
					<span class="badge badge-success">Rechazado</span>
					@endif
				</td>
				<td>
					{{$cliente->nom_cli}} ({{$cliente->rsoc_cli}} )
				</td>
				<td>
					<a href="/admin/cliente/edit/{{$cliente->cod_cli}}" class="btn btn-primary">Editar</a>
					@if ($cliente->baja_tmp_cli == "N")
					<a href="javascript:bajaDeCliente('{{$cliente->cod_cli}}',0)" class="btn btn-danger">Desactivar</a>
					@else
					<a href="javascript:bajaDeCliente('{{$cliente->cod_cli}}',1)" class="btn btn-success">Activar</a>
					@endif
				</td>
			</tr>

			@endforeach
		</tbody>
	</table>

	@stop
