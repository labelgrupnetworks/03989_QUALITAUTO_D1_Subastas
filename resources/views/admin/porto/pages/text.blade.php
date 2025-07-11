
@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

@section('content')

	<br><br><br><br>
	<center><big>Los datos se han enviado satisfactoriamente</big></center>

	@if (isset($return))
	<br><br><br>
		<center><a href="{{$return}}" class="btn btn-primary">Volver</a></center>
	@endif

@endsection




