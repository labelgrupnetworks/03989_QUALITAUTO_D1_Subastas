@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1>{{ trans("admin-app.button.edit") }} {{ trans_choice("admin-app.title.bill", 0) }}</h1>
		</div>
		<div class="col-xs-3">
			<a href="{{ route('bills.index') }}" class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>


	<div class="row well">

		<form action="{{ route('bills.update', $bill->number) }}" method="POST" enctype="multipart/form-data">
			@method('PUT')
			@csrf
			@include('admin::pages.facturacion.facturas._form', compact('formulario', 'bill'))
		</form>

	</div>

	@stop
