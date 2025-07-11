@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

		<a href="/admin/email" class="right btn btn-primary">Volver</a>

		<h1>Emails - Editar plantilla</h1>
		<br>


        <div class="row">
	        <div class="col-12 col-md-6">

	        	<h3>Editor</h3>
	        	<br>
	        	@csrf
	        	{!! $template !!}

		    </div>
	        <div class="col-12 col-md-6">
	        	<h3>Vista previa</h3>
	        	<br>
	        	<div id="resultado">

	        	</div>

	        </div>
	    </div>



@stop
