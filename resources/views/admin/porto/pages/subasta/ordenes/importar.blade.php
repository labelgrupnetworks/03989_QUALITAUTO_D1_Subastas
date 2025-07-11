@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

    <h1>{{ trans('admin-app.order.import_orders') }}</h1>

	<a href="{{ route('subasta.edit', ['id' => $idAuction]) }}" class="btn btn-primary right">{{ trans('admin-app.button.return') }}</a>

	<br><br>

	<form action="{{ route('order.import', ['idAuction' => $idAuction]) }}" method="POST" enctype="multipart/form-data">
		@csrf
		<h3>{{ trans('admin-app.order.upload_orders') }}</h3>
		<br><br>

		<div class="row">
			<div class="col-xs-12 col-md-4">
				<input type="file" id="csv" name="csv">
				<br>
				<button class="btn btn-primary" type="submit">{{ trans('admin-app.button.load') }}</button>
			</div>
		</div>

	</form>

@stop
