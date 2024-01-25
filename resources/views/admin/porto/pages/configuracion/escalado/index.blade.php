@extends('admin::layouts.logged')
@section('content')

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

			<a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>


	<div id="newbanner">

		<form name="escalado" id="formEscalado" method="post" action="/admin/escalado/save">

			@csrf
			<h1>{{ trans("admin-app.title.general_scaling") }}</h1>
			<br>

			<div class="row">
				<div class="col-12 col-md-4 col-md-offset-8">
					<a class="btn btn-primary" id="addEscalado">{{ trans("admin-app.button.add") }}</a>
					&nbsp;&nbsp;
					<input type="submit" value="Guardar" class="btn btn-success">
				</div>
			</div>


			<div class="row">
				<div class="col-12 col-md-2"></div>
				<div class="col-12 col-md-4">
					<b>{{ trans("admin-app.fields.up_to_import") }}</b>
				</div>
				<div class="col-12 col-md-4">
					<b>{{ trans("admin-app.fields.bid_import") }}</b>
				</div>

			</div>


			@foreach($escalado as $k => $item)

			<br>
			<div class="row items">
				<div class="col-12 col-md-2"></div>
				<div class="col-12 col-md-4">
					{!! $item['importe'] !!}
				</div>
				<div class="col-12 col-md-4">
					{!! $item['puja'] !!}
				</div>
			</div>

			@endforeach



		</form>

	</div>

	@stop
