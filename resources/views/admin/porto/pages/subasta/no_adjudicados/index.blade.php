
@extends('admin::layouts.logged')
@section('content')

	<section role="main" class="content-body">

		<div class="row well header-well d-flex align-items-center">
			<div class="col-xs-12">
				<h1>{{ trans('admin-app.title.not_award') }}</h1>
			</div>

		</div>

		<div class="row well">
			@include('admin::pages.subasta.no_adjudicados.table')
		</div>
	</section>
@stop
