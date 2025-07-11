@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1>{{ trans("admin-app.button.edit") }} {{ trans("admin-app.title.visibility") }}</h1>
		</div>
		<div class="col-xs-3">
			<a href="{{ route('admin.b2b.visibility') }}" class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>


	<div class="row well">

		<form method="POST" action="{{ route('admin.b2b.visibility.update', ['id' => $visibility->cod_visibilidad] ) }}">
			@method('PUT')
			@csrf
			@include('admin::pages.subasta.visibilidades._form', compact('formulario', 'visibility'))
		</form>

	</div>

	@stop

