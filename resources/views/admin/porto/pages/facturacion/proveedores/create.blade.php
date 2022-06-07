@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	@include('admin::includes.header_content')

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1>{{ trans("admin-app.button.new") }} {{ trans_choice("admin-app.title.provider", 0) }}</h1>
		</div>
		<div class="col-xs-3">
			<a href="{{ route('providers.index') }}" class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>


	<div class="row well">

		<form action="{{ route('providers.store') }}" method="POST" enctype="multipart/form-data">
			@csrf
			@include('admin::pages.facturacion.proveedores._form', compact('formulario', 'provider'))
		</form>

	</div>

	@stop
