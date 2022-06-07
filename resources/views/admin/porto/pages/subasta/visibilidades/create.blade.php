@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	@include('admin::includes.header_content')

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1>{{ trans("admin-app.button.new_fem") }} {{ trans("admin-app.title.visibility") }}</h1>
		</div>
		<div class="col-xs-3">
			<a href="{{ route('visibilidad.index') }}" class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>


	<div class="row well">

		<form action="{{ route('visibilidad.store') }}" method="POST">
			@csrf
			@include('admin::pages.subasta.visibilidades._form', compact('formulario', 'visibility'))
		</form>

	</div>

	@stop

