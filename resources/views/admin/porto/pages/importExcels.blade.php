@extends('admin::layouts.logged')
@section('content')
<section role="main" class="content-body">

	@include('admin::includes.header_content')

	<h1>Importar archivo</h1>

	<a href="{{ url()->previous() }}" class="btn btn-primary right">Volver</a>

	<br><br>

	<form name="uploadFile" id="uploadFile" action="" method="post" enctype="multipart/form-data">

		@csrf

		<h3>Subir fichero Excel</h3>
		<i class="fa fa-2x fa-info-circle" style="position:relative;top:6px;"></i>&nbsp;
		<span class="badge">
			Según la cantidad y el tamaño de las imágenes, el proceso puede tardar varios minutos
		</span>
		<br>


		<br><br><br>

		<div class="row">
			<div class="col-xs-12 col-md-4 text-center">
				<input type="file" id="file" name="file">
				<br><br>
				<button class="btn btn-primary" type="submit">Cargar</button>
			</div>
			<div class="col-xs-12 col-md-8 text-center">
				<div id="div_log" class="log">

				</div>
			</div>
		</div>

	</form>

@stop
