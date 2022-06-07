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

                        <a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
                </div>
        </header>
		@include('admin::includes.session_msg')
	<div id="editbanner">
		<form method="post" action="/admin/subcategory/update">

			<div class="right">
				<a href="/admin/subcategory" class="btn btn-primary">{{  trans('admin-app.title.return') }}</a>
				&nbsp;&nbsp;&nbsp;

				<button type="submit" class="btn btn-success">{{  trans('admin-app.title.save') }}</button>
			</div>
			<h1>
				@if(empty(request("idsubcategory")))
					{{  trans('admin-app.title.create') }}
				@else
					{{  trans('admin-app.title.edit') }}
				@endif

			</h1>

			<br>
			<div class="row">
				{!! $token !!}
				{!! $old_idsubcategory !!}

				<div class="col-xs-12 col-md-2">
					<label>ID: </label>
					{!! $idsubcategory !!}
				</div>


				<div class="col-xs-12 col-md-3">
					<label>{{  trans('admin-app.config.name') }}: </label>
					{!! $description !!}
				</div>

				<div class="col-xs-12 col-md-3">
					<label>{{  trans('admin-app.title.url_friendly') }}:</label>
					{!! $urlfriendly !!}
				</div>
				<div class="col-xs-12 col-md-3">
					<label>{{  trans('admin-app.title.category') }}:</label>
					{!! $idcategory !!}
				</div>
				<div class="col-xs-12 col-md-1">
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
