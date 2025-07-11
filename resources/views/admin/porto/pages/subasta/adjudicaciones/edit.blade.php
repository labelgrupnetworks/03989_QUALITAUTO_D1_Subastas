
@extends('admin::layouts.logged')
@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<section role="main" class="content-body">


	@csrf
	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			@if ($isNew)
			<h1>{{ trans("admin-app.button.new_fem") }} {{ trans("admin-app.title.award") }}</h1>
			@else
			<h1>{{ trans("admin-app.title.edit") }} {{ trans("admin-app.title.award") }}</h1>
			@endif

		</div>
		<div class="col-xs-3">
			<a href="{{ url()->previous() }}"
				class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>

	@if(session('errors'))
		@foreach ($errors as $error)
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<strong>{{ $error }}</strong>
			</div>
		@endforeach
	@endif
	@if(session('success'))
		<div class="alert alert-success" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong>{{ session('success')[0] }}</strong>
		</div>
	@endif

	<div class="row well header-well d-flex align-items-center">
		<form name="createAwards" id="{{ $formularioId }}" action="{{ $formularioAction }}" method="POST" class="col-12">

			{{csrf_field()}}
			@foreach($formulario as $index => $item)

				<div class="col-xs-12 col-md-6 mb-3 mt-3" style="margin: auto;">
					<fieldset>
						<label class="mt-1" for="{{$index}}">{{ trans("admin-app.fields.$index") }}</label>
						<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true" data-toggle="tooltip"
						data-placement="right" data-original-title="{{ trans("admin-app.help_fields.$index") }}"></i><br>
						<div>
						{!! $item !!}
						</div>
					</fieldset>
				</div>
				<div class="col-xs-12 col-md-offset-6 mb-3 mt-3" style="margin: auto"></div>

			@endforeach

		</form>
	</div>
	<div style="text-align: center">
		{!! $SUBMIT !!}
	</div>
@stop
