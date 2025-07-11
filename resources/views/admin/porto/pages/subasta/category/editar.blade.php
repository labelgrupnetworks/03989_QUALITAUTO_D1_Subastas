@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

	<div id="editbanner">
		<form method="post" action="/admin/category/update">

			<div class="right">
				<a href="/admin/category" class="btn btn-primary">{{  trans('admin-app.title.return') }}</a>
				&nbsp;&nbsp;&nbsp;

				<button type="submit" class="btn btn-success">{{  trans('admin-app.title.save') }}</button>
			</div>
			<h1>
				@if(empty(request("idcategory")))
					{{  trans('admin-app.title.create') }}
				@else
					{{  trans('admin-app.title.edit') }}
				@endif

			</h1>

			<br>
			<div class="row">
				{!! $token !!}
				{!! $idcategory !!}

				<div class="col-xs-12 col-md-4">
					<label>{{  trans('admin-app.config.name') }}: </label>
					{!! $description !!}
				</div>

				<div class="col-xs-12 col-md-4">
					<label>{{  trans('admin-app.title.key') }}:</label>
					{!! $urlfriendly !!}
				</div>
				<div class="col-xs-12 col-md-4">
					<label>{{  trans('admin-app.title.order') }}:</label>
					{!! $order !!}
				</div>




			</div>
			<div class="row">
				<div class="col-xs-12 col-md-12">
					<label>{{  trans('admin-app.title.meta_titulo') }}:</label>
					{!! $metatitle !!}
				</div>

			</div>

			<div class="row">
				<div class="col-xs-12 col-md-12">
					<label>{{  trans('admin-app.title.meta_description') }}:</label>
					{!! $metadescription !!}
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12 col-md-12">
					<label>{{  trans('admin-app.title.meta_content') }}:</label>
					{!! $metacontent !!}
				</div>

			</div>

		</form>

	</div>


@stop
