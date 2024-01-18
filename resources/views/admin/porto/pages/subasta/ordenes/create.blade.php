@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	@include('admin::includes.header_content')

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-12">
			<h1>{{ trans("admin-app.button.new_fem") }} {{ trans("admin-app.title.order") }}</h1>
			<p><i class="fa fa-2x fa-info-circle" style="position:relative;top:6px;"></i>&nbsp;<span class="badge">
				{{ trans("admin-app.information.obligatory_field_sub_reflot_cli") }}</span></p>
		</div>
		<div class="col-xs-6 text-right">
			<a href="{{ url()->previous() }}" class="btn btn-primary">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>

	<div class="row well">
		<form name="createOrders" action="{{route('orders.store')}}" method="POST">
			@include('admin::pages.subasta.ordenes.form')
		</form>
	</div>
</section>

@stop
