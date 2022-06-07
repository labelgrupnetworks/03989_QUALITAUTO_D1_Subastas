
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

                        <a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
                </div>
        </header>

	@csrf

	<a href="/admin/subasta/nuevo" class="btn btn-primary right">Nueva subasta</a>
	<h1>Subastas</h1>
	<br>

	<table id="tableClientes" class="table table-striped table-bordered dataTable hover" style="width:100%">
		<thead>
		<tr>
			<th>ID</th>
			<th>Nombre</th>
			<th>Fechas</th>
			<th>Opciones</th>
		</tr>
		</thead>
		<tbody>
	@foreach ($subastas as $subasta)

		<tr id="fila{{$subasta->cod_sub}}">
			<td>
				{{$subasta->cod_sub}}
			</td>
			<td>
				{{$subasta->des_sub}}
			</td>
			<td>
				{{\Tools::Construir_fecha($subasta->dfec_sub)}}&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;{{\Tools::Construir_fecha($subasta->hfec_sub)}}
			</td>
			<td>
				<a href="/admin/subasta/edit/{{$subasta->cod_sub}}" class="btn btn-primary">Editar</a>
				<a href="javascript:borrarSubasta('{{$subasta->cod_sub}}');" class="btn btn-danger">Borrar</a>
			</td>
		</tr>

	@endforeach

		</tbody>
	</table>


@stop
