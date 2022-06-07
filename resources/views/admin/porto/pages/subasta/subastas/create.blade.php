@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	@include('admin::includes.header_content')

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1>{{ trans("admin-app.button.new_fem") }} {{ trans("admin-app.title.auction") }}</h1>
		</div>
		<div class="col-xs-3">
			<a href="{{ route('subastas.index') }}"
				class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>

	<form action="{{ route('subastas.store') }}" method="POST" id="subastaStore" enctype="multipart/form-data">
		@csrf

		<div class="row well">
			@include('admin::pages.subasta.subastas._form', compact('formulario', 'fgSub'))
		</div>

		<div class="row">
			<div class="col-xs-12 text-center">
				{!! $formulario->submit !!}
			</div>
		</div>
	</form>

</section>
@stop
