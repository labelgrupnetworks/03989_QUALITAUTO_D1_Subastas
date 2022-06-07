
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
        
@section('content')

	<br><br><br><br>
	<center><big>Los datos se han enviado satisfactoriamente</big></center>

	@if (isset($return))
	<br><br><br>
		<center><a href="{{$return}}" class="btn btn-primary">Volver</a></center>
	@endif

@endsection




