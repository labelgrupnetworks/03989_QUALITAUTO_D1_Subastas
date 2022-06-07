@extends('admin::layouts.logged')
@section('content')
@php

	$type= request("type", "Excel")
@endphp
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

    <h1>Importar lotes</h1>

	<a href="{{ url()->previous() }}" class="btn btn-primary right">Volver</a>

	<br><br>

	<form name="uploadLotFile" id="uploadLotFile" action="/admin/lote/fileImport/{{$type}}" method="post" enctype="multipart/form-data">

		@csrf

		<h3>Subir fichero {{$type}}</h3>
		<i class="fa fa-2x fa-info-circle" style="position:relative;top:6px;"></i>&nbsp;
		<span class="badge">
			Según la cantidad y el tamaño de las imágenes, el proceso puede tardar varios minutos
		</span>
		<br>


		<br><br><br>

		<div class="row">
			<div class="col-xs-12 col-md-4 text-center">
				<input type="file" id="file" name="file">
				<input type="hidden" id="subasta" name="subasta" value="{{$subasta}}">
				<br><br>
				<button class="btn btn-primary" type="submit">Cargar</button>
			</div>
			<div class="col-xs-12 col-md-8 text-center">
				<div id="div_log" class="log">

				</div>
				<div class="progress">
					<div id="progressBarImg" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
					  <span class="sr-only"><span id="progressBarValue">0%</span> <span>Completado</span></span>
					</div>
				</div>
			</div>
		</div>

	</form>

@stop
