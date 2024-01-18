
@extends('admin::layouts.logged')
@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<section role="main" class="content-body">
        <header class="page-header">
                <div class="right-wrapper pull-right">
                        <ol class="breadcrumbs">
                                <li>
                                        <a href="/admin">
                                                <i class="fa fa-home"></i>
                                        </a>
                                </li>

                        </ol>

                        <a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
                </div>
        </header>

	@csrf

	<div class="row well">
		<div class="col-xs12">

			<h1>{{ trans("admin-app.title.new_licit") }}</h1>
	<p><i class="fa fa-2x fa-info-circle" style="position:relative;top:6px;"></i>&nbsp;<span class="badge">
		{{ trans("admin-app.information.obligatory_fields") }}</span></p>
	<br>

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


	<form name="createLicits" id="{{ $formularioId }}" action="{{ $formularioAction }}" method="POST" class="col-11">

		{{csrf_field()}}

		<div class="row">
			@foreach($formulario as $index => $item)

			@if ($index != 'SUBMIT' && $index != 'HIDDEN')
			<div class="col-xs-12 col-md-6" style="padding-bottom:15px;">
				<div class="row">
					<div class="col-xs-4 pt-2 text-right">
						<label>{{ ucfirst($index)}}: </label>
					</div>
					<div class="col-xs-8">
						{!! $item !!}
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			@elseif ($index != "SUBMIT")
			{!! $item !!}
			@endif

			@endforeach
		</div>
		<div class="row" style="margin-top: 20px">
			<div class="col-xs-12 col-md-6 text-right">
				{!! $formulario['SUBMIT'] !!}
			</div>
		</div>
		<br><br>
	</form>

		</div>
	</div>


@stop
