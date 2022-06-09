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
	@if (!empty($id))
	<h1>Subasta {{$id}}</h1>
	@else
	<h1>Nueva subasta</h1>
	@endif


	<a href="/admin/subasta/" class="btn btn-primary right">Volver</a>

	<br><br>

	<form name="edit" id="edit" action="/admin/subasta/edit_run" method="post" class="col-11"
		enctype="multipart/form-data">
		{{csrf_field()}}

		<div class="row">
			<div class="col-xs-12 col-md-6 pb-3">
				<div class="row">
					<div class="col-xs-4 pt-2 text-right">
						<label>Código: </label>
					</div>
					<div class="col-xs-8">
						{!! $formulario['id'] !!}
						{!! $formulario['codigo'] !!}
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-2 text-right"></div>
			<div class="col-xs-12 col-md-2 text-right">

				<img src="{{\Tools::url_img_session('subasta_medium',$id,'001')}}" width="100%">
			</div>
		</div>

		<br>

		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="home-tab" data-toggle="tab" href="#general" role="tab"
					aria-controls="general" aria-selected="true">General</a>
			</li>
			@if (!empty($id))
			<li class="nav-item">
					<a class="nav-link" id="sesiones-tab" data-toggle="tab" href="#sesiones" role="tab" aria-controls="sesiones" aria-selected="false">Sesiones</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="lotes-tab" data-toggle="tab" href="#lotes" role="tab" aria-controls="lotes"
					aria-selected="false">Lotes</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="pujas-tab" data-toggle="tab" href="#pujas" role="tab" aria-controls="pujas"
					aria-selected="false">Pujas</a>
			</li>
			@if (!empty($formularioOrdenes))
			<li class="nav-item">
				<a class="nav-link" id="pujas-tab" data-toggle="tab" href="#ordenes" role="tab" aria-controls="ordenes"
					aria-selected="false">Ordenes</a>
			</li>
			@endif
			<li class="nav-item">
				<a class="nav-link" id="pujas-tab" data-toggle="tab" href="#adjudicaciones" role="tab" aria-controls="adjudicaciones"
					aria-selected="false">Adjudicaciones</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="pujas-tab" data-toggle="tab" href="#ganadores" role="tab"
					aria-controls="ganadores" aria-selected="false">Ganadores</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="ficheros-tab" data-toggle="tab" href="#ficheros" role="tab"
					aria-controls="ficheros" aria-selected="false">Ficheros</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="escalado-tab" data-toggle="tab" href="#escalado" role="tab"
					aria-controls="escalado" aria-selected="false">Escalado</a>
			</li>
			@endif
		</ul>
		<div class="tab-content" id="myTabContent">

			<div class="tab-pane fade active in" id="general" role="tabpanel" aria-labelledby="general-tab">
				@include('admin::pages.subasta.subasta.forms.general', ['fomularioGeneral' => $formularioGeneral]);
			</div>
	</form>

	@if (!empty($id))

			<div class="tab-pane fade" id="sesiones" role="tabpanel" aria-labelledby="sesiones-tab">
				@include('admin::pages.subasta.sesiones.table', ['sesiones' => $sesiones, 'cod_sub' => $id]);
			</div>

	<div class="tab-pane fade" id="lotes" role="tabpanel" aria-labelledby="lotes-tab">
		@include('admin::pages.subasta.lote.table', ['lotes' => $lotes, 'cod_sub' => $id, 'maxOrdenes' => $maxOrdenes, 'maxPujas' => $maxPujas, 'simbolos' => $simbolos]);
	</div>

	<div class="tab-pane fade" id="pujas" role="tabpanel" aria-labelledby="pujas-tab">
		<a href="{{ route('lote.export', ['cod_sub' => $id]) }}" class="btn btn-primary"
			style="float:right">{{ trans('admin-app.button.export') }}</a>
		<div class="row">
			<div class="col-12">
				<h4>Pujas</h4>
				<br>
				<table id="tablePujas" class="table table-striped table-bordered dataTable hover" style="width:100%">
					<thead>
						<th>Referencia</th>
						<th>Linea</th>
						<th>Licitador</th>
						<th>Fecha y hora</th>
						<th>Importe</th>
						<th></th>
					</thead>
					<tbody>
						@foreach($pujas as $k => $item)
						<?php //print_r($item);die;?>
						<tr id="puja---{{$item->lin_asigl1}}---{{$item->ref_asigl1}}">
							<td>{!! $item->ref_asigl1 !!}</td>
							<td>{!! $item->lin_asigl1 !!}</td>
							@if (isset($licitadores[$item->licit_asigl1]))
							<td>{!! $licitadores[$item->licit_asigl1]->rsoc_licit !!} ({{$item->licit_asigl1}})</td>
							@else
							<td>No se ha encontrado el licitador en la subasta ({!! $item->licit_asigl1 !!})</td>
							@endif
							<td>{!! \Tools::Construir_fecha($item->fec_asigl1) !!} {!! $item->hora_asigl1 !!}</td>
							<td>{!! $item->imp_asigl1 !!}</td>
							<td><a href="javascript:borrarPuja('{!! $item->ref_asigl1 !!}---{!! $item->lin_asigl1 !!}---{!!$id!!}---{!!$item->asigl0_aux??'NO'!!}');"
									class="btn btn-danger">Borrar</a></td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<div class="tab-pane fade" id="ordenes" role="tabpanel" aria-labelledby="pujas-tab">
		<div class="row">
			<div class="col-12">
			 	{!! $formularioOrdenes ?? '' !!}
			</div>
		</div>
	</div>

	<div class="tab-pane fade" id="adjudicaciones" role="tabpanel" aria-labelledby="pujas-tab">
		<div class="row">
			<div class="col-12">
			 	{!! $formularioAdjudicaciones !!}
			</div>
		</div>
	</div>

	<div class="tab-pane fade" id="ganadores" role="tabpanel" aria-labelledby="pujas-tab">
		<a href="{{ route('winners.export', ['cod_sub' => $id]) }}" class="btn btn-primary"
			style="float:right">{{ trans('admin-app.button.export') }}</a>
		<div class="row">
			<div class="col-12">
				<h4>Ganadores</h4>
				<br>
				<table id="tablePujas" class="table table-striped table-bordered dataTable hover" style="width:100%">
					<thead>
						<th>Referencia</th>
						<th>Id Cliente</th>
						<th>Licitador</th>
						<th>Fecha y hora</th>
						<th>Importe</th>
					</thead>
					<tbody>
						@foreach($ganadores as $k => $item)
						<?php //print_r($item);die;?>
						<tr id="ganador---{{$item->ref_csub}}">
							<td>{!! $item->ref_csub !!}</td>

							@if (\Config::get('app.external_id', 0))
							<td>{{ $item->cod2_cli }}</td>
							@else
							<td>{{ $item->clifac_csub }}</td>
							@endif

							<td>{!! $item->nom_cli !!} ( Licitador: {{$item->licit_csub}} )</td>-->
							<td>{!! \Tools::getDateFormat($item->fec_asigl1, 'Y-m-d H:i:s', 'd/m/Y H:i:s') !!}</td>
							<td>{!! $item->himp_csub !!}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="tab-pane fade" id="ficheros" role="tabpanel" aria-labelledby="ficheros-tab">
		<div class="row">
			<div class="col-12">
				<h4>Ficheros</h4>
				<br>
				<form name="subirfichero" action="/admin/subasta/ficherosSubasta/{{$id}}" method="POST"
					enctype="multipart/form-data">
					<div class="well row">
						<div class="col-xs-12 col-md-7">
							@csrf
							<b>Selecciona fichero</b>
							<input type="file" name="fichero_adjunto" id="fichero_adjunto" class="form-control">
						</div>
						<div class="col-xs-12 col-md-5">
							<br>
							<input type="submit" class="btn btn-primary">
						</div>
					</div>
				</form>
			</div>
		</div>

		<br>
		<hr><br>

		<div class="row">
			@foreach ($archivos as $k => $item)
			<div id="fila{{$k}}" class="col-12 col-sm-6">
				<div class="row">
					<div class="col-2 text-center">
						<i class="fa fa-file fa-2x"></i>
					</div>
					<div class="col-10 col-sm-8">
						<a href="./files/{{$id}}/{{$item}}" target="_blank">{{$item}}</a>
					</div>
					<div class="col-2 text-center">
						<a href="javascript:borrarFichero('{{$id}}','{{$item}}','{{$k}}');" class="btn btn-danger">
							<i class="fa fa-times"></i>
						</a>
					</div>
				</div>
			</div>

			@endforeach
		</div>

	</div>


	<div class="tab-pane fade" id="escalado" role="tabpanel" aria-labelledby="escalado-tab">
		<div class="row">
			<div class="col-12">

				<form id="formEscalado" name="escalado" method="post" action="{{ route('guardarEscaladoSubastas') }}">
					@csrf
					<input type="hidden" name="sub" value="{{$id}}">
					<h4>Escalado para esta subasta</h4>

					<div class="row">
						<div class="col-12 col-md-4 col-md-offset-8">
							<a class="btn btn-primary" id="addEscalado">Añadir</a>
							&nbsp;&nbsp;
							<input type="submit" value="Guardar" class="btn btn-success">
						</div>
					</div>
					<br>
					<br>
					<div class="row">
						<div class="col-12 col-md-2"></div>
						<div class="col-12 col-md-4">
							<b>IMPORTE</b>
						</div>
						<div class="col-12 col-md-4">
							<b>PUJA</b>
						</div>

					</div>


					@foreach($escalado as $k => $item)

					<br>
					<div class="row items">
						<div class="col-12 col-md-2"></div>
						<div class="col-12 col-md-4">
							{!! $item['importe'] !!}
						</div>
						<div class="col-12 col-md-4">
							{!! $item['puja'] !!}
						</div>
					</div>

					@endforeach



				</form>
			</div>
		</div>



	</div>



	@endif




	{!! \FormLib::modalToList('list_modal')!!}

	</div>






	@stop
