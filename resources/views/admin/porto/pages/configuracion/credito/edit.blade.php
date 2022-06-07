@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	@include('admin::includes.header_content')

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1>{{ trans("admin-app.button.edit") }} {{ trans("admin-app.title.credit") }}</h1>
		</div>
		<div class="col-xs-3">
			<a href="{{ route('credito.index') }}"
				class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>


	<div class="row well">

		<form action="{{ route('credito.update', $fgCreditoSub->id_creditosub) }}" method="POST">
			@method('PUT')
			@csrf
			@include('admin::pages.subasta.depositos._form', compact('formulario', '$fgCreditoSub'))
		</form>

	</div>

</section>
@stop
