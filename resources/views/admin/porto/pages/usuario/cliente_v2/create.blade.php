@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1>{{ trans("admin-app.button.new") }} {{ trans_choice("admin-app.title.client", 1) }}</h1>
		</div>
		<div class="col-xs-3">
			<a href="{{ route('clientes.index', ['menu' => 'clientes']) }}"
				class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>

	<form action="{{ route('clientes.store') }}" method="POST" id="clientesStore" enctype="multipart/form-data">
		@csrf

		<div class="row well">
			@include('admin::pages.usuario.cliente_v2._form', compact('formulario', 'fxcli'))
		</div>

		<div class="row">
			<div class="col-xs-12 text-center">
				{!! $formulario->submit !!}
			</div>
		</div>
	</form>

</section>

@stop
