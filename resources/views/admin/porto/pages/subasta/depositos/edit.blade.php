@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1>{{ trans("admin-app.button.edit") }} {{ trans("admin-app.title.deposit") }}</h1>
		</div>
		<div class="col-xs-3">
			<a href="{{ route('deposito.index') }}" class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>


	<div class="row well">

		<form action="{{ route('deposito.update', $fgDeposito->cod_deposito) }}" method="POST">
			@method('PUT')
			@csrf
			@include('admin::pages.subasta.depositos._form', compact('formulario', 'fgDeposito'))
		</form>

	</div>

	@stop
