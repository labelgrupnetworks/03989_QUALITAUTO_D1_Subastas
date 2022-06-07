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
	<div id="category">

		<a href="/admin/category/edit" class="btn btn-primary" style="float:right">{{ trans('admin-app.title.new') }}</a>

		<h1>{{ trans('admin-app.title.categories') }}</h1>
		<br>


		<div class="row">
			<div class="col-12 col-md-1 ">
				<b>ID</b>
			</div>
			<div class="col-12 col-md-2 ">
				<b>{{  mb_strtoupper (trans('admin-app.title.nombre')) }}</b>
			</div>
			<div class="col-12 col-md-2">
				<b>{{  mb_strtoupper (trans('admin-app.title.key')) }}</b>
			</div>
			<div class="col-12 col-md-1">
				<b>{{  mb_strtoupper (trans('admin-app.title.order')) }}</b>
			</div>
			<div class="col-12 col-md-3">
				<b>{{  mb_strtoupper (trans('admin-app.placeholder.meta_description')) }}</b>
			</div>
			<div class="col-12 col-md-2 ">
				<b>{{  mb_strtoupper (trans('admin-app.title.options')) }}</b>
			</div>

		</div>

		@foreach($categories as $item)

		<div class="row items">
			<div class="col-12 col-md-1 ">
				{{ $item->lin_ortsec0 }}
			</div>
			<div class="col-12 col-md-2 ">
				{{ $item->des_ortsec0 }}
			</div>
			<div class="col-12 col-md-2">
				{{ $item->key_ortsec0 }}
			</div>
			<div class="col-12 col-md-1">
				{{ $item->orden_ortsec0 }}
			</div>
			<div class="col-12 col-md-3 " style="text-align: justify;">

				{{ $item->meta_description_ortsec0 }}


			</div>
			<div class="col-12 col-md-1 ">

				<a href="/admin/category/edit?idcategory={{ $item->lin_ortsec0 }}" class="btn btn-primary">{{  trans('admin-app.title.edit') }}</a>


			</div>
			<div class="col-12 col-md-1 ">
				<form  method="post" action="/admin/category/delete">
					<button class="delete_button_js btn btn-danger" type="button">{{  trans('admin-app.title.delete') }}</button>
					<input type="hidden" name="idcategory" value="{{ $item->lin_ortsec0 }}">
				</form>
			</div>
		</div>

		@endforeach
		{{ $paginator->links() }}
	</div>

@stop
