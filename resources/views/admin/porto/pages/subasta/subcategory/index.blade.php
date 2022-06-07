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

		<a href="/admin/subcategory/edit" class="btn btn-primary" style="float:right">{{ trans('admin-app.title.new') }}</a>

		<h1>{{ trans('admin-app.title.subcategories') }}</h1>
		<br>
		<div class="row">
			<div class="col-6 ">
				<form  method="get" action="/admin/subcategory">
					{{  mb_strtoupper (trans('admin-app.title.category')) }}:
					<select name="idcategory" >
						<option value=""></option>
						@foreach($categories as $keyCategory => $category)
							<option value="{{$keyCategory}}" {{ $keyCategory==request("idcategory")? "selected='selected'" : "" }}> {{$category}} </option>
						@endforeach
					</select>
					<button class=" btn btn-primary" type="submit">{{  trans('admin-app.title.search') }}</button>
				</form>
			</div>

		</div>
		<br>
		<div class="row">
			<div class="col-12 col-md-1 ">
				<b>ID</b>
			</div>
			<div class="col-12 col-md-2 ">
				<b>{{  mb_strtoupper (trans('admin-app.title.nombre')) }}</b>
			</div>
			<div class="col-12 col-md-2">
				<b>{{  mb_strtoupper (trans('admin-app.title.url_friendly')) }}</b>
			</div>
			<div class="col-12 col-md-2">
				<b>{{  mb_strtoupper (trans('admin-app.title.category')) }}</b>
			</div>
			<div class="col-12 col-md-2">
				<b>{{  mb_strtoupper (trans('admin-app.title.order')) }}</b>
			</div>

			<div class="col-12 col-md-2 ">
				<b>{{  mb_strtoupper (trans('admin-app.title.options')) }}</b>
			</div>

		</div>

		@foreach($subcategories as $item)

		<div class="row items">
			<div class="col-12 col-md-1 ">
				{{ $item->cod_sec }}
			</div>
			<div class="col-12 col-md-2 ">
				{{ $item->des_sec }}
			</div>
			<div class="col-12 col-md-2">
				{{ $item->key_sec }}
			</div>
			<div class="col-12 col-md-2" >
				{{ !empty($categories[$item->lin_ortsec1])? $categories[$item->lin_ortsec1] :  $item->lin_ortsec1 }}
			</div>
			<div class="col-12 col-md-2">
				{{ $item->orden_ortsec1 }}
			</div>

			<div class="col-12 col-md-1 ">

				<a href="/admin/subcategory/edit?idsubcategory={{ $item->cod_sec }}" class="btn btn-primary">{{  trans('admin-app.title.edit') }}</a>


			</div>
			<div class="col-12 col-md-1 ">
				<form  method="post" action="/admin/subcategory/delete">
					<button class="delete_button_js btn btn-danger" type="button">{{  trans('admin-app.title.delete') }}</button>
					<input type="hidden" name="idsubcategory" value="{{ $item->cod_sec }}">
				</form>
			</div>
		</div>

		@endforeach
		{{ $paginator->links() }}
	</div>

@stop
