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

	@if (isset($id) && !empty($id))
	<h1>Lote {{explode("-",$id)[2]}} - Subasta {{$subasta}}</h1>
	@else
	<h1>Nuevo lote - Subasta {{$subasta}}</h1>
	@endif

	<a href="/admin/subasta/edit/{{$subasta}}" class="btn btn-primary right">Volver</a>

	<br><br>

	<form name="edit" id="edit" action="/admin/lote/edit_run" method="post" class="col-xs-11"
		enctype="multipart/form-data">
		@csrf

		<h3>General</h3>

		<div class="row">
			@foreach($formulario as $k => $item)

			@if ($k != 'SUBMIT' && $k != "id" && $k != "lin" && $k != "num" && $k != "Url360" && $k != "Url 360")
			<div class="col-xs-12 col-md-6" style="padding-bottom:16px;">
				<div class="row">
					<div class="col-xs-4 text-right" style="padding-top:10px;">
						<label>{{ ucfirst($k)}}: </label>
					</div>
					<div class="col-xs-8">
						{!! $item !!}
					</div>
				</div>
			</div>
			@elseif ($k != "SUBMIT" && $k != "Url360")
			{!! $item !!}
			@endif

			@endforeach
			<div class="clearfix"></div>
		</div>

		@if (!empty($id))
		<br>
		<hr>
		<br>

		<h3>Textos</h3>

		<table width="100%" cellspacing=1 cellpadding=1>
			<tr>
				<th></th>
				@foreach(\Config::get("app.locales") as $lang => $name)
				<th>
					{{ ucfirst($name) }}
				</th>
				@endforeach
			</tr>


			<tr>
				<td>Título</td>
				@foreach(\Config::get("app.locales") as $lang => $name)
				<td>
					{!! $formularioLang[$lang]["DESCWEB_HCES1_LANG"] !!}
				</td>
				@endforeach
			</tr>

			<tr>
				<td>Descripción</td>
				@foreach(\Config::get("app.locales") as $lang => $name)
				<td>
					{!! $formularioLang[$lang]["DESC_HCES1_LANG"] !!}
				</td>
				@endforeach
			</tr>

			<tr>
				<td>Notas</td>
				@foreach(\Config::get("app.locales") as $lang => $name)
				<td>
					{!! $formularioLang[$lang]["DESCDET_HCES1_LANG"] !!}
				</td>
				@endforeach
			</tr>

			<tr>
				<td>Url</td>
				@foreach(\Config::get("app.locales") as $lang => $name)
				<td>
					{!! $formularioLang[$lang]["WEBFRIEND_HCES1_LANG"] !!}
				</td>
				@endforeach
			</tr>

			<tr>
				<td>Meta-titulo</td>
				@foreach(\Config::get("app.locales") as $lang => $name)
				<td>
					{!! $formularioLang[$lang]["WEBMETAT_HCES1_LANG"] !!}
				</td>
				@endforeach
			</tr>

			<tr>
				<td>Meta-descripción</td>
				@foreach(\Config::get("app.locales") as $lang => $name)
				<td>
					{!! $formularioLang[$lang]["WEBMETAD_HCES1_LANG"] !!}
				</td>
				@endforeach
			</tr>

		</table>
		@endif
		<br>
		<hr>
		<br>

		@if (!empty($id))
		{{-- Metodo original de añadir imagenes
		<div class="row">
			<div class="col-xs-12 col-md-6 text-center">
				<div class="well">
					<input type="file" name="ficheros" id="ficheros" class="form-control">
				</div>
			</div>

			<div class="col-xs-12 col-md-6">
				<div class="row">
					@foreach ($imagenes as $k => $imagen)
					<div class="col-xs-4" id="imagen{{$k}}">
						<img src="{{ $imagen }}" width="100%">
						<br><br>
						<center><a onclick="javascript:borrarImagenLote({{ $k }},'{{$imagen}}')"><i
									class="fa fa-2x fa-times red"></i></a></center>
						<br><br>

					</div>
					@endforeach
				</div>

			</div>
		</div>
		--}}

		<div class="row well" style="max-height: 350px;">
			<div class="col-md-6">
				<h3>Contenido extra:</h3>
			</div>
			<div class="clearfix"></div>
			<div class="col-md-2">
				<a id="img360button" class="btn btn-success">Modificar</a>
			</div>
			<div class="col-md-3" style="max-height: 350px;">
				{!! $formulario["Url360"] !!}
			</div>
		</div>

		<br><br>

		@else

		<center>Para poder asoicar imagenes primero debes crear el lote</center>

		@endif

		<br><br>
		<div class="row">
			<div class="col-xs-12 text-center">
				{!! $formulario['SUBMIT'] !!}
			</div>
		</div>
		<br><br>

	</form>

	@if (!empty($id))
	<div class="row">
		<div class="col-xs-12">
			<div class="well">
				<h3>Subir Imágenes</h3>
				<form action="{{ route('addLotImage') }}" class="dropzone" id="my-awesome-dropzone" method="POST">
					<input type="hidden" name="num_hces1" value="{{$num_hces1}}">
					<input type="hidden" name="lin_hces1" value="{{$lin_hces1}}">
				</form>
			</div>

			<div class="well">
				<h3>Imágenes</h3>
					<div class="row">
						@foreach ($imagenes as $k => $imagen)
						<div class="col-xs-6 col-md-3 image-wrapper" id="imagen{{$k}}">
							<img class="img-responsive" src="{{ $imagen }}?a={{rand()}}">
							<br>
							<center>
								<a onclick="javascript:borrarImagenLote({{ $k }},'{{$imagen}}')">
									<i class="fa fa-2x fa-times red"></i>
								</a>
							</center>
						</div>
						@endforeach
					</div>

				</div>

		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">




				<div class="well row">

					<h4>Ficheros</h4>
					@if(session('errors'))
						@foreach ($errors as $error)
							<div class="alert alert-danger" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
										aria-hidden="true">&times;</span></button>
								<strong>{{ $error }}</strong>
							</div>
						@endforeach
					@endif
					@if(session('success'))
						<div class="alert alert-success" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
									aria-hidden="true">&times;</span></button>
							<strong>{{ session('success')[0] }}</strong>
						</div>
					@endif

					<br>
					<div class="col-xs-12 col-md-7">


						<div class="row">
							<form name="subirfichero" action="/admin/lote/addfile" method="POST" enctype="multipart/form-data">
								<div class="col-xs-12 ">

									<b>Selecciona fichero</b>
									@csrf
									<input type="hidden" name="num_hces1" value="{{$num_hces1}}">
									<input type="hidden" name="lin_hces1" value="{{$lin_hces1}}">
									{!! \FormLib::File("ficheroAdjunto", 1); !!}
								</div>
								<div class="col-xs-12 ">
									<br>
									<input type="submit" class="btn btn-primary">
								</div>
							</form>
						</div>
						<br>
					</div>
					<div class="col-xs-12 col-md-5">

						<?php
							$path = "/files/".Config::get('app.emp')."/$num_hces1/$lin_hces1/files/";
							$files = [];
							if(is_dir(getcwd() . $path)){
								$files = array_slice(scandir(getcwd() . $path), 2);
							}
						?>


						<b>Ficheros asignados:</b>
						<table class="table table-striped table-files">
							<tr>
								<th>Nombre</th>
								<th>Acción</th>
							</tr>
							@foreach ($files as $file)
							<tr>
								<form method="POST" action="/admin/lote/deletefile">
									@csrf
									<input type="hidden" name="num_hces1" value="{{$num_hces1}}">
									<input type="hidden" name="lin_hces1" value="{{$lin_hces1}}">
									<input type="hidden" name="file" value="{{$file}}">

									<td>
										<a style="text-decoration: none;" href="{{$path . '/' . $file}}"
											target="_blank">
											<span class="mt-3">{{ $file }}</span>
										</a>
									</td>
									<td><button type="submit" class="btn btn-danger"><b>X</b></button></td>
								</form>
							</tr>
							@endforeach

						</table>
					</div>
					<div class="clearfix"></div>

				</div>

		</div>
	</div>





	{{-- Carga de videos --}}
	<div class="row">
		<div class="col-xs-12">

				<div class="well row">

					<h4>Videos</h4>
					@if(session('errorsVideo'))
						@foreach ($errorsVideo as $error)
						<div class="alert alert-danger" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
									aria-hidden="true">&times;</span></button>
							<strong>{{ $error }}</strong>
						</div>
						@endforeach
					@endif
					@if(session('successVideo'))
						<div class="alert alert-success" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
									aria-hidden="true">&times;</span></button>
							<strong>{{ session('successVideo')[0] }}</strong>
						</div>
					@endif

					<br>
					<div class="col-xs-12 col-md-7">


						<div class="row">
							<form name="subirvideos" action="/admin/lote/addvideo" method="POST" enctype="multipart/form-data">
								@csrf
								<input type="hidden" name="num_hces1" value="{{$num_hces1}}">
								<input type="hidden" name="lin_hces1" value="{{$lin_hces1}}">

								<div class="col-xs-12 ">
									<b>Selecciona video</b>
									{!! \FormLib::File("ficheroAdjunto", 1); !!}
								</div>
								<div class="col-xs-12">
									<br>
									<input type="submit" class="btn btn-primary">
								</div>
							</form>

						</div>
						<br>
					</div>
					<div class="col-xs-12 col-md-5">

						<?php
							$path = "/files/videos/".Config::get('app.emp')."/$num_hces1/$lin_hces1/";
							$files = [];
							if(is_dir(getcwd() . $path)){
								$files = array_slice(scandir(getcwd() . $path), 2);
							}
						?>


						<b>Videos asignados:</b>
						<table class="table table-striped table-files">
							<tr>
								<th>Nombre</th>
								<th>Acción</th>
							</tr>
							@foreach ($files as $file)
							<tr>
								<form method="POST" action="/admin/lote/deletevideo">
									@csrf
									<input type="hidden" name="num_hces1" value="{{$num_hces1}}">
									<input type="hidden" name="lin_hces1" value="{{$lin_hces1}}">
									<input type="hidden" name="file" value="{{$file}}">

									<td>
										<a style="text-decoration: none;" href="{{$path . '/' . $file}}"
											target="_blank">
											<span class="mt-3">{{ $file }}</span>
										</a>
									</td>
									<td><button type="submit" class="btn btn-danger"><b>X</b></button></td>
								</form>
							</tr>
							@endforeach

						</table>
					</div>
					<div class="clearfix"></div>

				</div>

		</div>
	</div>
	@endif

	<script>


	</script>

	@stop
