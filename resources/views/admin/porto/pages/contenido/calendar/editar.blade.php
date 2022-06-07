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
		<form method="post" action="/admin/calendar/update">

			<div class="right">
				<a href="/admin/calendar" class="btn btn-primary">{{  trans('admin-app.title.return') }}</a>
				&nbsp;&nbsp;&nbsp;

				<button type="submit" class="btn btn-success">{{  trans('admin-app.title.save') }}</button>
			</div>
			<h1>
				@if(empty(request("id")))
					{{  trans('admin-app.title.create') }}
				@else
					{{  trans('admin-app.title.edit') }}
				@endif

			</h1>

			<br>
			<div class="row">
				{!! $token !!}
				{!! $id !!}

				<div class="col-xs-12 col-md-3">
					<label>{{  trans('admin-app.config.name') }}: </label>
					{!! $name !!}
				</div>
				<div class="col-xs-12 col-md-3">
					<label>{{  trans('admin-app.title.start') }}:</label>
					{!! $start !!}
				</div>
				<div class="col-xs-12 col-md-3">
					<label>{{  trans('admin-app.title.end') }}:</label>
					{!! $end !!}
				</div>


				<div class="col-xs-12 col-md-3 text-center">
					<label>{{  trans('admin-app.placeholder.type') }}:</label>
					{!! $codevent !!}
				</div>

			</div>

			<div class="row">
				<div class="col-xs-12 col-md-12">
					<label>{{  trans('admin-app.config.description') }}:</label>
					{!! $description !!}
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-md-12">
					<label>{{  trans('admin-app.placeholder.url') }}:</label>
					{!! $url !!}
				</div>

			</div>

		</form>

	</div>


@stop
