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
