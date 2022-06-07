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

	<div id="editbanner">
		<form method="post" id="editBanner" action="/admin/newbanner/nuevo_run">

			<div class="right">
				@if (request("ubicacion") == "HOME")
					<a href="/admin/newbanner/ubicacionhome" class="btn btn-primary">Volver</a>
				@else
					<a href="/admin/newbanner" class="btn btn-primary">Volver</a>
				@endif

				&nbsp;&nbsp;&nbsp;
				<a href="javascript:vista_previa('{{$banner->key}}')" class="btn btn-primary">Vista previa</a>
				&nbsp;&nbsp;&nbsp;
				<a href="javascript:editar_run()" class="btn btn-success">Guardar</a>
			</div>

			<h1>Editar banner</h1>
			<br>
			<div class="row">
				{!! $token !!}
				{!! $id !!}

				<div class="col-xs-12 col-md-6">
					<label>Key:</label>
					{!! $nombre !!}
				</div>

				<div class="col-xs-12 col-md-2">
					<label>Orden:</label>
					{!! $orden !!}
				</div>

				<div class="col-xs-12 col-md-2 text-center">
					<label>Activo:</label>
					{!! $activo !!}
				</div>

			</div>
			<br>
			<div class="row">
				<div class="col-xs-12 col-md-6">
					<label>Descripción:</label>
					{!! $descripcion !!}
				</div>
				<div class="col-xs-12 col-md-6">
					<label>Ubicación:</label>
					{!! $ubicacion !!}
					<small><i>Posibles ubicaciones: {{$ubicaciones}}</i></small>
				</div>
			</div>

		</form>

			<br>
			<hr>
			<br>
			<p>*Se puede modificar el orden arrastrando los elementos<p>
			<div class="row">
				@foreach($bloques as $k => $bloque)

				<div class="col-xs-12 col-md-{{floor(12/sizeof($bloques))}} {{$bloque}}">

					<div class="bloqueBanner">
						<a href="javascript:nuevoItemBloque('{{$banner->id}}',{{$k}})" class="btn btn-primary">Nuevo</a>
						<h4>{{ucfirst($bloque)}}</h4>
						<br>
						<div class="bannerItems" id="bannerItems{{$k}}"></div>


					</div>
				</div>

				@endforeach
			</div>




	</div>


@stop
