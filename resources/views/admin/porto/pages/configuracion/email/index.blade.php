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

		<a href="/admin/email/plantilla" class="btn btn-primary right">Plantilla</a>


		<h1>Emails</h1>
		<br>


		<div class="row">
			<div class="col-12 col-md-2">
				<b>KEY</b>
			</div>
			<div class="col-12 col-md-4">
				<b>DESCRIPCIÓN</b>
			</div>
			<div class="col-12 col-md-1 text-center">
				<b>TIPO</b>
			</div>
			<div class="col-12 col-md-1 text-center">
				<b>ACTIVO</b>
			</div>
			<div class="col-12 col-md-4 text-right">
				<b>OPCIONES</b>
			</div>
		</div>

		@foreach($emails as $item)

		<hr class="solid">
		<div class="row items">
			<div class="col-12 col-md-2">
				{{ $item->cod_email }}
			</div>
			<div class="col-12 col-md-4">
				{{ $item->des_email }}
			</div>
			<div class="col-12 col-md-1 text-center">
				{{ $item->type_email }}
			</div>
			<div class="col-12 col-md-1 text-center">
				{{ $item->enabled_email }}
			</div>
			<div class="col-12 col-md-4 text-right">
				<a href="/admin/email/editar/{{ $item->cod_email }}" class="btn btn-primary">Editar</a>
				<!--&nbsp;&nbsp;
				<a href="/email/borrar/{{ $item->cod_email }}" class="btn btn-danger">Eliminar</a>-->
			</div>
		</div>

		@endforeach
			
	</div>	

@stop
