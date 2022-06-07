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


	<div id="newbanner">

		<a href="{{ route('event.create', ['ubicacion'=> request('ubicacion'), 'menu' => 'contenido']) }}" class="btn btn-primary" style="float:right">Nuevo</a>

		<h1>{{ Str::ucfirst(str_replace('-', ' ', $webNewBanners[0]->ubicacion ?? 'Eventos' )) }}</h1>
		<br>


		<div class="row">
			<div class="col-12 col-md-1 text-center">
				<b>ID</b>
			</div>
			<div class="col-12 col-md-1 text-center">
				<b>ORDEN</b>
			</div>
			<div class="col-12 col-md-2">
				<b>UBICACION</b>
			</div>
			<div class="col-12 col-md-4">
				<b>TITULO</b>
			</div>
			<div class="col-12 col-md-2 text-right">
				<b>OPCIONES</b>
			</div>
		</div>

		@foreach($webNewBanners as $webNewBanner)

		<div class="row items">
			<div class="col-12 col-md-1 text-center">
				{{ $webNewBanner->id }}
			</div>
			<div class="col-12 col-md-1 text-center">
				{{ $webNewBanner->orden }}
			</div>
			<div class="col-12 col-md-2">
				{{ $webNewBanner->ubicacion }}
			</div>
			<div class="col-12 col-md-3">
				{{ $webNewBanner->descripcion }}
			</div>
			<div class="col-12 col-md-3 text-right">
					<a
					@if ($webNewBanner->activo)
						title="Desactivar"
						estado="on"
						class="btn btn-success jsChangeStatus"
					@else
						title="Activar"
						estado="off"
						class="btn btn-danger jsChangeStatus"
					@endif
						id="{{ $webNewBanner->id }}">
							<i class="fa fa-power-off"></i>
					</a>
				&nbsp;&nbsp;
				<a href="{{ route('event.edit', ['event' => $webNewBanner->id, 'menu' => 'contenido'] ) }}" class="btn btn-primary">Editar</a>
				&nbsp;&nbsp;
				<a href="/admin/newbanner/borrar/{{ $webNewBanner->id }}" class="btn btn-danger">Eliminar</a>
			</div>
		</div>

		@endforeach

	</div>

@stop
